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

class Progress extends Page implements HasForms
{
    use InteractsWithForms;
    protected const STATUS_FLOW = [
        'produksi baru',
        'siap produksi',
        'dalam pengerjaan',
        'selesai dikerjakan',
        'lolos qc',
        'produksi siap diambil',
        'selesai',
    ];

    protected string $view = 'filament.pages.progress';
    
    protected static ?string $navigationLabel = 'Progress Product';
    
    protected static ?string $title = 'Progress';
    
    protected static ?int $navigationSort = 2;

    public ?int $selectedProduksiId = null;
    
    public ?Produksi $record = null;

    public ?string $updateStatusValue = null;

    public ?string $produksiSearch = '';


    public static function getNavigationGroup(): ?string
    {
        return 'Product';
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
            $this->record = Produksi::with('team')->find($this->selectedProduksiId);
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
    }

    public array $data = [];

    protected function getForms(): array
    {
        return [
            'produksiForm',
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
                            ->orderBy('createdAt', 'desc')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($produksi) {
                                return [
                                    $produksi->id => $produksi->no_produksi . ' | ' . $produksi->nama_produksi . ' - ' . $produksi->nama_bahan
                                ];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return Produksi::query()
                            ->where(function ($query) use ($search) {
                                $searchTerm = '%' . trim($search) . '%';
                                $query->where('no_produksi', 'like', $searchTerm)
                                    ->orWhere('nama_produksi', 'like', $searchTerm)
                                    ->orWhere('nama_bahan', 'like', $searchTerm);
                            })
                            ->orderBy('createdAt', 'desc')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($produksi) {
                                return [
                                    $produksi->id => $produksi->no_produksi . ' | ' . $produksi->nama_produksi . ' - ' . $produksi->nama_bahan
                                ];
                            })
                            ->toArray();
                    })
                    ->preload()
                    ->getOptionLabelUsing(fn ($value): ?string => Produksi::find($value)?->no_produksi . ' | ' . Produksi::find($value)?->nama_produksi . ' - ' . Produksi::find($value)?->nama_bahan)
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->selectedProduksiId = $state;
                        $this->loadRecord();
                    }),
            ])
            ->statePath('data');
    }

    public function updatedSelectedProduksiId(): void
    {
        $this->loadRecord();
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

        $this->record->status = $this->updateStatusValue;
        $this->record->save();
        $this->record->refresh();

        Notification::make()
            ->title('Success')
            ->success()
            ->body('Status produksi berhasil diperbarui menjadi '.ucwords($this->updateStatusValue).'.')
            ->send();
        $this->updateStatusValue = null;
    }

    protected function getAllowedStatusesForRole(): array
    {
        $role = Auth::user()?->role;
        $normalizedRole = $role ? str_replace(' ', '_', strtolower($role)) : null;

        $allStatuses = self::STATUS_FLOW;

        $roleStatusMap = [
            'admin_toko' => ['produksi baru', 'selesai'],
            'admin_gudang' => ['siap produksi', 'produksi siap diambil'],
            'kepala_teknisi_gudang' => ['dalam pengerjaan', 'lolos qc'],
            'petukang' => ['selesai dikerjakan'],
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

        return in_array($user->role, ['administrator', 'admin_toko', 'kepala_teknisi_gudang', 'petukang', 'admin_gudang'], true);
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
            'admin_toko' => ['produksi baru', 'selesai'],
            'admin_gudang' => ['siap produksi', 'produksi siap diambil'],
            'kepala_teknisi_gudang' => ['dalam pengerjaan', 'lolos qc'],
            'petukang' => ['selesai dikerjakan'],
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
}