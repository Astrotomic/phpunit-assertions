<?php

namespace Astrotomic\PhpunitAssertions;

use Iban\Validation\Iban;
use Iban\Validation\Validator;
use PHPUnit\Framework\Assert as PHPUnit;

final class IbanAssertions
{
    public static function assertFormat($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            '/\A[A-Z]{2}[0-9]{2}[A-Z0-9]{11,30}\z/',
            $actual,
            'Failed asserting that the value has a valid electronic IBAN format.'
        );
    }

    public static function assertChecksum($actual): void
    {
        self::assertFormat($actual);

        $rearranged = substr($actual, 4).substr($actual, 0, 4);

        PHPUnit::assertSame(
            1,
            Modulo97::remainder($rearranged),
            'Failed asserting that the value has a valid IBAN checksum.'
        );
    }

    public static function assertValid($actual): void
    {
        self::assertChecksum($actual);

        PHPUnit::assertTrue(
            class_exists(Validator::class),
            'IBAN validity assertions require jschaedl/iban-validation.'
        );
        PHPUnit::assertTrue(
            (new Validator)->validate(new Iban($actual)),
            'Failed asserting that the value is a valid IBAN.'
        );
    }
}
