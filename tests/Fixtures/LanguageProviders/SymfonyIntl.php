<?php

namespace Symfony\Component\Intl;

final class Languages
{
    public static function getNames(?string $displayLocale = null): array
    {
        return ['de' => 'German', 'zh' => 'Chinese', 'zz' => 'Test Language'];
    }

    public static function exists(string $language): bool
    {
        return in_array($language, ['de', 'zh', 'zz'], true);
    }

    public static function alpha3CodeExists(string $language): bool
    {
        return in_array($language, ['cmn', 'deu', 'zzz'], true);
    }
}
