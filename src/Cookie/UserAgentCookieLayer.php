<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Cookie;

class UserAgentCookieLayer extends AbstractCookieLayer
{
    public function getCookieValue(): string
    {
        return request()->userAgent();
    }

    public function getName(): string
    {
        return 'PARA-AGENT';
    }
}
