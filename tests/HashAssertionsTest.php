<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\HashAssertions;
use PHPUnit\Framework\AssertionFailedError;

final class HashAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider md5Digests
     */
    public function it_can_validate_md5_digests(string $digest): void
    {
        HashAssertions::assertMd5($digest);
    }

    public static function md5Digests(): array
    {
        return [
            'lowercase' => [hash('md5', 'phpunit-assertions')],
            'uppercase' => [strtoupper(hash('md5', 'phpunit-assertions'))],
        ];
    }

    /**
     * @test
     *
     * @dataProvider invalidMd5Digests
     */
    public function it_rejects_invalid_md5_digests($digest): void
    {
        $this->expectException(AssertionFailedError::class);

        HashAssertions::assertMd5($digest);
    }

    public static function invalidMd5Digests(): array
    {
        return [
            'not a string' => [123],
            'too short' => [str_repeat('a', 31)],
            'too long' => [str_repeat('a', 33)],
            'not hexadecimal' => [str_repeat('g', 32)],
        ];
    }

    /**
     * @test
     *
     * @dataProvider sha256Digests
     */
    public function it_can_validate_sha256_digests(string $digest): void
    {
        HashAssertions::assertSha256($digest);
    }

    public static function sha256Digests(): array
    {
        return [
            'lowercase' => [hash('sha256', 'phpunit-assertions')],
            'uppercase' => [strtoupper(hash('sha256', 'phpunit-assertions'))],
        ];
    }

    /**
     * @test
     *
     * @dataProvider invalidSha256Digests
     */
    public function it_rejects_invalid_sha256_digests($digest): void
    {
        $this->expectException(AssertionFailedError::class);

        HashAssertions::assertSha256($digest);
    }

    public static function invalidSha256Digests(): array
    {
        return [
            'not a string' => [123],
            'too short' => [str_repeat('a', 63)],
            'too long' => [str_repeat('a', 65)],
            'not hexadecimal' => [str_repeat('g', 64)],
        ];
    }

    /**
     * @test
     *
     * @dataProvider matchingDigests
     */
    public function it_can_match_content_to_a_digest(string $algorithm, string $content, string $digest): void
    {
        HashAssertions::assertMatches($algorithm, $content, $digest);
    }

    public static function matchingDigests(): array
    {
        return [
            'MD5' => ['md5', 'phpunit-assertions', hash('md5', 'phpunit-assertions')],
            'SHA-256' => ['sha256', 'phpunit-assertions', hash('sha256', 'phpunit-assertions')],
            'uppercase SHA-256' => ['sha256', 'phpunit-assertions', strtoupper(hash('sha256', 'phpunit-assertions'))],
            'SHA-1' => ['sha1', 'phpunit-assertions', hash('sha1', 'phpunit-assertions')],
        ];
    }

    /** @test */
    public function it_rejects_a_digest_for_different_content(): void
    {
        $this->expectException(AssertionFailedError::class);

        HashAssertions::assertMatches('sha256', 'actual content', hash('sha256', 'different content'));
    }

    /** @test */
    public function it_rejects_an_unsupported_hashing_algorithm(): void
    {
        $this->expectException(AssertionFailedError::class);

        HashAssertions::assertMatches('not-an-algorithm', 'content', 'digest');
    }

    /**
     * @test
     *
     * @dataProvider matchingHmacs
     */
    public function it_can_match_hmac_signatures(
        string $algorithm,
        string $content,
        string $key,
        string $signature,
        string $encoding = 'hex'
    ): void {
        HashAssertions::assertHmacMatches($algorithm, $content, $key, $signature, $encoding);
    }

    public static function matchingHmacs(): array
    {
        $sha256 = hash_hmac('sha256', 'phpunit-assertions', 'secret', true);

        return [
            'hexadecimal' => ['sha256', 'phpunit-assertions', 'secret', bin2hex($sha256)],
            'uppercase hexadecimal' => ['sha256', 'phpunit-assertions', 'secret', strtoupper(bin2hex($sha256))],
            'base64' => ['sha256', 'phpunit-assertions', 'secret', base64_encode($sha256), 'base64'],
            'base64url' => [
                'sha256',
                'phpunit-assertions',
                'secret',
                rtrim(strtr(base64_encode($sha256), '+/', '-_'), '='),
                'base64url',
            ],
            'binary' => ['sha256', 'phpunit-assertions', 'secret', $sha256, 'binary'],
            'SHA-512' => [
                'sha512',
                'phpunit-assertions',
                'secret',
                hash_hmac('sha512', 'phpunit-assertions', 'secret'),
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider nonMatchingHmacs
     */
    public function it_rejects_non_matching_hmac_signatures(
        string $content,
        string $key,
        $signature
    ): void {
        $this->expectException(AssertionFailedError::class);

        HashAssertions::assertHmacMatches('sha256', $content, $key, $signature);
    }

    public static function nonMatchingHmacs(): array
    {
        return [
            'different content' => ['actual content', 'secret', hash_hmac('sha256', 'different content', 'secret')],
            'different key' => ['content', 'actual key', hash_hmac('sha256', 'content', 'different key')],
            'invalid signature' => ['content', 'secret', str_repeat('0', 64)],
            'not a string' => ['content', 'secret', 123],
        ];
    }

    /** @test */
    public function it_rejects_an_unsupported_hmac_algorithm(): void
    {
        $this->expectException(AssertionFailedError::class);

        HashAssertions::assertHmacMatches('not-an-algorithm', 'content', 'secret', 'signature');
    }

    /** @test */
    public function it_rejects_an_unsupported_hmac_encoding(): void
    {
        $this->expectException(AssertionFailedError::class);

        HashAssertions::assertHmacMatches('sha256', 'content', 'secret', 'signature', 'rot13');
    }
}
