<?php

declare(strict_types=1);

namespace Addeeandra\Paranoia\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class IpInfo
{
    /**
     * @throws GuzzleException
     */
    public static function getCountry(?string $ip): string
    {
        if ($ip === null) {
            return '';
        }

        $client = new Client;
        $response = $client->get("https://ipinfo.io/$ip/country");

        return $response->getBody()->getContents();
    }
}
