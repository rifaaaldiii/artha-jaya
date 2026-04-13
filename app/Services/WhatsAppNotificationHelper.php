<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationHelper
{
    /**
     * Get users to notify based on branch and event type
     * 
     * @param string $branch Branch code (AJP, AJC, AJK, AJR)
     * @param string $eventType Event type (produksi_created, produksi_status_updated, jasa_created, jasa_status_updated)
     * @param string|null $newStatus New status (for status updates)
     * @return Collection Collection of User models
     */
    public static function getRecipientsByBranch(string $branch, string $eventType, ?string $newStatus = null): Collection
    {
        $query = User::where('branch', $branch)
            ->whereNotNull('kontak');

        // Determine which roles should be notified based on event type
        $rolesToNotify = self::getRolesForEvent($eventType, $newStatus);

        if (!empty($rolesToNotify)) {
            $query->whereIn('role', $rolesToNotify);
        }

        return $query->get();
    }

    /**
     * Get roles that should be notified for a specific event
     */
    protected static function getRolesForEvent(string $eventType, ?string $newStatus = null): array
    {
        return match ($eventType) {
            'produksi_created' => [
                'administrator',
                'admin_toko',
                'admin_gudang',
                'kepala_teknisi_gudang',
            ],
            
            'produksi_status_updated' => match ($newStatus) {
                'produksi baru' => ['administrator', 'admin_toko'],
                'siap produksi' => ['administrator', 'admin_gudang', 'kepala_teknisi_gudang'],
                'dalam pengerjaan' => ['administrator', 'kepala_teknisi_gudang'],
                'produksi siap diambil' => ['administrator', 'admin_toko'],
                'selesai' => ['administrator', 'admin_toko', 'admin_gudang'],
                default => ['administrator', 'admin_toko'],
            },

            'jasa_created' => [
                'administrator',
                'admin_toko',
                'kepala_teknisi_lapangan',
            ],

            'jasa_status_updated' => match ($newStatus) {
                'jasa baru' => ['administrator', 'admin_toko'],
                'terjadwal' => ['administrator', 'kepala_teknisi_lapangan', 'petugas'],
                'selesai dikerjakan' => ['administrator', 'admin_toko'],
                'selesai' => ['administrator', 'admin_toko', 'kepala_teknisi_lapangan'],
                default => ['administrator', 'admin_toko'],
            },

            default => ['administrator'],
        };
    }

    /**
     * Send notifications to all recipients
     * 
     * @param Collection $recipients Collection of User models
     * @param callable $messageBuilder Function to build message for each recipient
     * @return array Array of results
     */
    public static function sendToRecipients(Collection $recipients, callable $messageBuilder): array
    {
        $whatsAppService = app(FonteWhatsAppService::class);
        $results = [];

        foreach ($recipients as $user) {
            if (blank($user->kontak)) {
                continue;
            }

            $message = $messageBuilder($user);
            
            if ($message === null) {
                Log::warning('Message builder returned null', [
                    'user_id' => $user->id,
                ]);
                continue;
            }
            
            $result = $whatsAppService->sendMessage($user->kontak, $message);

            $results[] = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'phone' => $user->kontak,
                'result' => $result,
            ];
        }

        return $results;
    }

    /**
     * Send Produksi notification to recipients
     */
    public static function sendProduksiNotification(Collection $recipients, array $produksiData): array
    {
        $whatsAppService = app(FonteWhatsAppService::class);
        $results = [];

        foreach ($recipients as $user) {
            if (blank($user->kontak)) {
                continue;
            }

            $result = $whatsAppService->sendProduksiNotification($user->kontak, $produksiData);

            $results[] = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'phone' => $user->kontak,
                'result' => $result,
            ];
        }

        return $results;
    }

    /**
     * Send Produksi status update notification
     */
    public static function sendProduksiStatusUpdate(Collection $recipients, array $produksiData): array
    {
        $whatsAppService = app(FonteWhatsAppService::class);
        $results = [];

        foreach ($recipients as $user) {
            if (blank($user->kontak)) {
                continue;
            }

            $result = $whatsAppService->sendProduksiStatusUpdate($user->kontak, $produksiData);

            $results[] = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'phone' => $user->kontak,
                'result' => $result,
            ];
        }

        return $results;
    }

    /**
     * Send Jasa notification to recipients
     */
    public static function sendJasaNotification(Collection $recipients, array $jasaData): array
    {
        $whatsAppService = app(FonteWhatsAppService::class);
        $results = [];

        foreach ($recipients as $user) {
            if (blank($user->kontak)) {
                continue;
            }

            $result = $whatsAppService->sendJasaNotification($user->kontak, $jasaData);

            $results[] = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'phone' => $user->kontak,
                'result' => $result,
            ];
        }

        return $results;
    }

    /**
     * Send Jasa status update notification
     */
    public static function sendJasaStatusUpdate(Collection $recipients, array $jasaData): array
    {
        $whatsAppService = app(FonteWhatsAppService::class);
        $results = [];

        foreach ($recipients as $user) {
            if (blank($user->kontak)) {
                continue;
            }

            $result = $whatsAppService->sendJasaStatusUpdate($user->kontak, $jasaData);

            $results[] = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'phone' => $user->kontak,
                'result' => $result,
            ];
        }

        return $results;
    }

    /**
     * Validate if phone number is valid for WhatsApp
     */
    public static function isValidPhoneNumber(?string $phone): bool
    {
        if (blank($phone)) {
            return false;
        }

        // Remove formatting
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        // Check if it's a valid Indonesian phone number
        return preg_match('/^(62|0)\d{8,12}$/', $phone) === 1;
    }

    /**
     * Format phone number for display
     */
    public static function formatPhoneForDisplay(string $phone): string
    {
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        if (str_starts_with($phone, '62')) {
            return '0' . substr($phone, 2);
        }

        return $phone;
    }
}
