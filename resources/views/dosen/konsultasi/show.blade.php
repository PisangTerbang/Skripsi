<x-layout-dosen>
    <x-slot:title>Chat — {{ $mahasiswa->name }}</x-slot>

    <div class="space-y-4" x-data="chatDosenPage()" x-init="init()">

        {{-- ===== TOP BAR ===== --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('dosen.konsultasi.index') }}"
                class="group flex h-10 w-10 items-center justify-center rounded-xl border-2 border-gray-200 bg-white text-gray-400 shadow-sm transition hover:border-emerald-400 hover:bg-emerald-50 hover:text-emerald-600">
                <x-heroicon-o-arrow-left class="h-5 w-5 transition group-hover:-translate-x-0.5" />
            </a>
            <div
                class="flex flex-1 items-center gap-3 rounded-2xl border-2 border-gray-200 bg-white px-4 py-3 shadow-sm">
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 text-sm font-black text-white shadow-sm ring-2 ring-emerald-200">
                    {{ strtoupper(substr($mahasiswa->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-black text-gray-800">{{ $mahasiswa->name }}</p>
                    <p class="text-xs text-gray-400">{{ $mahasiswa->nim ?? 'Mahasiswa' }}</p>
                </div>
            </div>
        </div>

        {{-- ===== CHAT AREA ===== --}}
        <div class="overflow-hidden rounded-2xl border-2 border-gray-200 bg-white shadow-md">

            {{-- Messages --}}
            <div id="chat-messages-dosen" class="flex flex-col gap-3 overflow-y-auto p-5" style="height: 480px;">

                <template x-if="messages.length === 0">
                    <div class="flex flex-col items-center justify-center h-full text-center">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100 mb-4">
                            <x-heroicon-o-chat-bubble-left-ellipsis class="h-8 w-8 text-emerald-400" />
                        </div>
                        <p class="text-sm font-bold text-gray-600">Belum ada pesan</p>
                        <p class="mt-1 text-xs text-gray-400">Mahasiswa akan memulai percakapan</p>
                    </div>
                </template>

                <template x-for="(msg, index) in messages" :key="msg.id">
                    <div>
                        {{-- Date separator --}}
                        <template x-if="index === 0 || messages[index-1].date !== msg.date">
                            <div class="flex items-center gap-3 my-2">
                                <div class="h-px flex-1 bg-gray-200"></div>
                                <span
                                    class="rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-xs font-bold text-gray-400"
                                    x-text="msg.date"></span>
                                <div class="h-px flex-1 bg-gray-200"></div>
                            </div>
                        </template>

                        {{-- Bubble --}}
                        <div :class="msg.is_mine ? 'items-end' : 'items-start'" class="flex flex-col gap-1">

                            {{-- Judul Card --}}
                            <template x-if="msg.tipe === 'judul_card' && msg.judul_snapshot">
                                <div :class="msg.is_mine ? 'ml-auto' : 'mr-auto'" class="max-w-sm w-full">
                                    <div :class="msg.is_mine ?
                                        'bg-emerald-600 text-white rounded-2xl rounded-br-sm' :
                                        'bg-gray-100 text-gray-800 rounded-2xl rounded-bl-sm'"
                                        class="overflow-hidden shadow-sm">
                                        {{-- Card Header --}}
                                        <div :class="msg.is_mine ? 'bg-emerald-700' : 'bg-gray-200'"
                                            class="flex items-center gap-2 px-4 py-2.5">
                                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span class="text-xs font-black uppercase tracking-widest">Judul
                                                Ditanyakan</span>
                                        </div>
                                        {{-- Card Body --}}
                                        <div class="px-4 py-3 space-y-1.5">
                                            <p class="text-sm font-black leading-relaxed"
                                                x-text="msg.judul_snapshot.nama_judul"></p>
                                            <div class="flex flex-wrap gap-2 text-xs opacity-80">
                                                <span x-show="msg.judul_snapshot.kode" x-text="msg.judul_snapshot.kode"
                                                    class="font-mono"></span>
                                                <span x-show="msg.judul_snapshot.lab">
                                                    · <span x-text="msg.judul_snapshot.lab"></span>
                                                </span>
                                            </div>
                                            <p x-show="msg.judul_snapshot.deskripsi"
                                                x-text="msg.judul_snapshot.deskripsi"
                                                class="text-xs opacity-70 leading-relaxed line-clamp-2"></p>
                                        </div>
                                        {{-- Timestamp --}}
                                        <div class="px-4 pb-2.5 flex justify-end">
                                            <span class="text-[10px] opacity-60" x-text="msg.time"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            {{-- Text Bubble --}}
                            <template x-if="msg.tipe !== 'judul_card'">
                                <div :class="msg.is_mine ? 'ml-auto' : 'mr-auto'" class="max-w-sm">
                                    <div :class="msg.is_mine ?
                                        'bg-emerald-600 text-white rounded-2xl rounded-br-sm' :
                                        'bg-gray-100 text-gray-800 rounded-2xl rounded-bl-sm'"
                                        class="px-4 py-3 shadow-sm">
                                        <p class="text-sm leading-relaxed" x-text="msg.body"></p>
                                        <p class="mt-1 text-[10px] opacity-60 text-right" x-text="msg.time"></p>
                                    </div>
                                </div>
                            </template>

                        </div>
                    </div>
                </template>

            </div>

            {{-- ===== INPUT PESAN ===== --}}
            <div class="border-t-2 border-gray-100 p-4">
                <form method="POST" action="{{ route('dosen.konsultasi.send', $conversation->id) }}"
                    class="flex items-end gap-3">
                    @csrf
                    <textarea name="body" rows="1" required placeholder="Ketik balasan..."
                        class="flex-1 resize-none rounded-2xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition"
                        onkeydown="if(event.key==='Enter' && !event.shiftKey){event.preventDefault();this.form.submit();}">
                    </textarea>
                    <button type="submit"
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-sm transition hover:bg-emerald-700 hover:shadow-md">
                        <x-heroicon-o-paper-airplane class="h-5 w-5" />
                    </button>
                </form>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            function chatDosenPage() {
                return {
                    messages: [],
                    interval: null,

                    init() {
                        this.fetchMessages();
                        this.interval = setInterval(() => this.fetchMessages(), 5000);
                    },

                    async fetchMessages() {
                        try {
                            const res = await fetch("{{ route('dosen.konsultasi.poll', $conversation->id) }}", {
                                credentials: 'same-origin',
                                headers: {
                                    'Accept': 'application/json'
                                }
                            });
                            if (!res.ok) return;
                            const data = await res.json();
                            if (data.success) {
                                const wasAtBottom = this.isAtBottom();

                                // ✅ Hanya tambah pesan baru — tidak replace semua
                                const existingIds = new Set(this.messages.map(m => m.id));
                                const newMessages = data.messages.filter(m => !existingIds.has(m.id));

                                if (newMessages.length > 0) {
                                    this.messages.push(...newMessages);
                                    if (wasAtBottom) {
                                        this.$nextTick(() => this.scrollToBottom());
                                    }
                                }

                                // Pertama kali load — set semua pesan dan scroll ke bawah
                                if (this.messages.length === 0 && data.messages.length > 0) {
                                    this.messages = data.messages;
                                    this.$nextTick(() => this.scrollToBottom());
                                }
                            }
                        } catch (e) {
                            // silent fail
                        }
                    },

                    isAtBottom() {
                        const el = document.getElementById('chat-messages-dosen');
                        if (!el) return true;
                        return el.scrollHeight - el.scrollTop - el.clientHeight < 80;
                    },

                    scrollToBottom() {
                        const el = document.getElementById('chat-messages-dosen');
                        if (el) el.scrollTop = el.scrollHeight;
                    }
                }
            }
        </script>
    @endpush


</x-layout-dosen>
