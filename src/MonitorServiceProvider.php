<?php

namespace Kodeas\Monitor;

use Illuminate\Support\ServiceProvider;

class MonitorServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . "/../config/monitor.php" => config_path('monitor.php')
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/monitor.php', 'monitor'
        );
    }
}