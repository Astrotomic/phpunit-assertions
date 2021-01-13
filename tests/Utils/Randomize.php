<?php

namespace Astrotomic\PhpunitAssertions\Tests\Utils;

trait Randomize
{
    protected static function randomString(): string
    {
        return bin2hex(random_bytes(16));
    }

    protected static function randomBool(): bool
    {
        return boolval(mt_rand(0, 1));
    }

    protected static function randomInt(int $min = PHP_INT_MIN, int $max = PHP_INT_MAX): int
    {
        return mt_rand($min, $max);
    }

    protected static function randomFloat(float $min = PHP_INT_MIN, float $max = PHP_INT_MAX): float
    {
        return max($min, min(static::randomInt(intval(floor($min) * 100), intval(ceil($max) * 100)) / 100, $max));
    }
}