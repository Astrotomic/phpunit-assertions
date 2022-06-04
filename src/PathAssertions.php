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

    private static function agnosticPath(string $path): string
    {
        if (DIRECTORY_SEPARATOR === '/') {
            if (str_contains($path, '\\')) {
                return str_replace('\\', DIRECTORY_SEPARATOR, $path);
            }
            return $path;
        }

        return str_replace('/', DIRECTORY_SEPARATOR, $path);
    }

    public static function assertOsAgnosticPath(string $expected, $actual): void
    {
        $osNormalizedExpected = PathAssertions::agnosticPath($expected);
        PHPUnit::assertIsString($actual);
        PHPUnit::assertSame($osNormalizedExpected, $actual);
    }
}
