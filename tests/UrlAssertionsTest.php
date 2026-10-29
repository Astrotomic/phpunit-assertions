<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\UrlAssertions;
use PHPUnit\Framework\AssertionFailedError;

final class UrlAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_loose(): void
    {
        UrlAssertions::assertValidLoose('https://'.self::randomString().'.com');
    }

    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_scheme(): void
    {
        $scheme = self::randomElement([
            'http',
            'https',
            'ftp',
            'ssh',
            'file',
            'git',
            'imap',
            'irc',
        ]);

        UrlAssertions::assertScheme($scheme, $scheme.'://'.self::randomString().'.com');
    }

    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_host(): void
    {
        $host = self::randomString().'.com';

        UrlAssertions::assertHost($host, 'https://'.$host);
    }

    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_path(): void
    {
        $path = '/'.self::randomString().'/'.self::randomString();

        UrlAssertions::assertPath($path, 'https://'.self::randomString().'.com'.$path);
    }

    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_query(): void
    {
        $query = [
            self::randomString(2) => self::randomString(),
            self::randomString(2) => self::randomString(),
            self::randomString(2) => self::randomString(),
        ];

        UrlAssertions::assertQuery($query, 'https://'.self::randomString().'.com?'.http_build_query($query));
    }

    /** @test */
    public function it_can_validate_a_query_subset_with_strict_values(): void
    {
        UrlAssertions::assertQueryContains(
            ['format' => 'webp', 'size' => ['width' => '640', 'height' => '480']],
            'https://example.com/image?format=webp&quality=80&size%5Bwidth%5D=640&size%5Bheight%5D=480'
        );
    }

    /** @test */
    public function it_can_validate_a_query_subset_on_a_relative_url(): void
    {
        UrlAssertions::assertQueryContains(['format' => 'webp'], '/image?format=webp&quality=80');
    }

    /** @test */
    public function it_rejects_a_missing_query_parameter(): void
    {
        $this->expectException(AssertionFailedError::class);

        UrlAssertions::assertQueryContains(['format' => 'webp'], 'https://example.com/image?quality=80');
    }

    /** @test */
    public function it_rejects_a_query_parameter_with_a_different_value(): void
    {
        $this->expectException(AssertionFailedError::class);

        UrlAssertions::assertQueryContains(['format' => 'webp'], 'https://example.com/image?format=avif');
    }

    /** @test */
    public function it_compares_query_parameter_values_using_regular_equality(): void
    {
        UrlAssertions::assertQueryContains(['page' => 1], 'https://example.com?page=1');
    }
}
