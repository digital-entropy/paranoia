<?php

declare(strict_types=1);

namespace Dentro\Paranoia;

use Dentro\Paranoia\Providers\EventServiceProvider;
use Dentro\Paranoia\Storage\SessionStorageFactory;
use Illuminate\Support\ServiceProvider;

class ParanoiaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->bind(Paranoia::class, function (): \Dentro\Paranoia\Paranoia {

            /** @var string $sessionDriver */
            $sessionDriver = config('session.driver');
            $factory = (new SessionStorageFactory($sessionDriver));

            if ($sessionDriver === 'database') {
                /** @var string $tableName */
                $tableName = config('session.table');
                $factory->setSessionTableName($tableName);
            }

            return new Paranoia($factory->build());
        });
        $this->app->alias(Paranoia::class, 'paranoia');

        $this->mergeConfigFrom(__DIR__.'/../config/paranoia.php', 'paranoia');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/paranoia.php' => config_path('paranoia.php'),
            ], 'config');
        }
    }
}
