<?php

namespace App\Http\Controllers;

use App\Models\JasaUpdateToken;
use App\Services\WhatsAppNotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PublicJasaUpdateController extends Controller
{
    /**
     * Show the update form.
     */
    public function show($token)
    {
        $updateToken = JasaUpdateToken::where('token', $token)
            ->with(['jasa.pelanggan'])
            ->first();
        
        if (!$updateToken) {
            return view('public.jasa-update', [
                'error' => 'Link tidak valid. Token tidak ditemukan.'
            ]);
        }
        
        // Validate token
        if ($updateToken->is_used) {
            return view('public.jasa-update', [
                'error' => 'Link ini sudah tidak valid karena sudah digunakan.'
            ]);
        }
        
        if ($updateToken->isExpired()) {
            return view('public.jasa-update', [
                'error' => 'Link ini sudah expired (lebih dari 7 hari).'
            ]);
        }
        
        if ($updateToken->jasa->status !== 'terjadwal') {
            return view('public.jasa-update', [
                'error' => 'Jasa ini tidak dalam status yang benar untuk diupdate. Status saat ini: ' . ucwords($updateToken->jasa->status)
            ]);
        }
        
        return view('public.jasa-update', [
            'token' => $token,
            'jasa' => $updateToken->jasa,
            'updateToken' => $updateToken,
        ]);
    }
    
    /**
     * Handle the update submission.
     */
    public function update(Request $request, $token)
    {
        $request->validate([
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'notes' => 'nullable|string|max:1000',
            'confirm' => 'required|accepted',
        ], [
            'images.required' => 'Minimal 1 foto bukti pengerjaan wajib diupload.',
            'images.min' => 'Minimal 1 foto bukti pengerjaan wajib diupload.',
            'images.max' => 'Maksimal 5 foto.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'images.*.max' => 'Ukuran setiap foto maksimal 2MB.',
            'confirm.accepted' => 'Anda harus mengkonfirmasi bahwa pengerjaan sudah selesai.',
        ]);
        
        $updateToken = JasaUpdateToken::where('token', $token)
            ->with(['jasa'])
            ->first();
        
        if (!$updateToken || !$updateToken->isValid()) {
            return back()->withErrors(['error' => 'Link tidak valid atau sudah expired.']);
        }
        
        if ($updateToken->jasa->status !== 'terjadwal') {
            return back()->withErrors(['error' => 'Jasa sudah diupdate atau status tidak valid.']);
        }
        
        DB::beginTransaction();
        
        try {
            // Upload images
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('jasa-completion', 'public');
                    $imagePaths[] = Storage::url($path);
                }
            }
            
            // Update jasa
            $jasa = $updateToken->jasa;
            $oldStatus = $jasa->status;
            
            $jasa->update([
                'status' => 'selesai dikerjakan',
                'completion_images' => $imagePaths,
                'completion_notes' => $request->notes,
                'updateAt' => now(),
            ]);
            
            // Mark token as used
            $updateToken->update([
                'is_used' => true,
                'used_at' => now(),
                'used_by_ip' => $request->ip(),
                'used_by_device' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            // Send notification to admin
            $this->sendCompletionNotification($jasa, $oldStatus);
            
            return view('public.jasa-update-success', [
                'jasa' => $jasa,
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Public jasa update failed', [
                'token' => $token,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem. Silakan coba lagi.']);
        }
    }
    
    /**
     * Send notification to admin about completion.
     */
    protected function sendCompletionNotification($jasa, $oldStatus)
    {
        try {
            // Send to superadmin when kepala_lapangan completes the work
            $recipients = \App\Models\User::where('role', 'superadmin')
                ->whereNotNull('kontak')
                ->get();
            
            if ($recipients->isEmpty()) {
                \Log::warning('No superadmin found to notify', [
                    'jasa_id' => $jasa->id,
                ]);
                return;
            }
            
            $helper = new WhatsAppNotificationHelper();
            
            $jasaData = [
                'jasa_id' => $jasa->id,
                'no_jasa' => $jasa->no_jasa,
                'no_ref' => $jasa->no_ref,
                'branch' => $jasa->branch,
                'pelanggan' => $jasa->pelanggan?->nama ?? '-',
                'kontak' => $jasa->pelanggan?->kontak ?? '-',
                'alamat' => $jasa->alamat ?? $jasa->pelanggan?->alamat ?? '-',
                'old_status' => $oldStatus,
                'new_status' => $jasa->status,
                'jadwal_petugas' => $jasa->jadwal_petugas ? $jasa->jadwal_petugas->format('d/m/Y H:i') : '-',
            ];
            
            // Send notification to superadmin
            \App\Services\WhatsAppNotificationHelper::sendJasaStatusUpdate($recipients, $jasaData);
            
            \Log::info('Completion notification sent to superadmin', [
                'jasa_id' => $jasa->id,
                'no_jasa' => $jasa->no_jasa,
                'recipients_count' => $recipients->count(),
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send completion notification', [
                'jasa_id' => $jasa->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
