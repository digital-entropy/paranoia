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

    public function shouldCheckGeoRestriction(): bool
    {
        return auth()->guard()->check();
    }

    public function shouldCheckIPRestriction(): bool
    {
        return session()->getDefaultDriver() === 'database' && $this->getSessionTable() !== null && auth()->guard()->check();
    }

    public function shouldCheckUserAgentRestriction(): bool
    {
        return session()->getDefaultDriver() === 'database' && $this->getSessionTable() !== null && auth()->guard()->check();
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
