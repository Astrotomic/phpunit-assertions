<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

trait UrlAssertions
{
    public static function assertValidLoose($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertNotFalse(filter_var($actual, FILTER_VALIDATE_URL));
    }

    public static function assertScheme(string $expected, $actual): void
    {
        self::assertComponent($expected, $actual, PHP_URL_SCHEME);
    }

    public static function assertHost(string $expected, $actual): void
    {
        self::assertComponent($expected, $actual, PHP_URL_HOST);
    }

    public static function assertPath(string $expected, $actual): void
    {
        self::assertComponent($expected, $actual, PHP_URL_PATH);
    }

    public static function assertComponent($expected, $actual, int $component): void
    {
        self::assertValidLoose($actual);
        PHPUnit::assertSame($expected, parse_url($actual, $component));
    }
}
