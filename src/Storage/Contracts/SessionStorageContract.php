<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Storage\Contracts;

interface SessionStorageContract
{
    public function saveSessionIpAddress(string $sessionId): void;

    public function saveSessionUserAgent(string $sessionId): void;

    public function getSavedIpAddress(string $sessionId): ?string;

    public function getSavedUserAgent(string $sessionId): ?string;
}
