<?php

namespace Astrotomic\PhpunitAssertions\Laravel;

use Astrotomic\PhpunitAssertions\HashidAssertions as BaseHashidAssertions;
use Vinkla\Hashids\Facades\Hashids;

final class HashidAssertions
{
    public static function assertHashIds($actual, ?int $count = null, ?string $connection = null): void
    {
        $config = Hashids::getConnectionConfig($connection);

        BaseHashidAssertions::assertHashIds(
            $actual,
            $count,
            $config['salt'] ?? null,
            $config['length'] ?? 0,
            $config['alphabet'] ?? null
        );
    }

    public static function assertHashId($actual, ?string $connection = null): void
    {
        self::assertHashIds($actual, 1, $connection);
    }
}
