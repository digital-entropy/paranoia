<?php

declare(strict_types=1);

namespace Addeeandra\Paranoia\Middlewares;

use Addeeandra\Paranoia\Events\UserAgentChangeDuringSessionViolationDetected;
use Addeeandra\Paranoia\Paranoia;
use Illuminate\Http\Request;

class UserAgentChangeRestrictionMiddleware
{
    public function __construct(protected Paranoia $paranoia) {}

    public function handle(Request $request, \Closure $next): mixed
    {
        if ($this->paranoia->isCompatibleForUserAgentRestriction()) {
            $requestAgent = $request->userAgent();
            $sessionAgent = $this->paranoia->getSessionUserAgent();

            if ($requestAgent !== $sessionAgent) {
                event(UserAgentChangeDuringSessionViolationDetected::class, $request->getUser());
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
