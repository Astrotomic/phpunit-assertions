<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\CountryAssertions;
use Composer\Autoload\ClassLoader;
use League\ISO3166\ISO3166;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;

final class CountryAssertionsTest extends TestCase
{
    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_falls_back_to_symfony_intl(): void
    {
        self::assertIsString('preload PHPUnit constraints');
        self::assertSame(2, 2);
        self::assertTrue(true);

        $loaders = ClassLoader::getRegisteredLoaders();

        foreach ($loaders as $loader) {
            $loader->unregister();
        }

        try {
            require_once __DIR__.'/Fixtures/CountryProviders/SymfonyIntl.php';
            require_once __DIR__.'/../src/StringLengthAssertions.php';
            require_once __DIR__.'/../src/CountryAssertions.php';

            CountryAssertions::assertName('Germany');
            CountryAssertions::assertAlpha2('DE');
            CountryAssertions::assertAlpha3('DEU');
            CountryAssertions::assertNumeric('276');
        } finally {
            foreach ($loaders as $loader) {
                $loader->register(true);
            }
        }
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_keeps_league_iso3166_as_primary_when_both_are_installed(): void
    {
        require_once __DIR__.'/Fixtures/CountryProviders/SymfonyIntl.php';

        $this->expectException(AssertionFailedError::class);

        CountryAssertions::assertAlpha2('XZ');
    }

    #[Test]
    #[DataProvider('countryName')]
    public static function it_can_validate_country_by_name(string $actual): void
    {
        CountryAssertions::assertName($actual);
    }

    #[Test]
    #[DataProvider('countryAlpha2')]
    public static function it_can_validate_country_by_alpha2(string $actual): void
    {
        CountryAssertions::assertAlpha2($actual);
    }

    #[Test]
    #[DataProvider('countryAlpha3')]
    public static function it_can_validate_country_by_alpha3(string $actual): void
    {
        CountryAssertions::assertAlpha3($actual);
    }

    #[Test]
    #[DataProvider('countryNumeric')]
    public static function it_can_validate_country_by_numeric(string $actual): void
    {
        CountryAssertions::assertNumeric($actual);
    }

    public static function countryName(): iterable
    {
        return array_map(fn (array $country): array => [$country[ISO3166::KEY_NAME]], (new ISO3166)->all());
    }

    public static function countryAlpha2(): iterable
    {
        return array_map(fn (array $country): array => [$country[ISO3166::KEY_ALPHA2]], (new ISO3166)->all());
    }

    public static function countryAlpha3(): iterable
    {
        return array_map(fn (array $country): array => [$country[ISO3166::KEY_ALPHA3]], (new ISO3166)->all());
    }

    public static function countryNumeric(): iterable
    {
        return array_map(fn (array $country): array => [$country[ISO3166::KEY_NUMERIC]], (new ISO3166)->all());
    }

    #[Test]
    #[DataProvider('invalidCountries')]
    public function it_rejects_unknown_countries(string $method, $actual): void
    {
        $this->expectException(AssertionFailedError::class);

        CountryAssertions::{$method}($actual);
    }

    public static function invalidCountries(): array
    {
        return [
            'name' => ['assertName', 'Not a country'],
            'alpha-2' => ['assertAlpha2', 'ZZ'],
            'alpha-3' => ['assertAlpha3', 'ZZZ'],
            'numeric' => ['assertNumeric', '999'],
            'not a string' => ['assertAlpha2', 123],
        ];
    }
}
