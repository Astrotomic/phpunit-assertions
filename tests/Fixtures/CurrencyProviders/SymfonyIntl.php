<?php

namespace Symfony\Component\Intl;

final class Currencies
{
    public static function exists(string $currency): bool
    {
        return in_array($currency, ['EUR', 'JPY', 'XAU', 'XTS', 'XXX'], true);
    }

    public static function getFractionDigits(string $currency): ?int
    {
        if (in_array($currency, ['XAU', 'XTS', 'XXX'], true)) {
            return null;
        }

        return $currency === 'JPY' ? 0 : 2;
    }
}
