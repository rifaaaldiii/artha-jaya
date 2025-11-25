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
                    petugas::whereIn('id', $petugasIds)->update(['status' => 'busy']);
                }

                if (!empty($oldPetugasIds)) {
                    $petugasToReset = array_diff($oldPetugasIds, $petugasIds);
                    if (!empty($petugasToReset)) {
                        foreach ($petugasToReset as $petugasId) {
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
                $this->loadRecord();

                Notification::make()
                    ->title('Status diperbarui')
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
                return;
            }
        }

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

        $count = jasa::query()
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
