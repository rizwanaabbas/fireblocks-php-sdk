<?php

namespace Fireblocks\Sdk;

use Illuminate\Support\ServiceProvider;
use Fireblocks\Sdk\Api\FireblocksClient;

class FireblocksServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/fireblocks.php',
            'fireblocks'
        );

        $this->app->singleton(FireblocksClient::class, function ($app) {
            $config = $app['config']['fireblocks'];
            return new FireblocksClient($config);
        });

        $this->app->singleton('fireblocks', function ($app) {
            return $app->make(FireblocksClient::class);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/fireblocks.php' => config_path('fireblocks.php'),
            ], 'fireblocks-config');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            FireblocksClient::class,
            'fireblocks',
        ];
    }
}
