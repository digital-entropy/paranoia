<?php

use Addeeandra\Paranoia\Events\UserAgentChangeDuringSessionViolationDetected;
use Addeeandra\Paranoia\Middlewares\UserAgentChangeRestrictionMiddleware;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpKernel\Exception\HttpException;

covers(UserAgentChangeRestrictionMiddleware::class);

test('should throws exception', function (string $sessionAgent, string $newAgent): void {
    $request = \Illuminate\Http\Request::create(
        uri: '/',
        server: ['HTTP_USER_AGENT' => $newAgent]
    );

    $mockedParanoia = mock(\Addeeandra\Paranoia\Paranoia::class);
    $mockedParanoia->shouldReceive('isCompatibleForUserAgentRestriction')->andReturn(true);
    $mockedParanoia->shouldReceive('getSessionUserAgent')->andReturn($sessionAgent);

    try {
        Event::fake(UserAgentChangeDuringSessionViolationDetected::class);
        (new UserAgentChangeRestrictionMiddleware($mockedParanoia))->handle(
            $request,
            function ($request): void {
                // do nothing
            }
        );
    } catch (\Throwable $e) {
        Event::assertDispatched(UserAgentChangeDuringSessionViolationDetected::class);
        throw $e;
    }
})->with([
    [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36',
    ],
    [
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
    ],
])->throws(HttpException::class);

test('should not throws exception', function (string $sessionAgent, string $newAgent): void {
    $request = \Illuminate\Http\Request::create(
        uri: '/',
        server: ['HTTP_USER_AGENT' => $newAgent]
    );

    $mockedParanoia = mock(\Addeeandra\Paranoia\Paranoia::class);
    $mockedParanoia->shouldReceive('isCompatibleForUserAgentRestriction')->andReturn(true);
    $mockedParanoia->shouldReceive('getSessionUserAgent')->andReturn($sessionAgent);

    (new UserAgentChangeRestrictionMiddleware($mockedParanoia))->handle(
        $request,
        function ($request): void {
            // do nothing
        }
    );
})->with([
    [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
    ],
    [
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36',
    ],
])->throwsNoExceptions();
