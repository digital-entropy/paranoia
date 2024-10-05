<?php

declare(strict_types=1);

namespace Addeeandra\Paranoia\Middlewares\Session;

use Illuminate\Http\Request;

class IPChangedMiddleware
{
    public function handle(Request $request, \Closure $next): void
    {
        $next($request);
    }
}
