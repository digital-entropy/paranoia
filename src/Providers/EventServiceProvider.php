<?php

namespace Addeeandra\Paranoia\Providers;

use Addeeandra\Paranoia\Events\GeoDeviationDetected;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        GeoDeviationDetected::class => [
            //
        ],
    ];
}
