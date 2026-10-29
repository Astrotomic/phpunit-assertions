<?php

namespace Astrotomic\PhpunitAssertions;

use Alcohol\ISO4217;
use Brick\Money\Exception\UnknownCurrencyException;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use PHPUnit\Framework\Assert as PHPUnit;
use Symfony\Component\Intl\Currencies;

final class CurrencyAssertions
{
    public static function assertIso4217($actual): void
    {
        self::assertCurrency($actual);
    }

    public static function assertMinorUnits($currency, int $expected): void
    {
        $minorUnits = self::assertCurrency($currency);

        PHPUnit::assertSame(
            $expected,
            $minorUnits,
            sprintf('Failed asserting that %s has %d minor unit digits.', $currency, $expected)
        );
    }

    private static function assertCurrency($actual): int
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            '/^[A-Z]{3}\z/',
            $actual,
            'Failed asserting that the value is a valid ISO 4217 currency code.'
        );

        $minorUnits = self::minorUnits($actual);

        PHPUnit::assertNotNull(
            $minorUnits,
            sprintf('Failed asserting that %s exists in the installed currency provider.', $actual)
        );

        return $minorUnits;
    }

    private static function minorUnits(string $currency): ?int
    {
        if (class_exists(\Brick\Money\Currency::class)) {
            try {
                return \Brick\Money\Currency::of($currency)->getDefaultFractionDigits();
            } catch (UnknownCurrencyException $exception) {
                return null;
            }
        }

        if (
            class_exists(ISOCurrencies::class)
            && class_exists(Currency::class)
        ) {
            $currencies = new ISOCurrencies;
            $currency = new Currency($currency);

            return $currencies->contains($currency)
                ? $currencies->subunitFor($currency)
                : null;
        }

        if (class_exists(ISO4217::class)) {
            try {
                return (new ISO4217)->getByAlpha3($currency)['exp'];
            } catch (\DomainException|\OutOfBoundsException $exception) {
                return null;
            }
        }

        if (class_exists(Currencies::class)) {
            return Currencies::exists($currency)
                ? Currencies::getFractionDigits($currency)
                : null;
        }

        PHPUnit::fail(
            'Currency assertions require brick/money, moneyphp/money, alcohol/iso4217, or symfony/intl.'
        );
    }
}
