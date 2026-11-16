<?php

namespace Astrotomic\PhpunitAssertions\Tests\Laravel;

use Astrotomic\PhpunitAssertions\Laravel\CollectionAssertions;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class CollectionAssertionsTest extends TestCase
{
    #[Test]
    #[DataProvider('hundredTimes')]
    public function it_can_validate_contains(): void
    {
        $value = self::randomString(8);

        $collection = new Collection([
            $value,
            self::randomString(),
            self::randomString(),
            self::randomString(),
            self::randomString(),
        ]);

        CollectionAssertions::assertContains($collection, $value);
    }
}
