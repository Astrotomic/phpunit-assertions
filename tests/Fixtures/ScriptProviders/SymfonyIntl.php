<?php

namespace Symfony\Component\Intl;

final class Scripts
{
    public static function exists(string $script): bool
    {
        return in_array($script, ['Cyrl', 'Hans', 'Hant', 'Latn'], true);
    }
}
