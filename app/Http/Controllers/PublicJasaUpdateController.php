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
            return response()->view('errors.access-denied', [
                'message' => 'Link yang Anda akses tidak valid. Pastikan Anda menggunakan link yang benar dari sistem kami.'
            ], 404);
        }
        
        // Validate token
        if ($updateToken->is_used) {
            return response()->view('errors.access-denied', [
                'message' => 'Link ini sudah tidak dapat digunakan karena telah digunakan sebelumnya.'
            ], 404);
        }
        
        if ($updateToken->isExpired()) {
            return response()->view('errors.access-denied', [
                'message' => 'Link ini sudah tidak berlaku karena telah melewati batas waktu 7 hari.'
            ], 404);
        }
        
        if ($updateToken->jasa->status !== 'terjadwal') {
            return response()->view('errors.access-denied', [
                'message' => 'Jasa ini tidak dapat diupdate karena status saat ini adalah ' . ucwords($updateToken->jasa->status) . '. Update hanya dapat dilakukan pada jasa dengan status terjadwal.'
            ], 404);
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
            return response()->view('errors.access-denied', [
                'message' => 'Link yang Anda akses tidak valid atau sudah tidak berlaku.'
            ], 404);
        }
        
        if ($updateToken->jasa->status !== 'terjadwal') {
            return response()->view('errors.access-denied', [
                'message' => 'Jasa ini sudah diupdate atau status tidak valid. Silakan hubungi administrator jika memerlukan bantuan.'
            ], 404);
        }
        
        DB::beginTransaction();
        
        try {
            // Upload images to public_html/progress/jasa
            $imagePaths = [];
            if ($request->hasFile('images')) {
                // Ensure the jasa directory exists
                $progressPath = base_path('../public_html/progress/jasa');
                if (!is_dir($progressPath)) {
                    mkdir($progressPath, 0755, true);
                    \Log::info('Created jasa progress directory', ['path' => $progressPath]);
                }
                
                foreach ($request->file('images') as $image) {
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    
                    // Move file to public_html/progress/jasa
                    $image->move($progressPath, $filename);
                    
                    // Store relative path for database
                    $imagePaths[] = 'jasa/' . $filename;
                    
                    \Log::info('Image uploaded successfully', [
                        'filename' => $filename,
                        'path' => $progressPath . '/' . $filename,
                    ]);
                }
            }
            
            // Update jasa
            $jasa = $updateToken->jasa;
            $oldStatus = $jasa->status;
            
            // Prepare progress images data
            $progressImagesData = [];
            foreach ($imagePaths as $imagePath) {
                $progressImagesData[] = [
                    'path' => $imagePath,
                    'uploaded_at' => now()->format('Y-m-d H:i:s'),
                    'status_from' => $oldStatus,
                    'status_to' => 'selesai dikerjakan',
                    'uploaded_by' => null, // Public upload
                ];
            }
            
            // Merge with existing progress images if any
            $existingImages = $jasa->progress_images ?? [];
            if (!is_array($existingImages)) {
                $existingImages = [];
            }
            $allImages = array_merge($existingImages, $progressImagesData);
            
            $jasa->update([
                'status' => 'selesai dikerjakan',
                'progress_images' => $allImages,
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
            
            return response()->view('errors.access-denied', [
                'message' => 'Terjadi kesalahan pada sistem. Silakan coba beberapa saat lagi atau hubungi administrator jika masalah berlanjut.'
            ], 500);
        }
    }
    
    /**
     * Send notification to admin about completion.
     */
    protected function sendCompletionNotification($jasa, $oldStatus)
    {
        try {
            // Get recipients based on branch and event type
            // superadmin: receives from all branches
            // admin_toko: receives only from their own branch (matching jasa.branch)
            $recipients = \App\Services\WhatsAppNotificationHelper::getRecipientsByBranch(
                $jasa->branch ?? 'AJP',
                'jasa_status_updated',
                'selesai dikerjakan'
            );
            
            if ($recipients->isEmpty()) {
                \Log::warning('No recipients found to notify', [
                    'jasa_id' => $jasa->id,
                    'branch' => $jasa->branch,
                ]);
                return;
            }
            
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
            
            // Send notification to recipients (superadmin + admin_toko with matching branch)
            \App\Services\WhatsAppNotificationHelper::sendJasaStatusUpdate($recipients, $jasaData);
            
            \Log::info('Completion notification sent successfully', [
                'jasa_id' => $jasa->id,
                'no_jasa' => $jasa->no_jasa,
                'branch' => $jasa->branch,
                'recipients_count' => $recipients->count(),
                'recipients' => $recipients->map(fn($u) => [
                    'id' => $u->id,
                    'name' => $u->name,
                    'role' => $u->role,
                    'branch' => $u->branch,
                ])->toArray(),
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send completion notification', [
                'jasa_id' => $jasa->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
