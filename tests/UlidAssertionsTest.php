<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\UlidAssertions;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class UlidAssertionsTest extends TestCase
{
    #[Test]
    #[DataProvider('validUlids')]
    public function it_can_validate_ulids(string $ulid): void
    {
        UlidAssertions::assertUlid($ulid);
    }

    public static function validUlids(): array
    {
        return [
            'minimum value' => ['00000000000000000000000000'],
            'maximum value' => ['7ZZZZZZZZZZZZZZZZZZZZZZZZZ'],
            'lowercase maximum value' => ['7zzzzzzzzzzzzzzzzzzzzzzzzz'],
            'representative value' => ['01ARZ3NDEKTSV4RRFFQ69G5FAV'],
            'lowercase value' => ['01arz3ndektsv4rrffq69g5fav'],
            'mixed-case value' => ['01aRz3NdeKTSv4rRfFq69g5FaV'],
        ];
    }

    #[Test]
    #[DataProvider('invalidUlids')]
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
            'excluded I' => ['01ARZ3NDEKTSV4RRFFQ69G5FAI'],
            'excluded lowercase L' => ['01arz3ndektsv4rrffq69g5fal'],
            'excluded O' => ['01ARZ3NDEKTSV4RRFFQ69G5FAO'],
            'excluded lowercase U' => ['01arz3ndektsv4rrffq69g5fau'],
            'out of range' => ['8ZZZZZZZZZZZZZZZZZZZZZZZZZ'],
            'trailing newline' => ["01ARZ3NDEKTSV4RRFFQ69G5FAV\n"],
            'trailing whitespace' => ['01ARZ3NDEKTSV4RRFFQ69G5FAV '],
        ];
    }

    #[Test]
    #[DataProvider('canonicalUlids')]
    public function it_can_validate_canonically_formatted_ulids(string $ulid): void
    {
        UlidAssertions::assertCanonicalUlid($ulid);
    }

    public static function canonicalUlids(): array
    {
        return [
            'minimum value' => ['00000000000000000000000000'],
            'maximum value' => ['7ZZZZZZZZZZZZZZZZZZZZZZZZZ'],
            'representative value' => ['01ARZ3NDEKTSV4RRFFQ69G5FAV'],
        ];
    }

    #[Test]
    #[DataProvider('nonCanonicalUlids')]
    public function it_rejects_non_canonical_ulids($ulid): void
    {
        $this->expectException(AssertionFailedError::class);

        UlidAssertions::assertCanonicalUlid($ulid);
    }

    public static function nonCanonicalUlids(): array
    {
        return [
            'lowercase value' => ['01arz3ndektsv4rrffq69g5fav'],
            'mixed-case value' => ['01aRz3NdeKTSv4rRfFq69g5FaV'],
            'invalid value' => ['8ZZZZZZZZZZZZZZZZZZZZZZZZZ'],
            'trailing newline' => ["01ARZ3NDEKTSV4RRFFQ69G5FAV\n"],
        ];
    }
}
