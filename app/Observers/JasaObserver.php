<?php

namespace App\Observers;

use App\Models\Jasa;
use App\Services\FonteWhatsAppService;
use App\Services\WhatsAppNotificationHelper;
use App\Support\Polling\PollChannel;
use App\Support\Polling\PollTriggerStore;
use Illuminate\Support\Facades\Log;

class JasaObserver
{
    public function saved(Jasa $jasa): void
    {
        PollTriggerStore::bump([PollChannel::JASA, PollChannel::DASHBOARD]);
        
        // Send WhatsApp notification
        $this->sendWhatsAppNotification($jasa);
    }

    public function deleted(Jasa $jasa): void
    {
        PollTriggerStore::bump([PollChannel::JASA, PollChannel::DASHBOARD]);
    }

    /**
     * Send WhatsApp notification for Jasa events
     */
    protected function sendWhatsAppNotification(Jasa $jasa): void
    {
        try {
            // Skip if no branch
            if (blank($jasa->branch)) {
                return;
            }

            $whatsAppService = app(FonteWhatsAppService::class);
            
            // Check if this is a new record
            if ($jasa->wasRecentlyCreated) {
                // CRITICAL: Delay notification to ensure items are saved first
                // Items are saved AFTER parent model in Filament
                dispatch(function () use ($jasa, $whatsAppService) {
                    $this->notifyJasaCreated($jasa, $whatsAppService);
                })->afterResponse();
            } 
            // Check if status changed
            elseif ($jasa->wasChanged('status')) {
                $this->notifyStatusChanged($jasa, $whatsAppService);
            }
        } catch (\Exception $e) {
            Log::error('Jasa WhatsApp notification failed', [
                'jasa_id' => $jasa->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify about new Jasa
     */
    protected function notifyJasaCreated(Jasa $jasa, FonteWhatsAppService $whatsAppService): void
    {
        // Items should already be saved because we use afterResponse()
        $jasa->load(['pelanggan', 'items']);

        // Debug log
        Log::info('=== JASA CREATED DEBUG ===', [
            'jasa_id' => $jasa->id,
            'no_jasa' => $jasa->no_jasa,
            'items_count' => $jasa->items->count(),
        ]);

        // Skip if no items
        if ($jasa->items->isEmpty()) {
            Log::warning('Jasa has no items, skipping notification', [
                'jasa_id' => $jasa->id,
            ]);
            return;
        }

        $recipients = WhatsAppNotificationHelper::getRecipientsByBranch(
            $jasa->branch,
            'jasa_created'
        );

        if ($recipients->isEmpty()) {
            return;
        }

        // Build items data
        $itemsData = [];
        foreach ($jasa->items as $item) {
            $itemsData[] = [
                'jenis_layanan' => $item->jenis_layanan,
                'jumlah' => $item->jumlah ?? null,
                'harga' => $item->harga ?? null,
            ];
        }

        Log::info('Items data prepared for WhatsApp', [
            'itemsData' => $itemsData,
            'items_count' => count($itemsData),
        ]);

        $jasaData = [
            'jasa_id' => $jasa->id,
            'no_jasa' => $jasa->no_jasa,
            'no_ref' => $jasa->no_ref,
            'branch' => $jasa->branch,
            'pelanggan' => $jasa->pelanggan?->nama ?? '-',
            'alamat' => $jasa->alamat ?? $jasa->pelanggan?->alamat ?? '-',
            'items' => $itemsData,
            'jadwal' => $jasa->jadwal ? $jasa->jadwal->format('d/m/Y H:i') : '-',
            'catatan' => $jasa->catatan ?? '-',
        ];

        // Send notifications via helper
        WhatsAppNotificationHelper::sendJasaNotification($recipients, $jasaData);

        Log::info('Jasa created notification sent', [
            'jasa_id' => $jasa->id,
            'recipients_count' => $recipients->count(),
            'items_count' => count($itemsData),
        ]);
    }

    /**
     * Notify about status change
     */
    protected function notifyStatusChanged(Jasa $jasa, FonteWhatsAppService $whatsAppService): void
    {
        $oldStatus = $jasa->getOriginal('status');
        $newStatus = $jasa->status;

        $recipients = WhatsAppNotificationHelper::getRecipientsByBranch(
            $jasa->branch,
            'jasa_status_updated',
            $newStatus
        );

        if ($recipients->isEmpty()) {
            return;
        }

        $jasa->load(['pelanggan', 'petugasMany']);

        $jadwalPetugas = '';
        if ($jasa->jadwal_petugas) {
            $jadwalPetugas = $jasa->jadwal_petugas->format('d/m/Y H:i');
        }

        $jasaData = [
            'jasa_id' => $jasa->id,
            'no_jasa' => $jasa->no_jasa,
            'no_ref' => $jasa->no_ref,
            'branch' => $jasa->branch,
            'pelanggan' => $jasa->pelanggan?->nama ?? '-',
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'jadwal_petugas' => $jadwalPetugas,
        ];

        // Send notifications via helper
        WhatsAppNotificationHelper::sendJasaStatusUpdate($recipients, $jasaData);

        Log::info('Jasa status update notification sent', [
            'jasa_id' => $jasa->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'recipients_count' => $recipients->count(),
        ]);
    }
}

