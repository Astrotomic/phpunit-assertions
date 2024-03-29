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
        return (bool) random_int(0, 1);
    }

    protected static function randomInt(int $min = PHP_INT_MIN, int $max = PHP_INT_MAX): int
    {
        return random_int($min, $max);
    }

    protected static function randomFloat(float $min = PHP_INT_MIN, float $max = PHP_INT_MAX): float
    {
        return max($min, min(static::randomInt(intval(floor($min) * 100), intval(ceil($max) * 100)) / 100, $max));
    }

    protected static function randomElement(array $array)
    {
        return $array[array_rand($array)];
    }

    protected static function randomArray(int $count, bool $assoc = false): array
    {
        $array = array_fill(0, $count, null);

        $array = array_map(static function (): string {
            return self::randomString(self::randomInt(8, 64));
        }, $array);

        if ($assoc) {
            $values = $array;
            $array = [];

            foreach ($values as $value) {
                $array[self::randomString(self::randomInt(8, 64))] = $value;
            }
        }

        return $array;
    }
}
