<?php

namespace App\Filament\Pages;

use App\Models\jasa;
use App\Models\petugas;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

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
    
    protected static ?string $navigationLabel = 'Progress Jasa';
    
    protected static ?string $title = 'Progress Jasa';
    
    protected static ?int $navigationSort = 3;

    public ?int $selectedJasaId = null;
    
    public ?jasa $record = null;

    public ?string $updateStatusValue = null;

    public ?string $jasaSearch = '';

    // Properties untuk form update status terjadwal
    public ?string $jadwalPetugas = null;
    
    public array $petugasIds = [];

    public static function getNavigationGroup(): ?string
    {
        return 'Jasa & Layanan';
    }

    protected function loadRecord(): void
    {
        if ($this->selectedJasaId) {
            $this->record = jasa::with(['petugas', 'petugasMany', 'pelanggan'])->find($this->selectedJasaId);
            
            // Load petugas yang sudah dipilih untuk form terjadwal
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

    protected function getPollingInterval(): ?string
    {
        return '3s';
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
            $firstJasa = jasa::orderBy('createdAt', 'desc')->first();
            if ($firstJasa) {
                $this->selectedJasaId = $firstJasa->id;
                $this->loadRecord();
            }
        }

        $this->jasaForm->fill([
            'selectedJasaId' => $this->selectedJasaId,
        ]);

        // Inisialisasi form terjadwal jika record ada
        if ($this->record) {
            $this->terjadwalForm->fill([
                'jadwalPetugas' => $this->record->jadwal_petugas?->format('Y-m-d\TH:i:s'),
                'petugasIds' => $this->record->petugasMany()->pluck('petugas_id')->toArray(),
            ]);
        }
    }

    public array $data = [];

    protected function getForms(): array
    {
        return [
            'jasaForm',
            'terjadwalForm',
        ];
    }

    public function jasaForm($form)
    {
        return $form
            ->schema([
                Select::make('selectedJasaId')
                    ->label('Cari & Pilih Jasa')
                    ->options(function () {
                        return jasa::query()
                            ->orderBy('createdAt', 'desc')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($jasa) {
                                return [
                                    $jasa->id => $jasa->no_jasa . ' | ' . $jasa->no_ref . ' - ' . $jasa->jenis_layanan
                                ];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return jasa::query()
                            ->where(function ($query) use ($search) {
                                $searchTerm = '%' . trim($search) . '%';
                                $query->where('no_jasa', 'like', $searchTerm)
                                    ->orWhere('no_ref', 'like', $searchTerm)
                                    ->orWhere('jenis_layanan', 'like', $searchTerm);
                            })
                            ->orderBy('createdAt', 'desc')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($jasa) {
                                return [
                                    $jasa->id => $jasa->no_jasa . ' | ' . $jasa->no_ref . ' - ' . $jasa->jenis_layanan
                                ];
                            })
                            ->toArray();
                    })
                    ->preload()
                    ->getOptionLabelUsing(fn ($value): ?string => jasa::find($value)?->no_jasa . ' | ' . jasa::find($value)?->no_ref . ' - ' . jasa::find($value)?->jenis_layanan)
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
                    ->displayFormat('d F Y, H:i')
                    ->timezone('Asia/Jakarta')
                    ->helperText('Pilih tanggal dan waktu untuk petugas melaksanakan jasa ini.'),
                
                Select::make('petugasIds')
                    ->label('Pilih Petugas')
                    ->multiple()
                    ->required()
                    ->options(function () {
                        // Include petugas yang sudah dipilih meskipun statusnya busy
                        $currentPetugasIds = $this->record?->petugasMany()->pluck('petugas_id')->toArray() ?? [];
                        
                        return petugas::query()
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
        
        // Load form terjadwal setelah record di-load
        if ($this->record) {
            $this->terjadwalForm->fill([
                'jadwalPetugas' => $this->record->jadwal_petugas?->format('Y-m-d\TH:i:s'),
                'petugasIds' => $this->record->petugasMany()->pluck('petugas_id')->toArray(),
            ]);
        }
    }

    public function canUpdateJasaStatus($jasaId): bool
    {
        $jasa = jasa::find($jasaId);
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

        if ($this->updateStatusValue !== $nextStatus) {
            Notification::make()
                ->title('Status harus berurutan')
                ->warning()
                ->body('Anda hanya dapat memperbarui status ke langkah berikutnya yaitu '.ucwords($nextStatus).'.')
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

        // Jika update ke 'terjadwal', validasi form terjadwal
        if ($this->updateStatusValue === 'terjadwal') {
            $normalizedRole = Auth::user()?->role ? str_replace(' ', '_', strtolower(Auth::user()->role)) : null;
            
            // Hanya kepala_teknisi_lapangan yang bisa update ke terjadwal
            if (in_array($normalizedRole, ['kepala_teknisi_lapangan', 'admin_toko','administrator'], true)) {
                // Get data dari form terjadwal
                try {
                    $terjadwalData = $this->terjadwalForm->getState();
                } catch (\Exception $e) {
                    $terjadwalData = [];
                }
                
                $jadwalPetugasForm = $terjadwalData['jadwalPetugas'] ?? null;
                $petugasIdsForm = $terjadwalData['petugasIds'] ?? [];

                // Gunakan data dari form jika ada, jika tidak gunakan property
                $jadwalPetugas = $jadwalPetugasForm ?: $this->jadwalPetugas;
                $petugasIds = !empty($petugasIdsForm) ? $petugasIdsForm : $this->petugasIds;

                // Validasi jadwal_petugas dan petugas_ids dari form terjadwal
                if (empty($petugasIds) || !$jadwalPetugas) {
                    Notification::make()
                        ->title('Form terjadwal belum lengkap')
                        ->danger()
                        ->body('Silakan isi jadwal petugas dan pilih petugas yang akan menangani jasa ini.')
                        ->send();
                    return;
                }

                // Get old petugas IDs untuk update status mereka nanti
                $oldPetugasIds = $this->record->petugasMany()->pluck('petugas_id')->toArray();

                // Update jadwal_petugas dan sync petugas
                $this->record->jadwal_petugas = \Carbon\Carbon::parse($jadwalPetugas);
                $this->record->status = $this->updateStatusValue;
                $this->record->save();

                // Sync petugas many-to-many (akan otomatis handle detach yang lama)
                $this->record->petugasMany()->sync($petugasIds);

                // Update status petugas baru menjadi busy
                if (!empty($petugasIds)) {
                    petugas::whereIn('id', $petugasIds)->update(['status' => 'busy']);
                }

                // Update status petugas lama menjadi ready jika tidak ada jasa aktif lagi
                if (!empty($oldPetugasIds)) {
                    $petugasToReset = array_diff($oldPetugasIds, $petugasIds);
                    if (!empty($petugasToReset)) {
                        foreach ($petugasToReset as $petugasId) {
                            // Cek apakah petugas masih memiliki jasa aktif
                            $hasActiveJasa = jasa::query()
                                ->whereHas('petugasMany', function ($query) use ($petugasId) {
                                    $query->where('petugas_id', $petugasId);
                                })
                                ->where('id', '!=', $this->record->id)
                                ->where('status', '!=', 'selesai')
                                ->exists();

                            if (!$hasActiveJasa) {
                                petugas::where('id', $petugasId)->update(['status' => 'ready']);
                            }
                        }
                    }
                }

                $this->record->refresh();
                $this->loadRecord(); // Reload untuk refresh relations

                Notification::make()
                    ->title('Status diperbarui')
                    ->success()
                    ->body('Status jasa berhasil diperbarui menjadi Terjadwal. Petugas yang dipilih telah dijadwalkan.')
                    ->send();

                // Reset form terjadwal dan update status value
                $this->jadwalPetugas = null;
                $this->petugasIds = [];
                $this->updateStatusValue = null;
                $this->terjadwalForm->fill([
                    'jadwalPetugas' => null,
                    'petugasIds' => [],
                ]);
                return;
            }
        }

        // Update status normal untuk status selain 'terjadwal'
        $this->record->status = $this->updateStatusValue;
        $this->record->save();
        $this->record->refresh();

        Notification::make()
            ->title('Status diperbarui')
            ->success()
            ->body('Status jasa berhasil diperbarui menjadi '.ucwords($this->updateStatusValue).'.')
            ->send();
        $this->updateStatusValue = null;
    }

    protected function getAllowedStatusesForRole(): array
    {
        $role = Auth::user()?->role;
        $normalizedRole = $role ? str_replace(' ', '_', strtolower($role)) : null;

        $allStatuses = self::STATUS_FLOW;

        $roleStatusMap = [
            // 'admin_toko' => ['jasa baru', 'selesai'],
            'kepala_teknisi_lapangan' => ['terjadwal'],
            'petugas' => ['selesai dikerjakan'],
        ];

        if (in_array($normalizedRole, ['administrator', 'admin_toko'], true)) {
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
}
