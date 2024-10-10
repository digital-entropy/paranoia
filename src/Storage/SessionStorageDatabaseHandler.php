<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Storage;

use Illuminate\Support\Facades\DB;

class SessionStorageDatabaseHandler implements Contracts\SessionStorageContract
{
    public function __construct(
        protected string $sessionTableName
    ) {}

    public function saveSessionIpAddress(string $sessionId): void
    {
        // already handled by laravel's session handler
    }

    public function saveSessionUserAgent(string $sessionId): void
    {
        // already handled by laravel's session handler
    }

    public function getSavedIpAddress(string $sessionId): ?string
    {
        /** @var string|null */
        return DB::table($this->sessionTableName)
            ->where('id', $sessionId)
            ->value('ip_address');
    }

    public function getSavedUserAgent(string $sessionId): ?string
    {
        /** @var string|null */
        return DB::table($this->sessionTableName)
            ->where('id', $sessionId)
            ->value('user_agent');
    }

    /**
     * @throws \Throwable
     */
    public static function make(?string $tableName): SessionStorageDatabaseHandler
    {
        throw_if($tableName === null || $tableName === '' || $tableName === '0', new \InvalidArgumentException('Session table name is required for database driver'));

        /** @var string $tableName */
        return new self($tableName);
    }
}
