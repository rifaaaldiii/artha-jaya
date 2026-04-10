<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Filter Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
            {{ $this->filterForm }}
        </div>

        {{-- Results Count --}}
        @if(!$singleNumber)
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">
                    Hasil Laporan ({{ $resultCount }} data)
                </h2>
            </div>

            @if(count($previewRows) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            @if($filters['report_type'] === 'jasa')
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Ref</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Petugas</th>
                            @else
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Ref</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branch</th>
                            @endif
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($previewRows as $index => $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3">{{ (($currentPage - 1) * $perPage) + $index + 1 }}</td>
                            <td class="px-4 py-3 font-medium">{{ $row['number'] }}</td>
                            @if($filters['report_type'] === 'jasa')
                            <td class="px-4 py-3">{{ $row['no_ref'] }}</td>
                            <td class="px-4 py-3">{{ $row['customer'] ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $row['petugas'] ?? '-' }}</td>
                            @else
                            <td class="px-4 py-3">{{ $row['no_ref'] }}</td>
                            <td class="px-4 py-3">{{ $row['branch'] }}</td>
                            @endif
                            <td class="px-4 py-3">
                                <span class="text-sm">{{ $row['items_count'] }} items</span>
                            </td>
                            <td class="px-4 py-3 text-right font-medium">
                                Rp {{ number_format($row['total_harga'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $row['created_at'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <button onclick="window.openReport('{{ $row['number'] }}', '{{ $filters['report_type'] }}', 'report')"
                                            class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors">
                                        Report
                                    </button>
                                    <button onclick="window.openReport('{{ $row['number'] }}', '{{ $filters['report_type'] }}', 'invoice')"
                                            class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded transition-colors">
                                        Invoice
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @php
                $totalPages = ceil($resultCount / $perPage);
            @endphp
            @if($totalPages > 1)
            <div class="mt-4 flex justify-between items-center">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Halaman {{ $currentPage }} dari {{ $totalPages }}
                </div>
                <div class="flex gap-2">
                    <button wire:click="previousPage" 
                            @if($currentPage <= 1) disabled @endif
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                        Previous
                    </button>
                    
                    @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                    <button wire:click="goToPage({{ $i }})"
                            class="px-4 py-2 rounded {{ $i === $currentPage ? 'bg-primary-600 text-white' : 'bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                        {{ $i }}
                    </button>
                    @endfor

                    <button wire:click="nextPage"
                            @if($currentPage >= $totalPages) disabled @endif
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                        Next
                    </button>
                </div>
            </div>
            @endif
            @else
            <div class="text-center py-8">
                <p class="text-gray-500 dark:text-gray-400">Tidak ada data untuk ditampilkan.</p>
            </div>
            @endif
        </div>
        @endif

        {{-- Single Report View --}}
        @if($singleNumber && $reportData)
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">
                        {{ $reportType === 'jasa' ? 'Laporan Jasa' : 'Laporan Produksi' }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400">No: {{ $reportData['number'] }}</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="window.openReport('{{ $reportData['number'] }}', '{{ $reportType }}', 'report')"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors">
                        Download Report
                    </button>
                    <button onclick="window.openReport('{{ $reportData['number'] }}', '{{ $reportType }}', 'invoice')"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded transition-colors">
                        Download Invoice
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                @if($reportType === 'jasa')
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">No Ref</label>
                    <p class="text-lg">{{ $reportData['no_ref'] ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Pelanggan</label>
                    <p class="text-lg">{{ $reportData['pelanggan'] ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Petugas</label>
                    <p class="text-lg">{{ $reportData['petugas'] ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Jadwal</label>
                    <p class="text-lg">{{ $reportData['scheduled_at'] ?? '-' }}</p>
                </div>
                @else
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">No Ref</label>
                    <p class="text-lg">{{ $reportData['no_ref'] ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Branch</label>
                    <p class="text-lg">{{ $reportData['branch'] ?? '-' }}</p>
                </div>
                @endif
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                    <p class="text-lg">
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($reportData['status'] === 'selesai') bg-green-100 text-green-800
                            @elseif($reportData['status'] === 'terjadwal') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucwords($reportData['status']) }}
                        </span>
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</label>
                    <p class="text-lg">{{ $reportData['created_at'] }}</p>
                </div>
                @if(isset($reportData['catatan']))
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</label>
                    <p class="text-lg">{{ $reportData['catatan'] ?? '-' }}</p>
                </div>
                @endif
            </div>

            {{-- Items Table --}}
            @if(isset($reportData['items']) && count($reportData['items']) > 0)
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">Detail Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                @if($reportType === 'jasa')
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jasa</th>
                                @else
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Produksi</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bahan</th>
                                @endif
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($reportData['items'] as $item)
                            <tr>
                                @if($reportType === 'jasa')
                                <td class="px-4 py-2">{{ $item['nama_jasa'] ?? $item['jenis_layanan'] }}</td>
                                @else
                                <td class="px-4 py-2">{{ $item['nama_produksi'] }}</td>
                                <td class="px-4 py-2">{{ $item['nama_bahan'] }}</td>
                                @endif
                                <td class="px-4 py-2">{{ $item['jumlah'] }}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <td colspan="{{ $reportType === 'jasa' ? 2 : 3 }}" class="px-4 py-2 font-bold">Total</td>
                                <td class="px-4 py-2 text-right font-bold">
                                    Rp {{ number_format($reportData['total_harga'], 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>

    <script>
        window.openReport = function(number, type, format) {
            const url = `/admin/report?report_type=${type}&single_number=${number}&format=${format}`;
            window.open(url, '_blank');
        }
    </script>
</x-filament-panels::page>
