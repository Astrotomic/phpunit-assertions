<?php

namespace Astrotomic\PhpunitAssertions;

use League\ISO3166\ISO3166;
use OutOfBoundsException;
use PHPUnit\Framework\Assert as PHPUnit;

trait CountryAssertions
{
    public static function assertCountry($actual, string $key): void
    {
        switch ($key) {
            case ISO3166::KEY_NAME:
                self::assertCountryName($actual);
                break;
            case ISO3166::KEY_ALPHA2:
                self::assertCountryAlpha2($actual);
                break;
            case ISO3166::KEY_ALPHA3:
                self::assertCountryAlpha3($actual);
                break;
            case ISO3166::KEY_NUMERIC:
                self::assertCountryNumeric($actual);
                break;
            default:
                throw new OutOfBoundsException();
        }
    }

    public static function assertCountryName($actual): void
    {
        PHPUnit::assertIsString($actual);
        $country = (new ISO3166)->name($actual);
        PHPUnit::assertIsArray($country);
    }

    public static function assertCountryAlpha2($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertSame(2, strlen($actual));
        $country = (new ISO3166)->alpha2($actual);
        PHPUnit::assertIsArray($country);
    }

    public static function assertCountryAlpha3($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertSame(3, strlen($actual));
        $country = (new ISO3166)->alpha3($actual);
        PHPUnit::assertIsArray($country);
    }

    public static function assertCountryNumeric($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertSame(3, strlen($actual));
        $country = (new ISO3166)->numeric($actual);
        PHPUnit::assertIsArray($country);
    }
}
