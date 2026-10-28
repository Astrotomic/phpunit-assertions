<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\DateAssertions;
use PHPUnit\Framework\AssertionFailedError;

final class DateAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider validFormattedDates
     */
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
        ];
    }

    /**
     * @test
     *
     * @dataProvider invalidFormattedDates
     */
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
            'trailing data' => ['Y-m-d', '2026-10-28T12:00:00Z'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider validIso8601Dates
     */
    public function it_can_validate_iso8601_dates(string $actual): void
    {
        DateAssertions::assertIso8601($actual);
    }

    public static function validIso8601Dates(): array
    {
        return [
            'UTC' => ['2026-10-28T18:42:03Z'],
            'positive offset' => ['2026-10-28T18:42:03+01:00'],
            'negative offset' => ['2026-10-28T18:42:03-05:30'],
            'fractional seconds' => ['2026-10-28T18:42:03.123456789Z'],
            'maximum offset' => ['2026-10-28T18:42:03+14:00'],
            'leap day' => ['2024-02-29T00:00:00Z'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider invalidIso8601Dates
     */
    public function it_rejects_invalid_iso8601_dates($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        DateAssertions::assertIso8601($actual);
    }

    public static function invalidIso8601Dates(): array
    {
        return [
            'not a string' => [20261028],
            'date only' => ['2026-10-28'],
            'missing timezone' => ['2026-10-28T18:42:03'],
            'nonexistent day' => ['2026-02-30T18:42:03Z'],
            'invalid hour' => ['2026-10-28T24:00:00Z'],
            'invalid minute' => ['2026-10-28T18:60:00Z'],
            'leap second' => ['2026-10-28T18:42:60Z'],
            'offset too large' => ['2026-10-28T18:42:03+14:01'],
            'offset minute too large' => ['2026-10-28T18:42:03+01:60'],
            'space separator' => ['2026-10-28 18:42:03Z'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider validIso8601Dates
     */
    public function it_can_validate_rfc3339_dates(string $actual): void
    {
        DateAssertions::assertRfc3339($actual);
    }
}
