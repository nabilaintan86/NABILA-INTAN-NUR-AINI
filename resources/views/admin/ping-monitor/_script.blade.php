{{--
    Script tab pemantauan ping, dalam IIFE sendiri supaya tidak bentrok
    dengan variable milik halaman Network Tools yang juga punya skrip
    panjang pada halaman yang sama.
--}}
<script>
(function () {
    'use strict';

    /* ═══════════════════════════════════════════════════════
       KONFIGURASI
    ═══════════════════════════════════════════════════════ */
    var PING_URL = @json(route('admin.tools.ping'));

    var CSRF = document.querySelector('meta[name="csrf-token"]');

    /* Ambang batas untuk penilaian status. Nilai-nilai ini dipakai apa adanya
       di kotak analisis, jadi atasan tidak perlu menebak artinya. */
    var LATENCY_WARN_MS = 150;   /* di atas ini latensi dianggap tinggi  */
    var LATENCY_HIGH_MS = 300;   /* di atas ini dianggap sangat buruk    */

    /* Target bawaan tiap tab. Nilai ini juga jadi isi kolom IP,
       jadi keduanya tidak bisa berbeda. */
    var DEFAULTS = {
        radnet: '192.168.93.59',
        luar:   '8.8.8.8'
    };

    var LABEL = { radnet: 'Server Radnet', luar: 'Server Luar' };

    /* Hasil terakhir tiap target, dipakai oleh tab Status Ringkasan. */
    var lastResult = { radnet: null, luar: null };

    /* ═══════════════════════════════════════════════════════
       TAB
    ═══════════════════════════════════════════════════════ */
    var tabs   = Array.prototype.slice.call(document.querySelectorAll('.pm-tab'));
    var panels = Array.prototype.slice.call(document.querySelectorAll('[data-panel]'));

    function selectTab(name) {
        tabs.forEach(function (tab) {
            var on = tab.dataset.tab === name;
            tab.classList.toggle('is-active', on);
            tab.setAttribute('aria-selected', on ? 'true' : 'false');
        });

        panels.forEach(function (panel) {
            var on = panel.dataset.panel === name;
            panel.classList.toggle('hidden', !on);
            panel.classList.toggle('flex', on);
        });
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () { selectTab(tab.dataset.tab); });
    });

    /* ═══════════════════════════════════════════════════════
       LOG TERMINAL
    ═══════════════════════════════════════════════════════ */
    function logEl(key) { return document.querySelector('[data-log="' + key + '"]'); }

    function clearLog(key) {
        var box = logEl(key);
        box.innerHTML = '';
        box.scrollTop = box.scrollHeight;
    }

    function appendLine(key, text, tone) {
        var box = logEl(key);
        var line = document.createElement('div');

        line.className = 'whitespace-pre-wrap';
        if (tone === 'good')     { line.className += ' text-emerald-400'; }
        else if (tone === 'warn')    { line.className += ' text-amber-400'; }
        else if (tone === 'bad')     { line.className += ' text-rose-400'; }
        else if (tone === 'dim')     { line.className += ' text-slate-500'; }
        else if (tone === 'head')    { line.className += ' text-sky-300 font-semibold'; }

        line.textContent = text;
        box.appendChild(line);
        box.scrollTop = box.scrollHeight;

        return line;
    }

    /* Baris paket ditampilkan satu per satu supaya terminal terlihat
       hidup. Ini murni animasi tampilan: angkanya sudah lengkap di
       respons, tidak ada pengukuran yang dipecah jadi beberapa request. */
    function playLines(key, lines, done) {
        var index = 0;

        (function next() {
            if (index >= lines.length) { if (done) { done(); } return; }

            var entry = lines[index++];
            appendLine(key, entry.text, entry.tone);

            var wait = entry.tone === 'head' ? 90 : 150;
            setTimeout(next, wait);
        })();
    }

    /* ═══════════════════════════════════════════════════════
       PARSING HASIL PING ASLI
    ---------------------------------------------------------
       Backend menjalankan `ping -4 -c N -W 3` sungguhan, jadi yang
       masuk ke sini adalah output Linux apa adanya. Baris itu
       diterjemahkan ke format WhatsUp Gold:

         64 bytes ... icmp_seq=1 ... time=0.512 ms
            -> ping [IP] packet 1 round trip time = 0.512 ms

       Tidak ada angka yang dikarang: kalau backend tidak mengembalikan
       waktu paket, barisnya memang tidak dibuat.
    ═══════════════════════════════════════════════════════ */
    function parsePing(output, target) {
        var text = (output || '');
        var lines = [];

        /* --- Baris per paket --- */
        var packetRe = /icmp_seq=(\d+)[^]*?time=([\d.]+)\s*ms/;
        var re = new RegExp(packetRe.source, 'g');
        var match;
        var samples = [];
        var seq = 0;

        while ((match = re.exec(text)) !== null) {
            seq = parseInt(match[1], 10);
            var time = parseFloat(match[2]);
            samples.push(time);

            lines.push({
                text: 'ping [' + target + '] packet ' + seq +
                      ' round trip time = ' + time + ' ms',
                tone: 'normal'
            });
        }

        /* --- Statistik packet --- */
        var statsRe = /(\d+)\s+packets transmitted,\s*(\d+)\s+(?:packets )?received,\s*([\d.]+)%\s+packet loss/;
        var stats = text.match(statsRe);

        var sent = stats ? parseInt(stats[1], 10) : seq;
        var received = stats ? parseInt(stats[2], 10) : samples.length;
        var loss = stats ? parseFloat(stats[3]) : (sent ? 100 - (received / sent) * 100 : 100);

        /* --- Ringkasan round trip --- */
        var rttRe = /(?:rtt|round-trip)\s+min\/avg\/max(?:\/mdev|\/stddev)?\s*=\s*([\d.]+)\/([\d.]+)\/([\d.]+)(?:\/([\d.]+))?\s*ms/;
        var rtt = text.match(rttRe);

        var min = samples.length ? Math.min.apply(null, samples) : null;
        var avg = rtt ? parseFloat(rtt[2]) : (samples.length ? average(samples) : null);
        var max = samples.length ? Math.max.apply(null, samples) : null;

        return {
            lines: lines,
            samples: samples,
            sent: sent,
            received: received,
            loss: loss,
            min: min,
            avg: avg,
            max: max,
            raw: text
        };
    }

    function average(values) {
        return values.reduce(function (a, b) { return a + b; }, 0) / values.length;
    }

    /* ═══════════════════════════════════════════════════════
       PENILAIAN KESEHATAN
    ---------------------------------------------------------
       Tiga keadaan sesuai bahasa atasan:

         UP      —(loss 0% dan latensi kecil, atau masih di bawah ambang)
         WARNING —(packet loss > 0% ATAU latensi > 150ms)
         DOWN    —(tidak ada satu pun paket yang membalas)
    ═══════════════════════════════════════════════════════ */
    function assess(result) {
        if (!result || result.received === 0) {
            return {
                state: 'down',
                icon: '🔴',
                title: 'DOWN — Gangguan Total',
                tone: 'bad',
                message: 'Server tidak merespons sama sekali. Seluruh ' + result.sent +
                         ' paket hilang (100% loss) dan muncul pesan "Request Timed Out". ' +
                         'Kemungkinan server sedang mati, kabel jaringan terputus, atau ' +
                         'perangkat di ujung jalurnya belum menyala. Perlu pemeriksaan fisik ' +
                         'segera.'
            };
        }

        var highLatency = result.avg !== null && result.avg > LATENCY_WARN_MS;

        if (result.loss > 0 || highLatency) {
            var reasons = [];

            if (result.loss > 0) {
                reasons.push('ada ' + result.received + ' dari ' + result.sent +
                             ' paket yang lolos (' + trimNumber(result.loss) + '% hilang)');
            }
            if (highLatency) {
                reasons.push('rata-rata balasan ' + trimNumber(result.avg) + ' ms, ' +
                             'di atas ambang ' + LATENCY_WARN_MS + ' ms');
            }

            var severe = result.avg !== null && result.avg > LATENCY_HIGH_MS;

            return {
                state: 'warning',
                icon: '🟡',
                title: 'WARNING — Perlu Perhatian',
                tone: 'warn',
                message: 'Server masih hidup, tetapi koneksinya tidak sehat: ' +
                         reasons.join(' dan ') + '. ' +
                         (severe
                            ? 'Ini menandakan antrian Very Severe (di atas ' + LATENCY_HIGH_MS +
                              ' ms), biasanya karena trafik padat atau bottleneck di sisi upstream.'
: 'Pola ini wajar saat trafik sedang padat, atau karena routing ISP ' +
                               'memilih jalur yang lebih panjang dari biasanya. Bila kondisi ini ' +
                               'berlangsung lebih dari beberapa menit, perlu ditelusuri.')
            };
        }

        return {
            state: 'up',
            icon: '🟢',
            title: 'UP — Normal',
            tone: 'good',
            message: 'Server merespons dengan baik. Seluruh ' + result.sent +
                     ' paket sampai tanpa satu pun hilang (0% loss), dengan rata-rata ' +
                     trimNumber(result.avg) + ' ms' +
                     (result.max !== null
                        ? ' dan paling lambat ' + trimNumber(result.max) + ' ms'
                        : '') + '. ' +
                     'Tidak ada indikasi gangguan jaringan saat ini.'
        };
    }

    /* Buang angka nol di belakang koma supaya bahasa analyzing tetap
       enak dibaca: 12.40 -> 12.4, 12.00 -> 12. */
    function trimNumber(value) {
        if (value === null || value === undefined) { return '-'; }
        return parseFloat(Number(value).toFixed(2)).toString();
    }

    /* ═══════════════════════════════════════════════════════
       KOTAK ANALISIS
    ═══════════════════════════════════════════════════════ */
    function showAnalysis(key, verdict, result) {
        var box = document.querySelector('[data-analysis="' + key + '"]');
        if (!box) { return; }

        var toneClass = {
            good: 'border-emerald-500/40 bg-emerald-500/10',
            warn: 'border-amber-500/40 bg-amber-500/10',
            bad:  'border-rose-500/40 bg-rose-500/10'
        }[verdict.tone];

        var titleClass = {
            good: 'text-emerald-300',
            warn: 'text-amber-300',
            bad:  'text-rose-300'
        }[verdict.tone];

        var facts = [];
        if (result.sent !== null)     { facts.push('Terkirim: ' + result.sent); }
        if (result.received !== null) { facts.push('Diterima: ' + result.received); }
        if (result.loss !== null)     { facts.push('Loss: ' + trimNumber(result.loss) + '%'); }
        if (result.avg !== null)      { facts.push('Rata-rata: ' + trimNumber(result.avg) + ' ms'); }

        /* Sisi kiri tebal sebagai penanda status: kesimpulan bisa dibaca
           sekilas dari ketebalan warna, tanpa harus membaca paragrafnya. */
        box.className = 'rounded-xl border border-l-4 p-4 ' + toneClass;
        box.innerHTML = '';

        var head = document.createElement('div');
        head.className = 'flex items-center gap-2 text-sm font-extrabold ' + titleClass;
        head.textContent = verdict.icon + '  ' + verdict.title;

        var message = document.createElement('p');
        message.className = 'mt-2 text-sm leading-relaxed text-slate-300';
        message.textContent = verdict.message;

        var detail = document.createElement('p');
        detail.className = 'mt-2.5 font-mono text-xs text-slate-400';
        detail.textContent = facts.join('  ·  ');

        box.appendChild(head);
        box.appendChild(message);
        box.appendChild(detail);

        box.classList.remove('hidden');
    }

    /* ═══════════════════════════════════════════════════════
       BADGE STATUS DI HEADER KOTAK BIRU
    ═══════════════════════════════════════════════════════ */
    function setBadge(key, verdict, text) {
        var badge = document.querySelector('[data-badge="' + key + '"]');

        var toneClass = {
            good: 'bg-emerald-500/20 text-emerald-300 ring-1 ring-emerald-500/40',
            warn: 'bg-amber-500/20 text-amber-300 ring-1 ring-amber-500/40',
            bad:  'bg-rose-500/20 text-rose-300 ring-1 ring-rose-500/40',
            busy: 'bg-sky-500/20 text-sky-300 ring-1 ring-sky-500/40',
            idle: 'bg-slate-600/40 text-slate-300 ring-1 ring-slate-500/40'
        };

        badge.className = 'pm-badge inline-flex shrink-0 items-center gap-2 rounded-full px-3 py-1 ' +
            'text-xs font-extrabold ' + (verdict ? toneClass[verdict] : toneClass.idle);
        badge.textContent = text;
    }

    /* ═══════════════════════════════════════════════════════
       RINGKASAN
    ═══════════════════════════════════════════════════════ */
    function renderSummary() {
        var empty = document.querySelector('[data-summary-empty]');
        var filled = Object.keys(lastResult).some(function (k) { return lastResult[k]; });
        if (empty) { empty.classList.toggle('hidden', filled); }

        Object.keys(lastResult).forEach(function (key) {
            var card = document.querySelector('[data-summary="' + key + '"]');
            var stored = lastResult[key];
            if (!stored) { return; }

            var verdict = stored.verdict;
            var toneClass = {
                good: 'text-emerald-400',
                warn: 'text-amber-400',
                bad:  'text-rose-400'
            }[verdict.tone];

            /* Garis aksen di atas kartu ikut warna status, supaya kesimpulan
               bisa dibaca sekilas tanpa membaca teksnya. */
            card.setAttribute('data-state', verdict.tone);
            card.innerHTML = '';

            var label = document.createElement('div');
            label.className = 'text-xs font-extrabold tracking-wide text-slate-400 uppercase';
            label.textContent = (key === 'radnet' ? '🌐 ' : '🌍 ') + LABEL[key];

            var status = document.createElement('div');
            status.className = 'mt-2 text-lg font-extrabold ' + toneClass;
            status.textContent = verdict.icon + ' ' + verdict.title.split('—')[0].trim();

            var ip = document.createElement('div');
            ip.className = 'mt-1 font-mono text-xs text-slate-400';
            ip.textContent = stored.target;

            var facts = document.createElement('div');
            facts.className = 'mt-2 font-mono text-xs text-slate-500';
            facts.textContent = 'Rata-rata ' + trimNumber(stored.result.avg) + ' ms  ·  ' +
                'Loss ' + trimNumber(stored.result.loss) + '%';

            var when = document.createElement('div');
            when.className = 'mt-1 text-[11px] text-slate-600';
            when.textContent = 'Diuji ' + stored.at;

            card.appendChild(label);
            card.appendChild(status);
            card.appendChild(ip);
            card.appendChild(facts);
            card.appendChild(when);
        });
    }

    /* ═══════════════════════════════════════════════════════
       MENJALANKAN PING
    ═══════════════════════════════════════════════════════ */
    function headers() {
        var h = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
        if (CSRF) { h['X-CSRF-TOKEN'] = CSRF.content; }
        return h;
    }

    function runPing(key) {
        var form = document.querySelector('[data-form="' + key + '"]');
        var button = document.querySelector('[data-run="' + key + '"]');
        var input = form.querySelector('input[name="target"]');
        var count = form.querySelector('select[name="count"]');

        var target = (input.value || '').trim();
        var total = parseInt(count.value, 10) || 5;

        var analysis = document.querySelector('[data-analysis="' + key + '"]');

        button.disabled = true;
        button.textContent = 'Menguji...';
        setBadge(key, { }, 'Menguji...');
        if (analysis) { analysis.classList.add('hidden'); }
        clearLog(key);

        appendLine(key, 'Pinging ' + target + ' with ' + total + ' packets of 64 bytes...', 'head');

        var payload = { target: target, count: total };
        if (input.dataset.deviceId) { payload.device_id = input.dataset.deviceId; }

        return fetch(PING_URL, {
            method: 'POST',
            headers: headers(),
            credentials: 'same-origin',
            body: JSON.stringify(payload)
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    return { ok: response.ok, data: data };
                });
            })
            .then(function (payloadOut) {
                var data = payloadOut.data;

                if (!data || data.message) {
                    throw new Error(data && data.message ? data.message : 'Permintaan ditolak.');
                }

                /* Target yang diketik manual dibersihkan dari spasi dan
                   dichlor agar tampilan konsisten. */
                var shown = target;

                var result = parsePing(data.output, shown);

                if (!result.sent && !result.samples.length) {
                    /* Tidak ada satu pun baris yang bisa dibaca: backend
                       mungkin gagal menjalankan ping, atau binernya tidak
                       ada. Jangan tampilkan angka apa pun. */
                    appendLine(key, 'Tidak ada respons yang bisa dibaca dari server.', 'bad');
                    appendLine(key, 'Periksa apakah perintah ping tersedia di server.', 'dim');

                    if (analysis) {
                        analysis.classList.remove('hidden');
                        analysis.className = 'rounded-xl border border-rose-500/40 bg-rose-500/10 p-4';
                        analysis.innerHTML =
                            '<div class="text-sm font-extrabold text-rose-300">🔴  DOWN — Gangguan Total</div>' +
                            '<p class="mt-2 text-sm leading-relaxed text-slate-300">Pengujian tidak bisa ' +
                            'dijalankan, jadi kondisi server belum bisa dipastikan. Periksa konfigurasi ' +
                            'perintah ping di server NOC.</p>';
                    }

                    setBadge(key, { tone: 'bad' }, 'Gagal');
                    return;
                }

                var lines = result.lines.slice();

                if (result.sent) {
                    lines.push({
                        text: 'Sent: ' + result.sent + '  Received: ' + result.received +
                              '  Loss: ' + trimNumber(result.loss) + '%',
                        tone: result.received === 0 ? 'bad'
                            : (result.loss > 0 ? 'warn' : 'good')
                    });
                }

                if (result.avg !== null) {
                    lines.push({
                        text: 'Round-trip min/avg/max = ' + trimNumber(result.min) + '/' +
                              trimNumber(result.avg) + '/' + trimNumber(result.max) + ' ms',
                        tone: 'head'
                    });
                }

                playLines(key, lines, function () {
                    var verdict = assess(result);

                    showAnalysis(key, verdict, result);
                    setBadge(key, verdict, verdict.state === 'up'
                        ? 'UP'
                        : (verdict.state === 'warning' ? 'WARNING' : 'DOWN'));

                    lastResult[key] = {
                        target: shown,
                        result: result,
                        verdict: verdict,
                        at: new Date().toLocaleTimeString('id-ID')
                    };

                    renderSummary();
                });

                button.disabled = false;
                button.textContent = 'Mulai Ping';
            })
            .catch(function (error) {
                appendLine(key, 'Gagal: ' + (error && error.message ? error.message : 'tidak diketahui'), 'bad');

                if (analysis) {
                    analysis.classList.remove('hidden');
                    analysis.className = 'rounded-xl border border-rose-500/40 bg-rose-500/10 p-4';
                    analysis.innerHTML =
                        '<div class="text-sm font-extrabold text-rose-300">🔴  DOWN — Gangguan Total</div>' +
                        '<p class="mt-2 text-sm leading-relaxed text-slate-300">Pengujian tidak bisa ' +
                        'dijalankan: ' + (error && error.message ? error.message : 'tidak diketahui') +
                        '. Kondisi server belum dapat dipastikan.</p>';
                }

                setBadge(key, { tone: 'bad' }, 'Gagal');
                button.disabled = false;
                button.textContent = 'Mulai Ping';
            });
    }

    /* ═══════════════════════════════════════════════════════
       PASANG
    ═══════════════════════════════════════════════════════ */
    document.querySelectorAll('[data-form]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            runPing(form.dataset.form);
        });
    });

    /* Badge awal mengikuti warna idle. */
    ['radnet', 'luar'].forEach(function (key) { setBadge(key, null, 'Belum diuji'); });

    selectTab('radnet');
})();
</script>
