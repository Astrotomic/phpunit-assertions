<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\NullableTypeAssertions;

final class NullableTypeAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_nullable_string(): void
    {
        NullableTypeAssertions::assertIsNullableString(
            self::randomBool()
                ? self::randomString()
                : null
        );
    }

    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_nullable_int(): void
    {
        NullableTypeAssertions::assertIsNullableInt(
            self::randomBool()
                ? self::randomInt()
                : null
        );
    }

    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_nullable_float(): void
    {
        NullableTypeAssertions::assertIsNullableFloat(
            self::randomBool()
                ? self::randomFloat()
                : null
        );
    }

    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_nullable_array(): void
    {
        NullableTypeAssertions::assertIsNullableArray(
            self::randomBool()
                ? [self::randomString() => self::randomInt()]
                : null
        );
    }

    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_nullable_bool(): void
    {
        NullableTypeAssertions::assertIsNullableBool(
            self::randomBool()
                ? self::randomBool()
                : null
        );
    }
}
