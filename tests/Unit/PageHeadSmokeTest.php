<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class PageHeadSmokeTest extends TestCase
{
    public function test_component_renders_title_subtitle_and_actions(): void
    {
        $html = view('components.admin.page-head', [
            'title' => 'Kelola Router',
            'subtitle' => 'Kelola daftar router.',
            'actions' => '<button class="btn btn-primary">Tambah</button>',
        ])->render();

        $this->assertStringContainsString('Kelola Router', $html);
        $this->assertStringContainsString('Kelola daftar router.', $html);
        $this->assertStringContainsString('admin-page-head-actions', $html);
        $this->assertStringContainsString('Tambah', $html);
    }

    public function test_component_never_renders_a_breadcrumb(): void
    {
        // Breadcrumb dihapus: nama halaman aktif hanya tampil di header atas.
        $html = view('components.admin.page-head', [
            'title' => 'Log History',
            'subtitle' => 'Riwayat ping perangkat real-time dan arsip.',
        ])->render();

        $this->assertStringNotContainsString('breadcrumb', $html);
        $this->assertStringNotContainsString('admin-page-head-crumbs', $html);
    }

    public function test_component_ignores_a_legacy_crumbs_argument(): void
    {
        // View lama masih boleh mengirim :crumbs; komponen harus mengabaikannya
        // supaya tidak ada breadcrumb yang kembali muncul.
        $html = view('components.admin.page-head', [
            'title' => 'Network Tools',
            'crumbs' => [['label' => 'Network Tools']],
        ])->render();

        $this->assertStringNotContainsString('breadcrumb', $html);
    }

    public function test_component_omits_actions_when_absent(): void
    {
        $html = view('components.admin.page-head', [
            'title' => 'Tools',
        ])->render();

        $this->assertStringNotContainsString('admin-page-head-actions', $html);
    }

    public function test_action_slot_is_rendered_as_html_not_escaped(): void
    {
        $html = Blade::render(
            '<x-admin.page-head title="Log History">'
            .'<x-slot:actions><button class="btn btn-primary">Tambah</button></x-slot:actions>'
            .'</x-admin.page-head>'
        );

        $this->assertStringContainsString('<button class="btn btn-primary">Tambah</button>', $html);
        $this->assertStringNotContainsString('&lt;button', $html);
    }

    public function test_component_never_renders_an_icon(): void
    {
        // Ikon dihapus dari konten supaya teks intro mulai tepat di garis
        // kiri area konten, sejajar dengan header atas dan blok lain.
        $html = view('components.admin.page-head', [
            'title' => 'Kelola Router',
            'subtitle' => 'Kelola daftar router.',
        ])->render();

        $this->assertStringNotContainsString('admin-page-head-icon', $html);
        $this->assertStringNotContainsString('class="icon ni ni-', $html);
        $this->assertStringNotContainsString('<img', $html);
    }

    public function test_component_ignores_legacy_icon_arguments(): void
    {
        // View lama masih boleh mengirim icon/iconUrl; komponen harus
        // mengabaikannya supaya tidak ada ikon yang kembali muncul.
        $html = view('components.admin.page-head', [
            'title' => 'Network Tools',
            'icon' => 'activity',
            'iconUrl' => 'https://example.test/a.png',
            'iconClass' => 'is-telegram',
        ])->render();

        $this->assertStringNotContainsString('admin-page-head-icon', $html);
        $this->assertStringNotContainsString('<img', $html);
    }

    public function test_component_never_emits_a_malformed_class_attribute(): void
    {
        $html = view('components.admin.page-head', [
            'title' => 'Telegram Users',
            'boxed' => true,
        ])->render();

        $this->assertStringNotContainsString('class="class=', $html);
    }

    public function test_boxed_variant_keeps_admin_page_head_as_its_first_class(): void
    {
        $html = view('components.admin.page-head', [
            'title' => 'Dashboard', 'boxed' => true,
        ])->render();

        $this->assertStringContainsString('class="admin-page-head admin-page-head-boxed"', $html);
    }
}
