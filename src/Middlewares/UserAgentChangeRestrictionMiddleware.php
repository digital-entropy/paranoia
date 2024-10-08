<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Middlewares;

use Dentro\Paranoia\Events\UserAgentChangeDuringSessionViolationDetected;
use Dentro\Paranoia\Paranoia;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;

class UserAgentChangeRestrictionMiddleware
{
    public function __construct(protected Paranoia $paranoia) {}

    public function handle(Request $request, \Closure $next): mixed
    {
        if (! $this->paranoia->shouldCheckUserAgentRestriction()) {
            return $next($request);
        }

        $requestAgent = $request->userAgent();
        $sessionAgent = $this->paranoia->getSessionUserAgent();

        if ($requestAgent !== $sessionAgent) {
            event(UserAgentChangeDuringSessionViolationDetected::class, auth()->guard()->user());

            if (auth()->guard() instanceof StatefulGuard) {
                auth()->guard()->logout();
            }

            $this->actionWhenViolation();
        }

        return $next($request);
    }

    public function actionWhenViolation(): void
    {
        abort(403);
    }
}
