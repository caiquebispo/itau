<?php

namespace CaiqueBispo\Itau\Laravel;

use CaiqueBispo\Itau\Itau;
use CaiqueBispo\Itau\ItauConfig;
use CaiqueBispo\Itau\Webhook\WebhookHandler;
use Illuminate\Support\ServiceProvider;

class ItauServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/itau.php' => config_path('itau.php'),
        ], 'itau-config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/itau.php', 'itau');

        $this->app->singleton(ItauConfig::class, function ($app) {
            return new ItauConfig($app['config']->get('itau'));
        });

        $this->app->singleton(Itau::class, function ($app) {
            return new Itau($app->make(ItauConfig::class));
        });

        $this->app->alias(Itau::class, 'itau');

        $this->app->singleton(WebhookHandler::class, function ($app) {
            $secret = $app['config']->get('itau.webhook.secret');

            return new WebhookHandler($secret);
        });
    }
}
