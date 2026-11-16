<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

final class IpAddressAssertions
{
    public static function assertValid($actual): void
    {
        self::assertValidWithFlags($actual, 0, 'IP address');
    }

    public static function assertV4($actual): void
    {
        self::assertValidWithFlags($actual, FILTER_FLAG_IPV4, 'IPv4 address');
    }

    public static function assertV6($actual): void
    {
        self::assertValidWithFlags($actual, FILTER_FLAG_IPV6, 'IPv6 address');
    }

    private static function assertValidWithFlags($actual, int $flags, string $type): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertNotFalse(
            filter_var($actual, FILTER_VALIDATE_IP, $flags),
            sprintf('Failed asserting that "%s" is a valid %s.', $actual, $type)
        );
    }
}
