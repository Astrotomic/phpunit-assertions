<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\CountryAssertions;
use League\ISO3166\ISO3166;

final class CountryAssertionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider countries
     */
    public static function it_can_validate_country(string $name, string $alpha2, string $alpha3, string $numeric): void
    {
        CountryAssertions::assertCountry(ISO3166::KEY_NAME, $name);
        CountryAssertions::assertCountry(ISO3166::KEY_ALPHA2, $alpha2);
        CountryAssertions::assertCountry(ISO3166::KEY_ALPHA3, $alpha3);
        CountryAssertions::assertCountry(ISO3166::KEY_NUMERIC, $numeric);
    }

    /**
     * @test
     * @dataProvider countryName
     */
    public static function it_can_validate_country_by_name(string $actual): void
    {
        CountryAssertions::assertCountryName($actual);
    }

    /**
     * @test
     * @dataProvider countryAlpha2
     */
    public static function it_can_validate_country_by_alpha2(string $actual): void
    {
        CountryAssertions::assertCountryAlpha2($actual);
    }

    /**
     * @test
     * @dataProvider countryAlpha3
     */
    public static function it_can_validate_country_by_alpha3(string $actual): void
    {
        CountryAssertions::assertCountryAlpha3($actual);
    }

    /**
     * @test
     * @dataProvider countryNumeric
     */
    public static function it_can_validate_country_by_numeric(string $actual): void
    {
        CountryAssertions::assertCountryNumeric($actual);
    }

    public function countries(): iterable
    {
        return (new ISO3166())->all();
    }

    public function countryName(): iterable
    {
        return array_map(fn(array $country): array => [$country[ISO3166::KEY_NAME]], (new ISO3166())->all());
    }

    public function countryAlpha2(): iterable
    {
        return array_map(fn(array $country): array => [$country[ISO3166::KEY_ALPHA2]], (new ISO3166())->all());
    }

    public function countryAlpha3(): iterable
    {
        return array_map(fn(array $country): array => [$country[ISO3166::KEY_ALPHA3]], (new ISO3166())->all());
    }

    public function countryNumeric(): iterable
    {
        return array_map(fn(array $country): array => [$country[ISO3166::KEY_NUMERIC]], (new ISO3166())->all());
    }
}