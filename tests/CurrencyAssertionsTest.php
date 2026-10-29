<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\CurrencyAssertions;
use PHPUnit\Framework\AssertionFailedError;

final class CurrencyAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function it_uses_brick_money(): void
    {
        require_once __DIR__.'/Fixtures/CurrencyProviders/BrickMoney.php';

        CurrencyAssertions::assertIso4217('EUR');
        CurrencyAssertions::assertMinorUnits('EUR', 2);
        CurrencyAssertions::assertMinorUnits('JPY', 0);
    }

    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function it_uses_moneyphp_money(): void
    {
        require_once __DIR__.'/Fixtures/CurrencyProviders/MoneyPhp.php';

        CurrencyAssertions::assertIso4217('EUR');
        CurrencyAssertions::assertMinorUnits('EUR', 2);
        CurrencyAssertions::assertMinorUnits('JPY', 0);
    }

    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function it_uses_alcohol_iso4217(): void
    {
        require_once __DIR__.'/Fixtures/CurrencyProviders/AlcoholIso4217.php';

        CurrencyAssertions::assertIso4217('EUR');
        CurrencyAssertions::assertMinorUnits('EUR', 2);
        CurrencyAssertions::assertMinorUnits('JPY', 0);
    }

    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function it_uses_symfony_intl(): void
    {
        require_once __DIR__.'/Fixtures/CurrencyProviders/SymfonyIntl.php';

        CurrencyAssertions::assertIso4217('EUR');
        CurrencyAssertions::assertMinorUnits('EUR', 2);
        CurrencyAssertions::assertMinorUnits('JPY', 0);
    }

    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function it_rejects_an_unknown_currency(): void
    {
        require_once __DIR__.'/Fixtures/CurrencyProviders/BrickMoney.php';

        $this->expectException(AssertionFailedError::class);

        CurrencyAssertions::assertIso4217('ZZZ');
    }

    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function it_rejects_incorrect_minor_units(): void
    {
        require_once __DIR__.'/Fixtures/CurrencyProviders/BrickMoney.php';

        $this->expectException(AssertionFailedError::class);

        CurrencyAssertions::assertMinorUnits('JPY', 2);
    }

    /**
     * @test
     *
     * @dataProvider malformedCurrencyCodes
     */
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

    /** @test */
    public function it_requires_a_supported_currency_provider(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Currency assertions require');

        CurrencyAssertions::assertIso4217('EUR');
    }
}
