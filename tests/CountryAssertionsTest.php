<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\CountryAssertions;
use League\ISO3166\ISO3166;
use PHPUnit\Framework\AssertionFailedError;

final class CountryAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function it_uses_symfony_intl(): void
    {
        require_once __DIR__.'/Fixtures/CountryProviders/SymfonyIntl.php';

        CountryAssertions::assertName('Germany');
        CountryAssertions::assertAlpha2('DE');
        CountryAssertions::assertAlpha3('DEU');
        CountryAssertions::assertNumeric('276');
    }

    /**
     * @test
     *
     * @dataProvider countryName
     */
    public static function it_can_validate_country_by_name(string $actual): void
    {
        CountryAssertions::assertName($actual);
    }

    /**
     * @test
     *
     * @dataProvider countryAlpha2
     */
    public static function it_can_validate_country_by_alpha2(string $actual): void
    {
        CountryAssertions::assertAlpha2($actual);
    }

    /**
     * @test
     *
     * @dataProvider countryAlpha3
     */
    public static function it_can_validate_country_by_alpha3(string $actual): void
    {
        CountryAssertions::assertAlpha3($actual);
    }

    /**
     * @test
     *
     * @dataProvider countryNumeric
     */
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

    /**
     * @test
     *
     * @dataProvider invalidCountries
     */
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
