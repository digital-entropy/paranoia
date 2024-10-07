<?php

declare(strict_types=1);

namespace Addeeandra\Paranoia\Middlewares;

use Addeeandra\Paranoia\Events\IPChangeDuringSessionViolationDetected;
use Addeeandra\Paranoia\Paranoia;
use Illuminate\Http\Request;

class IPChangeRestrictionMiddleware
{
    public function __construct(protected Paranoia $paranoia) {}

    public function handle(Request $request, \Closure $next): mixed
    {
        if ($this->paranoia->isCompatibleForIPRestriction()) {
            $requestIP = $request->ip();
            $sessionIP = $this->paranoia->getSessionIpAddress();

            if ($requestIP !== $sessionIP) {
                event(IPChangeDuringSessionViolationDetected::class, $request->getUser());
                session()->flush();

                $this->actionWhenViolation();
            }
        }

        return $next($request);
    }

    public function actionWhenViolation(): void
    {
        abort(403);
    }
}
