<?php

namespace App\Providers;

use App\Helpers\AdminPageTitle;
use App\Models\DeviceNotification;
use App\Models\Note;
use App\Policies\NotePolicy;
use App\Services\SnmpInspectionService;
use App\Services\SnmpService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SnmpService::class, function () {
            return new SnmpService(config('snmp', []));
        });

        $this->app->singleton(SnmpInspectionService::class, function ($app) {
            return new SnmpInspectionService($app->make(SnmpService::class));
        });
    }

    public function boot(): void
    {
        Gate::policy(Note::class, NotePolicy::class);

        View::composer('layouts.partials.header', function ($view): void {
            $view->with('pageTitle', AdminPageTitle::resolve(request()));
            $view->with('unreadCount', (new DeviceNotification)->unreadCount());
        });

        View::composer('layouts.partials.offcanvas', function ($view): void {
            $view->with('recentNotifications', (new DeviceNotification)->recentForPanel());
            $view->with('unreadCount', (new DeviceNotification)->unreadCount());
        });
    }
}
