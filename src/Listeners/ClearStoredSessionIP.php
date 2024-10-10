<?php

namespace Dentro\Paranoia\Listeners;

use Dentro\Paranoia\Cookie\CookieLayer;
use Dentro\Paranoia\Paranoia;
use Illuminate\Auth\Events\Logout;

class ClearStoredSessionIP
{
    public function handle(Logout $event): void
    {
        /** @var Paranoia $driver */
        $driver = app('paranoia');

        if ($driver->useCookieForIP()) {
            $driver->cookieLayer(CookieLayer::IP)->clearCookie();
        }
    }
}
