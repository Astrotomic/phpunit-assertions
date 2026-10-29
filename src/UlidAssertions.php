<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

final class UlidAssertions
{
    public static function assertUlid($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            '/^[0-7][0-9A-HJKMNP-TV-Z]{25}\z/',
            $actual,
            'Failed asserting that the value is a valid ULID.'
        );
    }
}
