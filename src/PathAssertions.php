<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

final class PathAssertions
{
    public static function assertDirname(string $expected, $actual): void
    {
        self::assertComponent($expected, $actual, PATHINFO_DIRNAME);
    }

    public static function assertBasename(string $expected, $actual): void
    {
        self::assertComponent($expected, $actual, PATHINFO_BASENAME);
    }

    public static function assertFilename(string $expected, $actual): void
    {
        self::assertComponent($expected, $actual, PATHINFO_FILENAME);
    }

    public static function assertExtension(string $expected, $actual): void
    {
        self::assertComponent($expected, $actual, PATHINFO_EXTENSION);
    }

    public static function assertComponent($expected, $actual, int $component): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertSame($expected, pathinfo($actual, $component));
    }
}
