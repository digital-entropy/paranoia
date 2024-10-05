<?php

declare(strict_types=1);

namespace Tests\Units;

use Addeeandra\Paranoia\Events\GeoDeviationDetected;
use Addeeandra\Paranoia\Middlewares\Session\GeoDeviationMiddleware;
use Addeeandra\Paranoia\Services\IpInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpKernel\Exception\HttpException;

covers(GeoDeviationMiddleware::class);

test('allow single country and other country\'s IP should rejected (array)', function (string $allowed, string $clientIp, string $ipCountry): void {
    $this->app['config']->set('paranoia.geo.allowed', [$allowed]);

    mock(IpInfo::class)
        ->shouldReceive('getCountry')
        ->andReturn($ipCountry);

    $request = Request::create(
        uri: '/',
        server: ['REMOTE_ADDR' => $clientIp],
    );

    try {
        Event::fake(GeoDeviationDetected::class);
        (new GeoDeviationMiddleware)->handle($request, function (Request $request): void {
            //
        });
    } catch (\Throwable $exception) {
        Event::assertDispatched(GeoDeviationDetected::class);
        throw $exception;
    }
})->with([
    ['ID', '126.73.107.96', 'JP'], // JP
    ['ID', '81.155.62.104', 'GB'], // GB
    ['ID', '50.50.127.136', 'US'], // US
])->throws(HttpException::class);

test('allow single country and other country\'s IP should rejected (string)', function (string $allowed, string $clientIp, string $ipCountry): void {
    $this->app['config']->set('paranoia.geo.allowed', $allowed);

    mock(IpInfo::class)
        ->shouldReceive('getCountry')
        ->andReturn($ipCountry);

    $request = Request::create(
        uri: '/',
        server: ['REMOTE_ADDR' => $clientIp],
    );

    try {
        Event::fake(GeoDeviationDetected::class);
        (new GeoDeviationMiddleware)->handle($request, function (): void {
            // do nothing
        });
    } catch (\Throwable $e) {
        Event::assertDispatched(GeoDeviationDetected::class);
        throw $e;
    }
})->with([
    ['ID', '126.73.107.96', 'JP'], // JP
    ['ID', '81.155.62.104', 'GB'], // GB
    ['ID', '50.50.127.136', 'US'], // US
])->throws(HttpException::class);

test('allow single country and that country\'s IP should accepted', function (string $allowed, string $clientIp, string $ipCountry): void {
    $this->app['config']->set('paranoia.geo.allowed', $allowed);

    mock(IpInfo::class)
        ->shouldReceive('getCountry')
        ->andReturn($ipCountry);

    $request = Request::create(
        uri: '/',
        server: ['REMOTE_ADDR' => $clientIp],
    );

    Event::fake(GeoDeviationDetected::class);
    (new GeoDeviationMiddleware)->handle($request, function (): void {
        // do nothing
    });
    Event::assertNotDispatched(GeoDeviationDetected::class);
})->with([
    ['JP', '126.73.107.96', 'JP'], // JP
    ['GB', '81.155.62.104', 'GB'], // GB
    ['US', '50.50.127.136', 'US'], // US
]);

test('allow all countries and tested country\'s IP should accepted', function (string $clientIp, string $ipCountry): void {
    $this->app['config']->set('paranoia.geo.allowed', '*');

    mock(IpInfo::class)
        ->shouldReceive('getCountry')
        ->andReturn($ipCountry);

    $request = Request::create(
        uri: '/',
        server: ['REMOTE_ADDR' => $clientIp],
    );

    Event::fake(GeoDeviationDetected::class);
    (new GeoDeviationMiddleware)->handle($request, function (): void {
        // do nothing
    });
    Event::assertNotDispatched(GeoDeviationDetected::class);
})->with([
    ['126.73.107.96', 'JP'], // JP
    ['81.155.62.104', 'GB'], // GB
    ['50.50.127.136', 'US'], // US
]);
