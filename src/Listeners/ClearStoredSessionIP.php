<?php

namespace Dentro\Paranoia\Listeners;

use Dentro\Paranoia\Cookie\CookieLayer;
use Dentro\Paranoia\Paranoia;
use Illuminate\Auth\Events\Logout;

class ClearStoredSessionIP
{
    public function handle(Logout $event, Paranoia $driver): void
    {
        if ($driver->useCookieForIP()) {
            $driver->cookieLayer(CookieLayer::IP)->clearCookie();
        }
    }
}
