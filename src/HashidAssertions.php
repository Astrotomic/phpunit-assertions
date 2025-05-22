<?php

namespace Astrotomic\PhpunitAssertions;

use Hashids\Hashids;
use PHPUnit\Framework\Assert as PHPUnit;

final class HashidAssertions
{
    public static function assertHashIds(
        $actual,
        ?int $count = null,
        ?string $salt = null,
        int $minHashLength = 0,
        ?string $alphabet = null
    ): void {
        PHPUnit::assertIsString($actual);

        $hashid = new Hashids(
            $salt ?? '',
            $minHashLength,
            $alphabet ?? 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
        );

        $ids = $hashid->decode($actual);
        PHPUnit::assertIsArray($ids);

        if ($count !== null) {
            PHPUnit::assertCount($count, $ids);
        }

        foreach ($ids as $id) {
            PHPUnit::assertIsInt($id);
            PHPUnit::assertGreaterThanOrEqual(0, $id);
        }
    }

    public static function assertHashId(
        $actual,
        ?string $salt = null,
        int $minHashLength = 0,
        ?string $alphabet = null
    ): void {
        self::assertHashIds($actual, 1, $salt, $minHashLength, $alphabet);
    }
}
