<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah 2 kolom posisi di device_connections.
        Schema::table('device_connections', function (Blueprint $table) {
            $table->foreignId('from_position_id')
                ->nullable()
                ->after('from_device_id')
                ->constrained('device_positions')
                ->cascadeOnDelete();

            $table->foreignId('to_position_id')
                ->nullable()
                ->after('to_device_id')
                ->constrained('device_positions')
                ->cascadeOnDelete();
        });

        // 2. Backfill relasi lama: cari posisi device yang cocok.
        //    Prioritas: posisi di area yang sama dengan device.
        //    Kalau tidak ada, pakai posisi pertama device itu.
        $connections = DB::table('device_connections')->get();

        foreach ($connections as $conn) {
            $fromPos = $this->resolvePositionId($conn->from_device_id);
            $toPos   = $this->resolvePositionId($conn->to_device_id);

            DB::table('device_connections')
                ->where('id', $conn->id)
                ->update([
                    'from_position_id' => $fromPos,
                    'to_position_id'   => $toPos,
                ]);
        }
    }

    /**
     * Cari posisi device: kalau punya 1 posisi, pakai itu.
     * Kalau banyak, cari yang area-nya sama dengan network_area_id device.
     * Fallback: posisi pertama (id terkecil).
     */
    private function resolvePositionId(int $deviceId): ?int
    {
        $positions = DB::table('device_positions')
            ->where('device_id', $deviceId)
            ->orderBy('id')
            ->get();

        if ($positions->isEmpty()) {
            return null;
        }

        if ($positions->count() === 1) {
            return (int) $positions->first()->id;
        }

        // Banyak posisi → cari yang area-nya sama dengan device
        $device = DB::table('devices')->where('id', $deviceId)->first();
        if ($device && $device->network_area_id) {
            $match = $positions->firstWhere('network_area_id', $device->network_area_id);
            if ($match) {
                return (int) $match->id;
            }
        }

        return (int) $positions->first()->id;
    }

    public function down(): void
    {
        Schema::table('device_connections', function (Blueprint $table) {
            $table->dropForeign(['from_position_id']);
            $table->dropForeign(['to_position_id']);
            $table->dropColumn(['from_position_id', 'to_position_id']);
        });
    }
};