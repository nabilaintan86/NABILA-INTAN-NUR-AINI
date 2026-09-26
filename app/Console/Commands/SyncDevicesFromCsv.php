<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Services\DeviceCsvService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Audit & sinkronisasi tabel `device` terhadap devices.csv (daftar resmi).
 *
 * Default: DRY-RUN. Tidak ada satupun baris yang ditulis/dihapus kecuali
 * perintah dijalankan ulang dengan --apply. Perintah ini tidak pernah membuat
 * device baru hanya untuk menyamakan jumlah, dan tidak pernah menaikkan jumlah
 * agar cocok — device yang tidak ada di devices.csv dilaporkan sebagai
 * "di luar daftar resmi" dan hanya dihapus bila diminta lewat --prune.
 */
class SyncDevicesFromCsv extends Command
{
    protected $signature = 'devices:sync
        {--apply              : Terapkan perubahan nama (data tidak dihapus)}
        {--prune              : Hapus device yang tidak ada di devices.csv (wajib bersama --apply)}
        {--skip-prune         : Alias eksplisit untuk tidak menghapus apa pun}';

    protected $description = 'Audit dan sinkronkan daftar perangkat dengan devices.csv (dry-run secara default)';

    public function handle(DeviceCsvService $source): int
    {
        if (! $source->exists()) {
            $this->error("devices.csv tidak ditemukan di: {$source->path()}");

            return self::FAILURE;
        }

        $report = $source->syncReport();
        $apply  = (bool) $this->option('apply');
        $prune  = (bool) $this->option('prune');

        if ($prune && ! $apply) {
            $this->error('--prune hanya boleh dipakai bersama --apply.');

            return self::FAILURE;
        }

        $this->renderReport($report, $apply, $prune);

        if (! $apply) {
            $this->newLine();
            $this->line('  <fg=gray>DRY-RUN — tidak ada data yang diubah.</>');
            $this->line('  Terapkan perbaikan nama:  <info>php artisan devices:sync --apply</>');
            $this->line('  Hapus device di luar CSV :  <info>php artisan devices:sync --apply --prune</>');

            return self::SUCCESS;
        }

        return $this->apply($source, $report, $prune);
    }

    protected function renderReport(array $r, bool $apply, bool $prune): void
    {
        $this->newLine();
        $this->line('  <fg=cyan>══ AUDIT SUMBER DATA PERANGKAT ══</>');
        $this->newLine();

        $rows = [
            ['devices.csv (total baris file)', $r['file_lines']],
            ['devices.csv (baris data, tanpa header)', $r['csv_rows']],
            ['devices.csv (IP unik)', $r['csv_unique_ips']],
            ['Tabel device (total baris)', $r['db_rows']],
            ['Tabel device (IP unik)', $r['db_unique_ips']],
            ['Total resmi dipakai seluruh halaman', $r['official_total']],
        ];

        $this->table(['Keterangan', 'Jumlah'], $rows);

        $this->section('Di devices.csv tapi TIDAK ada di database', $r['missing_in_db'], true);
        $this->section(
            'Di database tapi TIDAK ada di devices.csv (tidak ikut dihitung sebagai resmi)',
            $r['extra_in_db'],
            false,
            ['id', 'name', 'ip', 'type', 'created']
        );
        $this->section('Selisih nama (database -> devices.csv)', $r['name_drift'], false, ['id', 'ip', 'db', 'csv']);
        $this->section('Selisih protocol', $r['protocol_drift'], false, ['id', 'ip', 'db', 'csv']);

        if ($r['in_sync']) {
            $this->newLine();
            $this->line('  <fg=green>Database sudah selaras dengan devices.csv.</>');
        }
    }

    protected function section(string $title, array $items, bool $emptyIsError, array $columns = ['ip', 'name']): void
    {
        $this->newLine();

        if ($items === []) {
            $this->line("  <fg=green>✔</> {$title}: <fg=green>0</>");

            return;
        }

        $this->line("  <fg=yellow>▲</> {$title}: <fg=yellow>".count($items)."</>");

        foreach (array_slice($items, 0, 40) as $item) {
            $parts = [];

            foreach ($columns as $column) {
                $value = $item[$column] ?? null;

                if ($value !== null && $value !== '') {
                    $parts[] = $column.'='.$value;
                }
            }

            $this->line('      '.implode('  ', $parts));
        }

        if (count($items) > 40) {
            $this->line('      <fg=gray>... dan '.(count($items) - 40).' lainnya</>');
        }
    }

    protected function apply(DeviceCsvService $source, array $report, bool $prune): int
    {
        $renamed = 0;
        $deleted = 0;

        DB::transaction(function () use ($report, $prune, &$renamed, &$deleted): void {
            // 1. Nama perangkat mengikuti devices.csv (tidak pernah menambah baris).
            foreach ($report['name_drift'] as $row) {
                $affected = Device::where('id', $row['id'])->update(['name' => $row['csv']]);
                $renamed += $affected;
            }

            // 2. Buang device di luar daftar resmi — hanya bila diminta.
            if ($prune) {
                foreach ($report['extra_in_db'] as $row) {
                    $deleted += Device::where('id', $row['id'])->delete();
                }
            }
        });

        // instance baru supaya memo tidak memakai hasil lama
        $fresh = app(DeviceCsvService::class)->syncReport();

        $this->newLine();
        $this->line("  <fg=green>✔</> Nama diperbarui mengikuti devices.csv: <fg=green>{$renamed}</>");

        if ($prune) {
            $this->line("  <fg=green>✔</> Device di luar devices.csv dihapus: <fg=green>{$deleted}</>");
        } else {
            $this->line('  <fg=yellow>▲</> Device di luar devices.csv belum dihapus (pakai --prune bila sudah yakin).');
        }

        $this->newLine();
        $this->line('  Total resmi sekarang: <info>'.$fresh['official_total'].'</info> perangkat'
            .' <fg=gray>(dari '.$fresh['csv_rows'].' baris CSV, '.$fresh['csv_unique_ips'].' IP unik)</>');

        return self::SUCCESS;
    }
}
