<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

final class BankIdentifierAssertions
{
    public static function assertAbaRoutingNumber($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            '/^[0-9]{9}$/',
            $actual,
            'Failed asserting that the value has a valid ABA routing number format.'
        );

        $prefix = (int) substr($actual, 0, 2);
        PHPUnit::assertTrue(
            $prefix <= 12
            || ($prefix >= 21 && $prefix <= 32)
            || ($prefix >= 61 && $prefix <= 72)
            || $prefix === 80,
            'Failed asserting that the value has a valid ABA routing number prefix.'
        );

        $digits = array_map('intval', str_split($actual));
        $checksum = 3 * ($digits[0] + $digits[3] + $digits[6])
            + 7 * ($digits[1] + $digits[4] + $digits[7])
            + $digits[2] + $digits[5] + $digits[8];

        PHPUnit::assertSame(
            0,
            $checksum % 10,
            'Failed asserting that the value has a valid ABA routing number checksum.'
        );
    }

    public static function assertBic($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            '/^[A-Z0-9]{4}[A-Z]{2}[A-Z0-9]{2}(?:[A-Z0-9]{3})?$/',
            $actual,
            'Failed asserting that the value is a valid BIC.'
        );
    }

    public static function assertIsin($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            '/^[A-Z]{2}[A-Z0-9]{9}[0-9]$/',
            $actual,
            'Failed asserting that the value has a valid ISIN format.'
        );
        PHPUnit::assertTrue(
            self::hasValidLuhnChecksum($actual),
            'Failed asserting that the value has a valid ISIN checksum.'
        );
    }

    public static function assertLei($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            '/^[A-Z0-9]{18}[0-9]{2}$/',
            $actual,
            'Failed asserting that the value has a valid LEI format.'
        );
        PHPUnit::assertSame(
            1,
            self::mod97(self::expandLetters($actual)),
            'Failed asserting that the value has a valid LEI checksum.'
        );
    }

    private static function hasValidLuhnChecksum(string $value): bool
    {
        $digits = self::expandLetters($value);
        $sum = 0;
        $double = false;

        for ($index = strlen($digits) - 1; $index >= 0; $index--) {
            $digit = (int) $digits[$index];

            if ($double) {
                $digit *= 2;
                $digit = intdiv($digit, 10) + ($digit % 10);
            }

            $sum += $digit;
            $double = ! $double;
        }

        return $sum % 10 === 0;
    }

    private static function expandLetters(string $value): string
    {
        $result = '';

        foreach (str_split($value) as $character) {
            $ascii = ord($character);
            $result .= $ascii >= 65 && $ascii <= 90
                ? (string) ($ascii - 55)
                : $character;
        }

        return $result;
    }

    private static function mod97(string $number): int
    {
        $remainder = 0;

        foreach (str_split($number) as $digit) {
            $remainder = ($remainder * 10 + (int) $digit) % 97;
        }

        return $remainder;
    }
}
