<?php

namespace Astrotomic\PhpunitAssertions\Tests\Laravel;

use Astrotomic\PhpunitAssertions\Laravel\CollectionAssertions;
use Illuminate\Support\Collection;

final class CollectionAssertionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider hundredTimes
     */
    public function it_can_validate_contains(): void
    {
        $value = static::randomString(8);

        $collection = new Collection([
            $value,
            self::randomString(),
            self::randomString(),
            self::randomString(),
            self::randomString(),
        ]);

        CollectionAssertions::assertContains($value, $collection);
    }
}
