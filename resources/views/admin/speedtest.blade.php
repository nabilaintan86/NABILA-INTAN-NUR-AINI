@extends('layouts.admin')

@section('title', 'Speed Test · RADNET NOC')

{{--
    Tailwind HANYA untuk halaman ini.

    Aset page-scoped (theme + utilities, tanpa preflight) supaya tema Noko
    dan seluruh halaman admin lain tidak ikut ter-reset. Jangan dipindah
    ke layouts/admin.blade.php.
--}}
@push('styles')
    @vite(['resources/css/speedtest.css'])
@endpush

@section('content')
    {{--
        Susunan mengikuti Speedtest by Ookla:

          1. Lingkaran gauge di tengah, dengan tombol MULAI di dalam dial.
          2. Setelah tes selesai, dua kotak besar muncul simetris di atas:
             UNDUHAN dan UNGGAHAN.
          3. Di bawahnya baris metrik ringkas: Ping, Jitter, Loss.
          4. Di paling bawah, kotak informasi koneksi.

        Tidak ada tombol mati di halaman ini. Yang bisa diklik hanya satu:
        tombol MULAI. Semua angka diukur nyata di browser — unduh dari CDN
        Cloudflare, unggah ke endpoint first-party RADNET NOC.
    --}}

    <x-admin.page-head class="stg-head" title="Speed Test"
        subtitle="Mengukur latensi, jitter, packet loss, laju unduh, dan laju unggah langsung dari perangkat Anda." />

    <div class="stg-page">

        {{-- ══ 1 · GAUGE + TOMBOL MULAI DI TENGAH ══════════════════════════ --}}
        <div class="stg-gauge-wrap" id="stgGaugeWrap">
            <div class="stg-halo" aria-hidden="true"></div>

            <svg class="stg-gauge" viewBox="0 0 320 320" aria-hidden="true">
                <defs>
                    <linearGradient id="stgGold" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#FFE680"></stop>
                        <stop offset="50%" stop-color="#FFD700"></stop>
                        <stop offset="100%" stop-color="#DAA520"></stop>
                    </linearGradient>
                    <filter id="stgGlow" x="-60%" y="-60%" width="220%" height="220%">
                        <feGaussianBlur stdDeviation="5" result="blur"></feGaussianBlur>
                        <feMerge>
                            <feMergeNode in="blur"></feMergeNode>
                            <feMergeNode in="SourceGraphic"></feMergeNode>
                        </feMerge>
                    </filter>
                </defs>

                {{-- Cincin yang berputar pelan saat idle. --}}
                <circle class="stg-idle-ring" cx="160" cy="160" r="176"></circle>

                {{-- Centang dibuat dari JS supaya jaraknya rapi tanpa
                     menulis puluhan elemen secara manual. --}}
                <g id="stgTicks"></g>

                {{-- Cincin progres keseluruhan pengujian. --}}
                <circle class="stg-progress" id="stgProgress"
                        cx="160" cy="160" r="152"
                        transform="rotate(-90 160 160)"></circle>

                {{-- Busur utama: tinggi laju saat ini. --}}
                <circle class="stg-track" cx="160" cy="160" r="140"
                        transform="rotate(-90 160 160)"></circle>
                <circle class="stg-arc" id="stgArc"
                        cx="160" cy="160" r="140"
                        transform="rotate(-90 160 160)"></circle>

                {{-- Ujung busur (jarum bulat). --}}
                <g class="stg-tip" id="stgTip" transform="rotate(0 160 160)">
                    <circle cx="160" cy="20" r="7"></circle>
                </g>
            </svg>

            {{-- Lapisan baca: angka real-time + status tahapan. Hanya terlihat
                 selama pengujian berjalan. --}}
            <div class="stg-center stg-center-read">
                <div class="stg-value is-idle text-[3.25rem] font-black" id="stgValue">0</div>
                <div class="mt-1 text-sm font-semibold uppercase tracking-[0.2em] text-zinc-400" id="stgUnit">Mbps</div>
                <div class="stg-status is-idle mt-3 inline-flex items-center justify-center rounded-full border border-zinc-700 bg-zinc-900/80 px-3 py-1 text-xs font-semibold text-zinc-400"
                     id="stgStatus">Siap diuji</div>
            </div>

            {{-- Lapisan aksi: tombol MULAI duduk tepat di tengah dial, seperti
                 di Speedtest by Ookla. --}}
            <div class="stg-center stg-center-go">
                <button type="button"
                        class="stg-go"
                        id="stgRun"
                        aria-label="Mulai Speed Test">
                    <span class="stg-go-dot" aria-hidden="true"></span>
                    <span id="stgRunLabel">MULAI</span>
                </button>
                <p class="stg-go-hint" id="stgGoHint">Ketuk untuk menguji koneksi</p>
            </div>
        </div>

        {{-- ══ HASIL: muncul setelah pengujian selesai ═════════════════════ --}}
        {{-- Sembunyi sebelum tes pertama selesai supaya tampilan awal benar-benar
             bersih: hanya satu lingkaran dan satu tombol, seperti Ookla. --}}
        <div class="stg-results" id="stgResults" hidden>

            {{-- ── Dua kotak besar, simetris ────────────────────────────── --}}
            <div class="stg-heroes">

                <div class="stg-hero is-live" data-hero="download">
                    <div class="stg-hero-head">
                        <svg class="stg-hero-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 3v12"></path>
                            <path d="m7 12 5 5 5-5"></path>
                            <path d="M5 21h14"></path>
                        </svg>
                        <span>Unduhan</span>
                    </div>
                    <div class="stg-hero-row">
                        <span class="stg-hero-number is-empty" data-hero-value="download">—</span>
                        <span class="stg-hero-unit">Mbps</span>
                    </div>
                    <p class="stg-hero-note" data-hero-note="download">Belum diuji</p>
                </div>

                <div class="stg-hero" data-hero="upload">
                    <div class="stg-hero-head">
                        <svg class="stg-hero-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 21V9"></path>
                            <path d="m7 12 5-5 5 5"></path>
                            <path d="M5 3h14"></path>
                        </svg>
                        <span>Unggahan</span>
                    </div>
                    <div class="stg-hero-row">
                        <span class="stg-hero-number is-empty" data-hero-value="upload">—</span>
                        <span class="stg-hero-unit">Mbps</span>
                    </div>
                    <p class="stg-hero-note" data-hero-note="upload">Belum diuji</p>
                </div>
            </div>

            {{-- ── Baris metrik ringkas ─────────────────────────────────── --}}
            <div class="stg-minis">

                <div class="stg-mini" data-metric="ping">
                    <svg class="stg-mini-ic text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 7v5l3 2"></path>
                    </svg>
                    <span class="stg-mini-label">Ping</span>
                    <span class="stg-mini-value is-empty" data-slot>—</span>
                    <span class="stg-mini-unit">ms</span>
                    <span class="stg-mini-note" data-note>Belum diuji</span>
                </div>

                <div class="stg-mini" data-metric="jitter">
                    <svg class="stg-mini-ic text-violet-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 12h3l2-6 4 12 2-6h4"></path>
                    </svg>
                    <span class="stg-mini-label">Jitter</span>
                    <span class="stg-mini-value is-empty" data-slot>—</span>
                    <span class="stg-mini-unit">ms</span>
                    <span class="stg-mini-note" data-note>Belum diuji</span>
                </div>

                <div class="stg-mini" data-metric="loss">
                    <svg class="stg-mini-ic text-rose-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 3 2 20h20L12 3Z"></path>
                        <path d="M12 9v4"></path>
                        <path d="M12 17h.01"></path>
                    </svg>
                    <span class="stg-mini-label">Loss</span>
                    <span class="stg-mini-value is-empty" data-slot>—</span>
                    <span class="stg-mini-unit">%</span>
                    <span class="stg-mini-note" data-note>Belum diuji</span>
                </div>
            </div>

            {{-- ── Kotak informasi koneksi ──────────────────────────────── --}}
            <div class="stg-info">
                <div class="stg-info-head">
                    <svg class="stg-info-ic text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M2 12h20"></path>
                        <path d="M12 3a15 15 0 0 1 0 18a15 15 0 0 1 0-18Z"></path>
                    </svg>
                    Informasi Koneksi
                </div>

                <dl class="stg-info-grid">
                    <dt>Koneksi (ISP)</dt>
                    <dd class="is-empty" data-info="isp">Tidak diketahui</dd>

                    <dt>IP Address</dt>
                    <dd class="is-empty stg-mono" data-info="ip">Tidak diketahui</dd>

                    <dt>Server Lokasi</dt>
                    <dd class="is-empty" data-info="server">Tidak diketahui</dd>
                </dl>

                <p class="stg-info-foot">
                    Laju diukur ke <strong>CDN Cloudflare</strong> (unduh) dan ke
                    <strong>server RADNET NOC</strong> (unggah). Baris di atas terisi otomatis
                    begitu pengujian berjalan.
                </p>
            </div>

            <p class="stg-note" id="stgNote">
                Pengujian memakai bandwidth penuh selama beberapa detik dan memakai data.
            </p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ═══════════════════════════════════════════════════════
       KONFIGURASI
       ---------------------------------------------------------
       Download memakai endpoint resmi speed test Cloudflare
       yang memang mengizinkan CORS, jadi angkanya nyata.
       Upload TIDAK bisa ke sana: POST lintas asal ditolak
       browser sebelum datanya sampai. Karena itu unggah
       dikirim ke endpoint milik RADNET NOC sendiri
       (admin.speed-test.upload-sink), yang bodinya langsung
       dibuang server tanpa disimpan.
     ═══════════════════════════════════════════════════════ */
    var CF_TRACE = 'https://cloudflare.com/cdn-cgi/trace';
    var CF_DOWN  = 'https://speed.cloudflare.com/__down';
    /* URL tong unggah ditulis sebagai string biasa, bukan lewat directive
       json() bawaan Blade: directive itu mengubah garis miring jadi \/
       sehingga alamatnya tidak lagi terbaca apa adanya saat halaman
       diuji. Jalur ini di-escape Blade seperti biasa, dan tidak
       mengandung karakter yang perlu escaping. */
    var UPLOAD_SINK = '{{ route('admin.speed-test.upload-sink') }}';
    var CSRF = document.querySelector('meta[name="csrf-token"]');

    /* IP dari server. Hanya dipakai sebagai cadangan kalau CDN tidak dapat
       dibaca, karena di balik proxy nilai $request->ip() bisa jadi IP proxy. */
    var SERVER_IP = @json($clientIp);

    /* Batas atas grafik. Angka di atas ini tetap ditunjukkan,
       hanya persentase lingkarannya yang mentok. */
    var GAUGE_MAX_MBPS = 1000;
    var PING_SAMPLES   = 6;
    var DOWNLOAD_ROUNDS = 4;
    var DOWNLOAD_BYTES = 8 * 1024 * 1024;
    var DOWNLOAD_CONCURRENCY = 4;
    var UPLOAD_ROUNDS = 3;
    var UPLOAD_BYTES  = 3 * 1024 * 1024;
    var REQUEST_TIMEOUT = 15000;

    /* ═══════════════════════════════════════════════════════
       GEOMETRI SVG
     ═══════════════════════════════════════════════════════ */
    var R = 140;
    var CIRCUMFERENCE = 2 * Math.PI * R;
    var PROGRESS_R = 152;
    var PROGRESS_CIRCUMFERENCE = 2 * Math.PI * PROGRESS_R;

    var arc       = document.getElementById('stgArc');
    var progress  = document.getElementById('stgProgress');
    var tip       = document.getElementById('stgTip');
    var ticksBox  = document.getElementById('stgTicks');
    var gaugeWrap = document.getElementById('stgGaugeWrap');
    var valueEl   = document.getElementById('stgValue');
    var unitEl    = document.getElementById('stgUnit');
    var statusEl  = document.getElementById('stgStatus');
    var runBtn    = document.getElementById('stgRun');
    var runLabel  = document.getElementById('stgRunLabel');
    var goHint    = document.getElementById('stgGoHint');
    var resultsEl = document.getElementById('stgResults');
    var noteEl    = document.getElementById('stgNote');

    var slots = {
        ping:   document.querySelector('[data-metric="ping"]'),
        jitter: document.querySelector('[data-metric="jitter"]'),
        loss:   document.querySelector('[data-metric="loss"]')
    };

    var heroes = {
        download: document.querySelector('[data-hero="download"]'),
        upload:   document.querySelector('[data-hero="upload"]')
    };

    var info = {
        isp:    document.querySelector('[data-info="isp"]'),
        ip:     document.querySelector('[data-info="ip"]'),
        server: document.querySelector('[data-info="server"]')
    };

    var running = false;

    /*
     * Penghitung packet loss untuk semua request yang gagal.
     *
     * Loss dihitung dari request HTTP yang benar-benar gagal atau tidak
     * pernah selesai — bukan ditebak dari angka lain. Semua request dihitung:
     * unduh, unggah, dan ping.
     */
    var loss = { attempted: 0, failed: 0 };

    /* ═══════════════════════════════════════════════════════
       UTILITAS
     ═══════════════════════════════════════════════════════ */
    function withTimeout(promise, ms) {
        return new Promise(function (resolve, reject) {
            var timer = setTimeout(function () {
                reject(new Error('timeout'));
            }, ms);

            promise.then(function (value) {
                clearTimeout(timer);
                resolve(value);
            }, function (error) {
                clearTimeout(timer);
                reject(error);
            });
        });
    }

    function csrfHeaders(extra) {
        var headers = extra || {};
        if (CSRF) { headers['X-CSRF-TOKEN'] = CSRF.content; }
        return headers;
    }

    /* Cache-buster supaya proxy tidak melayani respons lama dan pengukuran
       benar-benar mengunduh lewat jaringan, bukan dari cache browser. */
    function cacheBust(url) {
        return url + (url.indexOf('?') === -1 ? '?' : '&') + '_=' + Date.now() + '-' + Math.random();
    }

    function bytesToMbps(bytes, milliseconds) {
        if (!milliseconds || milliseconds <= 0) { return 0; }
        return (bytes * 8) / (milliseconds / 1000) / 1_000_000;
    }

    /*
     * Catat satu request untuk perhitungan loss.
     *
     * Membungkus satu promise dengan ini membuat setiap request yang gagal
     * otomatis menambah penghitung, tanpa harus menulis catcher di tiap tempat.
     */
    function track(promise) {
        loss.attempted++;
        return promise.then(
            function (value) { return value; },
            function (error) { loss.failed++; throw error; }
        );
    }

    function lossPercentage() {
        if (!loss.attempted) { return 0; }
        return (loss.failed / loss.attempted) * 100;
    }

    /*
     * Jitter: rata-rata selisih absolut antar sampel latensi berurutan.
     *
     * Ini definisi yang sama dipakai RFC 3550 untuk mengukur perubahan waktu
     * tempuh antar paket. Menilai jitter dari min/avg/max saja tidak akan
     * menangkap fluktuasi antar paket, jadi dua angka itu tidak dipakai.
     */
    function jitterOf(samples) {
        if (!samples || samples.length < 2) { return 0; }

        var total = 0;
        for (var i = 1; i < samples.length; i++) {
            total += Math.abs(samples[i] - samples[i - 1]);
        }
        return total / (samples.length - 1);
    }

    function lossNote(percent) {
        if (percent <= 0) { return 'Tidak ada paket hilang'; }
        if (percent < 1) { return 'Sedikit paket hilang'; }
        if (percent < 5) { return 'Ada paket hilang'; }
        return 'Banyak paket hilang';
    }

    /* ═══════════════════════════════════════════════════════
       TAMPILAN
     ═══════════════════════════════════════════════════════ */
    function paintArc(mbps) {
        var ratio = Math.max(0, Math.min(1, mbps / GAUGE_MAX_MBPS));
        var offset = CIRCUMFERENCE * (1 - ratio);

        arc.style.strokeDasharray = CIRCUMFERENCE;
        arc.style.strokeDashoffset = offset;
        tip.setAttribute('transform', 'rotate(' + (ratio * 359.9) + ' 160 160)');

        valueEl.textContent = formatSpeed(mbps);
    }

    function paintProgress(ratio) {
        var safe = Math.max(0, Math.min(1, ratio));
        progress.style.strokeDasharray = PROGRESS_CIRCUMFERENCE;
        progress.style.strokeDashoffset = PROGRESS_CIRCUMFERENCE * (1 - safe);
    }

    /* Laju lebih dari 1000 Mbps ditulis bulat supaya angka besar tidak
       terpotong jadi membingungkan. */
    function formatSpeed(mbps) {
        if (mbps >= 1000) { return mbps.toFixed(0); }
        if (mbps >= 100)  { return mbps.toFixed(1); }
        return mbps.toFixed(2);
    }

    function formatMetric(value) {
        if (value >= 100) { return value.toFixed(1); }
        return value.toFixed(2);
    }

    function setStatus(text, kind) {
        statusEl.textContent = text;
        statusEl.classList.toggle('is-idle', kind === 'idle');
        statusEl.classList.toggle('is-error', kind === 'error');
    }

    function setUnit(unit) { unitEl.textContent = unit; }

    function setSlot(metric, value, note) {
        var box = slots[metric];
        if (!box) { return; }

        var slot = box.querySelector('[data-slot]');
        var sub  = box.querySelector('[data-note]');

        if (value === null || value === undefined) {
            slot.textContent = '—';
            slot.classList.add('is-empty');
        } else {
            slot.textContent = formatMetric(value);
            slot.classList.remove('is-empty');
        }

        sub.textContent = note;
    }

    function highlight(metric, on) {
        if (slots[metric]) { slots[metric].classList.toggle('is-live', !!on); }
    }

    function setHero(kind, mbps, note) {
        var card = heroes[kind];
        if (!card) { return; }

        var value = card.querySelector('[data-hero-value]');
        var caption = card.querySelector('[data-hero-note]');

        if (mbps === null || mbps === undefined) {
            value.textContent = '—';
            value.classList.add('is-empty');
        } else {
            value.textContent = formatSpeed(mbps);
            value.classList.remove('is-empty');
        }

        caption.textContent = note;
    }

    function heroLive(kind, on) {
        if (heroes[kind]) { heroes[kind].classList.toggle('is-live', !!on); }
    }

    function setInfo(kind, text) {
        var cell = info[kind];
        if (!cell) { return; }

        if (!text) {
            cell.textContent = 'Tidak diketahui';
            cell.classList.add('is-empty');
        } else {
            cell.textContent = text;
            cell.classList.remove('is-empty');
        }
    }

    function resetUi() {
        setSlot('ping', null, 'Belum diuji');
        setSlot('jitter', null, 'Belum diuji');
        setSlot('loss', null, 'Belum diuji');

        highlight('ping', false);
        highlight('jitter', false);
        highlight('loss', false);

        setHero('download', null, 'Belum diuji');
        setHero('upload', null, 'Belum diuji');
        heroLive('download', false);
        heroLive('upload', false);

        paintArc(0);
        paintProgress(0);

        valueEl.classList.add('is-idle');
        setUnit('Mbps');
        setStatus('Siap diuji', 'idle');
        gaugeWrap.classList.remove('is-running');
    }

    /* Panel hasil disembunyikan lagi supaya mengulang tes terasa seperti
       mengukur ulang, bukan menimpa angka lama di layar. */
    function hideResults() {
        resultsEl.classList.remove('is-shown');
        resultsEl.hidden = true;
    }

    function showResults() {
        resultsEl.hidden = false;
        /* Paksa satu frame supaya transisi masuknya benar-benar terlihat. */
        void resultsEl.offsetWidth;
        resultsEl.classList.add('is-shown');
    }

    /* ═══════════════════════════════════════════════════════
       ANIMASI JARUM
       ---------------------------------------------------------
       Angka tidak langsung dipasang ke hasil ukur, tapi bergerak pelan ke arah
       target sambil sedikit bergetar, supaya watching-nya terasa hidup seperti
       speed test sungguhan.
     ═══════════════════════════════════════════════════════ */
    var target = 0;
    var shown = 0;
    var animationHandle = null;

    function animate() {
        shown += (target - shown) * 0.14;

        var wobble = running && target > 0
            ? (Math.random() - 0.5) * Math.max(0.4, target * 0.05)
            : 0;

        paintArc(Math.max(0, shown + wobble));

        if (running) {
            animationHandle = requestAnimationFrame(animate);
        } else {
            paintArc(shown);
            animationHandle = null;
        }
    }

    function startAnimation() {
        if (animationHandle === null) {
            animationHandle = requestAnimationFrame(animate);
        }
    }

    /* ═══════════════════════════════════════════════════════
       IDENTITAS KONEKSI
       ---------------------------------------------------------
       Endpoint trace Cloudflare mengembalikan daftar pasangan key=value.
       Tiga hal yang dibutuhkan panel informasinya ada di sana: alamat IP
       publik pengunjung, nama organisasi penyedia jasanya, dan kode pusat
       data yang melayani permintaan tersebut.
     ═══════════════════════════════════════════════════════ */
    function parseTrace(text) {
        var result = {};
        var lines = String(text || '').split('\n');

        for (var i = 0; i < lines.length; i++) {
            var separator = lines[i].indexOf('=');
            if (separator === -1) { continue; }

            var key = lines[i].slice(0, separator).trim();
            var value = lines[i].slice(separator + 1).trim();

            if (key) { result[key] = value; }
        }

        return result;
    }

    /*
     * Kode pusat data Cloudflare untuk kota yang paling sering dipakai
     * pembaca di Indonesia. Kode yang tidak dikenal tetap ditampilkan apa
     * adanya — lebih baik daripada menyembunyikan informasi.
     */
    var COLO_NAMES = {
        CGK: 'Jakarta, ID',
        SUB: 'Surabaya, ID',
        KUL: 'Kuala Lumpur, MY',
        SIN: 'Singapura, SG',
        HKG: 'Hong Kong, HK',
        NRT: 'Tokyo, JP',
        ICN: 'Seoul, KR',
        BOM: 'Mumbai, IN',
        DXB: 'Dubai, AE',
        FRA: 'Frankfurt, DE',
        LHR: 'London, GB',
        SFO: 'San Francisco, US',
        IAD: 'Ashburn, US',
        CDG: 'Paris, FR',
        AMS: 'Amsterdam, NL',
        SYD: 'Sydney, AU'
    };

    function describeServer(colo) {
        if (!colo) { return ''; }
        if (COLO_NAMES[colo]) { return COLO_NAMES[colo] + ' · Cloudflare'; }
        return 'Pusat data ' + colo + ' · Cloudflare';
    }

    /*
     * Ambil dan tampilkan identitas koneksi.
     *
     * Gagal diam-diam: kalau CDN tidak bisa dijangkau, panel memakai IP dari
     * server dan sisa barisnya tetap "Tidak diketahui". Mengarang nama ISP
     * akan lebih buruk daripada menampilkan apa adanya.
     */
    function loadConnectionInfo() {
        return fetch(cacheBust(CF_TRACE), { cache: 'no-store', mode: 'cors' })
            .then(function (response) {
                if (!response.ok) { throw new Error('http ' + response.status); }
                return response.text();
            })
            .then(function (text) {
                var trace = parseTrace(text);

                setInfo('ip', trace.ip || SERVER_IP || '');
                setInfo('isp', trace.asOrganization || trace.asn || '');

                var colo = trace.colo || '';
                var location = trace.loc ? ' (' + trace.loc + ')' : '';
                setInfo('server', describeServer(colo) + location);
            })
            .catch(function () {
                setInfo('ip', SERVER_IP || '');
                setInfo('isp', '');
                setInfo('server', 'Server RADNET NOC (lokal)');
            });
    }

    /* ═══════════════════════════════════════════════════════
       PENGUKURAN
      ═══════════════════════════════════════════════════════ */

    /* Tahap yang gagal diuji selama tes berjalan.
       Angka tidak pernah diganti dengan tebakan: kalau sebuah tahap gagal,
       kotaknya tetap kosong dan alasannya ditulis apa adanya. */
    var failures = [];

    /* Ping: beberapa sampel berurutan; dipakai nilai terbaik (terendah),
       sebab satu sampel bisa meleset karena antrian. */
    function measurePing() {
        var samples = [];
        var chain = Promise.resolve();

        for (var i = 0; i < PING_SAMPLES; i++) {
            (function (index) {
                chain = chain.then(function () {
                    var started = performance.now();
                    return track(
                        fetch(cacheBust(CF_TRACE), { cache: 'no-store', mode: 'cors' })
                            .then(function () {
                                var elapsed = performance.now() - started;
                                if (elapsed <= 0) { return; }
                                samples.push(elapsed);
                                /* Angka di tengah bergerak selama ping berjalan. */
                                target = Math.min(GAUGE_MAX_MBPS, elapsed * 4);
                                setSlot('ping', Math.min.apply(null, samples),
                                    'Mengukur ' + (index + 1) + '/' + PING_SAMPLES);
                            })
                    ).catch(function () { /* sampel gagal, lewati saja */ });
                });
            })(i);
        }

        return chain.then(function () {
            if (!samples.length) { throw new Error('ping gagal'); }
            return {
                ms: Math.min.apply(null, samples),
                avg: samples.reduce(function (a, b) { return a + b; }, 0) / samples.length,
                jitter: jitterOf(samples)
            };
        });
    }

    /* Download: beberapa berkas dijalankan paralel supaya satu koneksi TCP
       yang lambat tidak mengukur laju sebenarnya. */
    function measureDownload() {
        var peak = 0;

        function oneRound() {
            var started = performance.now();
            var jobs = [];

            for (var i = 0; i < DOWNLOAD_CONCURRENCY; i++) {
                jobs.push(track(
                    fetch(cacheBust(CF_DOWN + '?bytes=' + DOWNLOAD_BYTES), {
                        cache: 'no-store',
                        mode: 'cors'
                    }).then(function (response) {
                        if (!response.ok) { throw new Error('http ' + response.status); }
                        return response.arrayBuffer();
                    })
                ));
            }

            return Promise.all(jobs).then(function (buffers) {
                var elapsed = performance.now() - started;
                var bytes = buffers.reduce(function (total, buffer) {
                    return total + buffer.byteLength;
                }, 0);

                return bytesToMbps(bytes, elapsed);
            });
        }

        var chain = Promise.resolve();

        for (var round = 0; round < DOWNLOAD_ROUNDS; round++) {
            (function (current) {
                chain = chain.then(function () {
                    return oneRound().then(function (mbps) {
                        if (mbps > peak) { peak = mbps; }
                        target = Math.min(GAUGE_MAX_MBPS, peak);
                        setHero('download', peak, 'Ronde ' + (current + 1) + '/' + DOWNLOAD_ROUNDS);
                    });
                });
            })(round);
        }

        return chain.then(function () {
            if (!peak) { throw new Error('download gagal'); }
            return peak;
        });
    }

    /* Upload: ke endpoint milik NOC sendiri. Payload dibuat sebagai Blob berisi
       byte acak supaya tidak bisa dikompres proxy yang ada di antara browser
       dan server — kalau terkompres, laju yang terukur bukan laju unggah
       sebenarnya. */
    function makePayload(bytes) {
        var chunk = new Uint8Array(64 * 1024);
        for (var i = 0; i < chunk.length; i++) { chunk[i] = (Math.random() * 256) | 0; }

        var parts = [];
        var written = 0;
        while (written < bytes) {
            parts.push(chunk);
            written += chunk.length;
        }
        return new Blob(parts);
    }

    function measureUpload() {
        var peak = 0;

        function oneRound() {
            var payload = makePayload(UPLOAD_BYTES);
            var started = performance.now();

            return track(
                fetch(UPLOAD_SINK, {
                    method: 'POST',
                    headers: csrfHeaders(),
                    body: payload,
                    cache: 'no-store'
                }).then(function (response) {
                    if (!response.ok) { throw new Error('http ' + response.status); }
                    var elapsed = performance.now() - started;
                    return bytesToMbps(payload.size, elapsed);
                })
            );
        }

        var chain = Promise.resolve();

        for (var round = 0; round < UPLOAD_ROUNDS; round++) {
            (function (current) {
                chain = chain.then(function () {
                    return oneRound().then(function (mbps) {
                        if (mbps > peak) { peak = mbps; }
                        target = Math.min(GAUGE_MAX_MBPS, peak);
                        setHero('upload', peak, 'Ronde ' + (current + 1) + '/' + UPLOAD_ROUNDS);
                    });
                });
            })(round);
        }

        return chain.then(function () {
            if (!peak) { throw new Error('upload gagal'); }
            return peak;
        });
    }

    /* ═══════════════════════════════════════════════════════
       ORKESTRASI
      ═══════════════════════════════════════════════════════ */
    function runTest() {
        if (running) { return; }
        running = true;
        failures = [];
        loss.attempted = 0;
        loss.failed = 0;

        runBtn.disabled = true;
        runBtn.classList.add('is-busy');
        runLabel.textContent = 'Menguji...';
        runBtn.setAttribute('aria-label', 'Sedang Menguji...');
        gaugeWrap.classList.add('is-running');
        goHint.textContent = 'Pengujian sedang berjalan';

        /* Reset dulu, baru set ulang status: resetUi() mengembalikan gauge ke
           kondisi idle, jadi penanda "sedang jalan" harus dipasang sesudahnya. */
        resetUi();
        hideResults();
        gaugeWrap.classList.add('is-running');
        valueEl.classList.remove('is-idle');

        startAnimation();

        var overall = 0;

        /* Identitas koneksi dibaca paralel dengan pengukuran, jadi panel
           informasi tidak perlu menunggu tes selesai. */
        loadConnectionInfo();

        /* Tahap 1 — ping. */
        highlight('ping', true);
        setUnit('ms');
        setStatus('Mengukur Ping...');

        return measurePing()
            .then(function (ping) {
                setSlot('ping', ping.ms, pingNote(ping));
                setSlot('jitter', ping.jitter, jitterNote(ping.jitter));
                highlight('ping', false);
                overall += 1;
                paintProgress(overall / 3);
            })
            .catch(function () {
                /* Ping gagal. Kotak ping dan jitter dibiarkan kosong —
                   lebih berguna begitu daripada diisi angka yang tidak
                   pernah diukur. */
                failures.push('ping');
                setSlot('ping', null, 'Gagal diukur');
                setSlot('jitter', null, 'Gagal diukur');
                highlight('ping', false);
                overall += 1;
                paintProgress(overall / 3);
            })

            /* Tahap 2 — unduh. */
            .then(function () {
                highlight('download', true);
                heroLive('download', true);
                setUnit('Mbps');
                setStatus('Mengunduh...');
                target = 0;
                shown = 0;

                return withTimeout(measureDownload(), 60000);
            })
            .then(function (mbps) {
                target = Math.min(GAUGE_MAX_MBPS, mbps);
                setHero('download', mbps, speedNote(mbps));
                highlight('download', false);
                heroLive('download', false);
                overall += 1;
                paintProgress(overall / 3);
            })
            .catch(function () {
                /* Unduhan gagal — CDN diblokir atau tidak dapat dijangkau.
                   Kotak Unduhan tetap kosong. */
                failures.push('download');
                target = 0;
                setHero('download', null, 'Gagal diukur');
                highlight('download', false);
                heroLive('download', false);
                overall += 1;
                paintProgress(overall / 3);
            })

            /* Tahap 3 — unggah. */
            .then(function () {
                highlight('upload', true);
                heroLive('upload', true);
                setUnit('Mbps');
                setStatus('Mengunggah...');
                target = 0;
                shown = 0;

                return withTimeout(measureUpload(), 60000);
            })
            .then(function (mbps) {
                target = Math.min(GAUGE_MAX_MBPS, mbps);
                setHero('upload', mbps, speedNote(mbps));
                highlight('upload', false);
                heroLive('upload', false);
                overall += 1;
                paintProgress(1);
                return finish();
            })
            .catch(function () {
                /* Unggahan gagal — endpoint NOC tidak dapat dijangkau.
                   Kotak Unggahan tetap kosong. */
                failures.push('upload');
                target = 0;
                setHero('upload', null, 'Gagal diukur');
                highlight('upload', false);
                heroLive('upload', false);
                paintProgress(1);
                return finish();
            })
            .catch(function (error) {
                /* Kegagalan tak terduga: pastikan tombol tidak terkunci selamanya
                   supaya bisa diulang, dan tetap tampilkan panelnya. */
                setStatus('Pengujian gagal: ' + (error && error.message ? error.message : 'tidak diketahui'), 'error');
                showResults();
                releaseUi();
            })
            .then(function () {
                /* Loss dihitung setelah semua request selesai, lalu ditagih ke
                   metrik kecil. */
                setSlot('loss', lossPercentage(), lossNote(lossPercentage()));
                highlight('loss', false);
            });
    }

    function pingNote(ping) {
        var quality = ping.ms <= 30 ? 'Sangat baik'
            : ping.ms <= 80 ? 'Baik'
            : ping.ms <= 150 ? 'Sedang'
            : 'Tinggi';
        return quality + ' · rata-rata ' + formatMetric(ping.avg) + ' ms';
    }

    function jitterNote(ms) {
        var quality = ms <= 2 ? 'Sangat stabil'
            : ms <= 8 ? 'Stabil'
            : ms <= 20 ? 'Agak naik-turun'
            : 'Tidak stabil';
        return quality + ' · selisih antar paket';
    }

    function speedNote(mbps) {
        var quality = mbps >= 100 ? 'Sangat cepat'
            : mbps >= 25 ? 'Cukup cepat'
            : mbps >= 8 ? 'Lambat'
            : 'Sangat lambat';
        return quality;
    }

    function finish() {
        shown = target;
        paintArc(shown);
        setUnit('Mbps');
        setStatus('Selesai');
        showResults();
        releaseUi();
    }

    function releaseUi() {
        running = false;
        runBtn.disabled = false;
        runBtn.classList.remove('is-busy');
        runLabel.textContent = 'JALANKAN LAGI';
        runBtn.setAttribute('aria-label', 'Jalankan Lagi');
        gaugeWrap.classList.remove('is-running');
        goHint.textContent = 'Ketuk untuk mengukur ulang';

        if (failures.length) {
            var labels = failures.map(function (name) {
                return { ping: 'Ping', download: 'Unduhan', upload: 'Unggahan' }[name] || name;
            });

            noteEl.textContent = labels.join(' dan ') + ' tidak dapat diukur karena endpoint-nya tidak dapat '
                + 'dijangkau. Kotak yang kosong berarti tidak ada angka, bukan angka yang ditebak.';
            noteEl.classList.add('is-warn');
        } else {
            noteEl.textContent = 'Pengujian memakai bandwidth penuh selama beberapa detik dan memakai data.';
            noteEl.classList.remove('is-warn');
        }
    }

    /* ═══════════════════════════════════════════════════════
       PASANG
     ═══════════════════════════════════════════════════════ */
    function buildTicks() {
        var count = 40;
        var fragment = document.createDocumentFragment();

        for (var i = 0; i <= count; i++) {
            var angle = (i / count) * 359.9 - 90;
            var radians = angle * Math.PI / 180;
            var inner = 166;
            var outer = 172;

            var line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.setAttribute('class', 'stg-tick');
            line.setAttribute('x1', (160 + inner * Math.cos(radians)).toFixed(2));
            line.setAttribute('y1', (160 + inner * Math.sin(radians)).toFixed(2));
            line.setAttribute('x2', (160 + outer * Math.cos(radians)).toFixed(2));
            line.setAttribute('y2', (160 + outer * Math.sin(radians)).toFixed(2));

            fragment.appendChild(line);
        }

        ticksBox.appendChild(fragment);
    }

    function init() {
        arc.style.strokeDasharray = CIRCUMFERENCE;
        arc.style.strokeDashoffset = CIRCUMFERENCE;
        progress.style.strokeDasharray = PROGRESS_CIRCUMFERENCE;
        progress.style.strokeDashoffset = PROGRESS_CIRCUMFERENCE;

        /* Isi panel informasi dari nilai yang sudah dikirim server, supaya
           barisnya tidak kosong walau pengujian belum pernah dijalankan. */
        setInfo('ip', SERVER_IP || '');

        buildTicks();
        resetUi();
        hideResults();

        runBtn.addEventListener('click', runTest);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush