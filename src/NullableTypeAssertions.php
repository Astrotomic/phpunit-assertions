<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

final class NullableTypeAssertions
{
    /**
     * @param  string|mixed|null  $actual
     */
    public static function assertIsNullableString($actual): void
    {
        static::assertIsNullableType(
            is_null($actual) || is_string($actual),
            'string',
            $actual
        );
    }

    /**
     * @param  int|mixed|null  $actual
     */
    public static function assertIsNullableInt($actual): void
    {
        static::assertIsNullableType(
            is_null($actual) || is_int($actual),
            'int',
            $actual
        );
    }

    /**
     * @param  float|mixed|null  $actual
     */
    public static function assertIsNullableFloat($actual): void
    {
        static::assertIsNullableType(
            is_null($actual) || (is_numeric($actual) && (is_int($actual) || is_float($actual))),
            'float',
            $actual
        );
    }

    /**
     * @param  array|mixed|null  $actual
     */
    public static function assertIsNullableArray($actual): void
    {
        static::assertIsNullableType(
            is_null($actual) || is_array($actual),
            'array',
            $actual
        );
    }

    /**
     * @param  bool|mixed|null  $actual
     */
    public static function assertIsNullableBool($actual): void
    {
        static::assertIsNullableType(
            is_null($actual) || is_bool($actual),
            'boolean',
            $actual
        );
    }

    /**
     * @param  bool  $condition
     * @param  string  $type
     * @param  mixed|null  $actual
     */
    protected static function assertIsNullableType(bool $condition, string $type, $actual): void
    {
        PHPUnit::assertTrue(
            $condition,
            sprintf(
                'Failed to assert that "%s" is type of null|%s.'.PHP_EOL.'%s',
                gettype($actual),
                $type,
                var_export($actual, true)
            )
        );
    }
}
