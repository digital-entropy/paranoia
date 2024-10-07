<?php

use Addeeandra\Paranoia\Events\IPChangeDuringSessionViolationDetected;
use Addeeandra\Paranoia\Middlewares\IPChangeRestrictionMiddleware;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpKernel\Exception\HttpException;

covers(IPChangeRestrictionMiddleware::class);

test('should throws exception', function (string $sessionIp, string $newIp): void {
    $request = \Illuminate\Http\Request::create(
        uri: '/',
        server: ['REMOTE_ADDR' => $newIp]
    );

    $mockedParanoia = mock(\Addeeandra\Paranoia\Paranoia::class);
    $mockedParanoia->shouldReceive('isCompatibleForIPRestriction')->andReturn(true);
    $mockedParanoia->shouldReceive('getSessionIpAddress')->andReturn($sessionIp);

    try {
        Event::fake(IPChangeDuringSessionViolationDetected::class);
        (new IPChangeRestrictionMiddleware($mockedParanoia))->handle(
            $request,
            function ($request): void {
                // do nothing
            }
        );
    } catch (\Throwable $e) {
        Event::assertDispatched(IPChangeDuringSessionViolationDetected::class);
        throw $e;
    }
})->with([
    ['192.168.0.1', '192.168.0.2'],
    ['192.168.0.3', '192.168.0.4'],
    ['192.168.0.6', '194.168.0.9'],
])->throws(HttpException::class);

test('should not throws exception', function (string $sessionIp, string $newIp): void {
    $request = \Illuminate\Http\Request::create(
        uri: '/',
        server: ['REMOTE_ADDR' => $newIp]
    );

    $mockedParanoia = mock(\Addeeandra\Paranoia\Paranoia::class);
    $mockedParanoia->shouldReceive('isCompatibleForIPRestriction')->andReturn(true);
    $mockedParanoia->shouldReceive('getSessionIpAddress')->andReturn($sessionIp);

    (new IPChangeRestrictionMiddleware($mockedParanoia))->handle(
        $request,
        function ($request): void {
            // do nothing
        }
    );
})->with([
    ['192.168.0.1', '192.168.0.1'],
    ['192.166.0.5', '192.166.0.5'],
    ['192.120.0.110', '192.120.0.110'],
])->throwsNoExceptions();
