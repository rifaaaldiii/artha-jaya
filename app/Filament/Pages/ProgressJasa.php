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
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;

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
    public ?string $jadwalPetugas = null;
    public array $petugasIds = [];
    public array $selectedPetugasIds = []; // For multi-select in blade
    public array $imageData = [];
    public array $terjadwalData = [];
    public array $data = [];

    public static function getNavigationGroup(): ?string
    {
        return 'Jasa & Layanan';
    }

    protected function getForms(): array
    {
        return [
            'jasaForm',
            'terjadwalForm',
            'imageUploadForm',
        ];
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
            $this->record = Jasa::with([
                'pelanggan',
                'petugasMany',
                'items'
            ])->find($this->selectedJasaId);
            
            if ($this->record) {
                $this->petugasIds = $this->record->petugasMany->pluck('id')->toArray();
                $this->selectedPetugasIds = $this->petugasIds; // Sync with multi-select
                $this->jadwalPetugas = $this->record->jadwal_petugas?->format('Y-m-d\TH:i:s');
                
                $this->terjadwalForm->fill([
                    'jadwalPetugas' => $this->jadwalPetugas,
                    'petugasIds' => $this->petugasIds,
                ]);
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
            $this->loadRecord();
        }
    }

    public function mount(): void
    {
        $selectedJasaId = request()->query('selectedJasaId');
        
        if ($selectedJasaId) {
            $this->selectedJasaId = (int) $selectedJasaId;
        } else {
            $this->selectedJasaId = Jasa::orderBy('createdAt', 'desc')->value('id');
        }

        $this->loadRecord();

        $this->jasaForm->fill([
            'selectedJasaId' => $this->selectedJasaId,
        ]);

        $this->imageUploadForm->fill([
            'progressImages' => [],
        ]);

        $this->imageData = [
            'progressImages' => [],
        ];

        // Auto-set status value if next status is terjadwal
        $nextStatus = $this->getNextSequentialStatusProperty();
        if ($nextStatus === 'terjadwal') {
            $this->updateStatusValue = 'terjadwal';
        }
    }

    public function jasaForm($form)
    {
        return $form
            ->schema([
                Select::make('selectedJasaId')
                    ->label('Cari & Pilih Jasa')
                    ->options(function () {
                        $user = Auth::user();
                        if (!$user) {
                            return [];
                        }

                        $query = Jasa::query()
                            ->with(['pelanggan', 'items'])
                            ->where('status', '!=', 'selesai');

                        // Filter by branch: if user has branch, filter by it; otherwise fetch all
                        if ($user->branch) {
                            $query->where('branch', $user->branch);
                        }

                        return $query->orderBy('createdAt', 'desc')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($jasa) {
                                $customerName = $jasa->pelanggan?->nama ?? 'No Customer';
                                $itemsInfo = '';
                                if ($jasa->items && $jasa->items->count() > 0) {
                                    $firstItem = $jasa->items->first();
                                    $itemsInfo = $firstItem->jenis_layanan ?? 'Item';
                                    if ($jasa->items->count() > 1) {
                                        $itemsInfo .= ' (+' . ($jasa->items->count() - 1) . ')';
                                    }
                                }
                                return [
                                    $jasa->id => $jasa->no_jasa . ' | ' . $jasa->no_ref . ' - ' . $customerName . ' - ' . $itemsInfo
                                ];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        $user = Auth::user();
                        if (!$user) {
                            return [];
                        }

                        $query = Jasa::query()
                            ->with(['pelanggan', 'items'])
                            ->where('status', '!=', 'selesai');

                        // Filter by branch: if user has branch, filter by it; otherwise fetch all
                        if ($user->branch) {
                            $query->where('branch', $user->branch);
                        }

                        $searchTerm = '%' . trim($search) . '%';
                        $query->where(function ($q) use ($searchTerm) {
                            $q->where('no_jasa', 'like', $searchTerm)
                                ->orWhere('no_ref', 'like', $searchTerm)
                                ->orWhereHas('pelanggan', function ($q) use ($searchTerm) {
                                    $q->where('nama', 'like', $searchTerm);
                                })
                                ->orWhereHas('items', function ($q) use ($searchTerm) {
                                    $q->where('jenis_layanan', 'like', $searchTerm);
                                });
                        });

                        return $query->orderBy('createdAt', 'desc')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($jasa) {
                                $customerName = $jasa->pelanggan?->nama ?? 'No Customer';
                                $itemsInfo = '';
                                if ($jasa->items && $jasa->items->count() > 0) {
                                    $firstItem = $jasa->items->first();
                                    $itemsInfo = $firstItem->jenis_layanan ?? 'Item';
                                    if ($jasa->items->count() > 1) {
                                        $itemsInfo .= ' (+' . ($jasa->items->count() - 1) . ')';
                                    }
                                }
                                return [
                                    $jasa->id => $jasa->no_jasa . ' | ' . $jasa->no_ref . ' - ' . $customerName . ' - ' . $itemsInfo
                                ];
                            })
                            ->toArray();
                    })
                    ->preload()
                    ->getOptionLabelUsing(function ($value): ?string {
                        $user = Auth::user();
                        if (!$user) {
                            return null;
                        }

                        $query = Jasa::with(['pelanggan', 'items']);
                        
                        // Filter by branch: if user has branch, filter by it
                        if ($user->branch) {
                            $query->where('branch', $user->branch);
                        }
                        
                        $jasa = $query->find($value);
                        if (!$jasa) return null;
                        
                        $customerName = $jasa->pelanggan?->nama ?? 'No Customer';
                        $itemsInfo = '';
                        if ($jasa->items && $jasa->items->count() > 0) {
                            $firstItem = $jasa->items->first();
                            $itemsInfo = $firstItem->jenis_layanan ?? 'Item';
                            if ($jasa->items->count() > 1) {
                                $itemsInfo .= ' (+' . ($jasa->items->count() - 1) . ')';
                            }
                        }
                        return $jasa->no_jasa . ' | ' . $jasa->no_ref . ' - ' . $customerName . ' - ' . $itemsInfo;
                    })
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->selectedJasaId = $state;
                        $this->loadRecord();
                    }),
            ])
            ->statePath('data');
    }

    public function terjadwalForm($form)
    {
        return $form
            ->schema([
                DateTimePicker::make('jadwalPetugas')
                    ->label('Jadwal Petugas')
                    ->required()
                    ->native(false)
                    ->timezone('Asia/Jakarta')
                    ->displayFormat('d/m/Y H:i')
                    ->format('Y-m-d H:i:s')
                    ->default(now())
                    ->required(),
                
                Select::make('petugasIds')
                    ->label('Pilih Petugas')
                    ->multiple()
                    ->required()
                    ->options(function () {
                        return Petugas::query()
                            ->select('id', 'nama', 'kontak')
                            ->orderBy('nama')
                            ->pluck('nama', 'id')
                            ->toArray();
                    })
                    ->searchable()
                    ->preload(),
            ])
            ->statePath('terjadwalData');
    }

    public function imageUploadForm($form)
    {
        return $form
            ->schema([
                FileUpload::make('progressImages')
                    ->label('Upload Foto Progress')
                    ->image()
                    ->multiple()
                    ->disk('public_html_progress')
                    ->directory('jasa')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->maxFiles(10)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                    ->downloadable()
                    ->openable(),
            ])
            ->statePath('imageData');
    }

    public function updateStatus(): void
    {
        if (!$this->record) {
            Notification::make()
                ->title('Data jasa tidak ditemukan')
                ->danger()
                ->send();
            return;
        }

        $nextStatus = $this->getNextSequentialStatusProperty();

        if (!$nextStatus) {
            Notification::make()
                ->title('Tidak ada status lanjutan')
                ->warning()
                ->send();
            return;
        }

        // Auto-set status value to next status
        $this->updateStatusValue = $nextStatus;

        $allowedStatuses = $this->getAllowedStatusesForRole();

        if (!in_array($this->updateStatusValue, $allowedStatuses, true)) {
            Notification::make()
                ->title('Status tidak diizinkan')
                ->danger()
                ->send();
            return;
        }

        // Handle image upload
        try {
            $formData = $this->imageUploadForm->getState();
            $progressImages = $formData['progressImages'] ?? [];
            
            if (!empty($progressImages) && is_array($progressImages)) {
                $existingImages = $this->record->progress_images ?? [];
                if (!is_array($existingImages)) {
                    $existingImages = [];
                }
                
                foreach ($progressImages as $imagePath) {
                    if ($imagePath) {
                        // Image is already uploaded to public_html/progress/jasa
                        $existingImages[] = [
                            'path' => $imagePath,
                            'uploaded_at' => now()->format('Y-m-d H:i:s'),
                            'status_from' => $this->record->status,
                            'status_to' => $this->updateStatusValue,
                            'uploaded_by' => Auth::id(),
                        ];
                    }
                }
                
                $this->record->progress_images = $existingImages;
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error Upload Gambar')
                ->danger()
                ->body($e->getMessage())
                ->send();
            return;
        }

        // Handle terjadwal status
        if ($this->updateStatusValue === 'terjadwal') {
            $normalizedRole = str_replace(' ', '_', strtolower(Auth::user()?->role ?? ''));
            
            \Log::info('ProgressJasa updateStatus - terjadwal', [
                'role' => $normalizedRole,
                'is_superadmin' => in_array($normalizedRole, ['superadmin'], true),
                'jadwalPetugas_property' => $this->jadwalPetugas,
                'selectedPetugasIds' => $this->selectedPetugasIds,
            ]);
            
            if (in_array($normalizedRole, ['superadmin'], true)) {
                // Coba ambil dari Filament form terlebih dahulu, fallback ke blade form
                try {
                    $terjadwalData = $this->terjadwalForm->getState();
                    $jadwalPetugas = $terjadwalData['jadwalPetugas'] ?? null;
                    $petugasIds = $terjadwalData['petugasIds'] ?? [];
                    \Log::info('Got data from terjadwalForm', ['jadwalPetugas' => $jadwalPetugas, 'petugasIds' => $petugasIds]);
                } catch (\Exception $e) {
                    // Fallback ke blade form data
                    $jadwalPetugas = $this->jadwalPetugas;
                    $petugasIds = $this->selectedPetugasIds;
                    \Log::info('Using fallback blade form data', ['jadwalPetugas' => $jadwalPetugas, 'petugasIds' => $petugasIds]);
                }

                if (empty($petugasIds) || !$jadwalPetugas) {
                    \Log::warning('Form data incomplete', ['jadwalPetugas' => $jadwalPetugas, 'petugasIds' => $petugasIds]);
                    Notification::make()
                        ->title('Form terjadwal belum lengkap')
                        ->danger()
                        ->body('Silakan isi jadwal dan pilih petugas.')
                        ->send();
                    return;
                }

                $oldPetugasIds = $this->record->petugasMany->pluck('id')->toArray();

                // Parse jadwal
                $jadwalParsed = \Carbon\Carbon::parse($jadwalPetugas);
                
                // Direct database update untuk memastikan data tersimpan
                DB::table('jasas')
                    ->where('id', $this->record->id)
                    ->update([
                        'jadwal_petugas' => $jadwalParsed,
                        'petugas_id' => !empty($petugasIds) ? $petugasIds[0] : null,
                        'status' => $this->updateStatusValue,
                        'updateAt' => now(),
                    ]);

                \Log::info('Direct DB update completed', [
                    'jasa_id' => $this->record->id,
                    'jadwal_petugas' => $jadwalParsed,
                    'petugas_id' => !empty($petugasIds) ? $petugasIds[0] : null,
                    'status' => $this->updateStatusValue,
                ]);
                
                // Refresh record
                $this->record->refresh();

                // Generate update token for kepala_lapangan
                try {
                    \Log::info('Generating update token in ProgressJasa', [
                        'jasa_id' => $this->record->id,
                    ]);
                    
                    $token = $this->record->generateUpdateToken();
                    $updateLink = route('jasa.public.update', ['token' => $token]);
                    
                    \Log::info('Update token generated in ProgressJasa', [
                        'jasa_id' => $this->record->id,
                        'update_link' => $updateLink,
                    ]);
                    
                    // Send WhatsApp notification to kepala_lapangan
                    $recipients = \App\Services\WhatsAppNotificationHelper::getRecipientsByBranch(
                        $this->record->branch,
                        'jasa_status_updated',
                        'terjadwal'
                    );
                    
                    \Log::info('Recipients for terjadwal notification', [
                        'count' => $recipients->count(),
                        'recipients' => $recipients->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'role' => $u->role, 'kontak' => $u->kontak])->toArray(),
                    ]);
                    
                    if ($recipients->isNotEmpty()) {
                        $jasaData = [
                            'jasa_id' => $this->record->id,
                            'no_jasa' => $this->record->no_jasa,
                            'no_ref' => $this->record->no_ref,
                            'branch' => $this->record->branch,
                            'pelanggan' => $this->record->pelanggan?->nama ?? '-',
                            'kontak' => $this->record->pelanggan?->kontak ?? '-',
                            'alamat' => $this->record->alamat ?? $this->record->pelanggan?->alamat ?? '-',
                            'old_status' => 'jasa baru',
                            'new_status' => 'terjadwal',
                            'jadwal_petugas' => $jadwalParsed->format('d/m/Y H:i'),
                            'update_link' => $updateLink,
                        ];
                        
                        \App\Services\WhatsAppNotificationHelper::sendJasaStatusUpdate($recipients, $jasaData);
                        
                        \Log::info('WhatsApp notification sent to kepala_lapangan', [
                            'jasa_id' => $this->record->id,
                            'recipients_count' => $recipients->count(),
                        ]);
                    } else {
                        \Log::warning('No kepala_lapangan found to notify', [
                            'jasa_id' => $this->record->id,
                            'branch' => $this->record->branch,
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to generate token or send notification in ProgressJasa', [
                        'jasa_id' => $this->record->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }

                $this->record->petugasMany()->sync($petugasIds);

                if (!empty($petugasIds)) {
                    Petugas::whereIn('id', $petugasIds)->update(['status' => 'busy']);
                }

                if (!empty($oldPetugasIds)) {
                    $petugasToReset = array_diff($oldPetugasIds, $petugasIds);
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

                Notification::make()
                    ->title('Success')
                    ->success()
                    ->body('Status berhasil diperbarui menjadi Terjadwal.')
                    ->send();

                // Refresh the record and dispatch event instead of full reload
                $this->refresh();
                $this->dispatch('$refresh');
                return;
            }
        }

        $this->record->status = $this->updateStatusValue;
        $this->record->save();

        Notification::make()
            ->title('Success')
            ->success()
            ->body('Status berhasil diperbarui menjadi ' . ucwords($this->updateStatusValue) . '.')
            ->send();

        // Refresh the record and dispatch event instead of full reload
        $this->refresh();
        $this->dispatch('$refresh');
        
        // Dispatch event to refresh navigation badge globally
        $this->dispatch('refresh-navigation-badge');
    }

    protected function getAllowedStatusesForRole(): array
    {
        $normalizedRole = str_replace(' ', '_', strtolower(Auth::user()?->role ?? ''));

        $roleStatusMap = [
            'admin_toko' => ['jasa baru'],
            'superadmin' => ['terjadwal', 'selesai'],
            'kepala_lapangan' => ['selesai dikerjakan'],
            'administrator' => self::STATUS_FLOW,
        ];

        return $roleStatusMap[$normalizedRole] ?? [];
    }

    protected static function getAllowedStatusesForRoleStatic(): array
    {
        $user = Auth::user();
        $normalizedRole = str_replace(' ', '_', strtolower($user?->role ?? ''));

        $roleStatusMap = [
            'admin_toko' => ['jasa baru'],
            'superadmin' => ['terjadwal', 'selesai'],
            'kepala_lapangan' => ['selesai dikerjakan'],
            'administrator' => self::STATUS_FLOW,
        ];

        return $roleStatusMap[$normalizedRole] ?? [];
    }

    public function getNextSequentialStatusProperty(): ?string
    {
        if (!$this->record) {
            return null;
        }

        $currentIndex = array_search($this->record->status, self::STATUS_FLOW, true);

        if ($currentIndex === false) {
            return null;
        }

        return self::STATUS_FLOW[$currentIndex + 1] ?? null;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        return in_array($user?->role, ['administrator', 'admin_toko', 'kepala_lapangan', 'superadmin'], true);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getNavigationBadgeCount();
    }

    #[Computed]
    public function navigationBadgeCount(): ?string
    {
        return static::getNavigationBadgeCount();
    }

    protected static function getNavigationBadgeCount(): ?string
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        // Check if user has permission to update status
        $allowedStatuses = self::getAllowedStatusesForRoleStatic();
        
        // If user cannot update any status, hide the badge
        if (empty($allowedStatuses)) {
            return null;
        }

        // Build query for counting jasas
        $query = Jasa::query()
            ->where('status', '!=', 'selesai');

        // Filter by branch: if user has branch, filter by it; otherwise fetch all
        if ($user->branch) {
            $query->where('branch', $user->branch);
        }

        $statusFlow = self::STATUS_FLOW;
        
        $count = $query->get()
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

    #[On('refresh-navigation-badge')]
    public function refreshNavigationBadge(): void
    {
        $this->dispatch('$refresh');
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

        try {
            // Remove leading slash if present
            $cleanPath = ltrim($imagePath, '/');
            
            // Build URL directly from public_html/progress
            $baseUrl = rtrim(request()->getSchemeAndHttpHost(), '/');
            $url = $baseUrl . '/progress/' . $cleanPath;
            
            // Fix double slashes in URL
            return preg_replace('#([^:])//+#', '$1/', $url);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Getter untuk available petugas (digunakan di blade untuk multi-select)
    public function getAvailablePetugasProperty()
    {
        return Petugas::query()
            ->select('id', 'nama', 'kontak', 'status')
            ->where('status', 'ready')
            ->orderBy('nama')
            ->get();
    }
}
