{{--
    Terminal log + kotak analisis, dipakai bersama oleh tab
    "Ping Server Radnet" dan "Ping Server Luar".

    Parameter:
      $key  'radnet' atau 'luar' — dipakai sebagai penanda supaya JS
           bisa mengisi log dan analisis yang benar.

    Keduanya sengaja dipisah menjadi partial karena isinya identik;
    menyalinnya berdua hanya akan membuat perubahan harus di dua tempat.
--}}

{{-- ── Terminal log ────────────────────────────────────────
     Terlihat seperti konsol: judul di kiri, keterangan sumber di
     kanan, lalu kotak log gelap monospace yang otomatis menggulir
     ke bawah setiap kali baris baru masuk.
     ──────────────────────────────────────────────────────── --}}
<div class="mt-4">
    <div class="mb-1.5 flex items-center justify-between gap-3">
        <span class="text-xs font-bold text-slate-300">Log Hasil Ping</span>
        <span class="font-mono text-[11px] text-slate-500">Real-time dari server NOC</span>
    </div>

    <div class="pm-term-scroll h-64 overflow-y-auto rounded-lg border border-slate-800 bg-[#0b1220] p-4 font-mono text-xs leading-relaxed text-slate-300"
         data-log="{{ $key }}"
         role="log"
         aria-live="polite"
         aria-label="Log hasil ping {{ $key }}">
        {{-- Diisi oleh JavaScript setelah pengujian berjalan. --}}
    </div>
</div>

{{-- ── Kotak analisis (UP / WARNING / DOWN) ─────────────────
     Dikosongkan lebih dulu supaya tidak ada kotak abu-abu yang membingungkan
     sebelum ada hasil sama sekali.
     ──────────────────────────────────────────────────────── --}}
<div class="mt-4 hidden rounded-xl border border-slate-800 bg-slate-950/60 p-4"
     data-analysis="{{ $key }}"
     aria-live="polite">
    {{-- Diisi oleh JavaScript: ikon, judul, dan penjelasan bahasa manusia. --}}
</div>
