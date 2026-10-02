<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('device_notes') || ! Schema::hasTable('devices')) {
            return;
        }

        $this->makeUserNullable();

        if (! Schema::hasColumn('devices', 'penyebab') || ! Schema::hasColumn('device_notes', 'tanggal')) {
            return;
        }

        $devices = DB::table('devices')->select(['id', 'penyebab', 'created_at', 'updated_at'])->get();

        foreach ($devices as $device) {
            $catatan = trim((string) $device->penyebab);

            if ($catatan === '') {
                continue;
            }

            $timestamp = $this->timestamp($device->updated_at, $device->created_at);

            $alreadyImported = DB::table('device_notes')
                ->where('device_id', $device->id)
                ->where('catatan', $catatan)
                ->exists();

            if ($alreadyImported) {
                continue;
            }

            DB::table('device_notes')->insert([
                'device_id' => $device->id,
                'user_id' => null,
                'tanggal' => $timestamp,
                'catatan' => $catatan,
                'priority' => 'Normal',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('device_notes')
            || ! Schema::hasColumn('device_notes', 'user_id')
            || DB::table('device_notes')->whereNull('user_id')->exists()) {
            return;
        }

        $foreign = $this->userForeignKey();

        if ($foreign) {
            Schema::table('device_notes', function (Blueprint $table) use ($foreign): void {
                $table->dropForeign($foreign);
            });
        }

        Schema::table('device_notes', function (Blueprint $table): void {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });

        if ($foreign) {
            Schema::table('device_notes', function (Blueprint $table): void {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    private function makeUserNullable(): void
    {
        $column = collect(Schema::getColumns('device_notes'))
            ->first(fn (array $column): bool => ($column['name'] ?? null) === 'user_id');

        if ($column && ($column['nullable'] ?? false)) {
            return;
        }

        $foreign = $this->userForeignKey();

        if ($foreign) {
            Schema::table('device_notes', function (Blueprint $table) use ($foreign): void {
                $table->dropForeign($foreign);
            });
        }

        Schema::table('device_notes', function (Blueprint $table): void {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        if ($foreign) {
            Schema::table('device_notes', function (Blueprint $table): void {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    private function userForeignKey(): ?string
    {
        foreach (Schema::getForeignKeys('device_notes') as $foreign) {
            if (in_array('user_id', $foreign['columns'] ?? [], true)) {
                return $foreign['name'] ?? null;
            }
        }

        return null;
    }

    private function timestamp(mixed ...$values): string
    {
        foreach ($values as $value) {
            if ($value === null || trim((string) $value) === '') {
                continue;
            }

            try {
                return Carbon::parse((string) $value)->format('Y-m-d H:i:s');
            } catch (Throwable) {
                continue;
            }
        }

        return Carbon::now()->format('Y-m-d H:i:s');
    }
};
