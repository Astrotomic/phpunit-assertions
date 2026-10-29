<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\UlidAssertions;
use PHPUnit\Framework\AssertionFailedError;

final class UlidAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider validUlids
     */
    public function it_can_validate_ulids(string $ulid): void
    {
        UlidAssertions::assertUlid($ulid);
    }

    public static function validUlids(): array
    {
        return [
            'minimum value' => ['00000000000000000000000000'],
            'maximum value' => ['7ZZZZZZZZZZZZZZZZZZZZZZZZZ'],
            'representative value' => ['01ARZ3NDEKTSV4RRFFQ69G5FAV'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider invalidUlids
     */
    public function it_rejects_invalid_ulids($ulid): void
    {
        $this->expectException(AssertionFailedError::class);

        UlidAssertions::assertUlid($ulid);
    }

    public static function invalidUlids(): array
    {
        return [
            'not a string' => [123],
            'too short' => ['01ARZ3NDEKTSV4RRFFQ69G5FA'],
            'too long' => ['01ARZ3NDEKTSV4RRFFQ69G5FAV0'],
            'lowercase' => ['01arz3ndektsv4rrffq69g5fav'],
            'excluded character' => ['01ARZ3NDEKTSV4RRFFQ69G5FAI'],
            'out of range' => ['8ZZZZZZZZZZZZZZZZZZZZZZZZZ'],
            'trailing newline' => ["01ARZ3NDEKTSV4RRFFQ69G5FAV\n"],
            'trailing whitespace' => ['01ARZ3NDEKTSV4RRFFQ69G5FAV '],
        ];
    }
}
