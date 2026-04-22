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
        $query = User::whereNotNull('kontak');

        // For produksi_created and jasa_created, notify all superadmins regardless of branch
        if (in_array($eventType, ['produksi_created', 'jasa_created'])) {
            $query->where('role', 'superadmin');
        } else {
            // For other events, filter by branch
            $query->where('branch', $branch);
            
            // Determine which roles should be notified based on event type
            $rolesToNotify = self::getRolesForEvent($eventType, $newStatus);

            if (!empty($rolesToNotify)) {
                $query->whereIn('role', $rolesToNotify);
            }
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
                'superadmin',
            ],
            
            'produksi_status_updated' => match ($newStatus) {
                'baru', 'produksi baru' => ['superadmin'],
                'proses', 'siap produksi', 'dalam pengerjaan' => ['admin_toko'],
                'siap diambil', 'produksi siap diambil' => ['admin_toko'],
                'selesai', 'selesai dikerjakan' => [],
                default => [],
            },

            'jasa_created' => [
                'superadmin',
            ],

            'jasa_status_updated' => match ($newStatus) {
                'jasa baru' => ['superadmin'],
                'terjadwal' => ['kepala_lapangan'],
                'selesai dikerjakan' => ['superadmin'],
                'selesai' => ['admin_toko'],
                default => ['superadmin'],
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
