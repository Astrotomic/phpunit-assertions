<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

final class ArrayAssertions
{
    public static function assertIndexed($actual): void
    {
        PHPUnit::assertIsArray($actual);
        PHPUnit::assertTrue(self::isIndexed($actual));
    }

    public static function assertAssociative($actual): void
    {
        PHPUnit::assertIsArray($actual);
        PHPUnit::assertFalse(self::isIndexed($actual));
    }

    public static function assertEquals(array $expected, $actual): void
    {
        PHPUnit::assertIsArray($actual);

        if (self::isIndexed($expected)) {
            sort($expected);
            sort($actual);
        } else {
            ksort($expected);
            ksort($actual);
        }

        PHPUnit::assertEquals($expected, $actual);
    }

    public static function assertSubset(array $expected, $actual): void
    {
        PHPUnit::assertIsArray($actual);

        foreach ($expected as $key => $value) {
            PHPUnit::assertArrayHasKey($key, $actual);
            PHPUnit::assertEquals($value, $actual[$key]);
        }
    }

    public static function assertContainsAll(array $expected, $actual): void
    {
        PHPUnit::assertIsArray($actual);

        foreach ($expected as $value) {
            PHPUnit::assertContains($value, $actual);
        }
    }

    private static function isIndexed(array $array): bool
    {
        return $array === [] || array_keys($array) === range(0, count($array) - 1);
    }
}
