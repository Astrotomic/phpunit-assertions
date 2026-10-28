<?php

namespace Astrotomic\PhpunitAssertions;

use DateTimeImmutable;
use PHPUnit\Framework\Assert as PHPUnit;

final class DateAssertions
{
    private const ISO_8601_PATTERN = '/^(?<year>\d{4})-(?<month>\d{2})-(?<day>\d{2})T(?<hour>\d{2}):(?<minute>\d{2}):(?<second>\d{2})(?:\.\d+)?(?<timezone>Z|(?<offset_sign>[+-])(?<offset_hour>\d{2}):(?<offset_minute>\d{2}))$/D';

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

    public static function assertIso8601($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            self::ISO_8601_PATTERN,
            $actual
        );

        preg_match(
            self::ISO_8601_PATTERN,
            $actual,
            $matches
        );

        $validDate = checkdate((int) $matches['month'], (int) $matches['day'], (int) $matches['year']);
        $validTime = (int) $matches['hour'] <= 23
            && (int) $matches['minute'] <= 59
            && (int) $matches['second'] <= 59;
        $validTimezone = $matches['timezone'] === 'Z'
            || (
                (int) $matches['offset_hour'] <= 14
                && (int) $matches['offset_minute'] <= 59
                && ((int) $matches['offset_hour'] !== 14 || (int) $matches['offset_minute'] === 0)
            );

        PHPUnit::assertTrue(
            $validDate && $validTime && $validTimezone,
            sprintf('Failed asserting that "%s" is a valid ISO 8601 date and time.', $actual)
        );
    }

    public static function assertRfc3339($actual): void
    {
        self::assertIso8601($actual);
    }
}
