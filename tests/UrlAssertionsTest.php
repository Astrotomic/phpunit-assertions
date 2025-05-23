<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\UrlAssertions;

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
}
