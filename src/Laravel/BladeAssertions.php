<?php

namespace Astrotomic\PhpunitAssertions\Laravel;

use Gajus\Dindent\Indenter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use PHPUnit\Framework\Assert as PHPUnit;

final class BladeAssertions
{
    public static function assertRenderEquals(string $expected, string $template, array $data = []): void
    {
        $indenter = new Indenter();

        PHPUnit::assertSame(
            $indenter->indent($expected),
            $indenter->indent((string) static::render($template, $data))
        );
    }

    protected static function render(string $template, array $data = []): string
    {
        $tempDirectory = sys_get_temp_dir();

        if (! in_array($tempDirectory, View::getFinder()->getPaths())) {
            View::addLocation($tempDirectory);
        }

        $tempFile = tempnam($tempDirectory, 'laravel-blade').'.blade.php';

        file_put_contents($tempFile, $template);

        return View::make(Str::before(basename($tempFile), '.blade.php'), $data)->render();
    }
}
