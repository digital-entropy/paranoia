<?php

namespace Dentro\Paranoia\Listeners;

use Dentro\Paranoia\Cookie\CookieLayer;
use Dentro\Paranoia\Paranoia;
use Illuminate\Auth\Events\Logout;

class ClearStoredSessionUserAgent
{
    public function handle(Logout $event, Paranoia $driver): void
    {
        if ($driver->useCookieForUserAgent()) {
            $driver->cookieLayer(CookieLayer::USER_AGENT)->clearCookie();
        }
    }
}
