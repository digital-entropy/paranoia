<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Cookie;

class NullCookieLayer extends AbstractCookieLayer
{
    public function getCookieValue(): string
    {
        return '';
    }

    public function getName(): string
    {
        return 'NULL';
    }
}
