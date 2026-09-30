<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

final class GeographicAssertions
{
    private const EARTH_RADIUS_METERS = 6_371_000;

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

    public static function assertDistanceGreaterThanOrEqual(
        int $distance,
        $from,
        $to,
        string $lat = 'lat',
        string $lng = 'lng'
    ): void {
        PHPUnit::assertGreaterThanOrEqual($distance, self::distance($from, $to, $lat, $lng));
    }

    public static function assertDistanceLessThanOrEqual(
        int $distance,
        $from,
        $to,
        string $lat = 'lat',
        string $lng = 'lng'
    ): void {
        PHPUnit::assertLessThanOrEqual($distance, self::distance($from, $to, $lat, $lng));
    }

    private static function distance($from, $to, string $lat, string $lng): float
    {
        self::assertCoordinates($from, $lat, $lng);
        self::assertCoordinates($to, $lat, $lng);

        $fromLatitude = deg2rad((float) $from[$lat]);
        $toLatitude = deg2rad((float) $to[$lat]);
        $latitudeDelta = $toLatitude - $fromLatitude;
        $longitudeDelta = deg2rad((float) $to[$lng] - (float) $from[$lng]);

        $a = sin($latitudeDelta / 2) ** 2
            + cos($fromLatitude) * cos($toLatitude) * sin($longitudeDelta / 2) ** 2;

        return self::EARTH_RADIUS_METERS * 2 * asin(sqrt(min(1.0, $a)));
    }
}
