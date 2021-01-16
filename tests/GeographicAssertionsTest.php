<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\GeographicAssertions;

final class GeographicAssertionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_latitude(): void
    {
        GeographicAssertions::assertLatitude(static::randomFloat(-90, 90));
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_longitude(): void
    {
        GeographicAssertions::assertLongitude(static::randomFloat(-180, 180));
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_array_of_coordinates(): void
    {
        $coords = [
            'lat' => static::randomFloat(-90, 90),
            'lng' => static::randomFloat(-180, 180),
        ];

        GeographicAssertions::assertCoordinates($coords);
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_array_of_coordinates_with_custom_keys(): void
    {
        $lat = 'lat_'.static::randomString();
        $lng = 'lng_'.static::randomString();

        $coords = [
            $lat => static::randomFloat(-90, 90),
            $lng => static::randomFloat(-180, 180),
        ];

        GeographicAssertions::assertCoordinates($coords, $lat, $lng);
    }
}
