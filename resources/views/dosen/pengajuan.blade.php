<x-layout-dosen>
    <x-slot:title>{{ $title }}</x-slot>

    <div class="p-6 space-y-6">

        {{-- 🔔 ALERT --}}
        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded-lg">
                ❌ {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg">
                ✅ {{ session('success') }}
            </div>
        @endif

        @foreach ($pengajuan as $judulId => $items)
            @php
                $first = $items->first();
                $pemenang = $items->firstWhere('status', 'disetujui');
            @endphp

            <div class="bg-white shadow-xl rounded-2xl p-6">

                {{-- ================= HEADER ================= --}}
                <div class="mb-6 border-b pb-4">

                    @if ($first->jenis === 'pilih')
                        <h2 class="text-xl font-bold text-gray-800">
                            {{ optional($first->judul)->nama_judul ?? '-' }}
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Kode: {{ optional($first->judul)->kode ?? '-' }}
                        </p>
                    @endif

                    @if ($first->jenis === 'mandiri')
                        <div class="bg-purple-50 p-3 rounded-lg border border-purple-100">
                            <p class="text-purple-600 font-semibold text-sm">
                                📌 Judul Mandiri
                            </p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ $first->judul_mandiri }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $first->deskripsi_mandiri }}
                            </p>
                        </div>
                    @endif

                    @if ($pemenang)
                        <div class="mt-3 text-green-600 text-sm font-medium">
                            ✔ Sudah diambil oleh: {{ $pemenang->mahasiswa->name }}
                        </div>
                    @endif

                </div>

                {{-- ================= LIST ================= --}}
                <div class="space-y-4">

                    @foreach ($items as $p)
                        @php
                            $sudahPunyaJudul = \App\Models\Pengajuan::where('mahasiswa_id', $p->mahasiswa_id)
                                ->where('status', 'disetujui')
                                ->exists();
                        @endphp

                        <div class="border rounded-xl p-5 bg-gray-50 space-y-4">

                            {{-- HEADER --}}
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-800">
                                        👤 {{ $p->mahasiswa->name }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        Prioritas: {{ $p->prioritas }}
                                        @if ($p->prioritas == 1)
                                            <span class="text-blue-500 font-semibold ml-2">(Utama)</span>
                                        @endif
                                    </p>
                                </div>

                                <span
                                    class="
                                    px-3 py-1 text-xs rounded-full font-semibold
                                    @if ($p->status == 'pending') bg-yellow-100 text-yellow-600
                                    @elseif($p->status == 'disetujui') bg-green-100 text-green-600
                                    @else bg-red-100 text-red-600 @endif
                                ">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </div>

                            {{-- WARNING --}}
                            @if ($sudahPunyaJudul && $p->status !== 'disetujui')
                                <div class="text-xs text-orange-500 bg-orange-50 p-2 rounded">
                                    ⚠ Mahasiswa sudah memiliki judul disetujui
                                </div>
                            @endif

                            {{-- JUDUL --}}
                            <div>
                                <p class="text-sm text-gray-500">Judul</p>
                                <p class="font-medium text-gray-800">
                                    {{ $p->jenis === 'mandiri' ? $p->judul_mandiri : optional($p->judul)->nama_judul }}
                                </p>
                            </div>

                            {{-- ALASAN --}}
                            @if ($p->alasan)
                                <div class="bg-blue-50 border border-blue-100 p-3 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">
                                        Alasan Mahasiswa
                                    </p>
                                    <p class="text-sm text-gray-800">
                                        {{ $p->alasan }}
                                    </p>
                                </div>
                            @endif

                            {{-- DESKRIPSI MANDIRI --}}
                            @if ($p->jenis === 'mandiri')
                                <div class="text-sm text-gray-600">
                                    <p class="text-xs text-gray-500">Deskripsi</p>
                                    {{ $p->deskripsi_mandiri }}
                                </div>
                            @endif

                            {{-- ================= FORM ================= --}}
                            @if ($p->status === 'pending')
                                <form method="POST" action="{{ route('dosen.pengajuan.update', $p->id) }}"
                                    class="space-y-3">

                                    @csrf
                                    @method('PUT')

                                    {{-- 🔥 STATUS (AMAN) --}}
                                    <input type="hidden" name="status" id="status-{{ $p->id }}">

                                    {{-- CATATAN DOSEN --}}
                                    <textarea name="catatan_dosen" placeholder="Berikan catatan untuk mahasiswa..."
                                        class="w-full border rounded-lg p-3 text-sm focus:ring focus:ring-blue-200"></textarea>

                                    {{-- LAB (MANDIRI) --}}
                                    @if ($p->jenis === 'mandiri')
                                        <select name="laboratorium_id" class="border p-2 w-full rounded-lg text-sm"
                                            required>
                                            <option value="">Pilih Laboratorium</option>
                                            @foreach ($laboratorium as $lab)
                                                <option value="{{ $lab->id }}">{{ $lab->nama }}</option>
                                            @endforeach
                                        </select>
                                    @endif

                                    <div class="flex gap-2">

                                        {{-- SETUJUI --}}
                                        @if (!$sudahPunyaJudul)
                                            <button type="submit"
                                                onclick="setStatus('{{ $p->id }}','disetujui')"
                                                class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-sm font-medium transition">
                                                ✔ Setujui
                                            </button>
                                        @endif

                                        {{-- TOLAK --}}
                                        <button type="submit" onclick="setStatus('{{ $p->id }}','ditolak')"
                                            class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg text-sm font-medium transition">
                                            ✖ Tolak
                                        </button>

                                    </div>

                                </form>
                            @endif

                        </div>
                    @endforeach

                </div>

            </div>
        @endforeach

    </div>

    {{-- 🔥 SCRIPT --}}
    <script>
        function setStatus(id, value) {
            document.getElementById('status-' + id).value = value;
        }
    </script>

</x-layout-dosen>
