<?php

namespace Astrotomic\PhpunitAssertions;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;
use PHPUnit\Framework\Assert as PHPUnit;
use Throwable;

final class PhoneNumberAssertions
{
    public static function assertE164($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression('/^\+[1-9]\d{1,14}$/', $actual);
        StringLengthAssertions::assertLessThanOrEqual(16, $actual); // plus-sign and max. 15 digits incl. CC-prefix
    }

    public static function assertValid($actual): void
    {
        PHPUnit::assertTrue(
            PhoneNumberUtil::getInstance()->isValidNumber(
                self::getPhoneNumber($actual)
            )
        );
    }

    public static function assertValidForRegion($actual, string $regionCode): void
    {
        PHPUnit::assertTrue(
            PhoneNumberUtil::getInstance()->isValidNumberForRegion(
                self::getPhoneNumber($actual),
                $regionCode
            ),
            $regionCode.' // '.$actual
        );
    }

    protected static function getPhoneNumber($actual): PhoneNumber
    {
        if (is_string($actual)) {
            try {
                $actual = PhoneNumberUtil::getInstance()->parse($actual);
            } catch (Throwable $ex) {
            }
        }

        PHPUnit::assertInstanceOf(PhoneNumber::class, $actual);

        return $actual;
    }
}
