<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\HashidAssertions;
use Hashids\Hashids;

final class HashidAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_hashid(): void
    {
        $salt = self::randomString();
        $length = self::randomInt(0, 32);
        $hashid = new Hashids($salt, $length);

        HashidAssertions::assertHashId(
            $hashid->encode(self::randomInt(0)),
            $salt,
            $length
        );
    }

    /**
     * @test
     *
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_hashids(): void
    {
        $salt = self::randomString();
        $length = self::randomInt(0, 32);
        $hashid = new Hashids($salt, $length);

        $ids = array_map(fn () => self::randomInt(0), range(0, self::randomInt(2, 20)));

        HashidAssertions::assertHashIds(
            $hashid->encode($ids),
            count($ids),
            $salt,
            $length
        );
    }
}
