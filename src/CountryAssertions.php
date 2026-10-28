<?php

namespace Astrotomic\PhpunitAssertions;

use League\ISO3166\ISO3166;
use PHPUnit\Framework\Assert as PHPUnit;
use Symfony\Component\Intl\Countries;

final class CountryAssertions
{
    public static function assertName($actual): void
    {
        PHPUnit::assertIsString($actual);
        self::assertExists('name', $actual);
    }

    public static function assertAlpha2($actual): void
    {
        PHPUnit::assertIsString($actual);
        StringLengthAssertions::assertSame(2, $actual);
        self::assertExists('alpha2', $actual);
    }

    public static function assertAlpha3($actual): void
    {
        PHPUnit::assertIsString($actual);
        StringLengthAssertions::assertSame(3, $actual);
        self::assertExists('alpha3', $actual);
    }

    public static function assertNumeric($actual): void
    {
        PHPUnit::assertIsString($actual);
        StringLengthAssertions::assertSame(3, $actual);
        self::assertExists('numeric', $actual);
    }

    private static function assertExists(string $type, string $actual): void
    {
        $exists = self::existsInSymfonyIntl($type, $actual)
            ?? self::existsInLeagueIso3166($type, $actual);

        if ($exists === null) {
            PHPUnit::fail(
                'Country assertions require league/iso3166 or a compatible version of symfony/intl.'
            );
        }

        PHPUnit::assertTrue(
            $exists,
            sprintf('Failed asserting that "%s" is a valid ISO 3166-1 %s value.', $actual, $type)
        );
    }

    private static function existsInSymfonyIntl(string $type, string $actual): ?bool
    {
        if (! class_exists(Countries::class)) {
            return null;
        }

        return match ($type) {
            'name' => in_array($actual, Countries::getNames('en'), true),
            'alpha2' => Countries::exists($actual),
            'alpha3' => Countries::alpha3CodeExists($actual),
            'numeric' => method_exists(Countries::class, 'numericCodeExists')
                ? Countries::numericCodeExists($actual)
                : null,
        };
    }

    private static function existsInLeagueIso3166(string $type, string $actual): ?bool
    {
        if (! class_exists(ISO3166::class)) {
            return null;
        }

        try {
            match ($type) {
                'name' => (new ISO3166)->name($actual),
                'alpha2' => (new ISO3166)->alpha2($actual),
                'alpha3' => (new ISO3166)->alpha3($actual),
                'numeric' => (new ISO3166)->numeric($actual),
            };

            return true;
        } catch (\DomainException|\OutOfBoundsException $exception) {
            return false;
        }
    }
}
