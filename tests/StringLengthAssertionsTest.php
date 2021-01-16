<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\StringLengthAssertions;

final class StringLengthAssertionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_length(): void
    {
        StringLengthAssertions::assertSame(8, self::randomString(8));
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_not_length(): void
    {
        $string = self::randomString(self::randomInt(4, 8));
        StringLengthAssertions::assertNotSame(3, $string);
        StringLengthAssertions::assertNotSame(9, $string);
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_length_less_than(): void
    {
        StringLengthAssertions::assertLessThan(9, self::randomString(self::randomInt(4, 8)));
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_length_less_than_or_equal(): void
    {
        StringLengthAssertions::assertLessThanOrEqual(8, self::randomString(self::randomInt(4, 8)));
        StringLengthAssertions::assertLessThanOrEqual(8, self::randomString(8));
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_length_greater_than(): void
    {
        StringLengthAssertions::assertGreaterThan(3, self::randomString(self::randomInt(4, 8)));
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_length_greater_than_or_equal(): void
    {
        StringLengthAssertions::assertGreaterThanOrEqual(4, self::randomString(self::randomInt(4, 8)));
        StringLengthAssertions::assertGreaterThanOrEqual(4, self::randomString(4));
    }
}
