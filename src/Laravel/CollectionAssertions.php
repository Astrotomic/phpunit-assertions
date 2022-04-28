<?php

namespace Astrotomic\PhpunitAssertions\Laravel;

use Illuminate\Support\Enumerable;
use PHPUnit\Framework\Assert as PHPUnit;

final class CollectionAssertions
{
    public static function assertContains(Enumerable $collection, $expected): void
    {
        PHPUnit::assertTrue($collection->contains($expected));
    }
}
