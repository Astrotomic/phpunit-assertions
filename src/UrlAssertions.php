<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

final class UrlAssertions
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

    public static function assertQuery(array $expected, $actual): void
    {
        $queryStr = parse_url($actual, PHP_URL_QUERY);
        PHPUnit::assertIsString($queryStr);

        $query = [];
        parse_str($queryStr, $query);
        PHPUnit::assertIsArray($query);

        ArrayAssertions::assertEquals($expected, $query);
    }

    public static function assertComponent($expected, $actual, int $component): void
    {
        self::assertValidLoose($actual);
        PHPUnit::assertSame($expected, parse_url($actual, $component));
    }
}
