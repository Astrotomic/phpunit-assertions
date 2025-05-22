<?php

namespace Astrotomic\PhpunitAssertions\Tests\Laravel;

use Astrotomic\PhpunitAssertions\Laravel\BladeAssertions;

final class BladeAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public function it_can_validate_render(): void
    {
        $price = random_int(0, 10000) / 100;

        $string = number_format($price, 2);

        BladeAssertions::assertRenderEquals(
            "<p>Price: <code>{$string} €</code></p>",
            '<p>Price: <code>{{ number_format($price, 2) }} €</code></p>',
            ['price' => $price]
        );
    }
}
