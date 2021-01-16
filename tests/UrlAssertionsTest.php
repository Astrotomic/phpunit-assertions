<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\UrlAssertions;

final class UrlAssertionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_loose(): void
    {
        UrlAssertions::assertValidLoose('https://'.self::randomString().'.com');
    }

    /**
     * @test
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

        UrlAssertions::assertSameScheme($scheme, $scheme.'://'.self::randomString().'.com');
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_host(): void
    {
        $host = self::randomString().'.com';

        UrlAssertions::assertSameHost($host, 'https://'.$host);
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_path(): void
    {
        $path = '/'.self::randomString().'/'.self::randomString();

        UrlAssertions::assertSamePath($path, 'https://'.self::randomString().'.com'.$path);
    }
}
