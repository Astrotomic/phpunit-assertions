<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\ArrayAssertions;

final class ArrayAssertionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_array_is_indexed(): void
    {
        $actual = self::randomArray(self::randomInt(5, 20), false);

        ArrayAssertions::assertIndexed($actual);
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_array_is_associative(): void
    {
        $actual = self::randomArray(self::randomInt(5, 20), true);

        ArrayAssertions::assertAssociative($actual);
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_indexed_array_equality(): void
    {
        $array = self::randomArray(self::randomInt(5, 20), false);

        $actual = $array;
        $expected = $array;
        shuffle($actual);

        ArrayAssertions::assertEquals($expected, $actual);
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_associative_array_equality(): void
    {
        $array = self::randomArray(self::randomInt(5, 20), true);

        $actual = $array;
        $expected = $array;
        uksort($actual, fn (): int => self::randomInt() <=> self::randomInt());

        ArrayAssertions::assertEquals($expected, $actual);
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_associative_array_subset(): void
    {
        $array = self::randomArray(self::randomInt(5, 20), true);

        $actual = $array;
        $expected = array_slice($array, 0, ceil(count($array) / self::randomInt(2, 4)), true);
        uksort($expected, fn (): int => self::randomInt() <=> self::randomInt());

        ArrayAssertions::assertSubset($expected, $actual);
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_indexed_array_contains_all(): void
    {
        $array = self::randomArray(self::randomInt(5, 20), false);

        $actual = $array;
        $expected = array_slice($array, 0, ceil(count($array) / self::randomInt(2, 4)), true);
        shuffle($actual);

        ArrayAssertions::assertContainsAll($expected, $actual);
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_associative_array_contains_all(): void
    {
        $array = self::randomArray(self::randomInt(5, 20), true);

        $actual = $array;
        $expected = array_slice($array, 0, ceil(count($array) / self::randomInt(2, 4)), true);
        uksort($actual, fn (): int => self::randomInt() <=> self::randomInt());

        ArrayAssertions::assertContainsAll($expected, $actual);
    }
}
