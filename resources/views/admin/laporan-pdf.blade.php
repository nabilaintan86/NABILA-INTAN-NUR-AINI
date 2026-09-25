<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Monitoring {{ $period['label'] }}</title>
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
        <h1>Laporan Monitoring Jaringan</h1>
        <div class="subtitle">RADNEXT NOC</div>
        <div class="meta">Dashboard Laporan Monitoring &middot; Dihasilkan {{ now()->format('d M Y H:i:s') }}</div>
    </div>

    <h2>Ringkasan {{ ucfirst($period['type']) }}</h2>
    <div class="range">
        Periode: {{ $period['label'] }}
        &middot; {{ $period['start']->format('d M Y H:i') }} &mdash; {{ $period['end']->format('d M Y H:i') }}
    </div>

    <table class="summary">
        <tr>
            <td>
                {{ $kpi['uptime'] !== null ? number_format($kpi['uptime'], 2).'%' : '-' }}
                <span class="label">Uptime</span>
            </td>
            <td>
                {{ number_format($kpi['down_checks']) }}
                <span class="label">Total DOWN (cek)</span>
            </td>
            <td>
                {{ number_format($kpi['affected']) }}
                <span class="label">Device Bermasalah</span>
            </td>
            <td>
                {{ $kpi['avg_response'] !== null ? number_format($kpi['avg_response'], 0).' ms' : '-' }}
                <span class="label">Avg Response Time</span>
            </td>
            <td>
                {{ number_format($kpi['incidents']) }}
                <span class="label">Total Incident</span>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td>Total Pengecekan</td>
            <td class="num">{{ number_format($kpi['checks']) }}</td>
            <td>Cek UP</td>
            <td class="num up">{{ number_format($kpi['up']) }}</td>
        </tr>
        <tr>
            <td>Cek DOWN</td>
            <td class="num down">{{ number_format($kpi['down_checks']) }}</td>
            <td>Episode DOWN</td>
            <td class="num down">{{ number_format($kpi['down']) }}</td>
        </tr>
        <tr>
            <td>Response Avg / Min / Max</td>
            <td class="num">
                {{ $kpi['avg_response'] !== null ? number_format($kpi['avg_response'], 0) : '-' }} /
                {{ $kpi['min_response'] !== null ? number_format($kpi['min_response'], 0) : '-' }} /
                {{ $kpi['max_response'] !== null ? number_format($kpi['max_response'], 0) : '-' }} ms
            </td>
            <td>Recovery / Ongoing</td>
            <td class="num">{{ number_format($summary['recovery']) }} / {{ number_format($summary['ongoing']) }}</td>
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
            <tr><td>DOWN (episode perangkat mati / tidak responsif)</td><td class="num down">{{ number_format($summary['down']) }}</td></tr>
            <tr><td>UNREACHABLE (timeout / warning)</td><td class="num warn">{{ number_format($summary['unreachable']) }}</td></tr>
            <tr><td>RECOVERED (perangkat kembali normal)</td><td class="num up">{{ number_format($summary['recovery']) }}</td></tr>
            <tr><td>ONGOING (masih berlangsung)</td><td class="num">{{ number_format($summary['ongoing']) }}</td></tr>
        </tbody>
    </table>

    <h2>Network Availability</h2>
    @if(count($availability['labels'] ?? []))
    <table>
        <thead>
            <tr>
                <th>Periode</th>
                <th class="num">Availability</th>
            </tr>
        </thead>
        <tbody>
            @foreach($availability['labels'] as $i => $label)
            <tr>
                <td>{{ $label }}</td>
                <td class="num {{ ($availability['availability'][$i] ?? null) !== null && (float) $availability['availability'][$i] < 90 ? 'down' : 'up' }}">
                    {{ ($availability['availability'][$i] ?? null) !== null ? number_format((float) $availability['availability'][$i], 2).'%' : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="muted">Tidak ada data.</p>
    @endif

    <h2>Response Time (ms)</h2>
    @if(count($response['labels'] ?? []))
    <table>
        <thead>
            <tr>
                <th>Periode</th>
                <th class="num">Avg</th>
                <th class="num">Max</th>
                <th class="num">Min</th>
            </tr>
        </thead>
        <tbody>
            @foreach($response['labels'] as $i => $label)
            <tr>
                <td>{{ $label }}</td>
                <td class="num">{{ ($response['avg'][$i] ?? null) !== null ? number_format((int) $response['avg'][$i], 0) : '-' }}</td>
                <td class="num">{{ ($response['max'][$i] ?? null) !== null ? number_format((int) $response['max'][$i], 0) : '-' }}</td>
                <td class="num">{{ ($response['min'][$i] ?? null) !== null ? number_format((int) $response['min'][$i], 0) : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="muted">Tidak ada data.</p>
    @endif

    <div class="page-break"></div>

    <h2>Perangkat Paling Bermasalah</h2>
    @if($topDevices->isNotEmpty())
    <table>
        <thead>
            <tr>
                <th class="center" style="width:6%;">Rank</th>
                <th>Perangkat</th>
                <th>IP Address</th>
                <th class="num">Cek</th>
                <th class="num">UP</th>
                <th class="num">DOWN</th>
                <th class="num">Incident</th>
                <th class="num">Uptime</th>
                <th class="num">Downtime</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topDevices as $i => $device)
            <tr>
                <td class="center">#{{ $i + 1 }}</td>
                <td>{{ $device['device_name'] ?: 'NaN' }}</td>
                <td class="muted">{{ $device['ip_address'] ?: '-' }}</td>
                <td class="num">{{ number_format($device['checks']) }}</td>
                <td class="num up">{{ number_format($device['up']) }}</td>
                <td class="num {{ ($device['down'] ?? 0) > 0 ? 'down' : 'up' }}">{{ number_format($device['down']) }}</td>
                <td class="num {{ ($device['incidents'] ?? 0) > 0 ? 'down' : 'up' }}">{{ number_format($device['incidents']) }}</td>
                <td class="num {{ (float) ($device['uptime'] ?? 0) < 90 ? 'down' : 'up' }}">{{ number_format((float) $device['uptime'], 2) }}%</td>
                <td class="num down">{{ \App\Helpers\LaporanFormat::duration((int) ($device['downtime'] ?? 0)) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="muted">Tidak ada perangkat bermasalah pada periode ini.</p>
    @endif

    <h2>Detail Incident</h2>
    <p class="muted">
        Menampilkan {{ number_format($history['items']->count()) }} dari {{ number_format($history['total']) }} kejadian
        (filter perangkat: {{ $history['device'] ?? 'semua' }}).
    </p>
    @if($history['items']->isNotEmpty())
    <table>
        <thead>
            <tr>
                <th>Device</th>
                <th>Status</th>
                <th>Mulai</th>
                <th>Durasi</th>
                <th class="num">Response</th>
            </tr>
        </thead>
        <tbody>
            @foreach($history['items'] as $episode)
            <tr>
                <td>{{ $episode['device_name'] ?: 'NaN' }}</td>
                <td>
                    @if(! empty($episode['ongoing']))
                        <span class="badge b-ongoing">{{ strtoupper($episode['status']) }} &middot; BERLANGSUNG</span>
                    @else
                        <span class="badge b-rec">RECOVERED</span>
                        <span class="muted">{{ strtoupper($episode['status']) }}</span>
                    @endif
                </td>
                <td>{{ $episode['start'] }}</td>
                <td>{{ \App\Helpers\LaporanFormat::duration((int) ($episode['duration_seconds'] ?? 0)) }}</td>
                <td class="num">
                    {{ $episode['response_time'] !== null ? number_format((int) $episode['response_time'], 0).' ms' : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="muted">Tidak ada incident pada periode ini.</p>
    @endif

    <div class="footer">
        Laporan Monitoring Jaringan RADNEXT NOC &middot; Dihasilkan secara otomatis {{ now()->format('d M Y H:i:s') }}
    </div>

</body>
</html>