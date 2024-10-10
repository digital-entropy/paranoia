<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Cookie;

class CookieLayerFactory
{
    public function __construct(protected string $layer) {}

    public function make(): AbstractCookieLayer
    {
        return match ($this->layer) {
            CookieLayer::USER_AGENT => new UserAgentCookieLayer,
            CookieLayer::IP => new IPCookieLayer,
            default => new NullCookieLayer,
        };
    }
}
