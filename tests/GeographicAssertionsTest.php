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
}
