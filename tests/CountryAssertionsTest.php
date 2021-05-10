<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\CountryAssertions;
use League\ISO3166\ISO3166;

final class CountryAssertionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider countryName
     */
    public static function it_can_validate_country_by_name(string $actual): void
    {
        CountryAssertions::assertName($actual);
    }

    /**
     * @test
     * @dataProvider countryAlpha2
     */
    public static function it_can_validate_country_by_alpha2(string $actual): void
    {
        CountryAssertions::assertAlpha2($actual);
    }

    /**
     * @test
     * @dataProvider countryAlpha3
     */
    public static function it_can_validate_country_by_alpha3(string $actual): void
    {
        CountryAssertions::assertAlpha3($actual);
    }

    /**
     * @test
     * @dataProvider countryNumeric
     */
    public static function it_can_validate_country_by_numeric(string $actual): void
    {
        CountryAssertions::assertNumeric($actual);
    }

    public function countryName(): iterable
    {
        return array_map(fn (array $country): array => [$country[ISO3166::KEY_NAME]], (new ISO3166())->all());
    }

    public function countryAlpha2(): iterable
    {
        return array_map(fn (array $country): array => [$country[ISO3166::KEY_ALPHA2]], (new ISO3166())->all());
    }

    public function countryAlpha3(): iterable
    {
        return array_map(fn (array $country): array => [$country[ISO3166::KEY_ALPHA3]], (new ISO3166())->all());
    }

    public function countryNumeric(): iterable
    {
        return array_map(fn (array $country): array => [$country[ISO3166::KEY_NUMERIC]], (new ISO3166())->all());
    }
}
