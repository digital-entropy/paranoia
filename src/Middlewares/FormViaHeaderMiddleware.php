<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Middlewares;

use Illuminate\Http\Request;

class FormViaHeaderMiddleware
{
    protected string $headerName = 'X-Form';

    public function handle(Request $request, \Closure $next): mixed
    {
        if ($request->hasHeader($this->headerName)) {
            /** @var string $encodedForm */
            $encodedForm = $request->header($this->headerName);

            /** @var string $decodedHeader */
            $decodedHeader = base64_decode($encodedForm);

            /** @var array<string, string> $form */
            $form = json_decode($decodedHeader, true);

            $request->request->add($form);
            $request->headers->remove($this->headerName);
        }

        return $next($request);
    }
}
