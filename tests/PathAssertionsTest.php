<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\PathAssertions;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class PathAssertionsTest extends TestCase
{
    #[Test]
    #[DataProvider('hundredTimes')]
    public static function it_can_validate_dirname(): void
    {
        $directory = '/'.self::randomString().'/'.self::randomString();
        $filename = self::randomString();
        $extension = self::randomString(3);

        PathAssertions::assertDirname($directory, $directory.'/'.$filename.'.'.$extension);
    }

    #[Test]
    #[DataProvider('hundredTimes')]
    public static function it_can_validate_basename(): void
    {
        $directory = '/'.self::randomString().'/'.self::randomString();
        $filename = self::randomString();
        $extension = self::randomString(3);

        PathAssertions::assertBasename($filename.'.'.$extension, $directory.'/'.$filename.'.'.$extension);
    }

    #[Test]
    #[DataProvider('hundredTimes')]
    public static function it_can_validate_filename(): void
    {
        $directory = '/'.self::randomString().'/'.self::randomString();
        $filename = self::randomString();
        $extension = self::randomString(3);

        PathAssertions::assertFilename($filename, $directory.'/'.$filename.'.'.$extension);
    }

    #[Test]
    #[DataProvider('hundredTimes')]
    public static function it_can_validate_extension(): void
    {
        $directory = '/'.self::randomString().'/'.self::randomString();
        $filename = self::randomString();
        $extension = self::randomString(3);

        PathAssertions::assertExtension($extension, $directory.'/'.$filename.'.'.$extension);
    }
}
