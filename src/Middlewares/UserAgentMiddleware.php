<?php

declare(strict_types=1);

namespace Addeeandra\Paranoia\Middlewares;

use Illuminate\Http\Request;

class UserAgentMiddleware
{
    public function handle(Request $request, \Closure $next): void
    {
        $next($request);
    }
}
