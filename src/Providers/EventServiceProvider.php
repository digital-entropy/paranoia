<?php

namespace Addeeandra\Paranoia\Providers;

use Addeeandra\Paranoia\Events\GeoDeviationDetected;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<string, array<string>>
     */
    protected array $listen = [
        GeoDeviationDetected::class => [
            //
        ],
    ];
}
