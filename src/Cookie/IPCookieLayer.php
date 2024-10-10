<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Cookie;

class IPCookieLayer extends AbstractCookieLayer
{
    public function getCookieValue(): string
    {
        return request()->ip();
    }

    public function getName(): string
    {
        return 'PARA-IP';
    }

    public function hashAlgo(): string
    {
        return 'crc32';
    }
}
