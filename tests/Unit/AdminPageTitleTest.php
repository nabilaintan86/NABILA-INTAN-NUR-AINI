<?php

namespace Tests\Unit;

use App\Helpers\AdminPageTitle;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminPageTitleTest extends TestCase
{
    public function test_header_title_follows_current_route(): void
    {
        $titles = [
            '/admin' => 'Dashboard',
            '/admin/monitoring' => 'Monitoring Jaringan',
            '/admin/log' => 'Log History',
            '/admin/notifikasi' => 'Notifikasi',
            '/admin/kelola-router' => 'Kelola Router',
            '/admin/device-notes' => 'Report Catatan',
            '/admin/laporan' => 'Laporan & Grafik',
            '/admin/tools' => 'Network Tools',
            '/admin/troubleshooting' => 'Troubleshooting',
        ];

        foreach ($titles as $uri => $expected) {
            $this->assertSame($expected, AdminPageTitle::resolve($this->requestFor($uri)), $uri);
        }
    }

    public function test_sub_pages_inherit_the_title_of_their_section(): void
    {
        $titles = [
            '/admin/log/archive' => 'Log History',
            '/admin/kelola-user' => 'Kelola User',
            '/admin/kelola-user/create' => 'Tambah User',
            '/admin/kelola-user/3/edit' => 'Edit User',
            '/admin/telegram-users' => 'Telegram Bot',
            '/admin/device/5/detail' => 'Detail Device',
        ];

        foreach ($titles as $uri => $expected) {
            $this->assertSame($expected, AdminPageTitle::resolve($this->requestFor($uri)), $uri);
        }
    }

    public function test_unmapped_page_falls_back_to_its_url_segment(): void
    {
        $this->assertSame('User List', AdminPageTitle::resolve($this->requestFor('/admin/user-manage/user-list')));
    }

    public function test_shared_header_renders_the_resolved_title(): void
    {
        Schema::create('device_notifications', function (Blueprint $table): void {
            $table->id();
            $table->boolean('is_read')->default(false);
        });

        $this->app->instance('request', $this->requestFor('/admin/device-notes'));

        $html = view('layouts.partials.header')->with('route', null)->render();

        $this->assertStringContainsString('<h5 class="mb-0">Report Catatan</h5>', $html);
        $this->assertStringNotContainsString('Dashboard</h5>', $html);
    }

    private function requestFor(string $uri): Request
    {
        $request = Request::create($uri, 'GET');
        $matched = null;

        foreach (Route::getRoutes() as $route) {
            if ($route->matches($request)) {
                $matched = $route;
                break;
            }
        }

        $request->setRouteResolver(fn () => $matched instanceof RoutingRoute ? $matched : null);

        return $request;
    }
}
