<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan &amp; Grafik {{ $device['name'] }} {{ $period['label'] }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        h1 { font-size: 16px; margin: 0 0 2px 0; color: #0f172a; }
        h2 { font-size: 11px; margin: 14px 0 5px 0; color: #0f172a; padding-bottom: 3px; border-bottom: 2px solid #4F46E5; }
        .subtitle { color: #64748b; font-size: 9px; margin-bottom: 2px; }
        .range { color: #334155; font-size: 9.5px; margin-bottom: 10px; }

        .brand { text-align: center; padding: 10px 0 8px 0; border-bottom: 2px solid #4F46E5; margin-bottom: 12px; }
        .brand .meta { font-size: 8.5px; color: #64748b; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        th, td { border: 1px solid #cbd5e1; padding: 3px 5px; text-align: left; }
        th { background: #eef2ff; font-weight: 700; font-size: 8.5px; }
        td { font-size: 8.5px; }
        .num { text-align: right; }
        .center { text-align: center; }

        .summary { table-layout: fixed; }
        .summary td { text-align: center; font-size: 11px; font-weight: 700; padding: 6px 4px; }
        .summary .label { font-weight: 600; font-size: 7.5px; color: #64748b; display: block; margin-top: 2px; }

        .up    { color: #16a34a; font-weight: 600; }
        .down  { color: #dc2626; font-weight: 600; }
        .warn  { color: #d97706; font-weight: 600; }
        .muted { color: #64748b; }

        .badge { display: inline-block; padding: 1px 5px; border-radius: 3px; font-weight: 700; font-size: 8px; }
        .b-down { background: #fdeceb; color: #c73b37; }
        .b-unreach { background: #fdf3df; color: #b07d10; }
        .b-rec  { background: #e8f6ee; color: #106b41; }
        .b-ongoing { background: #ecebfd; color: #4F46E5; }

        /* Grafik batang (DomPDF: blok + lebar persen) */
        table.barchart { margin-bottom: 4px; }
        table.barchart td { border: 0; padding: 1px 3px; vertical-align: middle; }
        table.barchart .bc-lbl { width: 46px; color: #64748b; font-size: 7.5px; }
        table.barchart .bc-track { background: #f1f5f9; }
        table.barchart .bc-val { width: 58px; text-align: right; font-weight: 700; color: #1e293b; }
        table.barchart .bc-note { width: 92px; text-align: right; color: #94a3b8; font-size: 7.5px; }
        div.bc-fill { height: 7px; }
        .b-indigo { background-color: #4F46E5; }
        .b-green  { background-color: #16a34a; }
        .b-amber  { background-color: #d97706; }
        .b-red    { background-color: #dc2626; }
        .legend { font-size: 7.5px; color: #64748b; margin-top: 3px; }
        .legend span { margin-right: 10px; }
        .swatch { display: inline-block; width: 7px; height: 7px; margin-right: 3px; }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>

    <div class="brand">
        <h1>Laporan &amp; Grafik Jaringan</h1>
        <div class="subtitle">RADNEXT NOC &middot; {{ $device['name'] }}</div>
        <div class="meta">Laporan per perangkat &middot; Dihasilkan {{ $generatedAt }}</div>
    </div>

    <h2>Identitas Perangkat</h2>
    <table>
        <tr>
            <td style="width:22%">Nama Perangkat</td>
            <td class="fw">{{ $device['name'] }}</td>
            <td style="width:18%">IP Address</td>
            <td style="width:24%" class="font-mono">{{ $device['ip'] ?: '-' }}</td>
        </tr>
        <tr>
            <td>Tipe Perangkat</td>
            <td>{{ $device['type'] ?: '-' }}</td>
            <td>Router / Lokasi</td>
            <td>{{ $device['location'] ?: '-' }}</td>
        </tr>
        <tr>
            <td>Metode Monitoring</td>
            <td>{{ $device['protocol'] ?: '-' }}</td>
            <td>Status Saat Ini</td>
            <td>
                @if($device['status'])
                    <span class="badge {{ $device['status'] === 'UP' ? 'b-rec' : ($device['status'] === 'DOWN' ? 'b-down' : 'b-unreach') }}">{{ $device['status'] }}</span>
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td>Interval Poll</td>
            <td>{{ $device['polls'] !== null ? $device['polls'].'x' : '-' }}</td>
            <td>Pengecekan Terakhir</td>
            <td>{{ $metrics['last_seen'] ?: '-' }}</td>
        </tr>
        <tr>
            <td>Deskripsi</td>
            <td colspan="3">{{ $device['deskripsi'] ?: '-' }}</td>
        </tr>
    </table>

    <h2>Ringkasan {{ ucfirst($period['type']) }}</h2>
    <div class="range">
        Periode: {{ $period['label'] }}
        &middot; {{ $period['start']->format('d M Y H:i') }} &mdash; {{ $period['end']->format('d M Y H:i') }}
    </div>

    <table class="summary">
        <tr>
            <td>
                {{ $metrics['availability'] !== null ? number_format($metrics['availability'], 2).'%' : '-' }}
                <span class="label">Availability</span>
            </td>
            <td>
                {{ $metrics['avg_response'] !== null ? number_format($metrics['avg_response'], 0).' ms' : '-' }}
                <span class="label">Avg Response</span>
            </td>
            <td>
                {{ number_format($metrics['incidents']) }}
                <span class="label">Incident</span>
            </td>
            <td>
                {{ $metrics['downtime_text'] ?: '-' }}
                <span class="label">Total Downtime</span>
            </td>
            <td>
                {{ $metrics['alerts'] !== null ? number_format($metrics['alerts']) : '-' }}
                <span class="label">Alert</span>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width:26%">Total Pengejakan</td>
            <td class="num" style="width:24%">{{ number_format($metrics['checks']) }}</td>
            <td style="width:26%">UP / Responded</td>
            <td class="num up" style="width:24%">{{ number_format($metrics['up']) }}</td>
        </tr>
        <tr>
            <td>DOWN / Missed</td>
            <td class="num down">{{ number_format($metrics['down']) }}</td>
            <td>Status Lainnya</td>
            <td class="num">{{ number_format($metrics['other']) }}</td>
        </tr>
        <tr>
            <td>Response Min / Avg / Max</td>
            <td class="num">
                {{ $metrics['min_response'] !== null ? number_format($metrics['min_response'], 0) : '-' }} /
                {{ $metrics['avg_response'] !== null ? number_format($metrics['avg_response'], 0) : '-' }} /
                {{ $metrics['max_response'] !== null ? number_format($metrics['max_response'], 0) : '-' }} ms
            </td>
            <td>Recovery / Ongoing</td>
            <td class="num">{{ number_format($metrics['recovered']) }} / {{ number_format($metrics['ongoing']) }}</td>
        </tr>
    </table>

    <h2>Incident Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="num">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>DOWN (perangkat mati / tidak responsif)</td><td class="num down">{{ number_format($metrics['incident_down']) }}</td></tr>
            <tr><td>UNREACHABLE (timeout / warning)</td><td class="num warn">{{ number_format($metrics['incident_unreach']) }}</td></tr>
            <tr><td>RECOVERED (kembali normal)</td><td class="num up">{{ number_format($metrics['recovered']) }}</td></tr>
            <tr><td>ONGOING (masih berlangsung)</td><td class="num">{{ number_format($metrics['ongoing']) }}</td></tr>
        </tbody>
    </table>

    <h2>Grafik Network Availability ({{ $period['granularity'] === 'hour' ? 'per jam' : 'per hari' }})</h2>
    @php
        $availRows = [];
        $availMax  = 0;

        foreach (($availability['labels'] ?? []) as $i => $label) {
            $value = ($availability['availability'][$i] ?? null) !== null
                ? (float) $availability['availability'][$i]
                : null;

            $availRows[] = ['label' => $label, 'value' => $value];

            if ($value !== null) {
                $availMax = max($availMax, $value);
            }
        }

        $availTone = function ($value) {
            return $value === null
                ? ''
                : ($value >= 99 ? 'b-green' : ($value >= 90 ? 'b-amber' : 'b-red'));
        };
    @endphp
    @include('admin.partials.pdf-bar-chart', [
        'rows'     => $availRows,
        'max'      => $availMax > 0 ? $availMax : null,
        'suffix'   => '%',
        'decimals' => 2,
        'tone'     => $availTone,
    ])
    <div class="legend">
        <span><i class="swatch b-green"></i>&ge; 99%</span>
        <span><i class="swatch b-amber"></i>90% &ndash; 98,99%</span>
        <span><i class="swatch b-red"></i>&lt; 90%</span>
    </div>

    <h2>Grafik Response Time (ms)</h2>
    @php
        $respRows = [];
        $respMax  = 0;

        foreach (($response['labels'] ?? []) as $i => $label) {
            $value = ($response['avg'][$i] ?? null) !== null ? (float) $response['avg'][$i] : null;
            $min   = ($response['min'][$i] ?? null) !== null ? (int) $response['min'][$i] : null;
            $max   = ($response['max'][$i] ?? null) !== null ? (int) $response['max'][$i] : null;

            $note = ($min !== null || $max !== null)
                ? 'min '.($min !== null ? $min : '-').' / max '.($max !== null ? $max : '-')
                : '';

            $respRows[] = ['label' => $label, 'value' => $value, 'note' => $note];

            if ($value !== null) {
                $respMax = max($respMax, $value);
            }
        }

        $respTone = function ($value) {
            return $value === null
                ? ''
                : ($value <= 50 ? 'b-green' : ($value <= 150 ? 'b-amber' : 'b-red'));
        };
    @endphp
    @include('admin.partials.pdf-bar-chart', [
        'rows'     => $respRows,
        'max'      => $respMax > 0 ? $respMax : null,
        'suffix'   => '',
        'decimals' => 0,
        'tone'     => $respTone,
    ])
    <div class="legend">
        <span><i class="swatch b-green"></i>&le; 50 ms</span>
        <span><i class="swatch b-amber"></i>51&ndash;150 ms</span>
        <span><i class="swatch b-red"></i>&gt; 150 ms</span>
    </div>

    <div class="page-break"></div>

    <h2>Riwayat Perubahan Status</h2>
    <p class="muted">
        @if(count($history))
            Menampilkan {{ number_format(count($history)) }} kejadian pada periode ini.
        @else
            Tidak ada perubahan status pada periode ini.
        @endif
    </p>
    @if(count($history))
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Durasi</th>
                <th class="num">Response</th>
            </tr>
        </thead>
        <tbody>
            @foreach($history as $episode)
            <tr>
                <td>
                    @if($episode['ongoing'])
                        <span class="badge b-ongoing">{{ strtoupper($episode['status']) }} &middot; BERLANGSUNG</span>
                    @else
                        <span class="badge b-rec">RECOVERED</span>
                        <span class="muted">{{ strtoupper($episode['status']) }}</span>
                    @endif
                </td>
                <td>{{ $episode['start'] }}</td>
                <td>{{ $episode['end'] ?: '-' }}</td>
                <td>{{ \App\Helpers\LaporanFormat::duration((int) $episode['duration_seconds']) }}</td>
                <td class="num">
                    {{ $episode['response_time'] !== null ? number_format((int) $episode['response_time'], 0).' ms' : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        Laporan &amp; Grafik per perangkat &mdash; {{ $device['name'] }} &middot; RADNEXT NOC
        &middot; Dihasilkan {{ $generatedAt }}
    </div>

</body>
</html>
