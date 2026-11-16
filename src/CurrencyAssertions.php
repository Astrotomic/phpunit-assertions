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
    public const PROVIDER_ALCOHOL_ISO4217 = 'alcohol/iso4217';

    public const PROVIDER_BRICK_MONEY = 'brick/money';

    public const PROVIDER_MONEYPHP_MONEY = 'moneyphp/money';

    public const PROVIDER_SYMFONY_INTL = 'symfony/intl';

    private const PROVIDERS = [
        self::PROVIDER_BRICK_MONEY,
        self::PROVIDER_MONEYPHP_MONEY,
        self::PROVIDER_ALCOHOL_ISO4217,
        self::PROVIDER_SYMFONY_INTL,
    ];

    public static function assertIso4217($actual, ?string $provider = null): void
    {
        self::assertCurrency($actual, $provider);
    }

    public static function assertMinorUnits($currency, int $expected, ?string $provider = null): void
    {
        $provider = self::assertCurrency($currency, $provider);
        $minorUnits = self::currencyData($provider, $currency)['minorUnits'];

        PHPUnit::assertNotNull(
            $minorUnits,
            sprintf('The %s provider does not supply minor-unit digits for %s.', $provider, $currency)
        );

        PHPUnit::assertSame(
            $expected,
            $minorUnits,
            sprintf('Failed asserting that %s has %d minor unit digits.', $currency, $expected)
        );
    }

    private static function assertCurrency($actual, ?string $provider): string
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            '/\A[A-Z]{3}\z/',
            $actual,
            'Failed asserting that the value is a valid ISO 4217 currency code.'
        );

        $provider = self::resolveProvider($provider);
        $exists = self::currencyData($provider, $actual)['exists'];

        PHPUnit::assertTrue(
            $exists,
            sprintf('Failed asserting that %s exists in the %s currency provider.', $actual, $provider)
        );

        return $provider;
    }

    private static function resolveProvider(?string $provider): string
    {
        if ($provider !== null) {
            PHPUnit::assertContains(
                $provider,
                self::PROVIDERS,
                sprintf('Unknown currency provider "%s".', $provider)
            );
            PHPUnit::assertTrue(
                self::isProviderInstalled($provider),
                sprintf('Currency provider %s is not installed.', $provider)
            );

            return $provider;
        }

        $installed = array_values(array_filter(
            self::PROVIDERS,
            self::isProviderInstalled(...)
        ));

        if ($installed === []) {
            PHPUnit::fail(
                'Currency assertions require brick/money, moneyphp/money, alcohol/iso4217, or symfony/intl.'
            );
        }

        if (count($installed) > 1) {
            PHPUnit::fail(sprintf(
                'Multiple currency providers are installed (%s); pass one of the CurrencyAssertions::PROVIDER_* constants explicitly.',
                implode(', ', $installed)
            ));
        }

        return $installed[0];
    }

    private static function isProviderInstalled(string $provider): bool
    {
        return match ($provider) {
            self::PROVIDER_BRICK_MONEY => class_exists(\Brick\Money\Currency::class),
            self::PROVIDER_MONEYPHP_MONEY => class_exists(ISOCurrencies::class)
                && class_exists(Currency::class),
            self::PROVIDER_ALCOHOL_ISO4217 => class_exists(ISO4217::class),
            self::PROVIDER_SYMFONY_INTL => class_exists(Currencies::class),
        };
    }

    /**
     * @return array{exists: bool, minorUnits: ?int}
     */
    private static function currencyData(string $provider, string $currency): array
    {
        if ($provider === self::PROVIDER_BRICK_MONEY) {
            try {
                $value = \Brick\Money\Currency::of($currency);

                return ['exists' => true, 'minorUnits' => $value->getDefaultFractionDigits()];
            } catch (UnknownCurrencyException) {
                return ['exists' => false, 'minorUnits' => null];
            }
        }

        if ($provider === self::PROVIDER_MONEYPHP_MONEY) {
            $currencies = new ISOCurrencies;
            $value = new Currency($currency);

            return $currencies->contains($value)
                ? ['exists' => true, 'minorUnits' => $currencies->subunitFor($value)]
                : ['exists' => false, 'minorUnits' => null];
        }

        if ($provider === self::PROVIDER_ALCOHOL_ISO4217) {
            try {
                $data = (new ISO4217)->getByAlpha3($currency);
                $minorUnits = $data['exp'] ?? null;

                return [
                    'exists' => true,
                    'minorUnits' => is_int($minorUnits) ? $minorUnits : null,
                ];
            } catch (\DomainException|\OutOfBoundsException) {
                return ['exists' => false, 'minorUnits' => null];
            }
        }

        if (! Currencies::exists($currency)) {
            return ['exists' => false, 'minorUnits' => null];
        }

        return ['exists' => true, 'minorUnits' => Currencies::getFractionDigits($currency)];
    }
}
