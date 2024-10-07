<?php

namespace Addeeandra\Paranoia;

use Illuminate\Support\Facades\DB;

class Paranoia
{
    protected function getSessionTable(): string
    {
        /** @var string */
        return config('session.table', 'sessions');
    }

    public function isCompatibleForIPRestriction(): bool
    {
        return session()->getDefaultDriver() === 'database' && $this->getSessionTable() !== null;
    }

    public function isCompatibleForUserAgentRestriction(): bool
    {
        return session()->getDefaultDriver() === 'database' && $this->getSessionTable() !== null;
    }

    public function getSessionIpAddress(): ?string
    {
        /** @var string|null */
        return DB::table($this->getSessionTable())
            ->where('id', session()->getId())
            ->value('ip_address');
    }

    public function getSessionUserAgent(): ?string
    {
        /** @var string|null */
        return DB::table($this->getSessionTable())
            ->where('id', session()->getId())
            ->value('user_agent');
    }
}
