<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Storage;

class SessionStorageFactory
{
    protected string $sessionTableName = 'sessions';

    public function __construct(protected string $driver) {}

    /**
     * @throws \Throwable
     */
    public function build(): Contracts\SessionStorageContract
    {
        return match ($this->driver) {
            'database' => SessionStorageDatabaseHandler::make($this->sessionTableName),
            default => SessionStorageHandler::make(),
        };
    }

    public function setSessionTableName(string $sessionTableName): static
    {
        $this->sessionTableName = $sessionTableName;

        return $this;
    }
}
