<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

final class UlidAssertions
{
    public static function assertUlid($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            '/\A[0-7][0-9A-HJKMNP-TV-Z]{25}\z/i',
            $actual,
            'Failed asserting that the value is a valid ULID.'
        );
    }

    public static function assertCanonicalUlid($actual): void
    {
        self::assertUlid($actual);

        PHPUnit::assertSame(
            strtoupper($actual),
            $actual,
            'Failed asserting that the value is a canonically formatted ULID.'
        );
    }
}
