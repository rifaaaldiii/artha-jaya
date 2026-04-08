<?php

namespace App\Filament\Pages;

use App\Models\Jasa;
use App\Models\Petugas;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Filament\Forms\Components\FileUpload;

class ProgressJasa extends Page implements HasForms
{
    use InteractsWithForms;

    protected const STATUS_FLOW = [
        'jasa baru',
        'terjadwal',
        'selesai dikerjakan',
        'selesai',
    ];

    protected string $view = 'filament.pages.progressJasa';
    
    protected static ?string $navigationLabel = 'Progress';
    
    protected static ?string $title = 'Progress Jasa';
    
    protected static ?int $navigationSort = 3;

    public ?int $selectedJasaId = null;
    
    public ?Jasa $record = null;

    public ?string $updateStatusValue = null;

    public ?string $jasaSearch = '';

    public ?string $jadwalPetugas = null;
    
    public array $petugasIds = [];

    public static function getNavigationGroup(): ?string
    {
        return 'Jasa & Layanan';
    }

    #[On('aj-refresh-jasa')]
    public function handleExternalRefresh(): void
    {
        $this->loadRecord();
        $this->dispatch('$refresh');
    }

    protected function loadRecord(): void
    {
        if ($this->selectedJasaId) {
            $this->record = Jasa::with(['petugas', 'petugasMany', 'pelanggan', 'items'])->find($this->selectedJasaId);
            
            if ($this->record) {
                $this->petugasIds = $this->record->petugasMany()->pluck('petugas_id')->toArray();
                if ($this->record->jadwal_petugas) {
                    $this->jadwalPetugas = $this->record->jadwal_petugas->format('Y-m-d H:i:s');
                }
            }
        } else {
            $this->record = null;
            $this->petugasIds = [];
            $this->jadwalPetugas = null;
        }
    }

    public function refresh(): void
    {
        if ($this->record) {
            $this->record->refresh();
        }
    }

    public function mount(): void
    {
        $selectedJasaId = request()->query('selectedJasaId');
        
        if ($selectedJasaId) {
            $this->selectedJasaId = (int) $selectedJasaId;
            $this->loadRecord();
        } else {
            $firstJasa = Jasa::orderBy('createdAt', 'desc')->first();
            if ($firstJasa) {
                $this->selectedJasaId = $firstJasa->id;
                $this->loadRecord();
            }
        }

        $this->jasaForm->fill([
            'selectedJasaId' => $this->selectedJasaId,
        ]);

        if ($this->record) {
            $this->terjadwalForm->fill([
                'jadwalPetugas' => $this->record->jadwal_petugas?->format('Y-m-d\TH:i:s'),
                'petugasIds' => $this->record->petugasMany()->pluck('petugas_id')->toArray(),
            ]);
        }
        
        // Initialize image upload form
        $this->imageUploadForm->fill([
            'progressImages' => [],
        ]);
    }

    public array $data = [];

    protected function getForms(): array
    {
        return [
            'jasaForm',
            'terjadwalForm',
            'imageUploadForm',
        ];
    }

