<?php

namespace App\Filament\Pages;

use App\Models\produksi;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Progress extends Page
{
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
    
    public ?produksi $record = null;

    public ?string $updateStatusValue = null;

    public ?string $produksiSearch = '';

    public static function getNavigationGroup(): ?string
    {
        return 'Product';
    }


    protected function loadRecord(): void
    {
        if ($this->selectedProduksiId) {
            $this->record = produksi::with('team')->find($this->selectedProduksiId);
        } else {
            $this->record = null;
        }
    }

    // Enable polling for real-time updates (every 3 seconds)
    protected function getPollingInterval(): ?string
    {
        return '3s';
    }

    // Refresh record when polling
    public function refresh(): void
    {
        if ($this->record) {
            $this->record->refresh();
        }
    }

    public function mount(): void
    {
        // Check if selectedProduksiId is provided from query string
        $selectedProduksiId = request()->query('selectedProduksiId');
        
        if ($selectedProduksiId) {
            $this->selectedProduksiId = (int) $selectedProduksiId;
            $this->loadRecord();
        } else {
            // Load the first produksi if none selected
            $firstProduksi = produksi::orderBy('createdAt', 'desc')->first();
            if ($firstProduksi) {
                $this->selectedProduksiId = $firstProduksi->id;
                $this->loadRecord();
            }
        }
    }

    public function updatedSelectedProduksiId(): void
    {
        $this->loadRecord();
    }

    public function getProduksiOptions(): array
    {
        return produksi::query()
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
                ->body('Status produksi sudah berada pada posisi tersebut.')
                ->send();
            return;
        }

        $this->record->status = $this->updateStatusValue;
        $this->record->save();
        $this->record->refresh();

        Notification::make()
            ->title('Status diperbarui')
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
            'admin_gudang' => ['siap produksi', 'produksi siap diambil'],
            'kepala_teknisi_gudang' => ['dalam pengerjaan', 'lolos qc'],
            'petukang' => ['selesai dikerjakan'],
        ];

        if (in_array($normalizedRole, ['administrator', 'admin_toko'], true)) {
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
}