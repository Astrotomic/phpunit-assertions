<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\GeographicAssertions;

final class GeographicAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_latitude(): void
    {
        GeographicAssertions::assertLatitude(self::randomFloat(-90, 90));
    }

    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_longitude(): void
    {
        GeographicAssertions::assertLongitude(self::randomFloat(-180, 180));
    }

    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_array_of_coordinates(): void
    {
        $coords = [
            'lat' => self::randomFloat(-90, 90),
            'lng' => self::randomFloat(-180, 180),
        ];

        GeographicAssertions::assertCoordinates($coords);
    }

    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_array_of_coordinates_with_custom_keys(): void
    {
        $lat = 'lat_'.self::randomString();
        $lng = 'lng_'.self::randomString();

        $coords = [
            $lat => self::randomFloat(-90, 90),
            $lng => self::randomFloat(-180, 180),
        ];

        GeographicAssertions::assertCoordinates($coords, $lat, $lng);
    }

    /** @test */
    public static function it_can_validate_distance_greater_than_or_equal(): void
    {
        $hamburg = ['lat' => 53.551085, 'lng' => 9.993682];
        $berlin = ['lat' => 52.520008, 'lng' => 13.404954];

        GeographicAssertions::assertDistanceGreaterThanOrEqual(255_000, $hamburg, $berlin);
        GeographicAssertions::assertDistanceGreaterThanOrEqual(0, $hamburg, $hamburg);
    }

    /** @test */
    public static function it_can_validate_distance_less_than_or_equal(): void
    {
        $hamburg = ['lat' => 53.551085, 'lng' => 9.993682];
        $berlin = ['lat' => 52.520008, 'lng' => 13.404954];

        GeographicAssertions::assertDistanceLessThanOrEqual(256_000, $hamburg, $berlin);
        GeographicAssertions::assertDistanceLessThanOrEqual(0, $hamburg, $hamburg);
    }

    /** @test */
    public static function it_can_validate_distance_with_custom_keys(): void
    {
        $hamburg = ['latitude' => 53.551085, 'longitude' => 9.993682];
        $berlin = ['latitude' => 52.520008, 'longitude' => 13.404954];

        GeographicAssertions::assertDistanceGreaterThanOrEqual(
            255_000,
            $hamburg,
            $berlin,
            'latitude',
            'longitude'
        );
        GeographicAssertions::assertDistanceLessThanOrEqual(
            256_000,
            $hamburg,
            $berlin,
            'latitude',
            'longitude'
        );
    }
}
