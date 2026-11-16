<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\BankIdentifierAssertions;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class BankIdentifierAssertionsTest extends TestCase
{
    #[Test]
    #[DataProvider('validAbaRoutingNumbers')]
    public function it_can_validate_aba_routing_numbers(string $routingNumber): void
    {
        BankIdentifierAssertions::assertAbaRoutingNumber($routingNumber);
    }

    public static function validAbaRoutingNumbers(): array
    {
        return [
            ['021000021'],
            ['011000015'],
            ['111000025'],
        ];
    }

    #[Test]
    #[DataProvider('invalidAbaRoutingNumbers')]
    public function it_rejects_invalid_aba_routing_numbers($routingNumber): void
    {
        $this->expectException(AssertionFailedError::class);

        BankIdentifierAssertions::assertAbaRoutingNumber($routingNumber);
    }

    public static function invalidAbaRoutingNumbers(): array
    {
        return [
            'not a string' => [21000021],
            'too short' => ['02100002'],
            'non-numeric' => ['02100002A'],
            'reserved prefix' => ['130000006'],
            'invalid checksum' => ['021000022'],
            'trailing newline' => ["021000021\n"],
            'trailing whitespace' => ['021000021 '],
        ];
    }

    #[Test]
    #[DataProvider('validBics')]
    public function it_can_validate_bics(string $bic): void
    {
        BankIdentifierAssertions::assertBic($bic);
    }

    public static function validBics(): array
    {
        return [
            'eight characters' => ['DEUTDEFF'],
            'eleven characters' => ['NEDSZAJJXXX'],
            'numeric business prefix and suffix' => ['1234US33'],
        ];
    }

    #[Test]
    #[DataProvider('invalidBics')]
    public function it_rejects_invalid_bics($bic): void
    {
        $this->expectException(AssertionFailedError::class);

        BankIdentifierAssertions::assertBic($bic);
    }

    public static function invalidBics(): array
    {
        return [
            'not a string' => [123],
            'too short' => ['DEUTDEF'],
            'invalid branch length' => ['DEUTDEFFXX'],
            'numeric country code' => ['DEUT12FF'],
            'lowercase' => ['deutdeff'],
            'special character' => ['DEUTDEF!'],
            'trailing newline' => ["DEUTDEFF\n"],
            'trailing whitespace' => ['DEUTDEFF '],
        ];
    }

    #[Test]
    #[DataProvider('validSwiftBics')]
    public function it_can_validate_swift_bics(string $bic): void
    {
        BankIdentifierAssertions::assertSwiftBic($bic);
    }

    public static function validSwiftBics(): array
    {
        return [
            'eight characters' => ['DEUTDEFF'],
            'eleven characters' => ['NEDSZAJJXXX'],
        ];
    }

    #[Test]
    #[DataProvider('invalidSwiftBics')]
    public function it_rejects_invalid_swift_bics($bic): void
    {
        $this->expectException(AssertionFailedError::class);

        BankIdentifierAssertions::assertSwiftBic($bic);
    }

    public static function invalidSwiftBics(): array
    {
        return [
            'numeric business prefix' => ['1234US33'],
            'invalid general BIC structure' => ['DEUTDEF'],
            'trailing newline' => ["DEUTDEFF\n"],
        ];
    }

    #[Test]
    #[DataProvider('validIsins')]
    public function it_can_validate_isins(string $isin): void
    {
        BankIdentifierAssertions::assertIsin($isin);
    }

    public static function validIsins(): array
    {
        return [
            'Apple' => ['US0378331005'],
            'Roche' => ['CH0012032048'],
            'BAE Systems' => ['GB0002634946'],
        ];
    }

    #[Test]
    #[DataProvider('invalidIsins')]
    public function it_rejects_invalid_isins($isin): void
    {
        $this->expectException(AssertionFailedError::class);

        BankIdentifierAssertions::assertIsin($isin);
    }

    public static function invalidIsins(): array
    {
        return [
            'not a string' => [123],
            'too short' => ['US037833100'],
            'numeric country prefix' => ['120378331005'],
            'non-numeric check digit' => ['US037833100A'],
            'lowercase' => ['us0378331005'],
            'invalid checksum' => ['US0378331006'],
            'trailing newline' => ["US0378331005\n"],
            'trailing whitespace' => ['US0378331005 '],
        ];
    }

    #[Test]
    #[DataProvider('validLeis')]
    public function it_can_validate_leis(string $lei): void
    {
        BankIdentifierAssertions::assertLei($lei);
    }

    public static function validLeis(): array
    {
        return [
            'GLEIF' => ['506700GE1G29325QX363'],
            'Bloomberg' => ['5493001KJTIIGC8Y1R12'],
            'Apple' => ['HWUPKR0MPOU8FGXBT394'],
        ];
    }

    #[Test]
    #[DataProvider('invalidLeis')]
    public function it_rejects_invalid_leis($lei): void
    {
        $this->expectException(AssertionFailedError::class);

        BankIdentifierAssertions::assertLei($lei);
    }

    public static function invalidLeis(): array
    {
        return [
            'not a string' => [123],
            'too short' => ['506700GE1G29325QX36'],
            'non-numeric check digits' => ['506700GE1G29325QX3AA'],
            'lowercase' => ['506700ge1g29325qx363'],
            'invalid checksum' => ['506700GE1G29325QX364'],
            'trailing newline' => ["506700GE1G29325QX363\n"],
            'trailing whitespace' => ['506700GE1G29325QX363 '],
        ];
    }
}
