<?php

namespace EveryWell\Imagination;

use Illuminate\Support\ServiceProvider;

class ImaginationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/imagination.php' => config_path('imagination.php')
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/imagination.php', 'imagination');
    }
}
