<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

trait StringLengthAssertions
{
    public static function assertSame(int $length, $actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertSame($length, mb_strlen($actual));
    }

    public static function assertNotSame(int $length, $actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertNotSame($length, mb_strlen($actual));
    }

    public static function assertLessThan(int $length, $actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertLessThan($length, mb_strlen($actual));
    }

    public static function assertLessThanOrEqual(int $length, $actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertLessThanOrEqual($length, mb_strlen($actual));
    }

    public static function assertGreaterThan(int $length, $actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertGreaterThan($length, mb_strlen($actual));
    }

    public static function assertGreaterThanOrEqual(int $length, $actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertGreaterThanOrEqual($length, mb_strlen($actual));
    }
}
