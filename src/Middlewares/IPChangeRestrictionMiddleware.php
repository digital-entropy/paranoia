<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Middlewares;

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

        $requestIP = $request->ip();
        $sessionIP = $this->paranoia->getSessionIpAddress();

        if ($requestIP !== $sessionIP) {
            event(IPChangeDuringSessionViolationDetected::class, auth()->guard()->user());

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
