<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\DateAssertions;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class DateAssertionsTest extends TestCase
{
    #[Test]
    #[DataProvider('validFormattedDates')]
    public function it_can_validate_dates_against_a_format(string $format, string $actual): void
    {
        DateAssertions::assertFormat($format, $actual);
    }

    public static function validFormattedDates(): array
    {
        return [
            'date' => ['Y-m-d', '2024-02-29'],
            'date and time' => ['Y-m-d H:i:s', '2026-10-28 18:42:03'],
            'custom separator' => ['d/m/Y', '28/10/2026'],
            'partial date initialized from epoch' => ['m-d', '02-28'],
        ];
    }

    #[Test]
    #[DataProvider('invalidFormattedDates')]
    public function it_rejects_dates_that_do_not_strictly_match_a_format(string $format, $actual): void
    {
        $this->expectException(AssertionFailedError::class);

        DateAssertions::assertFormat($format, $actual);
    }

    public static function invalidFormattedDates(): array
    {
        return [
            'not a string' => ['Y-m-d', 20261028],
            'different format' => ['Y-m-d', '28/10/2026'],
            'nonexistent day' => ['Y-m-d', '2026-02-30'],
            'non-leap year' => ['Y-m-d', '2025-02-29'],
            'invalid month' => ['Y-m-d', '2026-13-01'],
            'invalid hour' => ['H:i:s', '24:00:00'],
            'non-canonical padding' => ['Y-m-d', '2026-1-02'],
            'trailing data' => ['Y-m-d', '2026-10-28T12:00:00Z'],
            'trailing newline' => ['Y-m-d', "2026-10-28\n"],
            'trailing whitespace' => ['Y-m-d', '2026-10-28 '],
        ];
    }

    #[Test]
    #[DataProvider('validRfc3339Dates')]
    public function it_can_validate_rfc3339_dates(string $actual): void
    {
        DateAssertions::assertRfc3339($actual);
    }

    public static function validRfc3339Dates(): array
    {
        return [
            'UTC' => ['2026-10-28T18:42:03Z'],
            'lowercase separator and timezone' => ['2016-12-31t23:59:59z'],
            'positive offset' => ['2026-10-28T18:42:03+01:00'],
            'negative offset' => ['2026-10-28T18:42:03-05:30'],
            'fractional seconds' => ['2026-10-28T18:42:03.123456789Z'],
            'real-world maximum positive offset' => ['2026-10-28T18:42:03+14:00'],
            'offset beyond real-world timezones' => ['2026-10-28T18:42:03+14:30'],
            'maximum positive grammar offset' => ['2026-10-28T18:42:03+23:59'],
            'maximum negative grammar offset' => ['2026-10-28T18:42:03-23:59'],
            'unknown local offset' => ['2026-10-28T18:42:03-00:00'],
            'leap day' => ['2024-02-29T00:00:00Z'],
            'year zero leap day' => ['0000-02-29T00:00:00Z'],
            'leap second' => ['2016-12-31T23:59:60Z'],
            'documented leap second' => ['1990-12-31T23:59:60Z'],
            'leap second shifted by offset' => ['1990-12-31T15:59:60-08:00'],
        ];
    }

    #[Test]
    #[DataProvider('invalidRfc3339Dates')]
    public function it_rejects_invalid_rfc3339_dates($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        DateAssertions::assertRfc3339($actual);
    }

    public static function invalidRfc3339Dates(): array
    {
        return [
            'not a string' => [20261028],
            'date only' => ['2026-10-28'],
            'missing timezone' => ['2026-10-28T18:42:03'],
            'nonexistent day' => ['2026-02-30T18:42:03Z'],
            'invalid hour' => ['2026-10-28T24:00:00Z'],
            'invalid minute' => ['2026-10-28T18:60:00Z'],
            'leap second outside June or December' => ['2026-10-28T18:42:60Z'],
            'leap second outside UTC day boundary' => ['2016-12-31T22:59:60Z'],
            'leap second not shifted with offset' => ['1990-12-31T23:59:60-08:00'],
            'offset hour too large' => ['2026-10-28T18:42:03+24:00'],
            'offset minute too large' => ['2026-10-28T18:42:03+01:60'],
            'space separator' => ['2026-10-28 18:42:03Z'],
            'trailing newline' => ["2026-10-28T18:42:03Z\n"],
            'trailing whitespace' => ['2026-10-28T18:42:03Z '],
        ];
    }
}
