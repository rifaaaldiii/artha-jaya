<?php

namespace App\Console\Commands;

use App\Services\FonteWhatsAppService;
use App\Services\WhatsAppNotificationHelper;
use Illuminate\Console\Command;

class TestWhatsAppIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:test {phone?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test WhatsApp integration with Fonte API';

    /**
     * Execute the console command.
     */
    public function handle(FonteWhatsAppService $whatsAppService)
    {
        $this->info('🔍 Testing WhatsApp Integration...');
        $this->newLine();

        // Test 1: Configuration
        $this->info('Test 1: Checking Configuration');
        $apiKey = config('services.fonte.api_key');
        $apiUrl = config('services.fonte.api_url');
        
        if ($apiKey) {
            $this->info("✅ API Key: " . substr($apiKey, 0, 10) . '...');
        } else {
            $this->error("❌ API Key not configured");
            return 1;
        }

        if ($apiUrl) {
            $this->info("✅ API URL: {$apiUrl}");
        } else {
            $this->error("❌ API URL not configured");
            return 1;
        }

        $this->newLine();

        // Test 2: Phone number formatting
        $this->info('Test 2: Phone Number Formatting');
        $testPhones = ['08123456789', '628123456789', '+62 812-3456-789'];
        
        foreach ($testPhones as $phone) {
            $formatted = $this->formatPhone($phone);
            $this->info("  {$phone} → {$formatted}");
        }

        $this->newLine();

        // Test 3: Check users with phone numbers
        $this->info('Test 3: Users with Phone Numbers');
        $usersWithPhone = \App\Models\User::whereNotNull('kontak')->get();
        
        if ($usersWithPhone->count() > 0) {
            $this->info("✅ Found {$usersWithPhone->count()} users with phone numbers:");
            foreach ($usersWithPhone as $user) {
                $this->info("  - {$user->name} ({$user->branch}): {$user->kontak}");
            }
        } else {
            $this->warn("⚠️  No users with phone numbers found");
        }

        $this->newLine();

        // Test 4: Send test message
        $phone = $this->argument('phone');
        
        if (!$phone) {
            $phone = $this->ask('Enter phone number to send test message (or press Enter to skip)');
        }

        if ($phone) {
            $this->info('Test 4: Sending Test Message');
            
            $message = "🧪 *TEST MESSAGE*\n\n";
            $message .= "This is a test message from Artha Jaya system.\n";
            $message .= "Time: " . now()->format('d/m/Y H:i:s') . "\n";
            $message .= "System: WhatsApp Integration Test\n\n";
            $message .= "If you receive this, the integration is working! ✅";

            $this->info("Sending to: {$phone}");
            
            $result = $whatsAppService->sendMessage($phone, $message);

            if ($result['success']) {
                $this->info("✅ Message sent successfully!");
                $this->info("Response: " . json_encode($result['data'] ?? $result, JSON_PRETTY_PRINT));
            } else {
                $this->error("❌ Failed to send message");
                $this->error("Error: " . ($result['error'] ?? 'Unknown error'));
            }
        } else {
            $this->info('⏭️  Skipped sending test message');
        }

        $this->newLine();
        $this->info('✅ WhatsApp Integration Test Complete!');
        
        return 0;
    }

    protected function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
