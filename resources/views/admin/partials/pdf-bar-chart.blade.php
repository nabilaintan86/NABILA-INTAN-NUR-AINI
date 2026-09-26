{{--
    Grafik batang sederhana untuk PDF (DomPDF tidak merender canvas/SVG).
    Semua nilai berasal dari data nyata; bucket tanpa data tetap tampil dengan
    bar kosong + tanda "-", bukan angka dummy.

    Parameter:
        $rows  array<int, array{label:string, value:float|null, note?:string|null}>
        $max   float|null  nilai tertinggi sebagai dasar panjang bar
        $suffix string     satuan di sebelah nilai (mis. "%" / " ms")
        $decimals int      jumlah angka desimal
        $tone  callable|null  fn(float|null $value): string  → kelas warna
--}}
@php
    $rows = $rows ?? [];
    $max  = isset($max) && $max ? (float) $max : null;
    $suffix = $suffix ?? '';
    $decimals = $decimals ?? 2;
    $tone = $tone ?? null;
    $hasValue = false;

    foreach ($rows as $row) {
        if ($row['value'] !== null) {
            $hasValue = true;
            break;
        }
    }
@endphp

@if(! $hasValue)
    <p class="muted">Tidak ada data pada periode ini.</p>
@else
<table class="barchart">
    <tbody>
        @foreach($rows as $row)
            @php
                $value = $row['value'] === null ? null : (float) $row['value'];
                $ratio = ($value !== null && $max !== null && $max > 0)
                    ? max(0.5, min(100, ($value / $max) * 100))
                    : 0;
                $toneClass = $tone ? $tone($value) : 'b-indigo';
            @endphp
            <tr>
                <td class="bc-lbl">{{ $row['label'] }}</td>
                <td class="bc-track">
                    @if($value !== null)
                        <div class="bc-fill {{ $toneClass }}" style="width: {{ round($ratio, 2) }}%"></div>
                    @endif
                </td>
                <td class="bc-val">{{ $value !== null ? number_format($value, $decimals).$suffix : '-' }}</td>
                <td class="bc-note">{{ $row['note'] ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif
