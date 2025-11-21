<div class="mr-4">
    <button
        class="relative cursor-pointer"
        x-data="{ open: false }"
        @click="open = ! open"
    >
        {{-- Icon Bell --}}
        <x-heroicon-o-bell class="w-6 h-6 text-gray-600" />

        {{-- Badge merah --}}
        <span
            class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-600"
        ></span>

        {{-- Dropdown notifikasi --}}
        <div
            x-show="open"
            @click.away="open = false"
            class="absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-lg p-4 z-50"
        >
            <p class="font-semibold">Notifikasi</p>

            <ul class="mt-2 space-y-2 text-sm">
                <li>Pesanan baru masuk</li>
                <li>Approval menunggu</li>
                <li>Backup data berhasil</li>
            </ul>
        </div>
    </button>
</div>
