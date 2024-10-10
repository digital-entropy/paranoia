<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Listeners;

use Dentro\Paranoia\Paranoia;
use Illuminate\Auth\Events\Login;

class StoreSessionUserAgent
{
    public function handle(Login $event): void
    {
        /** @var Paranoia $driver */
        $driver = app('paranoia');
        if ($driver->eligibleForUserAgentRestriction()) {
            $driver->saveSessionUserAgent();
        }
    }
}
