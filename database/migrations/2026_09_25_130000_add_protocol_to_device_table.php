<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('devices', 'protocol')) {
            Schema::table('devices', function (Blueprint $table) {
                $table->string('protocol', 32)->default('ICMP')->after('type');
            });
        }

        Schema::table('devices', function (Blueprint $table) {
            $table->string('type')->default('Server')->change();
        });

        $rows = DB::table('devices')->select(['id', 'name', 'type', 'icon_type', 'protocol'])->get();

        foreach ($rows as $row) {
            $legacyType = strtoupper(trim((string) $row->type));
            $isLegacyProtocol = in_array($legacyType, $this->protocols(), true);

            $protocol = $isLegacyProtocol
                ? $legacyType
                : strtoupper(trim((string) $row->protocol ?: 'ICMP'));

            if (! in_array($protocol, $this->protocols(), true)) {
                $protocol = 'ICMP';
            }

            $type = $isLegacyProtocol
                ? $this->typeFromLegacyDevice($row->name, $row->icon_type)
                : $this->normalizeType($row->type);

            DB::table('devices')
                ->where('id', $row->id)
                ->update([
                    'type' => $type,
                    'protocol' => $protocol,
                ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('devices', 'protocol')) {
            Schema::table('devices', function (Blueprint $table) {
                $table->dropColumn('protocol');
            });
        }
    }

    private function protocols(): array
    {
        return ['ICMP', 'HTTP', 'SNMP', 'TCP', 'SSH'];
    }

    private function normalizeType(?string $value): string
    {
        $value = strtolower(str_replace(['_', '-'], ' ', trim((string) $value)));

        return match ($value) {
            'router', 'rtr', 'core router', 'edge router' => 'Router',
            'switch', 'sw', 'switching' => 'Switch',
            'olt' => 'OLT',
            'epon' => 'EPON',
            'server', 'server room', 'peti' => 'Server',
            'access point', 'accesspoint', 'ap', 'wifi', 'wi fi' => 'Access Point',
            'pc', 'komputer', 'client' => 'Other',
            default => 'Other',
        };
    }

    private function typeFromLegacyDevice(?string $name, ?string $iconType): string
    {
        $icon = strtolower((string) $iconType);
        $name = strtolower((string) $name);
        $haystack = $name.' '.$icon;

        if (str_contains($haystack, 'olt')) {
            return 'OLT';
        }

        if (str_contains($haystack, 'epon')) {
            return 'EPON';
        }

        if (str_contains($haystack, 'access point') || str_contains($haystack, 'accesspoint') || str_contains($haystack, 'wifi') || str_contains($icon, 'ap')) {
            return 'Access Point';
        }

        if (str_contains($haystack, 'switch') || str_contains($icon, 'switch')) {
            return 'Switch';
        }

        if (str_contains($haystack, 'router') || str_contains($icon, 'router') || str_contains($name, 'rtr') || str_contains($name, 'core') || str_contains($name, 'edge') || str_contains($name, 'pop')) {
            return 'Router';
        }

        if (str_contains($haystack, 'server') || str_contains($icon, 'server')) {
            return 'Server';
        }

        return 'Other';
    }
};
