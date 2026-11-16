<?php

namespace Astrotomic\PhpunitAssertions;

use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\Assert as PHPUnit;

final class DateAssertions
{
    private const RFC_3339_PATTERN = '/\A(?<year>\d{4})-(?<month>\d{2})-(?<day>\d{2})[Tt](?<hour>[01]\d|2[0-3]):(?<minute>[0-5]\d):(?<second>[0-5]\d|60)(?:\.\d+)?(?<timezone>[Zz]|[+-](?:[01]\d|2[0-3]):[0-5]\d)\z/';

    public static function assertFormat(string $format, $actual): void
    {
        PHPUnit::assertIsString($actual);

        $date = DateTimeImmutable::createFromFormat('!'.$format, $actual);
        $errors = DateTimeImmutable::getLastErrors();
        $hasErrors = is_array($errors)
            && ($errors['warning_count'] > 0 || $errors['error_count'] > 0);

        PHPUnit::assertTrue(
            $date !== false && ! $hasErrors && $date->format($format) === $actual,
            sprintf('Failed asserting that "%s" is a valid date in format "%s".', $actual, $format)
        );
    }

    public static function assertRfc3339($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            self::RFC_3339_PATTERN,
            $actual
        );

        preg_match(
            self::RFC_3339_PATTERN,
            $actual,
            $matches
        );

        $validDate = self::isValidDate(
            (int) $matches['year'],
            (int) $matches['month'],
            (int) $matches['day']
        );
        $validLeapSecond = $matches['second'] !== '60'
            || ($validDate && self::isPossibleLeapSecond($matches));

        PHPUnit::assertTrue(
            $validDate && $validLeapSecond,
            sprintf('Failed asserting that "%s" is a valid RFC 3339 date and time.', $actual)
        );
    }

    private static function isValidDate(int $year, int $month, int $day): bool
    {
        if ($month < 1 || $month > 12 || $day < 1) {
            return false;
        }

        $daysInMonth = match ($month) {
            2 => self::isLeapYear($year) ? 29 : 28,
            4, 6, 9, 11 => 30,
            default => 31,
        };

        return $day <= $daysInMonth;
    }

    private static function isLeapYear(int $year): bool
    {
        return $year % 4 === 0 && ($year % 100 !== 0 || $year % 400 === 0);
    }

    private static function isPossibleLeapSecond(array $matches): bool
    {
        $timezone = strtolower($matches['timezone']) === 'z'
            ? '+00:00'
            : $matches['timezone'];
        $beforeLeapSecond = DateTimeImmutable::createFromFormat(
            '!Y-m-d H:i:sP',
            sprintf(
                '%s-%s-%s %s:%s:59%s',
                $matches['year'],
                $matches['month'],
                $matches['day'],
                $matches['hour'],
                $matches['minute'],
                $timezone
            )
        );

        if ($beforeLeapSecond === false) {
            return false;
        }

        $utc = $beforeLeapSecond->setTimezone(new DateTimeZone('UTC'));

        return in_array($utc->format('m-d H:i:s'), [
            '06-30 23:59:59',
            '12-31 23:59:59',
        ], true);
    }
}
