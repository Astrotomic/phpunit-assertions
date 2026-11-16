<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\IbanAssertions;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class IbanAssertionsTest extends TestCase
{
    #[Test]
    #[DataProvider('validIbanFormats')]
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

    #[Test]
    #[DataProvider('invalidIbanFormats')]
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

    #[Test]
    #[DataProvider('validIbans')]
    public function it_can_validate_iban_checksums_without_a_country_registry(string $iban): void
    {
        IbanAssertions::assertChecksum($iban);
    }

    #[Test]
    public function it_rejects_an_invalid_iban_checksum_without_a_country_registry(): void
    {
        $this->expectException(AssertionFailedError::class);

        IbanAssertions::assertChecksum('DE88370400440532013000');
    }

    #[Test]
    public function a_valid_checksum_does_not_imply_country_specific_validity(): void
    {
        IbanAssertions::assertChecksum('AA36AAAAAAAAAAA');

        $this->expectException(AssertionFailedError::class);

        IbanAssertions::assertValid('AA36AAAAAAAAAAA');
    }

    #[Test]
    #[DataProvider('validIbans')]
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

    #[Test]
    public function it_rejects_an_iban_with_an_invalid_checksum(): void
    {
        $this->expectException(AssertionFailedError::class);

        IbanAssertions::assertValid('DE88370400440532013000');
    }

    #[Test]
    public function it_rejects_an_iban_with_an_invalid_country_length(): void
    {
        $this->expectException(AssertionFailedError::class);

        IbanAssertions::assertValid('DE813704004405320130000');
    }
}
