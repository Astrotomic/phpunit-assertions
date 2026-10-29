<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\IbanAssertions;
use PHPUnit\Framework\AssertionFailedError;

final class IbanAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider validIbanFormats
     */
    public function it_can_validate_iban_format_without_checking_the_checksum(string $iban): void
    {
        IbanAssertions::assertFormat($iban);
    }

    public static function validIbanFormats(): array
    {
        return [
            'valid checksum' => ['DE89370400440532013000'],
            'invalid checksum' => ['DE88370400440532013000'],
            'minimum length' => ['AA00AAAAAAAAAAA'],
            'maximum length' => ['AA00'.str_repeat('A', 30)],
        ];
    }

    /**
     * @test
     *
     * @dataProvider invalidIbanFormats
     */
    public function it_rejects_invalid_iban_formats($iban): void
    {
        $this->expectException(AssertionFailedError::class);

        IbanAssertions::assertFormat($iban);
    }

    public static function invalidIbanFormats(): array
    {
        return [
            'not a string' => [123],
            'lowercase' => ['de89370400440532013000'],
            'spaces' => ['DE89 3704 0044 0532 0130 00'],
            'non-alphanumeric' => ['DE89-370400440532013000'],
            'too short' => ['DE001234567890'],
            'too long' => ['DE00'.str_repeat('1', 31)],
            'non-numeric checksum' => ['DEAA370400440532013000'],
            'trailing newline' => ["DE89370400440532013000\n"],
            'trailing whitespace' => ['DE89370400440532013000 '],
        ];
    }

    /**
     * @test
     *
     * @dataProvider validIbans
     */
    public function it_can_validate_ibans(string $iban): void
    {
        IbanAssertions::assertValid($iban);
    }

    public static function validIbans(): array
    {
        return [
            'Germany' => ['DE89370400440532013000'],
            'United Kingdom' => ['GB82WEST12345698765432'],
            'Netherlands' => ['NL91ABNA0417164300'],
            'Malta' => ['MT84MALT011000012345MTLCAST001S'],
        ];
    }

    /** @test */
    public function it_rejects_an_iban_with_an_invalid_checksum(): void
    {
        $this->expectException(AssertionFailedError::class);

        IbanAssertions::assertValid('DE88370400440532013000');
    }

    /** @test */
    public function it_rejects_an_iban_with_an_invalid_country_length(): void
    {
        $this->expectException(AssertionFailedError::class);

        IbanAssertions::assertValid('DE813704004405320130000');
    }
}
