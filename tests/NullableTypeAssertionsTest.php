<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\NullableTypeAssertions;

final class NullableTypeAssertionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_nullable_string(): void
    {
        NullableTypeAssertions::assertIsNullableString(
            static::randomBool()
                ? static::randomString()
                : null
        );
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_nullable_int(): void
    {
        NullableTypeAssertions::assertIsNullableInt(
            static::randomBool()
                ? static::randomInt()
                : null
        );
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_nullable_float(): void
    {
        NullableTypeAssertions::assertIsNullableFloat(
            static::randomBool()
                ? static::randomFloat()
                : null
        );
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_nullable_array(): void
    {
        NullableTypeAssertions::assertIsNullableArray(
            static::randomBool()
                ? [static::randomString() => static::randomInt()]
                : null
        );
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_nullable_bool(): void
    {
        NullableTypeAssertions::assertIsNullableBool(
            static::randomBool()
                ? static::randomBool()
                : null
        );
    }
}
