<?php

namespace Khalyomede\LaravelRouteFind;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Khalyomede\LaravelRouteFind\Commands\RouteFind;

final class ServiceProvider extends LaravelServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RouteFind::class,
            ]);
        }
    }
}
