<?php

namespace Astrotomic\PhpunitAssertions\Tests\Utils;

trait Randomize
{
    protected static function randomString(int $length = 16): string
    {
        $string = '';
        do {
            $string .= bin2hex(random_bytes($length));
        } while (mb_strlen($string) < $length);

        return mb_substr($string, 0, $length);
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

    protected static function randomElement(array $array)
    {
        return $array[array_rand($array)];
    }
}
