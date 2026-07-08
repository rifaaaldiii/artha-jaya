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
     * Show the petugas gate form.
     */
    public function petugasGate()
    {
        return view('public.petugas');
    }

    /**
     * Verify token from petugas gate and redirect to update form.
     */
    public function verifyPetugas(Request $request)
    {
        $token = preg_replace('/\D/', '', trim($request->input('token', '')));
        $request->merge(['token' => $token]);

        $request->validate([
            'token' => ['required', 'string', 'digits:6'],
        ], [
            'token.required' => 'Kode token wajib diisi.',
            'token.digits' => 'Kode token harus 6 digit angka.',
        ]);

        $updateToken = JasaUpdateToken::where('token', $token)
            ->with(['jasa'])
            ->first();

        if (!$updateToken) {
            return back()
                ->withInput()
                ->withErrors(['token' => 'Kode token tidak ditemukan.']);
        }

        $errorMessage = $this->validateUpdateTokenAccess($updateToken);
        if ($errorMessage) {
            \Log::warning('Petugas gate verify failed', [
                'ip' => $request->ip(),
                'reason' => $errorMessage,
            ]);

            return back()
                ->withInput()
                ->withErrors(['token' => $errorMessage]);
        }

        return redirect()->route('jasa.public.update', [
            'token' => $updateToken->token,
        ]);
    }

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

        $errorMessage = $this->validateUpdateTokenAccess($updateToken);
        if ($errorMessage) {
            return response()->view('errors.access-denied', [
                'message' => $errorMessage,
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
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'notes' => 'nullable|string|max:1000',
            'confirm' => 'required|accepted',
            'accessories.*.jumlah' => 'nullable|integer|min:0',
            'accessories.*.harga' => 'nullable|numeric|min:0',
        ], [
            'images.required' => 'Minimal 1 foto bukti pengerjaan wajib diupload.',
            'images.min' => 'Minimal 1 foto bukti pengerjaan wajib diupload.',
            'images.max' => 'Maksimal 5 foto.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Format gambar harus jpeg, png, jpg, atau webp.',
            'images.*.max' => 'Ukuran setiap foto maksimal 5MB.',
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
            // Update accessories quantity and price if provided
            if ($request->has('accessories')) {
                foreach ($request->accessories as $itemId => $data) {
                    if (isset($data['jumlah']) && isset($data['harga'])) {
                        \Log::info('Updating accessory item', [
                            'item_id' => $itemId,
                            'new_jumlah' => $data['jumlah'],
                            'new_harga' => $data['harga'],
                        ]);
                        
                        \DB::table('jasa_items')
                            ->where('id', $itemId)
                            ->where('jasa_id', $updateToken->jasa_id)
                            ->update([
                                'jumlah' => $data['jumlah'],
                                'harga' => $data['harga'],
                                'updateAt' => now(),
                            ]);
                    }
                }
            }
            
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
            
            // Notification will be sent automatically by JasaObserver when status changes
            // No need to send manually here to avoid duplicate notifications
            
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
     * Validate whether a token grants access to the update form.
     * Returns null if valid, or an error message string.
     */
    private function validateUpdateTokenAccess(JasaUpdateToken $updateToken): ?string
    {
        if ($updateToken->is_used) {
            return 'Kode token sudah pernah digunakan.';
        }

        if ($updateToken->isExpired()) {
            return 'Kode token sudah kedaluwarsa (maks. 7 hari).';
        }

        if ($updateToken->jasa->status !== 'terjadwal') {
            return 'Jasa tidak dapat diupdate. Status saat ini: '
                . ucwords($updateToken->jasa->status)
                . '. Update hanya dapat dilakukan pada jasa dengan status terjadwal.';
        }

        return null;
    }
}