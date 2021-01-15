<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

trait StringAssertions
{
    public static function assertLength(int $length, $actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertSame($length, mb_strlen($actual));
    }

    public static function assertNotLength(int $length, $actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertNotSame($length, mb_strlen($actual));
    }

    public static function assertLengthLessThan(int $length, $actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertLessThan($length, mb_strlen($actual));
    }

    public static function assertLengthLessThanOrEqual(int $length, $actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertLessThanOrEqual($length, mb_strlen($actual));
    }

    public static function assertLengthGreaterThan(int $length, $actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertGreaterThan($length, mb_strlen($actual));
    }

    public static function assertLengtGreaterThanOrEqual(int $length, $actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertGreaterThanOrEqual($length, mb_strlen($actual));
    }
}
