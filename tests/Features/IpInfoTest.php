<?php

declare(strict_types=1);

namespace Tests\Features;

use Addeeandra\Paranoia\Services\IpInfo;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

covers(IpInfo::class);

test('ipinfo should return correct country', function (?string $ip, string $country): void {
    $mockHandler = new MockHandler(
        [new Response(200, [], "$country\n")]
    );

    $infoCountry = trim(IpInfo::getCountry($ip, $mockHandler));
    $this->assertEquals($country, $infoCountry);
})->with([
    ['126.73.107.96', 'JP'], // JP
    ['81.155.62.104', 'GB'], // GB
    ['50.50.127.136', 'US'], // US
    [null, ''], // null IP
]);
