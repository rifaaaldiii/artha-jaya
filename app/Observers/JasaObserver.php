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
        PollTriggerStore::bump([PollChannel::JASA, PollChannel::DASHBOARD, PollChannel::NAVIGATION_BADGE]);
        
        // Send WhatsApp notification
        $this->sendWhatsAppNotification($jasa);
    }

    public function deleted(Jasa $jasa): void
    {
        PollTriggerStore::bump([PollChannel::JASA, PollChannel::DASHBOARD, PollChannel::NAVIGATION_BADGE]);
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
            'kontak' => $jasa->pelanggan?->kontak ?? '-',
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

        Log::info('Jasa status changed - checking recipients', [
            'jasa_id' => $jasa->id,
            'no_jasa' => $jasa->no_jasa,
            'branch' => $jasa->branch,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ]);

        $recipients = WhatsAppNotificationHelper::getRecipientsByBranch(
            $jasa->branch,
            'jasa_status_updated',
            $newStatus
        );

        Log::info('Recipients found for jasa status update', [
            'recipients_count' => $recipients->count(),
            'recipients' => $recipients->map(fn($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'role' => $u->role,
                'branch' => $u->branch,
                'kontak' => $u->kontak,
            ])->toArray(),
        ]);

        if ($recipients->isEmpty()) {
            Log::warning('No recipients found for jasa status update', [
                'jasa_id' => $jasa->id,
                'new_status' => $newStatus,
                'branch' => $jasa->branch,
            ]);
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
            'kontak' => $jasa->pelanggan?->kontak ?? '-',
            'alamat' => $jasa->alamat ?? $jasa->pelanggan?->alamat ?? '-',
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'jadwal_petugas' => $jadwalPetugas,
        ];

        // Generate update token if status changed to 'terjadwal'
        if ($newStatus === 'terjadwal') {
            try {
                Log::info('Attempting to generate update token', [
                    'jasa_id' => $jasa->id,
                    'no_jasa' => $jasa->no_jasa,
                ]);
                
                $token = $jasa->generateUpdateToken();
                $updateLink = route('jasa.public.update', ['token' => $token]);
                $jasaData['update_link'] = $updateLink;
                
                Log::info('Update token generated successfully', [
                    'jasa_id' => $jasa->id,
                    'no_jasa' => $jasa->no_jasa,
                    'update_link' => $updateLink,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to generate update token - will still send WhatsApp without link', [
                    'jasa_id' => $jasa->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                // Continue with notification even if token fails
            }
        } else {
            Log::info('Status is not terjadwal, skipping token generation', [
                'new_status' => $newStatus,
            ]);
        }

        // Send notifications via helper
        Log::info('Sending WhatsApp notification', [
            'recipients_count' => $recipients->count(),
            'has_update_link' => isset($jasaData['update_link']),
        ]);
        
        WhatsAppNotificationHelper::sendJasaStatusUpdate($recipients, $jasaData);

        Log::info('Jasa status update notification sent', [
            'jasa_id' => $jasa->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'recipients_count' => $recipients->count(),
        ]);
    }
}

