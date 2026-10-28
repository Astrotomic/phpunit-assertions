<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\CardBinAssertions;
use PHPUnit\Framework\AssertionFailedError;

final class CardBinAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider validIins
     */
    public function it_can_validate_iin_structure(string $actual): void
    {
        CardBinAssertions::assertIin($actual);
    }

    public static function validIins(): array
    {
        return [
            ['12345678'],
            ['00000000'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider invalidIins
     */
    public function it_rejects_invalid_iin_structure($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        CardBinAssertions::assertIin($actual);
    }

    public static function invalidIins(): array
    {
        return [
            'not a string' => [12345678],
            'legacy six digits' => ['123456'],
            'too long' => ['123456789'],
            'non-numeric' => ['1234567A'],
            'whitespace' => [' 12345678'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider validBins
     */
    public function it_can_validate_bin_structure(string $actual): void
    {
        CardBinAssertions::assertBin($actual);
    }

    public static function validBins(): array
    {
        return [
            'legacy six digits' => ['123456'],
            'eight digits' => ['12345678'],
            'leading zeroes' => ['000000'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider invalidBins
     */
    public function it_rejects_invalid_bin_structure($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        CardBinAssertions::assertBin($actual);
    }

    public static function invalidBins(): array
    {
        return [
            'not a string' => [123456],
            'too short' => ['12345'],
            'seven digits' => ['1234567'],
            'too long' => ['123456789'],
            'non-numeric' => ['12345A'],
            'whitespace' => ['123456 '],
        ];
    }
}
