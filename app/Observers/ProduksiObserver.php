<?php

namespace App\Observers;

use App\Models\Produksi;
use App\Services\FonteWhatsAppService;
use App\Services\WhatsAppNotificationHelper;
use App\Support\Polling\PollChannel;
use App\Support\Polling\PollTriggerStore;
use Illuminate\Support\Facades\Log;

class ProduksiObserver
{
    public function saved(Produksi $produksi): void
    {
        PollTriggerStore::bump([PollChannel::PRODUKSI, PollChannel::DASHBOARD]);
        
        // Send WhatsApp notification
        $this->sendWhatsAppNotification($produksi);
    }

    public function deleted(Produksi $produksi): void
    {
        PollTriggerStore::bump([PollChannel::PRODUKSI, PollChannel::DASHBOARD]);
    }

    /**
     * Send WhatsApp notification for Produksi events
     */
    protected function sendWhatsAppNotification(Produksi $produksi): void
    {
        try {
            // Skip if no branch
            if (blank($produksi->branch)) {
                return;
            }

            $whatsAppService = app(FonteWhatsAppService::class);
            
            // Check if this is a new record
            if ($produksi->wasRecentlyCreated) {
                // CRITICAL: Delay notification to ensure items are saved first
                // Items are saved AFTER parent model in Filament
                dispatch(function () use ($produksi, $whatsAppService) {
                    $this->notifyProduksiCreated($produksi, $whatsAppService);
                })->afterResponse();
            } 
            // Check if status changed
            elseif ($produksi->wasChanged('status')) {
                $this->notifyStatusChanged($produksi, $whatsAppService);
            }
        } catch (\Exception $e) {
            Log::error('Produksi WhatsApp notification failed', [
                'produksi_id' => $produksi->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify about new Produksi
     */
    protected function notifyProduksiCreated(Produksi $produksi, FonteWhatsAppService $whatsAppService): void
    {
        // Items should already be saved because we use afterResponse()
        $produksi->load(['team', 'items', 'pelanggan']);

        // Debug log
        Log::info('=== PRODUKSI CREATED DEBUG ===', [
            'produksi_id' => $produksi->id,
            'no_produksi' => $produksi->no_produksi,
            'items_count' => $produksi->items->count(),
        ]);

        // Skip if no items
        if ($produksi->items->isEmpty()) {
            Log::warning('Produksi has no items, skipping notification', [
                'produksi_id' => $produksi->id,
            ]);
            return;
        }

        $recipients = WhatsAppNotificationHelper::getRecipientsByBranch(
            $produksi->branch,
            'produksi_created'
        );

        if ($recipients->isEmpty()) {
            return;
        }

        // Build items data
        $itemsData = [];
        foreach ($produksi->items as $item) {
            $itemsData[] = [
                'nama_produksi' => $item->nama_produksi,
                'jumlah' => $item->jumlah,
                'nama_bahan' => $item->nama_bahan,
                'harga' => $item->harga ?? 0,
            ];
        }

        Log::info('Items data prepared for WhatsApp', [
            'itemsData' => $itemsData,
            'items_count' => count($itemsData),
        ]);

        $produksiData = [
            'produksi_id' => $produksi->id,
            'no_produksi' => $produksi->no_produksi,
            'no_ref' => $produksi->no_ref,
            'branch' => $produksi->branch,
            'team' => $produksi->team?->nama ?? '-',
            'pelanggan' => $produksi->pelanggan?->nama ?? '-',
            'kontak' => $produksi->pelanggan?->kontak ?? '-',
            'alamat' => $produksi->alamat ?? '-',
            'items' => $itemsData,
            'catatan' => $produksi->catatan ?? '-',
        ];

        // Send notifications via helper
        WhatsAppNotificationHelper::sendProduksiNotification($recipients, $produksiData);

        Log::info('Produksi created notification sent', [
            'produksi_id' => $produksi->id,
            'recipients_count' => $recipients->count(),
            'items_count' => count($itemsData),
        ]);
    }

    /**
     * Notify about status change
     */
    protected function notifyStatusChanged(Produksi $produksi, FonteWhatsAppService $whatsAppService): void
    {
        $oldStatus = $produksi->getOriginal('status');
        $newStatus = $produksi->status;

        $recipients = WhatsAppNotificationHelper::getRecipientsByBranch(
            $produksi->branch,
            'produksi_status_updated',
            $newStatus
        );

        if ($recipients->isEmpty()) {
            return;
        }

        $produksi->load(['team', 'pelanggan']);

        $produksiData = [
            'produksi_id' => $produksi->id,
            'no_produksi' => $produksi->no_produksi,
            'no_ref' => $produksi->no_ref,
            'branch' => $produksi->branch,
            'pelanggan' => $produksi->pelanggan?->nama ?? '-',
            'kontak' => $produksi->pelanggan?->kontak ?? '-',
            'alamat' => $produksi->alamat ?? '-',
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'team' => $produksi->team?->nama ?? '-',
            'catatan' => $produksi->catatan ?? '-',
        ];

        // Send notifications via helper
        WhatsAppNotificationHelper::sendProduksiStatusUpdate($recipients, $produksiData);

        Log::info('Produksi status update notification sent', [
            'produksi_id' => $produksi->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'recipients_count' => $recipients->count(),
        ]);
    }
}

