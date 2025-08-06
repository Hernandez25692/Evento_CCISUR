<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('local')) {
            app(Browsershot::class)->setNodeBinary('C:\Program Files\nodejs\node.exe');
            app(Browsershot::class)->setNpmBinary('C:\Program Files\nodejs\npm.cmd');
        }
    }
}
