<?php

declare(strict_types=1);

namespace Tests\Units;

use Addeeandra\Paranoia\Events\GeoRestrictionViolationDetected;
use Addeeandra\Paranoia\Middlewares\GeoRestrictionMiddleware;
use Addeeandra\Paranoia\Services\IpInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpKernel\Exception\HttpException;

covers(GeoRestrictionMiddleware::class);

describe('should not throws exception', function () {

    test('allow single country and that country\'s IP should accepted', function (string $allowed, string $clientIp, string $ipCountry): void {
        $this->app['config']->set('paranoia.geo.allowed', $allowed);

        mock_ipInfo_getCountry($clientIp, $ipCountry);

        $request = Request::create(
            uri: '/',
            server: ['REMOTE_ADDR' => $clientIp],
        );

        Event::fake(GeoRestrictionViolationDetected::class);
        (new GeoRestrictionMiddleware)->handle($request, function (): void {
            // do nothing
        });
        Event::assertNotDispatched(GeoRestrictionViolationDetected::class);
    })->with([
        ['JP', '126.73.107.96', 'JP'], // JP
        ['GB', '81.155.62.104', 'GB'], // GB
        ['US', '50.50.127.136', 'US'], // US
    ]);

    test('allow all countries and tested country\'s IP should accepted', function (string $clientIp, string $ipCountry): void {
        $this->app['config']->set('paranoia.geo.allowed', '*');

        mock_ipInfo_getCountry($clientIp, $ipCountry);

        $request = Request::create(
            uri: '/',
            server: ['REMOTE_ADDR' => $clientIp],
        );

        Event::fake(GeoRestrictionViolationDetected::class);
        (new GeoRestrictionMiddleware)->handle($request, function (): void {
            // do nothing
        });
        Event::assertNotDispatched(GeoRestrictionViolationDetected::class);
    })->with([
        ['126.73.107.96', 'JP'], // JP
        ['81.155.62.104', 'GB'], // GB
        ['50.50.127.136', 'US'], // US
    ]);

});

describe('should throws exception', function () {

    test('allow single country and other country\'s IP should rejected (array)', function (string $allowed, string $clientIp, string $ipCountry): void {
        $this->app['config']->set('paranoia.geo.allowed', [$allowed]);

        mock_ipInfo_getCountry($clientIp, $ipCountry);

        $request = Request::create(
            uri: '/',
            server: ['REMOTE_ADDR' => $clientIp],
        );

        try {
            Event::fake(GeoRestrictionViolationDetected::class);
            (new GeoRestrictionMiddleware)->handle($request, function (Request $request): void {
                //
            });
        } catch (\Throwable $exception) {
            Event::assertDispatched(GeoRestrictionViolationDetected::class);
            throw $exception;
        }
    })->with([
        ['ID', '126.73.107.96', 'JP'], // JP
        ['ID', '81.155.62.104', 'GB'], // GB
        ['ID', '50.50.127.136', 'US'], // US
    ])->throws(HttpException::class);

    test('allow single country and other country\'s IP should rejected (string)', function (string $allowed, string $clientIp, string $ipCountry): void {
        $this->app['config']->set('paranoia.geo.allowed', $allowed);

        mock_ipInfo_getCountry($clientIp, $ipCountry);

        $request = Request::create(
            uri: '/',
            server: ['REMOTE_ADDR' => $clientIp],
        );

        try {
            Event::fake(GeoRestrictionViolationDetected::class);
            (new GeoRestrictionMiddleware)->handle($request, function (): void {
                // do nothing
            });
        } catch (\Throwable $e) {
            Event::assertDispatched(GeoRestrictionViolationDetected::class);
            throw $e;
        }
    })->with([
        ['ID', '126.73.107.96', 'JP'], // JP
        ['ID', '81.155.62.104', 'GB'], // GB
        ['ID', '50.50.127.136', 'US'], // US
    ])->throws(HttpException::class);

});
