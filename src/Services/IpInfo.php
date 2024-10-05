<?php

declare(strict_types=1);

namespace Addeeandra\Paranoia\Services;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

final class IpInfo
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getCountry(?string $ip, ?callable $handler = null): string
    {
        if ($ip === null) {
            return '';
        }

        $client = new Client(['handler' => HandlerStack::create($handler)]);
        $response = $client->get("https://ipinfo.io/$ip/country", [
            'query' => [
                'token' => config('paranoia.ipinfo.token'),
            ],
        ]);

        return $response->getBody()->getContents();
    }
}
