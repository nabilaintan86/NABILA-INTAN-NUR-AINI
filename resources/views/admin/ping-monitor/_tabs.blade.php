{{--
    Tab pemantauan ping: deretan tombol + ketiga panelnya.
    Dipakai di halaman Network Tools (/admin/tools).

    Dua target bawaan ikut didefinisikan di sini supaya whoever memakainya
    tidak perlu menyalin angka yang sama lagi.

    Catatan: tombol "Return to Tools" sengaja tidak disertakan. Awalnya UI ini
    berdiri di halaman /admin/ping-monitor, jadi tombol itu berguna. Sekarang
    UI-nya sudah berada di dalam /admin/tools, sehingga tombol tersebut hanya
    akan menaut ke halaman yang sedang dibuka -- yaitu tombol mati.
--}}
    @php
        // Dua target bawaan. 192.168.93.59 adalah server Radnet internal
        // (terdaftar sebagai "To-DISNAKER" pada devices.csv), 8.8.8.8 adalah
        // DNS publik Google dipakai sebagai pembanding jalur keluar.
        $radnetTarget = '192.168.93.59';
        $luarTarget   = '8.8.8.8';
    @endphp

    {{-- ══ DERETAN TOMBOL TAB ═════════════════════════════ --}}
    <div class="pm-tabs" role="tablist" aria-label="Pilih target pemantauan">
        <button type="button" role="tab" data-tab="radnet" aria-selected="true"
                aria-controls="panel-radnet"
                class="pm-tab is-active">
            <span aria-hidden="true">🌐</span> Ping Server Radnet
        </button>

        <button type="button" role="tab" data-tab="luar" aria-selected="false"
                aria-controls="panel-luar"
                class="pm-tab">
            <span aria-hidden="true">🌍</span> Ping Server Luar
        </button>

        <button type="button" role="tab" data-tab="ringkasan" aria-selected="false"
                aria-controls="panel-ringkasan"
                class="pm-tab">
            <span aria-hidden="true">📊</span> Status Ringkasan
        </button>
    </div>

    {{-- ══ TAB: PING SERVER RADNET ════════════════════════ --}}
    <section id="panel-radnet" data-panel="radnet" role="tabpanel" aria-label="Ping Server Radnet"
             class="flex flex-col gap-4">

        {{-- Header kotak biru ala WhatsUp Gold: nama target di kiri,
             status koneksi di kanan. --}}
        <div class="overflow-hidden rounded-xl border border-blue-900/60 shadow-lg">
            <div class="bg-blue-800 px-5 py-3.5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="min-w-0">
                        <h2 class="truncate text-sm font-extrabold tracking-wide text-white uppercase">
                            🌐 Server Radnet
                        </h2>
                        <p class="mt-0.5 text-xs text-blue-200">
                            Jaringan internal &mdash; jalur lokal tanpa internet
                        </p>
                    </div>
                    <span class="pm-badge inline-flex shrink-0 items-center gap-2 rounded-full px-3 py-1
                                 text-xs font-extrabold"
                          data-badge="radnet">Belum diuji</span>
                </div>
            </div>

            <div class="border-t border-blue-900/60 bg-slate-900 p-5">
                {{-- Form + tombol --}}
                <form class="flex flex-wrap items-end gap-3" data-form="radnet">
                    <div class="min-w-0 flex-1 basis-56">
                        <label for="ip-radnet" class="mb-1.5 block text-xs font-bold text-slate-300">
                            Alamat IP Server Radnet
                        </label>
                        <input id="ip-radnet" name="target" type="text" inputmode="decimal"
                               autocomplete="off" spellcheck="false"
                               value="{{ $radnetTarget }}"
                               class="pm-field pm-field--mono" />
                    </div>

                    <div class="basis-32">
                        <label for="cnt-radnet" class="mb-1.5 block text-xs font-bold text-slate-300">
                            Jumlah Paket
                        </label>
                        <select id="cnt-radnet" name="count" class="pm-field pm-field--select">
                            <option value="5" selected>5 paket</option>
                            <option value="10">10 paket</option>
                            <option value="20">20 paket</option>
                        </select>
                    </div>

                    <button type="submit" data-run="radnet" class="pm-run">
                        <span aria-hidden="true">▶</span> Mulai Ping
                    </button>
                </form>

                @include('admin.ping-monitor._terminal', ['key' => 'radnet'])
            </div>
        </div>
    </section>

    {{-- ══ TAB: PING SERVER LUAR ═════════════════════════ --}}
    <section id="panel-luar" data-panel="luar" role="tabpanel" aria-label="Ping Server Luar"
             class="hidden flex-col gap-4">

        <div class="overflow-hidden rounded-xl border border-blue-900/60 shadow-lg">
            <div class="bg-blue-800 px-5 py-3.5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="min-w-0">
                        <h2 class="truncate text-sm font-extrabold tracking-wide text-white uppercase">
                            🌍 Server Luar
                        </h2>
                        <p class="mt-0.5 text-xs text-blue-200">
                            Internet publik &mdash; pembanding akses ke luar jaringan
                        </p>
                    </div>
                    <span class="pm-badge inline-flex shrink-0 items-center gap-2 rounded-full px-3 py-1
                                 text-xs font-extrabold"
                          data-badge="luar">Belum diuji</span>
                </div>
            </div>

            <div class="border-t border-blue-900/60 bg-slate-900 p-5">
                <form class="flex flex-wrap items-end gap-3" data-form="luar">
                    <div class="min-w-0 flex-1 basis-56">
                        <label for="ip-luar" class="mb-1.5 block text-xs font-bold text-slate-300">
                            Alamat IP Server Luar
                        </label>
                        <input id="ip-luar" name="target" type="text" inputmode="decimal"
                               autocomplete="off" spellcheck="false"
                               value="{{ $luarTarget }}"
                               class="pm-field pm-field--mono" />
                    </div>

                    <div class="basis-32">
                        <label for="cnt-luar" class="mb-1.5 block text-xs font-bold text-slate-300">
                            Jumlah Paket
                        </label>
                        <select id="cnt-luar" name="count" class="pm-field pm-field--select">
                            <option value="5" selected>5 paket</option>
                            <option value="10">10 paket</option>
                            <option value="20">20 paket</option>
                        </select>
                    </div>

                    <button type="submit" data-run="luar" class="pm-run">
                        <span aria-hidden="true">▶</span> Mulai Ping
                    </button>
                </form>

                @include('admin.ping-monitor._terminal', ['key' => 'luar'])
            </div>
        </div>
    </section>

    {{-- ══ TAB: STATUS RINGKASAN ══════════════════════════ --}}
    <section id="panel-ringkasan" data-panel="ringkasan" role="tabpanel" aria-label="Status Ringkasan"
             class="hidden flex-col gap-4">

        <div class="overflow-hidden rounded-xl border border-blue-900/60 shadow-lg">
            <div class="bg-blue-800 px-5 py-3.5">
                <h2 class="text-sm font-extrabold tracking-wide text-white uppercase">
                    📊 Status Ringkasan
                </h2>
                <p class="mt-0.5 text-xs text-blue-200">
                    Ringkasan hasil pengujian terakhir pada kedua server
                </p>
            </div>

            <div class="border-t border-blue-900/60 bg-slate-900 p-5">
                {{-- Kartu per target. Diisi oleh JS setelah ping selesai.
                     Garis aksen biru di atas kartu berubah mengikuti status
                     (hijau / kuning / merah) lewat atribut data-state. --}}
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="pm-summary" data-summary="radnet" data-state="idle">
                        <div class="text-xs font-extrabold tracking-wide text-slate-400 uppercase">
                            🌐 Server Radnet
                        </div>
                        <div class="mt-2 text-lg font-extrabold text-slate-500">Belum diuji</div>
                    </div>

                    <div class="pm-summary" data-summary="luar" data-state="idle">
                        <div class="text-xs font-extrabold tracking-wide text-slate-400 uppercase">
                            🌍 Server Luar
                        </div>
                        <div class="mt-2 text-lg font-extrabold text-slate-500">Belum diuji</div>
                    </div>
                </div>

                <p class="mt-4 text-xs text-slate-500" data-summary-empty>
                    Jalankan pengujian pada tab Ping Server Radnet atau Ping Server Luar
                    untuk mengisi ringkasan ini.
                </p>
            </div>
        </div>
    </section>
