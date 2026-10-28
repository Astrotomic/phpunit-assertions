<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

final class CardBinAssertions
{
    public static function assertIin($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            '/^[0-9]{8}$/D',
            $actual,
            'Failed asserting that the value has a valid eight-digit IIN structure.'
        );
    }

    public static function assertBin($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            '/^(?:[0-9]{6}|[0-9]{8})$/D',
            $actual,
            'Failed asserting that the value has a valid six- or eight-digit BIN structure.'
        );
    }
}
