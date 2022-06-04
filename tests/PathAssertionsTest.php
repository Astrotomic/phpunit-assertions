<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\PathAssertions;

final class PathAssertionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_dirname(): void
    {
        $directory = '/'.self::randomString().'/'.self::randomString();
        $filename = self::randomString();
        $extension = self::randomString(3);

        PathAssertions::assertDirname($directory, $directory.'/'.$filename.'.'.$extension);
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_basename(): void
    {
        $directory = '/'.self::randomString().'/'.self::randomString();
        $filename = self::randomString();
        $extension = self::randomString(3);

        PathAssertions::assertBasename($filename.'.'.$extension, $directory.'/'.$filename.'.'.$extension);
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_filename(): void
    {
        $directory = '/'.self::randomString().'/'.self::randomString();
        $filename = self::randomString();
        $extension = self::randomString(3);

        PathAssertions::assertFilename($filename, $directory.'/'.$filename.'.'.$extension);
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_extension(): void
    {
        $directory = '/'.self::randomString().'/'.self::randomString();
        $filename = self::randomString();
        $extension = self::randomString(3);

        PathAssertions::assertExtension($extension, $directory.'/'.$filename.'.'.$extension);
    }

    /**
     * Detect the OS of the PHP in use and return a path with opposite DIR seperator.
     *
     * When given the rand: true parameter it will sometimes provide mixed results.
     */
    private static function os_agnostic_get_expected_path(bool $rand = false): string
    {
        // Intentionally set the "expected" path to opposite of what should work on the platform.
        if (PHP_OS_FAMILY !== "Windows" || ($rand === true && 1 === self::randomInt(0, 4))) {
            return dirname(__DIR__) . '\tests\Utils';
        }

        return dirname(__DIR__) . '/tests/Utils';
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_os_agnostic_paths(): void
    {
        $expected = static::os_agnostic_get_expected_path(true);
        PathAssertions::assertOsAgnosticPath($expected, realpath(dirname(__DIR__) . '/tests/Utils'));
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_os_gnostic_paths_fail(): void
    {
        $expected = static::os_agnostic_get_expected_path();
        static::assertNotEquals($expected, realpath(dirname(__DIR__) . '/tests/Utils'));
    }
}
