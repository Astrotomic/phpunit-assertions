<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\StringAssertions;

final class StringAssertionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_length(): void
    {
        StringAssertions::assertLength(8, self::randomString(8));
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_not_length(): void
    {
        $string = self::randomString(self::randomInt(4, 8));
        StringAssertions::assertNotLength(3, $string);
        StringAssertions::assertNotLength(9, $string);
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_length_less_than(): void
    {
        StringAssertions::assertLengthLessThan(9, self::randomString(self::randomInt(4, 8)));
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_length_less_than_or_equal(): void
    {
        StringAssertions::assertLengthLessThanOrEqual(8, self::randomString(self::randomInt(4, 8)));
        StringAssertions::assertLengthLessThanOrEqual(8, self::randomString(8));
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_length_greater_than(): void
    {
        StringAssertions::assertLengthGreaterThan(3, self::randomString(self::randomInt(4, 8)));
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_length_greater_than_or_equal(): void
    {
        StringAssertions::assertLengtGreaterThanOrEqual(4, self::randomString(self::randomInt(4, 8)));
        StringAssertions::assertLengtGreaterThanOrEqual(4, self::randomString(4));
    }
}
