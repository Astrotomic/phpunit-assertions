<?php

namespace Symfony\Component\Intl;

final class Currencies
{
    public static function exists(string $currency): bool
    {
        return in_array($currency, ['EUR', 'JPY'], true);
    }

    public static function getFractionDigits(string $currency): int
    {
        return $currency === 'JPY' ? 0 : 2;
    }
}
