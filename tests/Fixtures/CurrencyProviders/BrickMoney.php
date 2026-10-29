<?php

namespace Brick\Money\Exception;

final class UnknownCurrencyException extends \RuntimeException {}

namespace Brick\Money;

use Brick\Money\Exception\UnknownCurrencyException;

final class Currency
{
    private int $minorUnits;

    private function __construct(int $minorUnits)
    {
        $this->minorUnits = $minorUnits;
    }

    public static function of(string $currency): self
    {
        if ($currency === 'EUR') {
            return new self(2);
        }

        if ($currency === 'JPY') {
            return new self(0);
        }

        throw new UnknownCurrencyException;
    }

    public function getDefaultFractionDigits(): int
    {
        return $this->minorUnits;
    }
}
