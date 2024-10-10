<?php

declare(strict_types=1);

namespace Dentro\Paranoia;

use Dentro\Paranoia\Cookie\AbstractCookieLayer;
use Dentro\Paranoia\Cookie\CookieLayerFactory;
use Dentro\Paranoia\Storage\Contracts\SessionStorageContract;
use Dentro\Paranoia\Storage\SessionStorageHandler;

class Paranoia
{
    public function __construct(
        protected SessionStorageContract $storage
    ) {}

    public function eligibleForGeoRestriction(): bool
    {
        return auth()->guard()->check();
    }

    public function eligibleForIPRestriction(): bool
    {
        return auth()->guard()->check();
    }

    public function eligibleForUserAgentRestriction(): bool
    {
        return auth()->guard()->check();
    }

    public function isUsingBaseDriver(): bool
    {
        return $this->storage instanceof SessionStorageHandler;
    }

    public function saveSessionIpAddress(): void
    {
        $this->storage->saveSessionIpAddress(session()->getId());
    }

    public function saveSessionUserAgent(): void
    {
        $this->storage->saveSessionUserAgent(session()->getId());
    }

    public function getSessionIpAddress(): ?string
    {
        /** @var string|null */
        return $this->storage->getSavedIpAddress(session()->getId());
    }

    public function getSessionUserAgent(): ?string
    {
        /** @var string|null */
        return $this->storage->getSavedUserAgent(session()->getId());
    }

    public function useCookieForUserAgent(): bool
    {
        /** @var bool */
        return config('paranoia.use_cookie_layer', true);
    }

    public function useCookieForIP(): bool
    {
        /** @var bool */
        return config('paranoia.use_cookie_layer', true);
    }

    public function cookieLayer(string $name): AbstractCookieLayer
    {
        return (new CookieLayerFactory($name))->make();
    }
}
