<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\UuidAssertions;
use Ramsey\Uuid\Uuid;

final class UuidAssertionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_uuid_v1(): void
    {
        $uuid = Uuid::uuid1();

        UuidAssertions::assertUuid($uuid->toString());
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_uuid_v2(): void
    {
        $uuid = Uuid::uuid2(Uuid::DCE_DOMAIN_PERSON);

        UuidAssertions::assertUuid($uuid->toString());
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_uuid_v3(): void
    {
        $uuid = Uuid::uuid3(Uuid::NAMESPACE_URL, 'https://astrotomic.info');

        UuidAssertions::assertUuid($uuid->toString());
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_uuid_v4(): void
    {
        $uuid = Uuid::uuid4();

        UuidAssertions::assertUuid($uuid->toString());
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_uuid_v5(): void
    {
        $uuid = Uuid::uuid5(Uuid::NAMESPACE_URL, 'https://astrotomic.info');

        UuidAssertions::assertUuid($uuid->toString());
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_uuid_v6(): void
    {
        $uuid = Uuid::uuid6();

        UuidAssertions::assertUuid($uuid->toString());
    }
}
