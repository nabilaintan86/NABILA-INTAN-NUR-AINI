@extends('layouts.admin')

@section('title', 'Network Tools · RADNET NOC')

@push('styles')
<style>
    .tools-panel {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 16px rgba(15, 23, 42, .06);
    }
    .tools-panel-head {
        background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 60%, #60a5fa 100%);
        padding: 22px 28px;
        color: #fff;
    }
    .tools-panel-head .tools-title {
        font-size: 18px;
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .tools-panel-head .tools-subtitle {
        color: #dbeafe;
        font-size: 12.5px;
        margin: 4px 0 0 34px;
    }
    .tools-panel-body {
        padding: 26px 28px;
    }

    .tools-field .tools-label {
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 6px;
        display: block;
    }
    .tools-field .form-control,
    .tools-field .form-select {
        font-size: 13.5px;
        border-color: #e2e8f0;
        box-shadow: none;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
    .tools-field .form-control:focus,
    .tools-field .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
    }
    .tools-field .input-group-text {
        background: #f1f5f9;
        border-color: #e2e8f0;
        color: #64748b;
        font-size: 12.5px;
    }

    .tools-actions {
        border-top: 1px dashed #e2e8f0;
        padding-top: 18px;
        margin-top: 22px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
    }
    .tools-actions .btn {
        min-width: 88px;
        font-weight: 700;
        border-radius: 8px;
    }
    .btn-tool-ping   { background: #059669; border-color: #059669; }
    .btn-tool-ping:hover, .btn-tool-ping:focus { background: #047857; border-color: #047857; }
    .btn-tool-trace  { background: #6d28d9; border-color: #6d28d9; }
    .btn-tool-trace:hover, .btn-tool-trace:focus { background: #5b21b6; border-color: #5b21b6; }
    .btn-tool-lookup { background: #2563eb; border-color: #2563eb; }
    .btn-tool-lookup:hover, .btn-tool-lookup:focus { background: #1d4ed8; border-color: #1d4ed8; }
    .btn-tool-scan   { background: #dc2626; border-color: #dc2626; }
    .btn-tool-scan:hover, .btn-tool-scan:focus { background: #b91c1c; border-color: #b91c1c; }

    .console-box {
        background: #0f172a;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(15, 23, 42, .10);
    }
    .console-head {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 14px 20px;
        border-bottom: 1px solid #1e293b;
    }
    .console-dot {
        width: 11px;
        height: 11px;
        border-radius: 50%;
        display: inline-block;
    }
    .console-dot-red    { background: #f87171; }
    .console-dot-yellow { background: #fbbf24; }
    .console-dot-green  { background: #34d399; }
    .console-title {
        color: #e2e8f0;
        font-size: 14px;
        font-weight: 700;
        margin-left: 8px;
    }
    .console-actions {
        margin-left: auto;
        display: flex;
        gap: 6px;
    }
    .console-actions .btn {
        font-size: 11px;
        font-weight: 700;
        border-radius: 6px;
        padding: 4px 10px;
    }
    .console-output {
        font-family: 'Consolas', 'Menlo', 'Monaco', 'Courier New', monospace;
        font-size: 12.5px;
        line-height: 1.6;
        color: #cbd5e1;
        padding: 18px 22px;
        min-height: 320px;
        max-height: 520px;
        overflow-y: auto;
        white-space: pre-wrap;
        word-break: break-word;
    }
    .console-output .cmd-line {
        color: #60a5fa;
        font-weight: 600;
        margin-bottom: 8px;
    }
    .console-output .ok-line  { color: #94a3b8; }
    .console-output .err-line { color: #f87171; }
    .console-output .ns-line  { color: #4ade80; font-weight: 700; }
    .console-hint {
        color: #475569;
        font-family: 'Consolas', 'Menlo', monospace;
        font-size: 12.5px;
        text-align: center;
        padding-top: 26px;
    }
    .console-cursor {
        display: inline-block;
        width: 8px;
        height: 15px;
        background: #4ade80;
        vertical-align: middle;
        animation: blink 1s steps(2, start) infinite;
    }
    @keyframes blink { to { visibility: hidden; } }
</style>
@endpush

@section('content')
<div class="nk-content">
    <div class="container">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                {{-- HEADER --}}
                <div class="nk-block-head">
                    <div class="nk-block-head-between flex-wrap gap g-2">
                        <div class="nk-block-head-content">
                            <nav>
                                <ol class="breadcrumb breadcrumb-arrow mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Network Tools
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                {{-- FORM PANEL --}}
                <div class="nk-block">
                    <form id="networkToolForm" autocomplete="off">

                        <div class="tools-panel">

                            <div class="tools-panel-head">
                                <h5 class="tools-title">
                                    <em class="icon ni ni-activity-round"></em>
                                    Command Center — Diagnosa Jaringan
                                </h5>
                                <p class="tools-subtitle">
                                    Ping, traceroute, DNS lookup &amp; port scan terhadap IP / domain target.
                                </p>
                            </div>

                            <div class="tools-panel-body">

                                {{-- TARGET --}}
                                <div class="tools-field mb-4">
                                    <label class="tools-label" for="toolTarget">Name / Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><em class="icon ni ni-globe"></em></span>
                                        <input type="text" class="form-control" id="toolTarget" name="target"
                                               placeholder="Contoh: 202.154.4.254 atau google.com"
                                               required>
                                    </div>
                                </div>

                                <div class="row g-4">

                                    <div class="col-md-6 col-lg-3">
                                        <div class="tools-field">
                                            <label class="tools-label" for="toolCount">Ping count</label>
                                            <input type="number" class="form-control" id="toolCount" name="count"
                                                   value="5" min="1" max="100">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <div class="tools-field">
                                            <label class="tools-label" for="toolHops">Trace max hops</label>
                                            <input type="number" class="form-control" id="toolHops" name="hops"
                                                   value="32" min="1" max="64">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <div class="tools-field">
                                            <label class="tools-label" for="toolLookupType">Lookup type</label>
                                            <select class="form-select" id="toolLookupType" name="lookup_type">
                                                <option value="A" selected>A</option>
                                                <option value="AAAA">AAAA</option>
                                                <option value="CNAME">CNAME</option>
                                                <option value="MX">MX</option>
                                                <option value="NS">NS</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <div class="tools-field">
                                            <label class="tools-label" for="toolLookupServer">Lookup server</label>
                                            <input type="text" class="form-control" id="toolLookupServer"
                                                   name="lookup_server"
                                                   placeholder="8.8.8.8 (kosong = default)">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <div class="tools-field">
                                            <label class="tools-label" for="toolFirstPort">Scan first port</label>
                                            <input type="number" class="form-control" id="toolFirstPort"
                                                   name="first_port" value="1" min="1" max="65535">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <div class="tools-field">
                                            <label class="tools-label" for="toolLastPort">Scan last port</label>
                                            <input type="number" class="form-control" id="toolLastPort"
                                                   name="last_port" value="512" min="1" max="65535">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <div class="tools-field">
                                            <label class="tools-label" for="toolScanTimeout">Scan timeout (ms 100-2000)</label>
                                            <input type="number" class="form-control" id="toolScanTimeout"
                                                   name="scan_timeout" value="300" min="100" max="2000">
                                        </div>
                                    </div>

                                </div>

                                {{-- ACTIONS --}}
                                <div class="tools-actions">
                                    <input type="hidden" name="action" id="toolAction" value="ping">
                                    <button type="button" class="btn btn-primary btn-tool-ping" data-action="ping">
                                        <em class="icon ni ni-send"></em>
                                        <span>Ping</span>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-tool-trace" data-action="trace">
                                        <em class="icon ni ni-route"></em>
                                        <span>Trace</span>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-tool-lookup" data-action="lookup">
                                        <em class="icon ni ni-search"></em>
                                        <span>Lookup</span>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-tool-scan" data-action="scan">
                                        <em class="icon ni ni-drag-alt"></em>
                                        <span>Scan</span>
                                    </button>
                                </div>

                            </div>
                        </div>

                    </form>
                </div>

                {{-- CONSOLE OUTPUT --}}
                <div class="nk-block mt-3">
                    <div class="console-box">

                        <div class="console-head">
                            <span class="console-dot console-dot-red"></span>
                            <span class="console-dot console-dot-yellow"></span>
                            <span class="console-dot console-dot-green"></span>
                            <h6 class="console-title">Terminal Output</h6>
                            <div class="console-actions">
                                <button type="button" class="btn btn-light btn-dim" id="btnConsoleCopy">
                                    <em class="icon ni ni-copy me-1"></em>Salin
                                </button>
                                <button type="button" class="btn btn-outline-light btn-dim" id="btnConsoleClear">
                                    <em class="icon ni ni-reload me-1"></em>Bersihkan
                                </button>
                            </div>
                        </div>

                        <div class="console-output" id="consoleOutput">
                            <span class="console-hint">
                                &gt; Pilih target di atas lalu klik <strong>Ping</strong>, <strong>Trace</strong>,
                                <strong>Lookup</strong>, atau <strong>Scan</strong>.<br>
                                Hasil eksekusi perintah akan muncul di sini secara real-time.
                            </span>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const form      = document.getElementById('networkToolForm');
    const actionBox = document.getElementById('toolAction');
    const output    = document.getElementById('consoleOutput');

    const csrf = document.querySelector('meta[name="csrf-token"]');
    const routes = {
        ping:   '{{ route("admin.tools.ping") }}',
        trace:  '{{ route("admin.tools.trace") }}',
        lookup: '{{ route("admin.tools.lookup") }}',
        scan:   '{{ route("admin.tools.scan") }}',
    };

    function escHTML(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function lines(str) {
        return String(str).split('\n');
    }

    function renderResult(res) {
        if (!res.success) {
            let msg = res.message || res.error || 'Perintah selesai dengan kode keluar ' + res.exit + '.';
            printLine('ERROR', msg, 'err-line');

            if (res.error && res.message !== res.error) {
                printLine('DETAIL', res.error, 'err-line');
            }
            if (res.exit) {
                printLine('EXIT', 'Kode keluar: ' + res.exit, 'err-line');
            }
            if (res.network_fault) {
                printLine('!', 'Kegagalan terkait jaringan / DNS (bukan masalah eksekusi perintah).', 'err-line');
            }
            if (res.command) {
                printLine('CMD', '$ ' + res.command, 'cmd-line');
            }
            if (res.output) {
                printLine('---', '--------------------------------------------------', 'ok-line');
                lines(res.output).forEach(function (l) {
                    printLine('', l, 'ok-line');
                });
            }
            return;
        }

        printLine('CMD', '$ ' + (res.command || ''), 'cmd-line');
        if (res.summary) {
            printLine('INFO', 'target: ' + res.summary, 'ok-line');
        }
        printLine('RUN', res.label + ' — selesai dalam ' + res.duration + ' s (exit ' + res.exit + ')', 'ok-line');
        printLine('---', '--------------------------------------------------', 'ok-line');

        const text = (res.timedout)
            ? 'Waktu eksekusi melebihi batas maksimum…'
            : (res.output || 'Perintah selesai tanpa output.');

        lines(text).forEach(function (l) {
            printLine('', l, 'ok-line');
        });
    }

    function printLine(prefix, text, cls) {
        const line = document.createElement('div');
        line.className = cls;
        const p = prefix ? '<span class="ns-line">[' + prefix + ']</span> ' : '';
        line.innerHTML = p + escHTML(text);
        output.appendChild(line);
        output.scrollTop = output.scrollHeight;
    }

    function banner(msg) {
        const line = document.createElement('div');
        line.className = 'err-line';
        line.innerHTML = '<span class="ns-line">[!]</span> ' + escHTML(msg);
        output.appendChild(line);
        output.scrollTop = output.scrollHeight;
    }

    function setLoading(on) {
        output.classList.toggle('is-loading', on);
        form.querySelectorAll('button[data-action]').forEach(function (btn) {
            btn.disabled = on;
        });
        if (on) {
            const cursor = document.createElement('span');
            cursor.className = 'console-cursor';
            output.appendChild(cursor);
        }
    }

    form.querySelectorAll('button[data-action]').forEach(function (btn) {
        btn.addEventListener('click', function () {

            const target = document.getElementById('toolTarget').value.trim();
            if (!target) {
                banner('Isi kolom Name/Address terlebih dahulu.');
                document.getElementById('toolTarget').focus();
                return;
            }

            if (btn.querySelector('.console-cursor')) {
                return;
            }

            actionBox.value = btn.dataset.action;
            const url      = routes[btn.dataset.action];
            const body     = new FormData(form);
            const action   = btn.dataset.action.toUpperCase();

            output.innerHTML = '';
            printLine('EXEC', action + ' ' + target + '…', 'cmd-line');
            setLoading(true);

            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf ? csrf.content : '' },
                body: body,
            })
            .then(function (r) { return Promise.all([r.status, r.json()]); })
            .catch(function () {
                return [[408], { success: false, message: 'Tidak dapat terhubung ke server atau waktu habis.' }];
            })
            .then(function (data) {
                setLoading(false);
                if (data[0] === 422) {
                    banner(data[1].message || 'Input tidak valid.');
                    return;
                }
                renderResult(data[1]);
            });
        });
    });

    document.getElementById('btnConsoleClear').addEventListener('click', function () {
        output.innerHTML = '';
    });

    document.getElementById('btnConsoleCopy').addEventListener('click', function () {
        const text = output.innerText;
        if (!text) return;
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function () {
                alert('Output disalin ke clipboard.');
            });
        } else {
            const ta = document.createElement('textarea');
            ta.value = text;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            alert('Output disalin ke clipboard.');
        }
    });
})();
</script>
@endpush