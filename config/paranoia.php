<?php

declare(strict_types=1);

return [
    'ipinfo' => [
        'token' => env('IPINFO_TOKEN', null),
    ],
    'geo' => [ // ISO 3166's Countries Code
        'allowed' => '*',
    ],
];
