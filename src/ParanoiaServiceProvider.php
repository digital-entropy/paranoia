<?php

declare(strict_types=1);

namespace Addeeandra\Paranoia;

use Addeeandra\Paranoia\Providers\EventServiceProvider;
use Illuminate\Support\ServiceProvider;

class ParanoiaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    public function boot(): void {}
}
