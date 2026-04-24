<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonteWhatsAppService
{
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.fonte.api_key');
        $this->apiUrl = config('services.fonte.api_url');
    }

    /**
     * Send WhatsApp message to a phone number
     * 
     * @param string $phoneNumber Phone number (e.g., 628123456789 or 08123456789)
     * @param string $message Message content
     * @return array Response from API
     */
    public function sendMessage(string $phoneNumber, string $message): array
    {
        try {
            // Format phone number - keep as is with 0 prefix or 62
            // Fonte will handle country code automatically
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);

            // Fonte API uses POST with form data, not JSON
            // Authorization header does NOT use Bearer, just the token
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->asForm()->post($this->apiUrl, [
                'target' => $phoneNumber,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Check Fonte API response structure
                if (isset($responseData['status']) && $responseData['status'] == true) {
                    Log::info('WhatsApp sent successfully via Fonte', [
                        'phone' => $phoneNumber,
                        'response' => $responseData,
                    ]);

                    return [
                        'success' => true,
                        'data' => $responseData,
                        'status_code' => $response->status(),
                    ];
                } else {
                    Log::error('Fonte API returned error', [
                        'phone' => $phoneNumber,
                        'response' => $responseData,
                    ]);

                    return [
                        'success' => false,
                        'error' => $responseData['reason'] ?? $responseData['message'] ?? 'Unknown error',
                        'status_code' => $response->status(),
                    ];
                }
            }

            Log::error('Fonte API HTTP error', [
                'phone' => $phoneNumber,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => $response->body(),
                'status_code' => $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp sending failed', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send notification about new Produksi
     * 
     * @param string $phoneNumber Phone number
     * @param array $produksiData Produksi data
     * @return array Response
     */
    public function sendProduksiNotification(string $phoneNumber, array $produksiData): array
    {
        $message = $this->buildProduksiMessage($produksiData);
        return $this->sendMessage($phoneNumber, $message);
    }

    /**
     * Send notification about Produksi status update
     * 
     * @param string $phoneNumber Phone number
     * @param array $produksiData Produksi data
     * @return array Response
     */
    public function sendProduksiStatusUpdate(string $phoneNumber, array $produksiData): array
    {
        $message = $this->buildProduksiStatusMessage($produksiData);
        return $this->sendMessage($phoneNumber, $message);
    }

    /**
     * Send notification about new Jasa
     * 
     * @param string $phoneNumber Phone number
     * @param array $jasaData Jasa data
     * @return array Response
     */
    public function sendJasaNotification(string $phoneNumber, array $jasaData): array
    {
        $message = $this->buildJasaMessage($jasaData);
        return $this->sendMessage($phoneNumber, $message);
    }

    /**
     * Send notification about Jasa status update
     * 
     * @param string $phoneNumber Phone number
     * @param array $jasaData Jasa data
     * @return array Response
     */
    public function sendJasaStatusUpdate(string $phoneNumber, array $jasaData): array
    {
        $message = $this->buildJasaStatusMessage($jasaData);
        return $this->sendMessage($phoneNumber, $message);
    }

    /**
     * Build message for new Produksi
     */
    protected function buildProduksiMessage(array $data): string
    {
        // Log untuk debug
        Log::info('Building Produksi message', [
            'has_items' => isset($data['items']),
            'items_count' => isset($data['items']) ? count($data['items']) : 0,
            'items_data' => $data['items'] ?? null,
        ]);

        $message = "*PRODUKSI BARU*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $message .= "📋 *Informasi Produksi*\n";
        $message .= "No. Produksi: *{$data['no_produksi']}*\n";
        $message .= "No. Ref: {$data['no_ref']}\n";
        $message .= "Branch: {$data['branch']}\n";
        $message .= "Team: {$data['team']}\n\n";
        
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "👤 *Data Pelanggan*\n";
        $message .= "Nama: {$data['pelanggan']}\n";
        $message .= "Kontak: {$data['kontak']}\n";
        $message .= "Alamat: {$data['alamat']}\n\n";
        
        // Items section - ENSURE THIS WORKS
        if (!empty($data['items']) && is_array($data['items'])) {
            $itemCount = count($data['items']);
            $message .= "📦 *Detail Item* ({$itemCount} item):\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n";
            
            foreach ($data['items'] as $index => $item) {
                $itemNum = $index + 1;
                $message .= "\n*{$itemNum}. {$item['nama_produksi']}*\n";
                $message .= "   Bahan: {$item['nama_bahan']}\n";
                $message .= "   Jumlah: {$item['jumlah']}\n";
                if (isset($item['harga']) && $item['harga'] > 0) {
                    $hargaFormatted = number_format($item['harga'], 0, ',', '.');
                    $message .= "   Harga: Rp {$hargaFormatted}\n";
                }
            }
        } else {
            $message .= "⚠️ *Tidak ada item*\n";
        }
        
        $message .= "\n━━━━━━━━━━━━━━━━━━━━\n";
        
        // Catatan
        if (!empty($data['catatan']) && $data['catatan'] !== 'Tidak ada catatan') {
            $message .= "\n📝 *Catatan:*\n";
            $message .= "{$data['catatan']}\n";
        }
        
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "💡 _Mohon segera ditindak lanjuti_\n";
        $message .= "🔗 " . url('/admin/progress?selectedProduksiId=' . $data['produksi_id']);

        return $message;
    }

    /**
     * Build message for Produksi status update
     */
    protected function buildProduksiStatusMessage(array $data): string
    {
        $message = "*UPDATE STATUS PRODUKSI*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $message .= "📋 *Informasi*\n";
        $message .= "No. Produksi: *{$data['no_produksi']}*\n";
        $message .= "No. Ref: {$data['no_ref']}\n";
        $message .= "Branch: {$data['branch']}\n";
        if (!empty($data['team'])) {
            $message .= "Team: {$data['team']}\n";
        }
        
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "👤 *Data Pelanggan*\n";
        $message .= "Nama: {$data['pelanggan']}\n";
        $message .= "Kontak: {$data['kontak']}\n";
        $message .= "Alamat: {$data['alamat']}\n";
        
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "\n📊 *Perubahan Status*\n";
        $message .= "Dari: {$data['old_status']}\n";
        $message .= "Menjadi: *{$data['new_status']}*\n";

        // Catatan
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        if (!empty($data['catatan']) && $data['catatan'] !== 'Tidak ada catatan') {
            $message .= "\n📝 *Catatan:*\n";
            $message .= "{$data['catatan']}\n\n";
        }
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "💡 _Status telah diperbarui_\n";
        $message .= "🔗 " . url('/admin/progress?selectedProduksiId=' . $data['produksi_id']);

        return $message;
    }

    /**
     * Build message for new Jasa
     */
    protected function buildJasaMessage(array $data): string
    {
        // Log untuk debug
        Log::info('Building Jasa message', [
            'has_items' => isset($data['items']),
            'items_count' => isset($data['items']) ? count($data['items']) : 0,
            'items_data' => $data['items'] ?? null,
        ]);

        $message = "*JASA BARU*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $message .= "📋 *Informasi Jasa*\n";
        $message .= "No. Jasa: *{$data['no_jasa']}*\n";
        $message .= "No. Ref: {$data['no_ref']}\n";
        $message .= "Branch: {$data['branch']}\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        
        $message .= "👤 *Data Pelanggan*\n";
        $message .= "Nama: {$data['pelanggan']}\n";
        $message .= "Kontak: {$data['kontak']}\n";
        $message .= "Alamat: {$data['alamat']}\n\n";
        
        // Items section - ENSURE THIS WORKS
        if (!empty($data['items']) && is_array($data['items'])) {
            $itemCount = count($data['items']);
            $message .= "📦 *Detail Layanan* ({$itemCount} item):\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n";
            
            foreach ($data['items'] as $index => $item) {
                $itemNum = $index + 1;
                $message .= "\n*{$itemNum}. {$item['jenis_layanan']}*\n";
                if (isset($item['jumlah']) && $item['jumlah'] > 0) {
                    $message .= "   Jumlah: {$item['jumlah']}\n";
                }
                if (isset($item['harga']) && $item['harga'] > 0) {
                    $hargaFormatted = number_format($item['harga'], 0, ',', '.');
                    $message .= "   Harga: Rp {$hargaFormatted}\n";
                }
            }
        } else {
            $message .= "⚠️ *Tidak ada item*\n";
        }
        
        $message .= "\n━━━━━━━━━━━━━━━━━━━━\n";
        
        // Jadwal
        if (!empty($data['jadwal']) && $data['jadwal'] !== '-') {
            $message .= "\n📅 *Jadwal:*\n";
            $message .= "Tanggal: {$data['jadwal']}\n\n";
        }
        
        // Catatan
        if (!empty($data['catatan']) && $data['catatan'] !== '-') {
            $message .= "📝 *Catatan:*\n";
            $message .= "{$data['catatan']}\n\n";
        }
        
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "💡 _Mohon segera ditindak lanjuti_\n";
        $message .= "🔗 " . url('/admin/progress-jasa?selectedJasaId=' . $data['jasa_id']);

        return $message;
    }

    /**
     * Build message for Jasa status update
     */
    protected function buildJasaStatusMessage(array $data): string
    {
        $message = "*UPDATE STATUS JASA*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $message .= "📋 *Informasi*\n";
        $message .= "No. Jasa: *{$data['no_jasa']}*\n";
        $message .= "No. Ref: {$data['no_ref']}\n";
        $message .= "Branch: {$data['branch']}\n";
        $message .= "Pelanggan: {$data['pelanggan']}\n";

        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "👤 *Data Pelanggan*\n";
        $message .= "Nama: {$data['pelanggan']}\n";
        $message .= "Kontak: {$data['kontak']}\n";
        $message .= "Alamat: {$data['alamat']}\n";
        
        $message .= "📊 *Perubahan Status*\n";
        $message .= "Dari: {$data['old_status']}\n";
        $message .= "Menjadi: *{$data['new_status']}*\n\n";
        
        if (!empty($data['jadwal_petugas'])) {
            $message .= "📅 *Jadwal Petugas:*\n";
            $message .= "Tanggal: {$data['jadwal_petugas']}\n\n";
        }
        
        $message .= "━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "💡 _Status telah diperbarui_\n";
        $message .= "🔗 " . url('/admin/progress-jasa?selectedJasaId=' . $data['jasa_id']);

        return $message;
    }

    /**
     * Format phone number for Fonte API
     * Fonte handles countryCode automatically, so we keep 0 prefix
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove spaces, dashes, and parentheses
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        // Remove + sign if present
        $phone = str_replace('+', '', $phone);

        // If starts with 62, convert to 0 format for Fonte
        // Since we're using countryCode=62, it will add 62 automatically
        if (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        }

        // Ensure it starts with 0
        if (!str_starts_with($phone, '0')) {
            $phone = '0' . $phone;
        }

        return $phone;
    }
}
