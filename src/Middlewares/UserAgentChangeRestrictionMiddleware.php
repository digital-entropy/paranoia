<?php

declare(strict_types=1);

namespace Addeeandra\Paranoia\Middlewares;

use Addeeandra\Paranoia\Events\UserAgentChangeDuringSessionViolationDetected;
use Addeeandra\Paranoia\Paranoia;
use Illuminate\Http\Request;

class UserAgentChangeRestrictionMiddleware
{
    public function handle(Request $request, \Closure $next): void
    {
        $paranoia = (new Paranoia);

        if ($paranoia->isCompatibleForUserAgentRestriction()) {
            $requestAgent = $request->userAgent();
            $sessionAgent = $paranoia->getSessionUserAgent();

            if ($requestAgent !== $sessionAgent) {
                event(UserAgentChangeDuringSessionViolationDetected::class, $request->getUser());
                session()->flush();

                $this->actionWhenViolation();
            }
        }

        $next($request);
    }

    public function actionWhenViolation(): void
    {
        abort(403);
    }
}
