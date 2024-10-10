<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Storage;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SessionStorageHandler implements Contracts\SessionStorageContract
{
    public function saveSessionIpAddress(string $sessionId): void
    {
        if (! session()->has('ip_address')) {
            session()->put('ip_address', request()->ip());
        }
    }

    public function saveSessionUserAgent(string $sessionId): void
    {
        if (! session()->has('user_agent')) {
            session()->put('user_agent', request()->userAgent());
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getSavedIpAddress(string $sessionId): ?string
    {
        return session()->get('ip_address', null);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getSavedUserAgent(string $sessionId): ?string
    {
        return session()->get('user_agent', null);
    }

    /**
     * @throws \Throwable
     */
    public static function make(): SessionStorageHandler
    {
        return new self;
    }
}
