<?php

namespace App\Services;

use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class ErpCustomerSyncService
{
    public function sync(): array
    {
        $columns = config('erp.customer_columns');
        $connection = config('erp.connection');
        $table = config('erp.customers_table');

        $erpCustomers = DB::connection($connection)
            ->table($table)
            ->orderBy($columns['code'])
            ->get();

        $existingCodes = Pelanggan::query()
            ->whereNotNull('erp_cus_code')
            ->pluck('erp_cus_code')
            ->flip();

        $rows = [];
        $skipped = 0;
        $created = 0;
        $updated = 0;
        $now = now();

        foreach ($erpCustomers as $erpCustomer) {
            if ($this->shouldSkip($erpCustomer, $columns)) {
                $skipped++;
                continue;
            }

            $code = (string) data_get($erpCustomer, $columns['code']);
            $modifiedAt = $this->resolveModifiedAt($erpCustomer, $columns);

            if ($existingCodes->has($code)) {
                $updated++;
            } else {
                $created++;
            }

            $rows[] = [
                'erp_cus_code' => $code,
                'nama' => trim((string) data_get($erpCustomer, $columns['name'], '')),
                'kontak' => $this->resolveContact($erpCustomer, $columns),
                'alamat' => $this->buildAddress($erpCustomer, $columns),
                'createdAt' => $existingCodes->has($code)
                    ? null
                    : ($modifiedAt ?? $now),
                'UpdateAt' => $modifiedAt ?? $now,
            ];
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            $this->upsertChunk($chunk);
        }

        return [
            'total' => $erpCustomers->count(),
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
        ];
    }

    protected function upsertChunk(array $chunk): void
    {
        $insertRows = [];

        foreach ($chunk as $row) {
            $insertRows[] = [
                'erp_cus_code' => $row['erp_cus_code'],
                'nama' => $row['nama'],
                'kontak' => $row['kontak'],
                'alamat' => $row['alamat'],
                'createdAt' => $row['createdAt'] ?? now(),
                'UpdateAt' => $row['UpdateAt'],
            ];
        }

        Pelanggan::upsert(
            $insertRows,
            ['erp_cus_code'],
            ['nama', 'kontak', 'alamat', 'UpdateAt']
        );
    }

    protected function shouldSkip(object $erpCustomer, array $columns): bool
    {
        $code = trim((string) data_get($erpCustomer, $columns['code'], ''));
        $name = trim((string) data_get($erpCustomer, $columns['name'], ''));

        if ($code === '' || $name === '') {
            return true;
        }

        if (! config('erp.skip_bad_customers')) {
            return false;
        }

        $badStatus = data_get($erpCustomer, $columns['status_bad']);

        return in_array((string) $badStatus, ['1', 'Y', 'y', 'true', 'TRUE'], true);
    }

    protected function resolveContact(object $erpCustomer, array $columns): string
    {
        $phone = trim((string) data_get($erpCustomer, $columns['phone'], ''));
        $email = trim((string) data_get($erpCustomer, $columns['email'], ''));

        if ($phone !== '') {
            return $phone;
        }

        if ($email !== '') {
            return $email;
        }

        return '-';
    }

    protected function buildAddress(object $erpCustomer, array $columns): ?string
    {
        $parts = Collection::make([
            data_get($erpCustomer, $columns['address']),
            data_get($erpCustomer, $columns['town']),
            data_get($erpCustomer, $columns['state']),
        ])
            ->map(fn ($value) => trim((string) $value))
            ->filter(fn (string $value) => $value !== '')
            ->values();

        if ($parts->isEmpty()) {
            return null;
        }

        return $parts->implode(', ');
    }

    protected function resolveModifiedAt(object $erpCustomer, array $columns): Carbon
    {
        $modifiedAt = data_get($erpCustomer, $columns['modified_at']);

        if (blank($modifiedAt)) {
            return now();
        }

        return Carbon::parse($modifiedAt);
    }
}
