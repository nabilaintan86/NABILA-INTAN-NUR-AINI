<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeviceNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_create_note_for_device(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $device = Device::create([
            'name' => 'Routeruji',
            'type' => 'Router',
            'protocol' => 'ICMP',
            'ip_address' => '10.0.0.10',
        ]);

        $response = $this->actingAs($staff)->post(route('admin.device.notes.store', $device), [
            'tanggal' => '2026-09-25T18:30',
            'priority' => 'Urgent',
            'catatan' => 'Kabel uplink perlu diperiksa.',
        ]);

        $response->assertRedirect(route('admin.device.detail', $device));
        $this->assertDatabaseHas('device_notes', [
            'device_id' => $device->id,
            'user_id' => $staff->id,
            'tanggal' => '2026-09-25 18:30:00',
            'priority' => 'Urgent',
            'catatan' => 'Kabel uplink perlu diperiksa.',
        ]);

        $this->actingAs($staff)
            ->get(route('admin.device.detail', $device))
            ->assertOk()
            ->assertSee('Kabel uplink perlu diperiksa.')
            ->assertSee('25 Sep 2026, 18:30')
            ->assertSee('Dicatat oleh')
            ->assertSee($staff->name)
            ->assertSee('Riwayat Catatan');
    }

    public function test_staff_can_only_update_and_delete_own_note(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $otherStaff = User::factory()->create(['role' => 'staff']);
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $device = Device::create([
            'name' => 'Switchuji',
            'type' => 'Switch',
            'protocol' => 'SNMP',
            'ip_address' => '10.0.0.11',
        ]);
        $otherNote = Note::create([
            'device_id' => $device->id,
            'user_id' => $otherStaff->id,
            'tanggal' => now()->toDateString(),
            'catatan' => 'Catatan milik staff lain.',
            'priority' => 'Normal',
        ]);
        $ownNote = Note::create([
            'device_id' => $device->id,
            'user_id' => $staff->id,
            'tanggal' => now()->toDateString(),
            'catatan' => 'Catatan milik staff.',
            'priority' => 'Normal',
        ]);

        $this->actingAs($staff)
            ->put(route('admin.device.notes.update', $otherNote), [
                'tanggal' => now()->toDateString(),
                'priority' => 'Urgent',
                'catatan' => 'Tidak boleh diubah.',
            ])
            ->assertForbidden();

        $this->actingAs($staff)
            ->delete(route('admin.device.notes.destroy', $otherNote))
            ->assertForbidden();

        $this->actingAs($staff)
            ->put(route('admin.device.notes.update', $ownNote), [
                'tanggal' => now()->toDateString(),
                'priority' => 'Urgent',
                'catatan' => 'Sudah diperbarui staff.',
            ])
            ->assertRedirect();

        $this->actingAs($staff)
            ->delete(route('admin.device.notes.destroy', $ownNote))
            ->assertRedirect(route('admin.device.detail', $device));

        $this->assertDatabaseMissing('device_notes', ['id' => $ownNote->id]);

        $this->actingAs($superAdmin)
            ->put(route('admin.device.notes.update', $otherNote), [
                'tanggal' => now()->toDateString(),
                'priority' => 'Urgent',
                'catatan' => 'Sudah diperbarui admin.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('device_notes', [
            'id' => $otherNote->id,
            'catatan' => 'Sudah diperbarui admin.',
        ]);

        $this->actingAs($superAdmin)
            ->delete(route('admin.device.notes.destroy', $otherNote))
            ->assertRedirect(route('admin.device.detail', $device));

        $this->assertDatabaseMissing('device_notes', ['id' => $otherNote->id]);
    }

    public function test_legacy_note_without_author_is_visible_but_only_super_admin_can_edit(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $device = Device::create([
            'name' => 'Legacy Device',
            'type' => 'Router',
            'protocol' => 'ICMP',
            'ip_address' => '10.0.0.16',
        ]);
        $note = Note::create([
            'device_id' => $device->id,
            'user_id' => null,
            'tanggal' => '2026-09-25 18:59:01',
            'catatan' => 'mm',
            'priority' => 'Normal',
        ]);

        $this->actingAs($staff)
            ->get(route('admin.device.detail', $device))
            ->assertOk()
            ->assertSee('User tidak ditemukan')
            ->assertSee('mm');

        $this->actingAs($staff)
            ->put(route('admin.device.notes.update', $note), [
                'tanggal' => '2026-09-25 19:00',
                'priority' => 'Normal',
                'catatan' => 'Tidak boleh diubah.',
            ])
            ->assertForbidden();

        $this->actingAs($superAdmin)
            ->put(route('admin.device.notes.update', $note), [
                'tanggal' => '2026-09-25 19:00',
                'priority' => 'Urgent',
                'catatan' => 'Diperbarui oleh admin.',
            ])
            ->assertRedirect();
    }

    public function test_staff_can_view_notes_created_by_other_staff(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $otherStaff = User::factory()->create([
            'name' => 'Budi',
            'role' => 'staff',
        ]);
        $device = Device::create([
            'name' => 'Shared Device',
            'type' => 'Router',
            'protocol' => 'ICMP',
            'ip_address' => '10.0.0.15',
        ]);

        Note::create([
            'device_id' => $device->id,
            'user_id' => $otherStaff->id,
            'tanggal' => '2026-09-25 15:10:00',
            'catatan' => 'Dilakukan pengecekan koneksi.',
            'priority' => 'Normal',
        ]);

        $this->actingAs($staff)
            ->get(route('admin.device.detail', $device))
            ->assertOk()
            ->assertSee('Dilakukan pengecekan koneksi.')
            ->assertSee('Budi')
            ->assertSee('25 Sep 2026, 15:10');

        $this->actingAs($staff)
            ->get(route('admin.device.notes.report'))
            ->assertOk()
            ->assertSee('Dilakukan pengecekan koneksi.');
    }

    public function test_report_create_uses_authenticated_user_and_only_exposes_own_actions_to_staff(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $otherStaff = User::factory()->create(['role' => 'staff']);
        $device = Device::create([
            'name' => 'Report Device',
            'type' => 'Router',
            'protocol' => 'ICMP',
            'ip_address' => '10.0.0.17',
        ]);
        $ownNote = Note::create([
            'device_id' => $device->id,
            'user_id' => $staff->id,
            'tanggal' => '2026-09-25 15:00:00',
            'catatan' => 'Catatan milik staff.',
            'priority' => 'Normal',
        ]);
        $otherNote = Note::create([
            'device_id' => $device->id,
            'user_id' => $otherStaff->id,
            'tanggal' => '2026-09-25 16:00:00',
            'catatan' => 'Catatan milik staff lain.',
            'priority' => 'Normal',
        ]);

        $this->actingAs($staff)
            ->get(route('admin.device.notes.report'))
            ->assertOk()
            ->assertSee('Tambah Catatan')
            ->assertSee('ni-note', false)
            ->assertSee('reportNoteModal', false)
            ->assertSee('Catatan milik staff.')
            ->assertSee('Catatan milik staff lain.')
            ->assertSee(route('admin.device.notes.destroy', $ownNote), false)
            ->assertDontSee(route('admin.device.notes.destroy', $otherNote), false)
            ->assertSee('edit_note='.$ownNote->id, false)
            ->assertDontSee('edit_note='.$otherNote->id, false);

        $this->actingAs($staff)
            ->post(route('admin.device.notes.store-report'), [
                'device_id' => $device->id,
                'tanggal' => '2026-09-25T18:00',
                'priority' => 'Urgent',
                'catatan' => 'Catatan baru dari Report.',
                'user_id' => $otherStaff->id,
            ])
            ->assertRedirect(route('admin.device.notes.report'));

        $this->assertDatabaseHas('device_notes', [
            'device_id' => $device->id,
            'user_id' => $staff->id,
            'catatan' => 'Catatan baru dari Report.',
        ]);

        $note = Note::query()->latest('id')->firstOrFail();
        $this->assertNotNull($note->created_at);
        $this->assertNotNull($note->updated_at);
        $this->assertSame($staff->name, $note->user->name);

        $this->actingAs($staff)
            ->get(route('admin.device.notes.report'))
            ->assertOk()
            ->assertSee('Dicatat oleh')
            ->assertSee($staff->name)
            ->assertSee('Catatan baru dari Report.')
            ->assertSee('18:00');
    }

    public function test_super_admin_report_exposes_actions_for_all_notes(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $device = Device::create([
            'name' => 'Super Admin Report',
            'type' => 'Router',
            'protocol' => 'ICMP',
            'ip_address' => '10.0.0.18',
        ]);
        $firstNote = Note::create([
            'device_id' => $device->id,
            'user_id' => $staff->id,
            'tanggal' => '2026-09-25 15:00:00',
            'catatan' => 'Catatan pertama.',
            'priority' => 'Normal',
        ]);
        $secondNote = Note::create([
            'device_id' => $device->id,
            'user_id' => $staff->id,
            'tanggal' => '2026-09-25 16:00:00',
            'catatan' => 'Catatan kedua.',
            'priority' => 'Urgent',
        ]);

        $this->actingAs($superAdmin)
            ->get(route('admin.device.notes.report'))
            ->assertOk()
            ->assertSee('Tambah Catatan')
            ->assertSee(route('admin.device.notes.destroy', $firstNote), false)
            ->assertSee(route('admin.device.notes.destroy', $secondNote), false)
            ->assertSee('edit_note='.$firstNote->id, false)
            ->assertSee('edit_note='.$secondNote->id, false);
    }

    public function test_report_filters_notes_from_database(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $device = Device::create([
            'name' => 'OLT-01',
            'type' => 'OLT',
            'protocol' => 'ICMP',
            'ip_address' => '10.0.0.12',
        ]);
        Note::create([
            'device_id' => $device->id,
            'user_id' => $staff->id,
            'tanggal' => '2026-09-20',
            'catatan' => 'Fiber drop tidak stabil.',
            'priority' => 'Urgent',
        ]);
        Note::create([
            'device_id' => $device->id,
            'user_id' => $staff->id,
            'tanggal' => '2026-09-21',
            'catatan' => 'Routine check selesai.',
            'priority' => 'Normal',
        ]);

        $response = $this->actingAs($staff)->get(route('admin.device.notes.report', [
            'device_id' => $device->id,
            'priority' => 'Urgent',
        ]));

        $response->assertOk()
            ->assertSee('Fiber drop tidak stabil.')
            ->assertDontSee('Routine check selesai.');

        $this->actingAs($staff)
            ->get(route('admin.device.notes.report'))
            ->assertOk()
            ->assertSee('Fiber drop tidak stabil.')
            ->assertSee('Routine check selesai.');

        $this->actingAs($staff)
            ->get(route('admin.device.notes.report').'?'.http_build_query([
                'q' => ['invalid'],
                'device_id' => ['invalid'],
            ]))
            ->assertOk();
    }

    public function test_each_note_submit_creates_a_new_history_record(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $device = Device::create([
            'name' => 'Router History',
            'type' => 'Router',
            'protocol' => 'ICMP',
            'ip_address' => '10.0.0.14',
        ]);

        $this->actingAs($staff)
            ->post(route('admin.device.notes.store', $device), [
                'priority' => 'Normal',
                'catatan' => 'Pengecekan awal selesai.',
            ])
            ->assertRedirect(route('admin.device.detail', $device));

        $this->actingAs($staff)
            ->post(route('admin.device.notes.store', $device), [
                'priority' => 'Urgent',
                'catatan' => 'Perangkat tidak merespons ICMP sejak 18:19.',
            ])
            ->assertRedirect(route('admin.device.detail', $device));

        $this->assertDatabaseCount('device_notes', 2);
        $this->assertDatabaseHas('device_notes', [
            'user_id' => $staff->id,
            'catatan' => 'Pengecekan awal selesai.',
            'priority' => 'Normal',
        ]);
        $this->assertDatabaseHas('device_notes', [
            'user_id' => $staff->id,
            'catatan' => 'Perangkat tidak merespons ICMP sejak 18:19.',
            'priority' => 'Urgent',
        ]);
    }

    public function test_device_type_and_protocol_are_stored_separately(): void
    {
        $this->post(route('admin.kelola-router.store'), [
            'name' => 'OLT Baru',
            'type' => 'OLT',
            'protocol' => 'SNMP',
            'ip_address' => '10.0.0.13',
        ])->assertRedirect(route('admin.kelola-router'));

        $this->assertDatabaseHas('device', [
            'name' => 'OLT Baru',
            'type' => 'OLT',
            'protocol' => 'SNMP',
            'icon_type' => 'switch',
        ]);
    }
}
