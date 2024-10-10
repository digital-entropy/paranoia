<?php

declare(strict_types=1);

return [
    'use_cookie_layer' => false,
    'geo' => [ // ISO 3166's Countries Code
        'allowed' => '*',
    ],
    'ipinfo' => [
        'token' => env('IPINFO_TOKEN', null),
    ],
];
