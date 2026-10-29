<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;

final class HashAssertions
{
    public static function assertMd5($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            '/^[a-fA-F0-9]{32}\z/',
            $actual,
            'Failed asserting that the value is a valid MD5 digest.'
        );
    }

    public static function assertSha256($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            '/^[a-fA-F0-9]{64}\z/',
            $actual,
            'Failed asserting that the value is a valid SHA-256 digest.'
        );
    }

    public static function assertMatches(string $algorithm, string $content, $actual): void
    {
        PHPUnit::assertContains(
            $algorithm,
            hash_algos(),
            sprintf('Failed asserting that "%s" is a supported hashing algorithm.', $algorithm)
        );
        PHPUnit::assertIsString($actual);
        PHPUnit::assertTrue(
            hash_equals(hash($algorithm, $content), strtolower($actual)),
            sprintf('Failed asserting that the digest matches the content using %s.', $algorithm)
        );
    }

    public static function assertHmacMatches(
        string $algorithm,
        string $content,
        string $key,
        $actual,
        string $encoding = 'hex'
    ): void {
        PHPUnit::assertContains(
            $algorithm,
            hash_hmac_algos(),
            sprintf('Failed asserting that "%s" is a supported HMAC algorithm.', $algorithm)
        );
        PHPUnit::assertContains(
            $encoding,
            ['hex', 'base64', 'base64url', 'binary'],
            sprintf('Failed asserting that "%s" is a supported HMAC encoding.', $encoding)
        );
        PHPUnit::assertIsString($actual);

        $hmac = hash_hmac($algorithm, $content, $key, true);
        $expected = match ($encoding) {
            'hex' => bin2hex($hmac),
            'base64' => base64_encode($hmac),
            'base64url' => rtrim(strtr(base64_encode($hmac), '+/', '-_'), '='),
            'binary' => $hmac,
        };

        if ($encoding === 'hex') {
            $actual = strtolower($actual);
        }

        PHPUnit::assertTrue(
            hash_equals($expected, $actual),
            sprintf(
                'Failed asserting that the HMAC matches the content and key using %s with %s encoding.',
                $algorithm,
                $encoding
            )
        );
    }
}
