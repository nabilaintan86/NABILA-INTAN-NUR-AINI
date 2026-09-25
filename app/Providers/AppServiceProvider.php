<?php

namespace App\Providers;

use App\Helpers\AdminPageTitle;
use App\Models\Note;
use App\Policies\NotePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Note::class, NotePolicy::class);

        View::composer('layouts.partials.header', function ($view): void {
            $view->with('pageTitle', AdminPageTitle::resolve(request()));
        });
    }
}
