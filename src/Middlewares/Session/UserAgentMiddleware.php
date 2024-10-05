<?php

declare(strict_types=1);

namespace Addeeandra\Paranoia\Middlewares\Session;

use Illuminate\Http\Request;

class UserAgentMiddleware
{
    public function handle(Request $request, \Closure $next): void
    {
        $next($request);
    }
}
