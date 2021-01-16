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

    public static function assertSameScheme(string $expected, $actual): void
    {
        self::assertSameComponent($expected, $actual, PHP_URL_SCHEME);
    }

    public static function assertSameHost(string $expected, $actual): void
    {
        self::assertSameComponent($expected, $actual, PHP_URL_HOST);
    }

    public static function assertSamePath(string $expected, $actual): void
    {
        self::assertSameComponent($expected, $actual, PHP_URL_PATH);
    }

    public static function assertSameComponent($expected, $actual, $component): void
    {
        self::assertValidLoose($actual);
        PHPUnit::assertSame($expected, parse_url($actual, $component));
    }
}
