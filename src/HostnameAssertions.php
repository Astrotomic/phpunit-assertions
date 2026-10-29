<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

final class HostnameAssertions
{
    public static function assertValid($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertTrue(
            self::isValid($actual),
            sprintf('Failed asserting that "%s" is a valid ASCII hostname.', $actual)
        );
    }

    public static function assertFqdn($actual): void
    {
        self::assertValid($actual);

        $hostname = rtrim($actual, '.');

        PHPUnit::assertGreaterThanOrEqual(
            2,
            count(explode('.', $hostname)),
            sprintf('Failed asserting that "%s" is a fully qualified hostname.', $actual)
        );
    }

    private static function isValid(string $hostname): bool
    {
        if ($hostname === '') {
            return false;
        }

        if (substr($hostname, -1) === '.') {
            $hostname = substr($hostname, 0, -1);
        }

        if ($hostname === '' || strlen($hostname) > 253) {
            return false;
        }

        foreach (explode('.', $hostname) as $label) {
            if (
                $label === ''
                || strlen($label) > 63
                || preg_match('/^[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\z/', $label) !== 1
            ) {
                return false;
            }
        }

        return true;
    }
}
