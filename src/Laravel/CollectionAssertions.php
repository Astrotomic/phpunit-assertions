<?php

namespace Astrotomic\PhpunitAssertions\Laravel;

use Illuminate\Support\Enumerable;
use PHPUnit\Framework\Assert as PHPUnit;

trait CollectionAssertions
{
    public static function assertContains($expected, $actual): void
    {
        PHPUnit::assertInstanceOf(Enumerable::class, $actual);
        PHPUnit::assertTrue($actual->contains($expected));
    }
}
