<?php

namespace App\Filament\Pages;

use App\Models\Produksi;
use Filament\Pages\Page;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;

class Progress extends Page implements HasForms
{
    use InteractsWithForms;
    protected const STATUS_FLOW = [
        'produksi baru',
        'siap produksi',
        'dalam pengerjaan',
        'produksi siap diambil',
        'selesai',
    ];

    protected string $view = 'filament.pages.progress';
    
    protected static ?string $navigationLabel = 'Progress';
    
    protected static ?string $title = 'Progress';
    
    protected static ?int $navigationSort = 2;

    public ?int $selectedProduksiId = null;
    
    public ?Produksi $record = null;

    public ?string $updateStatusValue = null;

    public ?string $produksiSearch = '';


    public static function getNavigationGroup(): ?string
    {
        return 'Jasa StepNosing / Plint';
    }

    #[On('aj-refresh-produksi')]
    public function handleExternalRefresh(): void
    {
        $this->loadRecord();
        $this->dispatch('$refresh');
    }

    protected function loadRecord(): void
    {
        if ($this->selectedProduksiId) {
            $this->record = Produksi::with(['team', 'items'])->find($this->selectedProduksiId);
        } else {
            $this->record = null;
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
        $selectedProduksiId = request()->query('selectedProduksiId');
        
        if ($selectedProduksiId) {
            $this->selectedProduksiId = (int) $selectedProduksiId;
            $this->loadRecord();
        } else {
            $firstProduksi = Produksi::orderBy('createdAt', 'desc')->first();
            if ($firstProduksi) {
                $this->selectedProduksiId = $firstProduksi->id;
                $this->loadRecord();
            }
        }

        $this->produksiForm->fill([
            'selectedProduksiId' => $this->selectedProduksiId,
        ]);
        
        // Initialize imageData with proper structure
        $this->imageData = [
            'progressImages' => [],
        ];
    }

    public array $data = [];

    protected function getForms(): array
    {
        return [
            'produksiForm',
            'imageUploadForm',
        ];
    }

    public function produksiForm($form)
    {
        return $form
            ->schema([
                Select::make('selectedProduksiId')
                    ->label('Cari & Pilih Produksi')
                    ->options(function () {
                        return Produksi::query()
                            ->with('items')
                            ->orderBy('createdAt', 'desc')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($produksi) {
                                $itemsInfo = '';
                                if ($produksi->items && $produksi->items->count() > 0) {
                                    $firstItem = $produksi->items->first();
                                    $itemsInfo = $firstItem->nama_produksi;
                                    if ($produksi->items->count() > 1) {
                                        $itemsInfo .= ' (+' . ($produksi->items->count() - 1) . ')';
                                    }
                                }
                                return [
                                    $produksi->id => $produksi->no_produksi . ' | ' . $produksi->no_ref . ' - ' . $itemsInfo
                                ];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return Produksi::query()
                            ->with('items')
                            ->where(function ($query) use ($search) {
                                $searchTerm = '%' . trim($search) . '%';
                                $query->where('no_produksi', 'like', $searchTerm)
                                    ->orWhere('no_ref', 'like', $searchTerm)
                                    ->orWhereHas('items', function ($q) use ($searchTerm) {
                                        $q->where('nama_produksi', 'like', $searchTerm)
                                            ->orWhere('nama_bahan', 'like', $searchTerm);
                                    });
                            })
                            ->orderBy('createdAt', 'desc')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($produksi) {
                                $itemsInfo = '';
                                if ($produksi->items && $produksi->items->count() > 0) {
                                    $firstItem = $produksi->items->first();
                                    $itemsInfo = $firstItem->nama_produksi;
                                    if ($produksi->items->count() > 1) {
                                        $itemsInfo .= ' (+' . ($produksi->items->count() - 1) . ')';
                                    }
                                }
                                return [
                                    $produksi->id => $produksi->no_produksi . ' | ' . $produksi->no_ref . ' - ' . $itemsInfo
                                ];
                            })
                            ->toArray();
                    })
                    ->preload()
                    ->getOptionLabelUsing(function ($value): ?string {
                        $produksi = Produksi::with('items')->find($value);
                        if (!$produksi) return null;
                        
                        $itemsInfo = '';
                        if ($produksi->items && $produksi->items->count() > 0) {
                            $firstItem = $produksi->items->first();
                            $itemsInfo = $firstItem->nama_produksi;
                            if ($produksi->items->count() > 1) {
                                $itemsInfo .= ' (+' . ($produksi->items->count() - 1) . ')';
                            }
                        }
                        return $produksi->no_produksi . ' | ' . $produksi->no_ref . ' - ' . $itemsInfo;
                    })
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->selectedProduksiId = $state;
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
                    ->disk('public')
                    ->directory('progress/produksi')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->maxFiles(10)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                    ->helperText('Upload foto progress untuk dokumentasi perubahan status. Maksimal 2MB.')
                    ->downloadable()
                    ->openable(),
            ])
            ->statePath('imageData');
    }

    public array $imageData = [];

    public bool $isUploading = false;

    public function updatedSelectedProduksiId(): void
    {
        $this->loadRecord();
    }

    public function setUploadingStatus(bool $status): void
    {
        $this->isUploading = $status;
    }

    public function getProduksiOptions(): array
    {
        return Produksi::query()
            ->when($this->produksiSearch, function ($query, $search) {
                $searchTerm = '%'.trim($search).'%';

                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('no_produksi', 'like', $searchTerm)
                        ->orWhere('nama_produksi', 'like', $searchTerm)
                        ->orWhere('nama_bahan', 'like', $searchTerm);
                });
            })
            ->orderBy('createdAt', 'desc')
            ->limit(50)
            ->get()
            ->mapWithKeys(function ($produksi) {
                return [
                    $produksi->id => 
                        $produksi->no_produksi . ' | ' .
                        $produksi->nama_produksi . ' - ' .
                        $produksi->nama_bahan
                ];
            })
            ->toArray();
    }

    public function getProduksiOptionsWithStatus(): array
    {
        $allowedStatuses = $this->getAllowedStatusesForRole();
        
        return Produksi::query()
            ->when($this->produksiSearch, function ($query, $search) {
                $searchTerm = '%'.trim($search).'%';

                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('no_produksi', 'like', $searchTerm)
                        ->orWhere('nama_produksi', 'like', $searchTerm)
                        ->orWhere('nama_bahan', 'like', $searchTerm);
                });
            })
            ->orderBy('createdAt', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($produksi) use ($allowedStatuses) {
                $currentStatus = $produksi->status;
                $currentIndex = array_search($currentStatus, self::STATUS_FLOW, true);
                $nextStatus = ($currentIndex !== false && isset(self::STATUS_FLOW[$currentIndex + 1])) 
                    ? self::STATUS_FLOW[$currentIndex + 1] 
                    : null;
                
                $canUpdate = $nextStatus && in_array($nextStatus, $allowedStatuses, true) && $produksi->status !== 'selesai';
                
                return [
                    'id' => $produksi->id,
                    'label' => $produksi->no_produksi . ' | ' . $produksi->nama_produksi . ' - ' . $produksi->nama_bahan,
                    'status' => $produksi->status,
                    'can_update' => $canUpdate,
                ];
            })
            ->toArray();
    }

    public function canUpdateProduksiStatus($produksiId): bool
    {
        $produksi = Produksi::find($produksiId);
        if (!$produksi || $produksi->status === 'selesai') {
            return false;
        }

        $allowedStatuses = $this->getAllowedStatusesForRole();
        $currentStatus = $produksi->status;
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
                ->title('Data produksi tidak ditemukan')
                ->danger()
                ->body('Silakan pilih produksi terlebih dahulu.')
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
                ->body('Produksi ini telah berada pada status akhir atau tidak memiliki langkah berikutnya.')
                ->send();
            return;
        }

        if (empty($this->updateStatusValue)) {
            Notification::make()
                ->title('Gagal Memperbarui Status')
                ->danger()
                ->body('Gagal memperbarui status produksi. Silakan pilih status terlebih dahulu.')
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
                ->body('Status produksi sudah berada pada posisi tersebut.')
                ->send();
            return;
        }

        // Handle image upload
        try {
            $formData = $this->imageUploadForm->getState();
            $progressImages = $formData['progressImages'] ?? [];
            
            \Log::info('=== IMAGE UPLOAD DEBUG ===');
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

        $this->record->status = $this->updateStatusValue;
        $this->record->save();
        $this->record->refresh();

        \Log::info('=== STATUS UPDATE SUCCESS ===');
        \Log::info('Record ID:', ['id' => $this->record->id]);
        \Log::info('New status:', ['status' => $this->record->status]);
        \Log::info('Total progress images:', ['count' => count($this->record->progress_images ?? [])]);
        
        // Reset form - Clear all uploaded images
        \Log::info('Resetting image data...');
        $this->imageData = ['progressImages' => []];
        \Log::info('Image data after reset:', $this->imageData);
        
        Notification::make()
            ->title('Success')
            ->success()
            ->body('Status produksi berhasil diperbarui menjadi '.ucwords($this->updateStatusValue).'.')
            ->send();
        $this->updateStatusValue = null;
        
        \Log::info('Dispatching page reload...');
        // Use JavaScript to force page reload instead of Livewire refresh
        $this->js('window.location.reload();');
        \Log::info('=== UPDATE COMPLETE ===');
    }

    protected function getAllowedStatusesForRole(): array
    {
        $role = Auth::user()?->role;
        $normalizedRole = $role ? str_replace(' ', '_', strtolower($role)) : null;

        $allStatuses = self::STATUS_FLOW;

        $roleStatusMap = [
            'cs' => ['produksi baru', 'selesai'],
            'admin_toko' => ['siap produksi', 'produksi siap diambil', 'selesai'],
            'admin_gudang' => ['siap produksi', 'produksi siap diambil'],
            'kepala_teknisi_gudang' => ['dalam pengerjaan'],
        ];

        if (in_array($normalizedRole, ['administrator'], true)) {
            return $allStatuses;
        }

        if ($normalizedRole && array_key_exists($normalizedRole, $roleStatusMap)) {
            return $roleStatusMap[$normalizedRole];
        }

        return $allStatuses;
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

        return in_array($user->role, ['administrator', 'admin_toko', 'admin_gudang', 'kepala_teknisi_gudang'], true);
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
            'cs' => ['produksi baru', 'selesai'],
            'admin_toko' => ['siap produksi', 'produksi siap diambil', 'selesai'],
            'admin_gudang' => ['siap produksi', 'produksi siap diambil'],
            'kepala_teknisi_gudang' => ['dalam pengerjaan'],
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

        $count = Produksi::query()
            ->where('status', '!=', 'selesai')
            ->get()
            ->filter(function ($produksi) use ($statusFlow, $allowedStatuses) {
                $currentStatus = $produksi->status;
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

    public function getImageUrl(?string $imagePath): ?string
    {
        if (!$imagePath) {
            return null;
        }

        // Use Storage facade to get the URL - works better on hosting environments
        try {
            return Storage::disk('public')->url($imagePath);
        } catch (\Exception $e) {
            // Fallback to manual URL construction
            $requestUrl = request()->getSchemeAndHttpHost();
            return $requestUrl . '/storage/' . $imagePath;
        }
    }
}