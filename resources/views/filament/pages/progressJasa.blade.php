<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Selection Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
            {{ $this->jasaForm }}
        </div>

        @if($record)
        {{-- Record Details --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm">
            <h2 class="text-xl font-bold mb-4">Detail Jasa</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">No Jasa</label>
                    <p class="text-lg font-semibold">{{ $record->no_jasa }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">No Ref</label>
                    <p class="text-lg font-semibold">{{ $record->no_ref ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Pelanggan</label>
                    <p class="text-lg">{{ $record->pelanggan?->nama ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                    <p class="text-lg">
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($record->status === 'selesai') bg-green-100 text-green-800
                            @elseif($record->status === 'terjadwal') bg-blue-100 text-blue-800
                            @elseif($record->status === 'selesai dikerjakan') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucwords($record->status) }}
                        </span>
                    </p>
                </div>
                @if($record->jadwal_petugas)
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Jadwal Petugas</label>
                    <p class="text-lg">{{ $record->jadwal_petugas->format('d F Y, H:i') }}</p>
                </div>
                @endif
                @if($record->petugasMany->isNotEmpty())
                <div>
                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Petugas</label>
                    <p class="text-lg">{{ $record->petugasMany->pluck('nama')->join(', ') }}</p>
                </div>
                @endif
            </div>

            {{-- Items --}}
            @if($record->items->isNotEmpty())
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">Item Jasa</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jasa</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($record->items as $item)
                            <tr>
                                <td class="px-4 py-2">{{ $item->nama_jasa }}</td>
                                <td class="px-4 py-2">{{ $item->jumlah }}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <td colspan="2" class="px-4 py-2 font-bold">Total</td>
                                <td class="px-4 py-2 text-right font-bold">Rp {{ number_format($record->items->sum('harga'), 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif

            {{-- Progress Images --}}
            @if(is_array($record->progress_images) && count($record->progress_images) > 0)
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">Foto Progress</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($record->progress_images as $image)
                    <div class="relative group cursor-pointer" onclick="window.openImageModal('{{ $this->getImageUrl($image['path']) }}')">
                        <img src="{{ $this->getImageUrl($image['path']) }}" 
                             alt="Progress" 
                             class="w-full h-40 object-cover rounded-lg border-2 border-gray-200 dark:border-gray-700">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                            </svg>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Update Status Section --}}
            @if($record->status !== 'selesai')
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold mb-4">Update Status</h3>
                
                @php
                    $nextStatus = $this->getNextSequentialStatusProperty();
                    $allowedStatuses = $this->getAllowedStatusesForRole();
                @endphp

                @if($nextStatus && in_array($nextStatus, $allowedStatuses))
                    {{-- Terjadwal Form --}}
                    @if($nextStatus === 'terjadwal')
                    <div class="mb-4">
                        {{ $this->terjadwalForm }}
                    </div>
                    @endif

                    {{-- Image Upload --}}
                    <div class="mb-4">
                        {{ $this->imageUploadForm }}
                    </div>

                    <button wire:click="updateStatus"
                            wire:confirm="Apakah Anda yakin ingin mengubah status menjadi {{ ucwords($nextStatus) }}?"
                            class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
                        Update Status ke {{ ucwords($nextStatus) }}
                    </button>
                @else
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada status lanjutan yang tersedia untuk role Anda.</p>
                @endif
            </div>
            @endif
        </div>
        @else
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm text-center">
            <p class="text-gray-500 dark:text-gray-400">Silakan pilih jasa untuk melihat detail.</p>
        </div>
        @endif
    </div>

    {{-- Image Modal --}}
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4" onclick="window.closeImageModal(event)">
        <div class="relative max-w-5xl max-h-full">
            <button onclick="window.closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="modalImage" src="" alt="Preview" class="max-w-full max-h-[90vh] object-contain">
        </div>
    </div>

    <script>
        window.openImageModal = function(url) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modalImg.src = url;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        window.closeImageModal = function(event) {
            if (event && event.target !== event.currentTarget && event.target.closest('button') === null) return;
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.closeImageModal();
            }
        });

        // Track file upload status
        window.trackUploadStatus = function() {
            const component = Livewire.find(document.querySelector('[wire\\:id]')?.getAttribute('wire:id'));
            if (!component) return;

            const fileInputs = document.querySelectorAll('input[type="file"]');
            
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.files && this.files.length > 0) {
                        window.dispatchEvent(new CustomEvent('uploading-status-changed', { detail: { status: true } }));
                    } else {
                        window.dispatchEvent(new CustomEvent('uploading-status-changed', { detail: { status: false } }));
                    }
                });
            });

            document.addEventListener('filament:file-upload:completed', () => {
                setTimeout(() => {
                    const hasFiles = Array.from(fileInputs).some(input => input.files && input.files.length > 0);
                    window.dispatchEvent(new CustomEvent('uploading-status-changed', { detail: { status: hasFiles } }));
                }, 500);
            });

            document.addEventListener('filament:file-upload:deleted', () => {
                setTimeout(() => {
                    const hasFiles = Array.from(fileInputs).some(input => input.files && input.files.length > 0);
                    window.dispatchEvent(new CustomEvent('uploading-status-changed', { detail: { status: hasFiles } }));
                }, 300);
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => window.trackUploadStatus(), 500);
            });
        } else {
            setTimeout(() => window.trackUploadStatus(), 500);
        }
    </script>
</x-filament-panels::page>
