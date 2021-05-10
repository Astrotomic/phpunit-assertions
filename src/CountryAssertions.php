<?php

namespace Astrotomic\PhpunitAssertions;

use League\ISO3166\ISO3166;
use PHPUnit\Framework\Assert as PHPUnit;

trait CountryAssertions
{
    public static function assertName($actual): void
    {
        PHPUnit::assertIsString($actual);
        $country = (new ISO3166)->name($actual);
        PHPUnit::assertIsArray($country);
    }

    public static function assertAlpha2($actual): void
    {
        PHPUnit::assertIsString($actual);
        StringLengthAssertions::assertSame(2, $actual);
        $country = (new ISO3166)->alpha2($actual);
        PHPUnit::assertIsArray($country);
    }

    public static function assertAlpha3($actual): void
    {
        PHPUnit::assertIsString($actual);
        StringLengthAssertions::assertSame(3, $actual);
        $country = (new ISO3166)->alpha3($actual);
        PHPUnit::assertIsArray($country);
    }

    public static function assertNumeric($actual): void
    {
        PHPUnit::assertIsString($actual);
        StringLengthAssertions::assertSame(3, $actual);
        $country = (new ISO3166)->numeric($actual);
        PHPUnit::assertIsArray($country);
    }
}
