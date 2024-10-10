<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Middlewares;

use Dentro\Paranoia\Cookie\CookieLayer;
use Dentro\Paranoia\Events\UserAgentChangeDuringSessionViolationDetected;
use Dentro\Paranoia\Paranoia;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;

class UserAgentChangeRestrictionMiddleware
{
    public function __construct(protected Paranoia $paranoia) {}

    public function handle(Request $request, \Closure $next): mixed
    {
        if (! $this->paranoia->eligibleForUserAgentRestriction()) {
            return $next($request);
        }

        $this->validateSessionUserAgent($request);

        $this->validateCookieUserAgent($request);

        return $next($request);
    }

    public function actionWhenViolation(): void
    {
        abort(403);
    }

    public function validateSessionUserAgent(Request $request): void
    {
        $requestAgent = $request->userAgent();
        $sessionAgent = $this->paranoia->getSessionUserAgent();

        if ($requestAgent === $sessionAgent) {
            return;
        }

        event(UserAgentChangeDuringSessionViolationDetected::class, auth()->guard()->user());

        $this->logout();

        $this->actionWhenViolation();
    }

    public function validateCookieUserAgent(Request $request): void
    {
        if (! $this->paranoia->useCookieForUserAgent()) {
            return;
        }

        $verified = $this
            ->paranoia
            ->cookieLayer(CookieLayer::USER_AGENT)
            ->verify((string) auth()->guard()->user()?->getAuthIdentifier());

        if (! $verified) {
            event(UserAgentChangeDuringSessionViolationDetected::class, auth()->guard()->user());

            $this->logout();

            $this->actionWhenViolation();
        }
    }

    protected function logout(): void
    {
        if (auth()->guard() instanceof StatefulGuard) {
            auth()->guard()->logout();
        }
    }
}
