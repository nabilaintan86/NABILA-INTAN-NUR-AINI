<?php

namespace App\Console\Commands;

use App\Models\LogArchive;
use App\Models\MonitoringLog;
use Illuminate\Console\Command;

class ArchiveOldLogs extends Command
{
    protected $signature = 'logs:archive';

    protected $description = 'Pindahkan monitoring_logs yang berumur > 7 hari ke tabel log_archives';

    public function handle(): int
    {
        $cutoff = now()->subDays(7);
        $moved = 0;

        MonitoringLog::where('checked_at', '<', $cutoff)
            ->orderBy('id')
            ->chunkById(500, function ($logs) use (&$moved) {
                $rows = $logs->map(function (MonitoringLog $log) {
                    return [
                        'device_id'     => $log->device_id,
                        'device_name'   => $log->device_name,
                        'ip_address'    => $log->ip_address,
                        'status'        => $log->status,
                        'response_time' => $log->response_time,
                        'checked_at'    => $log->checked_at,
                        'created_at'    => $log->created_at,
                        'updated_at'    => $log->updated_at,
                    ];
                })->all();

                LogArchive::insert($rows);

                MonitoringLog::whereIn('id', $logs->pluck('id'))->delete();

                $moved += count($rows);
            });

        if ($moved === 0) {
            $this->info("Tidak ada log yang berumur lebih dari 7 hari.");

            return self::SUCCESS;
        }

        $this->info("{$moved} log lama dipindahkan ke log_archives.");

        return self::SUCCESS;
    }
}