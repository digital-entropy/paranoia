<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Cookie;

abstract class AbstractCookieLayer
{
    public function generate(string $key): string
    {
        return hash($this->hashAlgo(), $this->getCookieValue().$key);
    }

    public function setCookie(string $key): void
    {
        if ($this->getCookieValue() === '' || $this->getCookieValue() === '0') {
            return;
        }

        cookie()->queue(cookie($this->getName(), $this->generate($key), 60 * 24 * 365));
    }

    public function verify($key): bool
    {
        if ($this->getCookieValue() === '' || $this->getCookieValue() === '0') {
            return false;
        }

        return request()->cookie($this->getName()) === $this->generate($key);
    }

    public function hashAlgo(): string
    {
        return 'sha256';
    }

    abstract public function getCookieValue(): string;

    abstract public function getName(): string;
}
