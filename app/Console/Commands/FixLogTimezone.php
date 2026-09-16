<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FixLogTimezone extends Command
{
    protected $signature = 'logs:fix-timezone
                            {--cutoff=2026-09-14 06:00:00 : Batas waktu (WIB) sebelum data lama dianggap UTC}
                            {--force : Jalankan lagi meskipun sudah pernah dijalankan}';

    protected $description = 'Konversi data log lama (tersimpan UTC) menjadi WIB dengan menambahkan +7 jam';

    protected const DONE_KEY = 'logs_timezone_fix_done';

    public function handle(): int
    {
        if (! $this->option('force') && Cache::has(self::DONE_KEY)) {
            $this->warn('Konversi timezone sudah pernah dijalankan. Gunakan --force jika ingin menjalankan ulang.');

            return self::SUCCESS;
        }

        $cutoffUtc = Carbon::createFromFormat('Y-m-d H:i:s', $this->option('cutoff'), 'Asia/Jakarta')
            ->utc()
            ->format('Y-m-d H:i:s');

        $tables = [
            'monitoring_logs' => ['checked_at', 'created_at'],
            'ping_history'    => ['polled_at', 'created_at'],
            'log_archives'    => ['checked_at', 'created_at'],
        ];

        $total = 0;

        foreach ($tables as $table => $columns) {
            $updated = $this->fixTable($table, $columns, $cutoffUtc);
            $this->line(sprintf('  %s : %d record di-update', $table, $updated));
            $total += $updated;
        }

        if ($total === 0) {
            $this->info('Tidak ada data lama yang perlu dikonversi.');
        } else {
            $this->info("Selesai. Total {$total} record dikonversi ke WIB.");
        }

        Cache::forever(self::DONE_KEY, now());

        return self::SUCCESS;
    }

    protected function fixTable(string $table, array $columns, string $cutoffUtc): int
    {
        $updated = 0;

        DB::table($table)
            ->where($columns[0], '<', $cutoffUtc)
            ->orderBy('id')
            ->chunkById(500, function ($rows) use ($table, $columns, &$updated): void {
                foreach ($rows as $row) {
                    $data = [];

                    foreach ($columns as $column) {
                        $value = $row->{$column} ?? null;

                        if ($value === null) {
                            continue;
                        }

                        $data[$column] = Carbon::parse($value)
                            ->addHours(7)
                            ->format('Y-m-d H:i:s');
                    }

                    if ($data !== []) {
                        DB::table($table)->where('id', $row->id)->update($data);
                        $updated++;
                    }
                }
            });

        return $updated;
    }
}