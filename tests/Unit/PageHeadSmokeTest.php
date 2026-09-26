<?php

namespace Tests\Unit;

use Tests\TestCase;

class PageHeadSmokeTest extends TestCase
{
    public function test_component_renders_title_subtitle_crumbs_and_actions(): void
    {
        $html = view('components.admin.page-head', [
            'title' => 'Kelola Router',
            'subtitle' => 'Kelola daftar router.',
            'icon' => 'wifi',
            'crumbs' => [
                ['label' => 'Kelola Router', 'url' => 'https://example.test/admin/kelola-router'],
                ['label' => 'Detail'],
            ],
            'actions' => '<button class="btn btn-primary">Tambah</button>',
        ])->render();

        $this->assertStringContainsString('Kelola Router', $html);
        $this->assertStringContainsString('Kelola daftar router.', $html);
        $this->assertStringContainsString('ni-wifi', $html);
        $this->assertStringContainsString('breadcrumb-item active', $html);
        $this->assertStringContainsString('admin-page-head-actions', $html);
        $this->assertStringContainsString('Tambah', $html);
        $this->assertStringNotContainsString('>Home<', $html);
    }

    public function test_component_omits_actions_when_absent(): void
    {
        $html = view('components.admin.page-head', [
            'title' => 'Tools',
        ])->render();

        $this->assertStringNotContainsString('admin-page-head-actions', $html);
    }
}
