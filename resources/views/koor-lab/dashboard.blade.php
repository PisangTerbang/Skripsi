<x-layout-koorlab>
    <x-slot:title>{{ $title }}</x-slot>

    <div class="space-y-6">

        {{-- Header --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl shadow-xl p-6">
            <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}</h2>
            <p class="text-blue-100 text-sm">Koordinator Laboratorium {{ auth()->user()->laboratorium->nama ?? '-' }}</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-lg border-gray-100 p-6">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mb-4">
                    <x-heroicon-o-clock class="w-6 h-6 text-yellow-600" />
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Menungu Dikelompokkan</p>
                <p class="text-3xl font-bold text-gray-800">{{ $pendingKoor }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4">
                    <x-heroicon-o-check-circle class="w-6 h-6 text-green-600" />
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Sudah Dikelompokkan</p>
                <p class="text-3xl font-bold text-gray-800">{{ $sudahDikelompokkan }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                    <x-heroicon-o-document-text class="w-6 h-6 text-blue-600" />
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Judul di Lab</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalJudul }}</p>
            </div>
        </div>

    </div>

</x-layout-koorlab>
