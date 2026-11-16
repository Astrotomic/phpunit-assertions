<?php

namespace Astrotomic\PhpunitAssertions;

/** @internal */
final class Modulo97
{
    public static function remainder(string $value): int
    {
        $remainder = 0;

        foreach (str_split($value) as $character) {
            $digits = $character >= 'A' && $character <= 'Z'
                ? (string) (ord($character) - 55)
                : $character;

            foreach (str_split($digits) as $digit) {
                $remainder = ($remainder * 10 + (int) $digit) % 97;
            }
        }

        return $remainder;
    }
}
