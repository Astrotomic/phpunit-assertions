<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

final class AsnAssertions
{
    private const MAX = '4294967295';

    private const DOCUMENTATION_RANGES = [
        ['64496', '64511'],
        ['65536', '65551'],
    ];

    private const PRIVATE_RANGES = [
        ['64512', '65534'],
        ['4200000000', '4294967294'],
    ];

    private const RESERVED = [
        '0',
        '23456',
        '65535',
        '4294967295',
    ];

    public static function assertValid($actual): void
    {
        self::normalize($actual);
    }

    public static function assertPrivate($actual): void
    {
        $asn = self::normalize($actual);

        PHPUnit::assertTrue(
            self::isInRanges($asn, self::PRIVATE_RANGES),
            sprintf('Failed asserting that "%s" is a private-use ASN.', $actual)
        );
    }

    public static function assertDocumentation($actual): void
    {
        $asn = self::normalize($actual);

        PHPUnit::assertTrue(
            self::isInRanges($asn, self::DOCUMENTATION_RANGES),
            sprintf('Failed asserting that "%s" is a documentation ASN.', $actual)
        );
    }

    public static function assertPublic($actual): void
    {
        $asn = self::normalize($actual);

        PHPUnit::assertFalse(
            self::isInRanges($asn, self::PRIVATE_RANGES)
            || self::isInRanges($asn, self::DOCUMENTATION_RANGES)
            || in_array($asn, self::RESERVED, true),
            sprintf('Failed asserting that "%s" is a public ASN.', $actual)
        );
    }

    private static function normalize($actual): string
    {
        PHPUnit::assertTrue(
            is_int($actual) || is_string($actual),
            'Failed asserting that the ASN is an integer or string.'
        );

        if (is_int($actual)) {
            PHPUnit::assertGreaterThanOrEqual(
                0,
                $actual,
                'Failed asserting that the ASN is unsigned.'
            );

            $asn = (string) $actual;
        } else {
            PHPUnit::assertMatchesRegularExpression(
                '/^(?:AS)?(?:0|[1-9][0-9]{0,9})\z/i',
                $actual,
                'Failed asserting that the value is an ASN in asplain notation.'
            );

            $asn = preg_replace('/^AS/i', '', $actual);
        }

        PHPUnit::assertTrue(
            self::compare($asn, self::MAX) <= 0,
            'Failed asserting that the ASN fits in the unsigned 32-bit range.'
        );

        return $asn;
    }

    /**
     * @param  array<array{string, string}>  $ranges
     */
    private static function isInRanges(string $asn, array $ranges): bool
    {
        foreach ($ranges as [$minimum, $maximum]) {
            if (self::compare($asn, $minimum) >= 0 && self::compare($asn, $maximum) <= 0) {
                return true;
            }
        }

        return false;
    }

    private static function compare(string $left, string $right): int
    {
        return strlen($left) === strlen($right)
            ? strcmp($left, $right)
            : strlen($left) <=> strlen($right);
    }
}
