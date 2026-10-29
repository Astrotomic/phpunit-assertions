<?php

namespace Astrotomic\PhpunitAssertions;

use Astrotomic\ISO639\ISO639;
use PHPUnit\Framework\Assert as PHPUnit;
use Symfony\Component\Intl\Languages;

final class LanguageAssertions
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

    public static function assertIso6392B($actual): void
    {
        PHPUnit::assertIsString($actual);
        StringLengthAssertions::assertSame(3, $actual);
        self::assertExists('iso6392B', $actual);
    }

    public static function assertIso6392T($actual): void
    {
        PHPUnit::assertIsString($actual);
        StringLengthAssertions::assertSame(3, $actual);
        self::assertExists('iso6392T', $actual);
    }

    private static function assertExists(string $type, string $actual): void
    {
        $exists = self::existsInSymfonyIntl($type, $actual)
            ?? self::existsInAstrotomicIso639($type, $actual);

        if ($exists === null) {
            $providers = $type === 'iso6392B'
                ? 'astrotomic/iso639'
                : 'astrotomic/iso639 or symfony/intl';

            PHPUnit::fail(sprintf('Language assertions require %s.', $providers));
        }

        PHPUnit::assertTrue(
            $exists,
            sprintf('Failed asserting that "%s" is a valid %s language value.', $actual, $type)
        );
    }

    private static function existsInSymfonyIntl(string $type, string $actual): ?bool
    {
        if (! class_exists(Languages::class) || $type === 'iso6392B') {
            return null;
        }

        return match ($type) {
            'name' => in_array($actual, Languages::getNames('en'), true),
            'alpha2' => Languages::exists($actual),
            'iso6392T' => Languages::alpha3CodeExists($actual),
        };
    }

    private static function existsInAstrotomicIso639(string $type, string $actual): ?bool
    {
        if (! class_exists(ISO639::class)) {
            return null;
        }

        try {
            match ($type) {
                'name' => (new ISO639)->name($actual),
                'alpha2' => (new ISO639)->alpha2($actual),
                'iso6392B' => (new ISO639)->iso639_2B($actual),
                'iso6392T' => (new ISO639)->iso639_2T($actual),
            };

            return true;
        } catch (\OutOfBoundsException $exception) {
            return false;
        }
    }
}
