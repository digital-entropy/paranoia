<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Middlewares;

use Dentro\Paranoia\Cookie\CookieLayer;
use Dentro\Paranoia\Events\IPChangeDuringSessionViolationDetected;
use Dentro\Paranoia\Paranoia;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;

class IPChangeRestrictionMiddleware
{
    public function __construct(protected Paranoia $paranoia) {}

    public function handle(Request $request, \Closure $next): mixed
    {
        if (! $this->paranoia->eligibleForIPRestriction()) {
            return $next($request);
        }

        $this->validateSessionIp($request);

        $this->validateCookieIp($request);

        return $next($request);
    }

    public function actionWhenViolation(): void
    {
        abort(403);
    }

    protected function validateSessionIp(Request $request): void
    {
        $requestIP = $request->ip();
        $sessionIP = $this->paranoia->getSessionIpAddress();

        if ($requestIP === $sessionIP) {
            return;
        }

        event(IPChangeDuringSessionViolationDetected::class, auth()->guard()->user());

        $this->logout();

        $this->actionWhenViolation();
    }

    protected function validateCookieIp(Request $request): void
    {
        if (! $this->paranoia->useCookieForIP()) {
            return;
        }

        $verified = $this
            ->paranoia
            ->cookieLayer(CookieLayer::IP)
            ->verify(auth()->guard()->user()?->getAuthIdentifier());

        if ($verified) {
            return;
        }

        event(IPChangeDuringSessionViolationDetected::class, auth()->guard()->user());

        $this->logout();

        $this->actionWhenViolation();
    }

    protected function logout(): void
    {
        if (auth()->guard() instanceof StatefulGuard) {
            auth()->guard()->logout();
        }
    }
}
