<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Filter Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Filter Laporan</h3>
            {{ $this->filterForm }}
        </div>

        {{-- Results Count --}}
        @if(!$singleNumber)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    Hasil Laporan ({{ $resultCount }} data)
                </h2>
            </div>

            @if(count($previewRows) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">No</th>
                            @if($filters['report_type'] === 'jasa')
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">No Jasa</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">No Ref</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Petugas</th>
                            @else
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">No Produksi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">No Ref</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Branch</th>
                            @endif
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Items</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($previewRows as $index => $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ (($currentPage - 1) * $perPage) + $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $row['number'] }}</td>
                            @if($filters['report_type'] === 'jasa')
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $row['no_ref'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $row['customer'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $row['petugas'] ?? '-' }}</td>
                            @else
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $row['no_ref'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $row['branch'] }}</td>
                            @endif
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ $row['items_count'] }} items
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($row['total_harga'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $row['created_at'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <button wire:click="generatePdf('{{ $row['number'] }}', '{{ $filters['report_type'] }}', 'report')"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Report
                                    </button>
                                    <button wire:click="generatePdf('{{ $row['number'] }}', '{{ $filters['report_type'] }}', 'invoice')"
                                            class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
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
            <div class="mt-6 flex justify-between items-center border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Halaman {{ $currentPage }} dari {{ $totalPages }}
                </div>
                <div class="flex gap-2">
                    <button wire:click="previousPage" 
                            @if($currentPage <= 1) disabled @endif
                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm font-medium text-gray-700 dark:text-gray-200">
                        Previous
                    </button>
                    
                    @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                    <button wire:click="goToPage({{ $i }})"
                            class="px-4 py-2 rounded-lg transition-colors text-sm font-medium {{ $i === $currentPage ? 'bg-blue-600 text-white shadow-sm' : 'bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200' }}">
                        {{ $i }}
                    </button>
                    @endfor

                    <button wire:click="nextPage"
                            @if($currentPage >= $totalPages) disabled @endif
                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors text-sm font-medium text-gray-700 dark:text-gray-200">
                        Next
                    </button>
                </div>
            </div>
            @endif
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Tidak ada data untuk ditampilkan.</p>
            </div>
            @endif
        </div>
        @endif

        {{-- Single Report View --}}
        @if($singleNumber && $reportData)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        {{ $reportType === 'jasa' ? 'Laporan Jasa' : 'Laporan Produksi' }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400">No: <span class="font-semibold text-gray-900 dark:text-white">{{ $reportData['number'] }}</span></p>
                </div>
                <div class="flex gap-3">
                    <button wire:click="generatePdf('{{ $reportData['number'] }}', '{{ $reportType }}', 'report')"
                            class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors shadow-sm font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Report
                    </button>
                    <button wire:click="generatePdf('{{ $reportData['number'] }}', '{{ $reportType }}', 'invoice')"
                            class="inline-flex items-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors shadow-sm font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Invoice
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                @if($reportType === 'jasa')
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">No Ref</label>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $reportData['no_ref'] ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Pelanggan</label>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $reportData['pelanggan'] ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Petugas</label>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $reportData['petugas'] ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Jadwal</label>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $reportData['scheduled_at'] ?? '-' }}</p>
                </div>
                @else
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">No Ref</label>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $reportData['no_ref'] ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Branch</label>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $reportData['branch'] ?? '-' }}</p>
                </div>
                @endif
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                    <p class="text-lg mt-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($reportData['status'] === 'selesai') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                            @elseif($reportData['status'] === 'terjadwal') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                            {{ ucwords($reportData['status']) }}
                        </span>
                    </p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</label>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $reportData['created_at'] }}</p>
                </div>
                @if(isset($reportData['catatan']))
                <div class="md:col-span-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</label>
                    <p class="text-lg text-gray-900 dark:text-white mt-1">{{ $reportData['catatan'] ?? '-' }}</p>
                </div>
                @endif
            </div>

            {{-- Items Table --}}
            @if(isset($reportData['items']) && count($reportData['items']) > 0)
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Detail Items</h3>
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
                            <tr>
                                @if($reportType === 'jasa')
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Jasa</th>
                                @else
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Produksi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Bahan</th>
                                @endif
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($reportData['items'] as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                @if($reportType === 'jasa')
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $item['nama_jasa'] ?? $item['jenis_layanan'] }}</td>
                                @else
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $item['nama_produksi'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $item['nama_bahan'] }}</td>
                                @endif
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $item['jumlah'] }}</td>
                                <td class="px-4 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
                            <tr>
                                <td colspan="{{ $reportType === 'jasa' ? 2 : 3 }}" class="px-4 py-3 font-bold text-gray-900 dark:text-white">Total</td>
                                <td class="px-4 py-3 text-right font-bold text-lg text-blue-600 dark:text-blue-400">
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
</x-filament-panels::page>
