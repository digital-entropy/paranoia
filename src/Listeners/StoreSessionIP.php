<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Listeners;

use Dentro\Paranoia\Paranoia;
use Illuminate\Auth\Events\Login;

class StoreSessionIP
{
    public function handle(Login $event): void
    {
        /** @var Paranoia $driver */
        $driver = app('paranoia');
        if ($driver->eligibleForIPRestriction()) {
            $driver->saveSessionIpAddress();
        }
    }
}
