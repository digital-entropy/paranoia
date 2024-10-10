<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Providers;

use Dentro\Paranoia\Listeners\ClearStoredSessionIP;
use Dentro\Paranoia\Listeners\ClearStoredSessionUserAgent;
use Dentro\Paranoia\Listeners\StoreSessionIP;
use Dentro\Paranoia\Listeners\StoreSessionUserAgent;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<string, array<string>>
     */
    protected array $listen = [
        Login::class => [
            StoreSessionIP::class,
            StoreSessionUserAgent::class,
        ],
        Logout::class => [
            ClearStoredSessionIP::class,
            ClearStoredSessionUserAgent::class,
        ],
    ];
}
