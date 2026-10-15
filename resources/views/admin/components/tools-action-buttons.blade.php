{{--
    Empat tombol pemeriksaan (Ping / Trace / Lookup / Scan Port).

    Dipakai satu kali di Bagian bawah halaman Network Tools. Targetnya
    tunggal: alamat yang ada di kolom "IP target atau nama domain" — boleh
    diisi otomatis dari perangkat Radnet yang diklik di Bagian atas, atau
    ditulis sendiri untuk IP / domain bebas.

    Parameter:
      $guide  true (default) — tampilkan panduan arti tombol di bawahnya
--}}
<div class="tool-actions">
    <button type="button" class="tool-btn tool-btn--ping" data-action="ping">
        <em class="icon ni ni-signal"></em>
        <span>Ping</span>
    </button>

    <button type="button" class="tool-btn tool-btn--trace" data-action="trace">
        <em class="icon ni ni-network"></em>
        <span>Trace</span>
    </button>

    <button type="button" class="tool-btn tool-btn--lookup" data-action="lookup">
        <em class="icon ni ni-search"></em>
        <span>Lookup</span>
    </button>

    <button type="button" class="tool-btn tool-btn--scan" data-action="scan">
        <em class="icon ni ni-lock"></em>
        <span>Scan Port</span>
    </button>
</div>

@if($guide ?? true)
    <ul class="tool-guide">
        <li class="tool-guide-item">
            <span class="tool-guide-dot tool-guide-dot--ping"></span>
            <span class="tool-guide-text">
                <span class="tool-guide-name">Ping <em class="tool-guide-color tool-guide-color--ping">Hijau</em></span>
                Cek kehadiran &amp; kecepatan sinyal, untuk memastikan perangkatnya benar-benar menyala.
            </span>
        </li>
        <li class="tool-guide-item">
            <span class="tool-guide-dot tool-guide-dot--trace"></span>
            <span class="tool-guide-text">
                <span class="tool-guide-name">Trace <em class="tool-guide-color tool-guide-color--trace">Ungu</em></span>
                Lacak jalur perjalanan data, untuk melihat ada tidak router yang macet di tengah jalan.
            </span>
        </li>
        <li class="tool-guide-item">
            <span class="tool-guide-dot tool-guide-dot--lookup"></span>
            <span class="tool-guide-text">
                <span class="tool-guide-name">Lookup <em class="tool-guide-color tool-guide-color--lookup">Biru</em></span>
                Cari info nama/domain yang terhubung (pencarian DNS).
            </span>
        </li>
        <li class="tool-guide-item">
            <span class="tool-guide-dot tool-guide-dot--scan"></span>
            <span class="tool-guide-text">
                <span class="tool-guide-name">Scan Port <em class="tool-guide-color tool-guide-color--scan">Merah</em></span>
                Cek pintu masuk perangkat, untuk melihat port yang terbuka/tertutup demi keamanan.
            </span>
        </li>
    </ul>
@endif
