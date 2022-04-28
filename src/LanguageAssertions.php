<?php

namespace Astrotomic\PhpunitAssertions;

use Astrotomic\ISO639\ISO639;
use PHPUnit\Framework\Assert as PHPUnit;

final class LanguageAssertions
{
    public static function assertName($actual): void
    {
        PHPUnit::assertIsString($actual);
        $language = (new ISO639())->name($actual);
        PHPUnit::assertIsArray($language);
    }

    public static function assertAlpha2($actual): void
    {
        PHPUnit::assertIsString($actual);
        StringLengthAssertions::assertSame(2, $actual);
        $language = (new ISO639)->alpha2($actual);
        PHPUnit::assertIsArray($language);
    }
}