    public function jasaForm($form)
    {
        return $form
            ->schema([
                Select::make('selectedJasaId')
                    ->label('Cari & Pilih Jasa')
                    ->options(function () {
                        return Jasa::query()
                            ->with('items')
                            ->orderBy('createdAt', 'desc')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($jasa) {
                                $itemsInfo = '';
                                if ($jasa->items && $jasa->items->count() > 0) {
                                    $firstItem = $jasa->items->first();
                                    $itemsInfo = $firstItem->jenis_layanan;
                                    if ($jasa->items->count() > 1) {
                                        $itemsInfo .= ' (+' . ($jasa->items->count() - 1) . ')';
                                    }
                                }
                                return [
                                    $jasa->id => $jasa->no_jasa . ' | ' . $jasa->no_ref . ' - ' . $itemsInfo
                                ];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return Jasa::query()
                            ->with('items')
                            ->where(function ($query) use ($search) {
                                $searchTerm = '%' . trim($search) . '%';
                                $query->where('no_jasa', 'like', $searchTerm)
                                    ->orWhere('no_ref', 'like', $searchTerm)
                                    ->orWhereHas('items', function ($q) use ($search) {
                                        $q->where('jenis_layanan', 'like', $searchTerm);
                                    });
                            })
                            ->orderBy('createdAt', 'desc')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($jasa) {
                                $itemsInfo = '';
                                if ($jasa->items && $jasa->items->count() > 0) {
                                    $firstItem = $jasa->items->first();
                                    $itemsInfo = $firstItem->jenis_layanan;
                                    if ($jasa->items->count() > 1) {
                                        $itemsInfo .= ' (+' . ($jasa->items->count() - 1) . ')';
                                    }
                                }
                                return [
                                    $jasa->id => $jasa->no_jasa . ' | ' . $jasa->no_ref . ' - ' . $itemsInfo
                                ];
                            })
                            ->toArray();
                    })
                    ->preload()
                    ->getOptionLabelUsing(function ($value): ?string {
                        $jasa = Jasa::with('items')->find($value);
                        if (!$jasa) return null;
                        
                        $itemsInfo = '';
                        if ($jasa->items && $jasa->items->count() > 0) {
                            $firstItem = $jasa->items->first();
                            $itemsInfo = $firstItem->jenis_layanan;
                            if ($jasa->items->count() > 1) {
                                $itemsInfo .= ' (+' . ($jasa->items->count() - 1) . ')';
                            }
                        }
                        return $jasa->no_jasa . ' | ' . $jasa->no_ref . ' - ' . $itemsInfo;
                    })
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->selectedJasaId = $state;
                        $this->loadRecord();
                    }),
            ])
            ->statePath('data');
    }

    public function imageUploadForm($form)
    {
        return $form
            ->schema([
                FileUpload::make('progressImages')
                    ->label('Upload Foto Progress')
                    ->image()
                    ->multiple()
                    ->disk('public')  // Explicitly use public disk
                    ->directory('progress/jasa')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->maxFiles(10)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                    ->helperText('Upload foto progress untuk dokumentasi perubahan status. Maksimal 2MB.'),
            ])
            ->statePath('imageData');
    }

    public array $imageData = [];

    public bool $isUploading = false;

    public function terjadwalForm($form)
    {
        return $form
            ->schema([
                DateTimePicker::make('jadwalPetugas')
                    ->label('Jadwal Petugas')
                    ->required()
                    ->native(false)
                    ->displayFormat('d F Y, H:i')
                    ->timezone('Asia/Jakarta')
                    ->helperText('Pilih tanggal dan waktu untuk petugas melaksanakan jasa ini.'),
                
                Select::make('petugasIds')
                    ->label('Pilih Petugas')
                    ->multiple()
                    ->required()
                    ->options(function () {
                        $currentPetugasIds = $this->record?->petugasMany()->pluck('petugas_id')->toArray() ?? [];
                        
                        return Petugas::query()
                            ->where(function ($query) use ($currentPetugasIds) {
                                $query->where('status', 'ready');
                                if (!empty($currentPetugasIds)) {
                                    $query->orWhereIn('id', $currentPetugasIds);
                                }
                            })
                            ->orderBy('nama')
                            ->get()
                            ->mapWithKeys(function ($petugas) use ($currentPetugasIds) {
                                $statusLabel = in_array($petugas->id, $currentPetugasIds) ? ' (Sedang dipilih)' : ($petugas->status === 'ready' ? ' - Ready' : ' - Busy');
                                return [
                                    $petugas->id => $petugas->nama . ' (' . $petugas->kontak . ')' . $statusLabel
                                ];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih satu atau lebih petugas yang akan menangani jasa ini. Disarankan memilih petugas dengan status Ready.'),
            ])
            ->statePath('terjadwalData');
    }

    public array $terjadwalData = [];

    public function updatedSelectedJasaId(): void
    {
        $this->loadRecord();
        
        if ($this->record) {
            $this->terjadwalForm->fill([
                'jadwalPetugas' => $this->record->jadwal_petugas?->format('Y-m-d\TH:i:s'),
                'petugasIds' => $this->record->petugasMany()->pluck('petugas_id')->toArray(),
            ]);
        }
    }

    public function setUploadingStatus(bool $status): void
    {
        $this->isUploading = $status;
    }

    public function canUpdateJasaStatus($jasaId): bool
    {
        $jasa = Jasa::find($jasaId);
        if (!$jasa || $jasa->status === 'selesai') {
            return false;
        }

        $allowedStatuses = $this->getAllowedStatusesForRole();
        $currentStatus = $jasa->status;
        $currentIndex = array_search($currentStatus, self::STATUS_FLOW, true);
        
        if ($currentIndex === false) {
            return false;
        }

        $nextStatus = self::STATUS_FLOW[$currentIndex + 1] ?? null;
        
        return $nextStatus && in_array($nextStatus, $allowedStatuses, true);
    }

    public function updateStatus(): void
    {
        if (! $this->record) {
            Notification::make()
                ->title('Data jasa tidak ditemukan')
                ->danger()
                ->body('Silakan pilih jasa terlebih dahulu.')
                ->send();
            return;
        }

        // Check if images are still uploading
        if ($this->isUploading) {
            Notification::make()
                ->title('Upload Belum Selesai')
                ->warning()
                ->body('Mohon tunggu hingga semua gambar selesai diupload sebelum memperbarui status.')
                ->send();
            return;
        }

        $nextStatus = $this->nextSequentialStatus;

        if (! $nextStatus) {
            Notification::make()
                ->title('Tidak ada status lanjutan')
                ->warning()
                ->body('Jasa ini telah berada pada status akhir atau tidak memiliki langkah berikutnya.')
                ->send();
            return;
        }

        if (empty($this->updateStatusValue) && $nextStatus === 'terjadwal') {
            $normalizedRole = Auth::user()?->role ? str_replace(' ', '_', strtolower(Auth::user()->role)) : null;
            if (in_array($normalizedRole, ['kepala_teknisi_lapangan', 'admin_toko','administrator'], true)) {
                $this->updateStatusValue = 'terjadwal';
            }
        }

        if (empty($this->updateStatusValue)) {
            Notification::make()
                ->title('Gagal Memperbarui Status')
                ->danger()
                ->body('Gagal memperbarui status jasa. Silakan pilih status terlebih dahulu.')
                ->send();
            return;
        }

        $allowedStatuses = $this->allowedStatuses;

        if (! in_array($this->updateStatusValue, $allowedStatuses, true)) {
            Notification::make()
                ->title('Status tidak diizinkan')
                ->danger()
                ->body('Anda tidak memiliki izin untuk mengubah status ke pilihan tersebut.')
                ->send();
            $this->updateStatusValue = null;
            return;
        }

        if ($this->record->status === $this->updateStatusValue) {
            Notification::make()
                ->title('Tidak ada perubahan')
                ->warning()
                ->body('Status jasa sudah berada pada posisi tersebut.')
                ->send();
            return;
        }

        // Handle image upload
        try {
            $formData = $this->imageUploadForm->getState();
            $progressImages = $formData['progressImages'] ?? [];
            
            \Log::info('=== JASA IMAGE UPLOAD DEBUG ===');
            \Log::info('Form data:', $formData);
            \Log::info('Progress images count:', ['count' => is_array($progressImages) ? count($progressImages) : 0]);
            \Log::info('Progress images:', $progressImages);
            
            if (!empty($progressImages) && is_array($progressImages)) {
                // Get existing images or initialize empty array
                $existingImages = $this->record->progress_images ?? [];
                if (!is_array($existingImages)) {
                    $existingImages = [];
                }
                
                \Log::info('Existing images before update:', ['count' => count($existingImages)]);
                
                // Add each new image to array
                foreach ($progressImages as $imagePath) {
                    if ($imagePath) {
                        $fullStoragePath = storage_path('app/public/' . $imagePath);
                        $fileExists = file_exists($fullStoragePath);
                        
                        \Log::info('Processing image:', [
                            'path' => $imagePath,
                            'full_path' => $fullStoragePath,
                            'exists' => $fileExists,
                        ]);
                        
                        $existingImages[] = [
                            'path' => $imagePath,
                            'uploaded_at' => now()->format('Y-m-d H:i:s'),
                            'status_from' => $this->record->status,
                            'status_to' => $this->updateStatusValue,
                            'uploaded_by' => Auth::id(),
                        ];
                    }
                }
                
                // Update record with new images array
                $this->record->progress_images = $existingImages;
                
                \Log::info('Total images after update:', ['count' => count($existingImages)]);
            } else {
                \Log::warning('No images uploaded or invalid format');
            }
        } catch (\Exception $e) {
            \Log::error('Image upload error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            Notification::make()
                ->title('Error Upload Gambar')
                ->danger()
                ->body('Terjadi kesalahan saat mengupload gambar: ' . $e->getMessage())
                ->send();
            return;
        }

        if ($this->updateStatusValue === 'terjadwal') {
            $normalizedRole = Auth::user()?->role ? str_replace(' ', '_', strtolower(Auth::user()->role)) : null;
            
            if (in_array($normalizedRole, ['kepala_teknisi_lapangan', 'administrator'], true)) {
                try {
                    $terjadwalData = $this->terjadwalForm->getState();
                } catch (\Exception $e) {
                    $terjadwalData = [];
                }
                
                $jadwalPetugasForm = $terjadwalData['jadwalPetugas'] ?? null;
                $petugasIdsForm = $terjadwalData['petugasIds'] ?? [];

                $jadwalPetugas = $jadwalPetugasForm ?: $this->jadwalPetugas;
                $petugasIds = !empty($petugasIdsForm) ? $petugasIdsForm : $this->petugasIds;

                if (empty($petugasIds) || !$jadwalPetugas) {
                    Notification::make()
                        ->title('Form terjadwal belum lengkap')
                        ->danger()
                        ->body('Silakan isi jadwal petugas dan pilih petugas yang akan menangani jasa ini.')
                        ->send();
                    return;
                }

                $oldPetugasIds = $this->record->petugasMany()->pluck('petugas_id')->toArray();

                $this->record->jadwal_petugas = \Carbon\Carbon::parse($jadwalPetugas);
                $this->record->status = $this->updateStatusValue;
                $this->record->save();

                $this->record->petugasMany()->sync($petugasIds);

                if (!empty($petugasIds)) {
                    Petugas::whereIn('id', $petugasIds)->update(['status' => 'busy']);
                }

                if (!empty($oldPetugasIds)) {
                    $petugasToReset = array_diff($oldPetugasIds, $petugasIds);
                    if (!empty($petugasToReset)) {
                        foreach ($petugasToReset as $petugasId) {
                            $hasActiveJasa = Jasa::query()
                                ->whereHas('petugasMany', function ($query) use ($petugasId) {
                                    $query->where('petugas_id', $petugasId);
                                })
                                ->where('id', '!=', $this->record->id)
                                ->where('status', '!=', 'selesai')
                                ->exists();

                            if (!$hasActiveJasa) {
                                Petugas::where('id', $petugasId)->update(['status' => 'ready']);
                            }
                        }
                    }
                }

                $this->record->refresh();
                $this->loadRecord();

                Notification::make()
                    ->title('Success')
                    ->success()
                    ->body('Status jasa berhasil diperbarui menjadi Terjadwal. Petugas yang dipilih telah dijadwalkan.')
                    ->send();

                
                $this->jadwalPetugas = null;
                $this->petugasIds = [];
                $this->updateStatusValue = null;
                $this->terjadwalForm->fill([
                    'jadwalPetugas' => null,
                    'petugasIds' => [],
                ]);
                
                // Reset form - Clear all uploaded images
                \Log::info('[JASA] Resetting image data...');
                $this->imageData = ['progressImages' => []];
                \Log::info('[JASA] Image data after reset:', $this->imageData);
                
                \Log::info('[JASA] Dispatching page reload...');
                // Use JavaScript to force page reload instead of Livewire refresh
                $this->js('window.location.reload();');
                \Log::info('[JASA] === UPDATE COMPLETE ===');
                return;
            }
        }

        $this->record->status = $this->updateStatusValue;
        $this->record->save();
        $this->record->refresh();

        Notification::make()
            ->title('Success')
            ->success()
            ->body('Status jasa berhasil diperbarui menjadi '.ucwords($this->updateStatusValue).'.')
            ->send();
        $this->updateStatusValue = null;
        
        // Reset form - Clear all uploaded images
        \Log::info('[JASA] Resetting image data...');
        $this->imageData = ['progressImages' => []];
        \Log::info('[JASA] Image data after reset:', $this->imageData);
        
        \Log::info('[JASA] Dispatching page reload...');
        // Use JavaScript to force page reload instead of Livewire refresh
        $this->js('window.location.reload();');
        \Log::info('[JASA] === UPDATE COMPLETE ===');
    }

    protected function getAllowedStatusesForRole(): array
    {
        $role = Auth::user()?->role;
        $normalizedRole = $role ? str_replace(' ', '_', strtolower($role)) : null;

        $allStatuses = self::STATUS_FLOW;

        $roleStatusMap = [
            'admin_toko' => ['jasa baru', 'selesai'],
            'kepala_teknisi_lapangan' => ['terjadwal'],
            'petugas' => ['selesai dikerjakan'],
        ];

        if (in_array($normalizedRole, ['administrator'], true)) {
            return $allStatuses;
        }

        if ($normalizedRole && array_key_exists($normalizedRole, $roleStatusMap)) {
            return $roleStatusMap[$normalizedRole];
        }

        return [];
    }

    public function getAllowedStatusesProperty(): array
    {
        return $this->getAllowedStatusesForRole();
    }

    public function getNextSequentialStatusProperty(): ?string
    {
        if (! $this->record) {
            return null;
        }

        $currentStatus = $this->record->status;
        $currentIndex = array_search($currentStatus, self::STATUS_FLOW, true);

        if ($currentIndex === false) {
            return null;
        }

        return self::STATUS_FLOW[$currentIndex + 1] ?? null;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();

        return in_array($user->role, ['administrator', 'admin_toko', 'kepala_teknisi_lapangan', 'petugas'], true);
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        $role = $user->role ?? null;
        $normalizedRole = $role ? str_replace(' ', '_', strtolower($role)) : null;
        $statusFlow = self::STATUS_FLOW;

        $roleStatusMap = [
            'admin_toko' => ['jasa baru', 'selesai'],
            'kepala_teknisi_lapangan' => ['terjadwal'],
            'petugas' => ['selesai dikerjakan'],
        ];

        if (in_array($normalizedRole, ['administrator'], true)) {
            $allowedStatuses = $statusFlow;
        } elseif ($normalizedRole && array_key_exists($normalizedRole, $roleStatusMap)) {
            $allowedStatuses = $roleStatusMap[$normalizedRole];
        } else {
            $allowedStatuses = [];
        }

        if (empty($allowedStatuses)) {
            return null;
        }

        $count = Jasa::query()
            ->where('status', '!=', 'selesai')
            ->get()
            ->filter(function ($jasa) use ($statusFlow, $allowedStatuses) {
                $currentStatus = $jasa->status;
                $currentIndex = array_search($currentStatus, $statusFlow, true);
                if ($currentIndex === false) {
                    return false;
                }

                $nextStatus = $statusFlow[$currentIndex + 1] ?? null;
                return $nextStatus && in_array($nextStatus, $allowedStatuses, true);
            })
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

}
