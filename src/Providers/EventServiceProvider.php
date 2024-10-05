<?php

namespace Addeeandra\Paranoia\Providers;

use Addeeandra\Paranoia\Events\GeoRestrictionViolationDetected;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<string, array<string>>
     */
    protected array $listen = [
        GeoRestrictionViolationDetected::class => [
            //
        ],
    ];
}
