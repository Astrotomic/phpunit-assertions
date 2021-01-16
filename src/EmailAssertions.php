<?php

namespace Astrotomic\PhpunitAssertions;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use PHPUnit\Framework\Assert as PHPUnit;

trait EmailAssertions
{
    public static function assertValidLoose($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertNotFalse(filter_var($actual, FILTER_VALIDATE_EMAIL));
    }

    public static function assertValidStrict($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertTrue((new EmailValidator())->isValid($actual, new RFCValidation()));
    }

    public static function assertSameDomain(string $expected, $actual): void
    {
        PHPUnit::assertIsString($actual);
        [, $domain] = explode('@', $actual, 2);
        PHPUnit::assertSame($expected, $domain);
    }

    public static function assertSameLocalPart(string $expected, $actual): void
    {
        PHPUnit::assertIsString($actual);
        [$localPart] = explode('@', $actual, 2);
        PHPUnit::assertSame($expected, $localPart);
    }
}
