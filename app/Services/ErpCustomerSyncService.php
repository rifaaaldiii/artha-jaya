<?php

namespace App\Services;

use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;

class ErpCustomerSyncService
{
    /**
     * @return array{created: int, updated: int, skipped: int}
     */
    public function sync(): array
    {
        $created = 0;
        $updated = 0;
        $skipped = 0;

        $skipBadCustomers = (bool) config('services.erp.skip_bad_customers', true);

        $erpCustomers = DB::connection('supabase')
            ->table('erp_customers')
            ->orderBy('cus_code')
            ->get();

        DB::connection()->transaction(function () use (
            $erpCustomers,
            $skipBadCustomers,
            &$created,
            &$updated,
            &$skipped,
        ): void {
            foreach ($erpCustomers as $customer) {
                if (blank($customer->cus_code)) {
                    $skipped++;

                    continue;
                }

                if ($skipBadCustomers && ($customer->status_bad_yn ?? '0') !== '0') {
                    $skipped++;

                    continue;
                }

                $pelanggan = Pelanggan::query()
                    ->where('customer_code', $customer->cus_code)
                    ->first();

                $attributes = [
                    'nama' => $this->resolveNama($customer),
                    'kontak' => $this->resolveKontak($customer),
                    'alamat' => $this->buildAlamat($customer),
                    'UpdateAt' => $customer->date_time_modified ?? now(),
                ];

                if ($pelanggan) {
                    $pelanggan->update($attributes);
                    $updated++;

                    continue;
                }

                Pelanggan::create([
                    'customer_code' => $customer->cus_code,
                    ...$attributes,
                    'createdAt' => $customer->date_time_modified ?? now(),
                ]);
                $created++;
            }
        });

        return compact('created', 'updated', 'skipped');
    }

    private function resolveNama(object $customer): string
    {
        $nama = trim((string) ($customer->cus_name ?? ''));

        return $nama !== '' ? $nama : 'Tanpa Nama';
    }

    private function resolveKontak(object $customer): string
    {
        $tel = trim((string) ($customer->tel ?? ''));
        if ($tel !== '') {
            return $tel;
        }

        $email = trim((string) ($customer->email ?? ''));
        if ($email !== '') {
            return $email;
        }

        return '-';
    }

    private function buildAlamat(object $customer): string
    {
        $parts = array_filter([
            trim((string) ($customer->address ?? '')),
            trim((string) ($customer->town ?? '')),
            trim((string) ($customer->state ?? '')),
        ], fn (string $part): bool => $part !== '');

        return $parts !== [] ? implode(', ', $parts) : '-';
    }
}
