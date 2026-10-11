<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('devices')) {
            Schema::create('devices', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('ip_address')->nullable();
                $table->string('hostname')->nullable();
                $table->string('type')->default('Other');
                $table->string('protocol', 32)->default('ICMP');
                $table->text('deskripsi')->nullable();
                $table->text('penyebab')->nullable();
                $table->string('gambar')->nullable();
                $table->string('status')->default('up');
                $table->string('icon_type')->nullable();
                $table->string('pop_name')->nullable();
                $table->integer('pos_x')->nullable();
                $table->integer('pos_y')->nullable();
                $table->boolean('map_positioned')->default(false);
                $table->unsignedBigInteger('polls')->default(0);
                $table->decimal('responded_percentage', 6, 2)->default(0);
                $table->decimal('missed_percentage', 6, 2)->default(0);
                $table->string('down_time')->nullable();
                $table->string('period')->nullable();
                $table->unsignedInteger('alerts_count')->default(0);
                $table->integer('avg_delay')->default(0);
                $table->integer('min_delay')->default(0);
                $table->integer('max_delay')->default(0);
                $table->string('uptime')->nullable();
                $table->string('vendor')->nullable();
                $table->string('location')->nullable();
                $table->unsignedInteger('downtime_count')->default(0);
                $table->timestamps();
            });

            return;
        }

        $columns = [
            'ip_address' => fn (Blueprint $table) => $table->string('ip_address')->nullable(),
            'hostname' => fn (Blueprint $table) => $table->string('hostname')->nullable(),
            'type' => fn (Blueprint $table) => $table->string('type')->default('Other'),
            'protocol' => fn (Blueprint $table) => $table->string('protocol', 32)->default('ICMP'),
            'deskripsi' => fn (Blueprint $table) => $table->text('deskripsi')->nullable(),
            'penyebab' => fn (Blueprint $table) => $table->text('penyebab')->nullable(),
            'gambar' => fn (Blueprint $table) => $table->string('gambar')->nullable(),
            'status' => fn (Blueprint $table) => $table->string('status')->default('up'),
            'icon_type' => fn (Blueprint $table) => $table->string('icon_type')->nullable(),
            'pop_name' => fn (Blueprint $table) => $table->string('pop_name')->nullable(),
            'pos_x' => fn (Blueprint $table) => $table->integer('pos_x')->nullable(),
            'pos_y' => fn (Blueprint $table) => $table->integer('pos_y')->nullable(),
            'map_positioned' => fn (Blueprint $table) => $table->boolean('map_positioned')->default(false),
            'polls' => fn (Blueprint $table) => $table->unsignedBigInteger('polls')->default(0),
            'responded_percentage' => fn (Blueprint $table) => $table->decimal('responded_percentage', 6, 2)->default(0),
            'missed_percentage' => fn (Blueprint $table) => $table->decimal('missed_percentage', 6, 2)->default(0),
            'down_time' => fn (Blueprint $table) => $table->string('down_time')->nullable(),
            'period' => fn (Blueprint $table) => $table->string('period')->nullable(),
            'alerts_count' => fn (Blueprint $table) => $table->unsignedInteger('alerts_count')->default(0),
            'avg_delay' => fn (Blueprint $table) => $table->integer('avg_delay')->default(0),
            'min_delay' => fn (Blueprint $table) => $table->integer('min_delay')->default(0),
            'max_delay' => fn (Blueprint $table) => $table->integer('max_delay')->default(0),
            'uptime' => fn (Blueprint $table) => $table->string('uptime')->nullable(),
            'vendor' => fn (Blueprint $table) => $table->string('vendor')->nullable(),
            'location' => fn (Blueprint $table) => $table->string('location')->nullable(),
            'downtime_count' => fn (Blueprint $table) => $table->unsignedInteger('downtime_count')->default(0),
        ];

        foreach ($columns as $name => $definition) {
            if (Schema::hasColumn('devices', $name)) {
                continue;
            }

            Schema::table('devices', function (Blueprint $table) use ($definition): void {
                $definition($table);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
