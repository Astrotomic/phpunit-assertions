<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

trait GeoAssertions
{
    public static function assertLatitude($actual): void
    {
        PHPUnit::assertIsNumeric($actual);
        PHPUnit::assertGreaterThanOrEqual(-90, $actual);
        PHPUnit::assertLessThanOrEqual(90, $actual);
    }

    public static function assertLongitude($actual): void
    {
        PHPUnit::assertIsNumeric($actual);
        PHPUnit::assertGreaterThanOrEqual(-180, $actual);
        PHPUnit::assertLessThanOrEqual(180, $actual);
    }

    public static function assertCoordinates($actual, string $lat = 'lat', string $lng = 'lng'): void
    {
        PHPUnit::assertIsArray($actual);
        PHPUnit::assertArrayHasKey($lat, $actual);
        self::assertLatitude($actual[$lat]);
        PHPUnit::assertArrayHasKey($lng, $actual);
        self::assertLongitude($actual[$lng]);
    }
}