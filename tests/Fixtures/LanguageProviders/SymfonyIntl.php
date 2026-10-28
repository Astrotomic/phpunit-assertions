<?php

namespace Symfony\Component\Intl;

final class Languages
{
    public static function getNames(?string $displayLocale = null): array
    {
        return ['de' => 'German', 'zh' => 'Chinese'];
    }

    public static function exists(string $language): bool
    {
        return in_array($language, ['de', 'zh'], true);
    }

    public static function alpha3CodeExists(string $language): bool
    {
        return in_array($language, ['cmn', 'deu'], true);
    }
}
