<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\EmailAssertions;

final class EmailAssertionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_loose(): void
    {
        EmailAssertions::assertValidLoose(self::randomString().'@email.com');
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_strict(): void
    {
        EmailAssertions::assertValidStrict(self::randomString().'@email.com');
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_domain(): void
    {
        EmailAssertions::assertDomain('email.com', self::randomString().'@email.com');
    }

    /**
     * @test
     * @dataProvider hundredTimes
     */
    public static function it_can_validate_local_part(): void
    {
        $localPart = self::randomString();

        EmailAssertions::assertLocalPart($localPart, $localPart.'@email.com');
    }
}
