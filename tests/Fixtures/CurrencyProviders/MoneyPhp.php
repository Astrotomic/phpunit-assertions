<?php

namespace Money;

final class Currency
{
    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}

namespace Money\Currencies;

use Money\Currency;

final class ISOCurrencies
{
    public function contains(Currency $currency): bool
    {
        return in_array($currency->getCode(), ['EUR', 'JPY'], true);
    }

    public function subunitFor(Currency $currency): int
    {
        return $currency->getCode() === 'JPY' ? 0 : 2;
    }
}
