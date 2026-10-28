<?php

namespace Symfony\Component\Intl;

final class Countries
{
    public static function getNames(?string $displayLocale = null): array
    {
        return ['CN' => 'China', 'DE' => 'Germany'];
    }

    public static function exists(string $country): bool
    {
        return in_array($country, ['CN', 'DE'], true);
    }

    public static function alpha3CodeExists(string $country): bool
    {
        return in_array($country, ['CHN', 'DEU'], true);
    }

    public static function numericCodeExists(string $country): bool
    {
        return in_array($country, ['156', '276'], true);
    }
}
