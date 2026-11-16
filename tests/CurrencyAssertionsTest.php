<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\CurrencyAssertions;
use Composer\Autoload\ClassLoader;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;

final class CurrencyAssertionsTest extends TestCase
{
    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_uses_brick_money(): void
    {
        require_once __DIR__.'/Fixtures/CurrencyProviders/BrickMoney.php';

        CurrencyAssertions::assertIso4217('EUR', CurrencyAssertions::PROVIDER_BRICK_MONEY);
        CurrencyAssertions::assertMinorUnits('EUR', 2, CurrencyAssertions::PROVIDER_BRICK_MONEY);
        CurrencyAssertions::assertMinorUnits('JPY', 0, CurrencyAssertions::PROVIDER_BRICK_MONEY);
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_uses_moneyphp_money(): void
    {
        require_once __DIR__.'/Fixtures/CurrencyProviders/MoneyPhp.php';

        CurrencyAssertions::assertIso4217('EUR', CurrencyAssertions::PROVIDER_MONEYPHP_MONEY);
        CurrencyAssertions::assertMinorUnits('EUR', 2, CurrencyAssertions::PROVIDER_MONEYPHP_MONEY);
        CurrencyAssertions::assertMinorUnits('JPY', 0, CurrencyAssertions::PROVIDER_MONEYPHP_MONEY);
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_uses_alcohol_iso4217(): void
    {
        require_once __DIR__.'/Fixtures/CurrencyProviders/AlcoholIso4217.php';

        CurrencyAssertions::assertIso4217('EUR', CurrencyAssertions::PROVIDER_ALCOHOL_ISO4217);
        CurrencyAssertions::assertMinorUnits('EUR', 2, CurrencyAssertions::PROVIDER_ALCOHOL_ISO4217);
        CurrencyAssertions::assertMinorUnits('JPY', 0, CurrencyAssertions::PROVIDER_ALCOHOL_ISO4217);
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_uses_symfony_intl(): void
    {
        require_once __DIR__.'/Fixtures/CurrencyProviders/SymfonyIntl.php';

        CurrencyAssertions::assertIso4217('EUR', CurrencyAssertions::PROVIDER_SYMFONY_INTL);
        CurrencyAssertions::assertMinorUnits('EUR', 2, CurrencyAssertions::PROVIDER_SYMFONY_INTL);
        CurrencyAssertions::assertMinorUnits('JPY', 0, CurrencyAssertions::PROVIDER_SYMFONY_INTL);
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_does_not_treat_missing_minor_units_as_an_unknown_currency(): void
    {
        require_once __DIR__.'/Fixtures/CurrencyProviders/SymfonyIntl.php';

        CurrencyAssertions::assertIso4217('XAU', CurrencyAssertions::PROVIDER_SYMFONY_INTL);

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('does not supply minor-unit digits');

        CurrencyAssertions::assertMinorUnits('XAU', 0, CurrencyAssertions::PROVIDER_SYMFONY_INTL);
    }

    #[Test]
    public function it_requires_an_explicit_provider_when_multiple_are_installed(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Multiple currency providers are installed');

        CurrencyAssertions::assertIso4217('EUR');
    }

    #[Test]
    public function it_tests_real_installed_providers_explicitly(): void
    {
        CurrencyAssertions::assertIso4217('EUR', CurrencyAssertions::PROVIDER_BRICK_MONEY);
        CurrencyAssertions::assertIso4217('EUR', CurrencyAssertions::PROVIDER_MONEYPHP_MONEY);
        CurrencyAssertions::assertIso4217('EUR', CurrencyAssertions::PROVIDER_ALCOHOL_ISO4217);
        CurrencyAssertions::assertIso4217('EUR', CurrencyAssertions::PROVIDER_SYMFONY_INTL);
        CurrencyAssertions::assertMinorUnits('JPY', 0, CurrencyAssertions::PROVIDER_BRICK_MONEY);
        CurrencyAssertions::assertMinorUnits('JPY', 0, CurrencyAssertions::PROVIDER_MONEYPHP_MONEY);
        CurrencyAssertions::assertMinorUnits('JPY', 0, CurrencyAssertions::PROVIDER_ALCOHOL_ISO4217);
        CurrencyAssertions::assertMinorUnits('JPY', 0, CurrencyAssertions::PROVIDER_SYMFONY_INTL);
    }

    #[Test]
    public function it_accepts_rare_iso_codes_from_a_provider_that_contains_them(): void
    {
        CurrencyAssertions::assertIso4217('XAU', CurrencyAssertions::PROVIDER_MONEYPHP_MONEY);
        CurrencyAssertions::assertIso4217('XTS', CurrencyAssertions::PROVIDER_MONEYPHP_MONEY);
        CurrencyAssertions::assertIso4217('XXX', CurrencyAssertions::PROVIDER_MONEYPHP_MONEY);
    }

    #[Test]
    #[DataProvider('providersWithoutRareIsoCodes')]
    public function it_respects_the_explicit_provider_catalog(string $provider): void
    {
        $this->expectException(AssertionFailedError::class);

        CurrencyAssertions::assertIso4217('XAU', $provider);
    }

    public static function providersWithoutRareIsoCodes(): array
    {
        return [
            'brick/money' => [CurrencyAssertions::PROVIDER_BRICK_MONEY],
            'alcohol/iso4217' => [CurrencyAssertions::PROVIDER_ALCOHOL_ISO4217],
            'symfony/intl' => [CurrencyAssertions::PROVIDER_SYMFONY_INTL],
        ];
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_rejects_an_unknown_currency(): void
    {
        require_once __DIR__.'/Fixtures/CurrencyProviders/BrickMoney.php';

        $this->expectException(AssertionFailedError::class);

        CurrencyAssertions::assertIso4217('ZZZ', CurrencyAssertions::PROVIDER_BRICK_MONEY);
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_rejects_incorrect_minor_units(): void
    {
        require_once __DIR__.'/Fixtures/CurrencyProviders/BrickMoney.php';

        $this->expectException(AssertionFailedError::class);

        CurrencyAssertions::assertMinorUnits('JPY', 2, CurrencyAssertions::PROVIDER_BRICK_MONEY);
    }

    #[Test]
    #[DataProvider('malformedCurrencyCodes')]
    public function it_rejects_a_malformed_currency_code(string $actual): void
    {
        $this->expectException(AssertionFailedError::class);

        CurrencyAssertions::assertIso4217($actual);
    }

    public static function malformedCurrencyCodes(): array
    {
        return [
            'lowercase' => ['eur'],
            'trailing newline' => ["EUR\n"],
            'trailing whitespace' => ['EUR '],
        ];
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_selects_the_only_installed_provider_automatically(): void
    {
        self::assertIsString('preload PHPUnit constraints');
        self::assertContains('value', ['value']);
        self::assertMatchesRegularExpression('/\A.+\z/', 'value');
        self::assertTrue(true);

        $loaders = ClassLoader::getRegisteredLoaders();

        foreach ($loaders as $loader) {
            $loader->unregister();
        }

        try {
            require_once __DIR__.'/Fixtures/CurrencyProviders/BrickMoney.php';
            require_once __DIR__.'/../src/CurrencyAssertions.php';

            CurrencyAssertions::assertIso4217('EUR');
        } finally {
            foreach ($loaders as $loader) {
                $loader->register(true);
            }
        }
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_requires_a_supported_currency_provider(): void
    {
        self::assertIsString('preload PHPUnit constraints');
        self::assertMatchesRegularExpression('/\A.+\z/', 'value');
        self::assertTrue(true);
        class_exists(AssertionFailedError::class);

        $loaders = ClassLoader::getRegisteredLoaders();

        foreach ($loaders as $loader) {
            $loader->unregister();
        }

        try {
            require_once __DIR__.'/../src/CurrencyAssertions.php';

            $this->expectException(AssertionFailedError::class);
            $this->expectExceptionMessage('Currency assertions require');

            CurrencyAssertions::assertIso4217('EUR');
        } finally {
            foreach ($loaders as $loader) {
                $loader->register(true);
            }
        }
    }

    #[Test]
    public function it_rejects_an_unknown_explicit_provider(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Unknown currency provider');

        CurrencyAssertions::assertIso4217('EUR', 'unknown/provider');
    }
}
