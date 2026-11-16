<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\EmailAssertions;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class EmailAssertionsTest extends TestCase
{
    #[Test]
    #[DataProvider('hundredTimes')]
    public static function it_can_validate_loose(): void
    {
        EmailAssertions::assertValidLoose(self::randomString().'@email.com');
    }

    #[Test]
    #[DataProvider('hundredTimes')]
    public static function it_can_validate_strict(): void
    {
        EmailAssertions::assertValidStrict(self::randomString().'@email.com');
    }

    #[Test]
    #[DataProvider('hundredTimes')]
    public static function it_can_validate_domain(): void
    {
        EmailAssertions::assertDomain('email.com', self::randomString().'@email.com');
    }

    #[Test]
    #[DataProvider('hundredTimes')]
    public static function it_can_validate_local_part(): void
    {
        $localPart = self::randomString();

        EmailAssertions::assertLocalPart($localPart, $localPart.'@email.com');
    }

    #[Test]
    #[DataProvider('hundredTimes')]
    public static function it_can_validate_plus_mailbox(): void
    {
        $mailbox = self::randomString();
        $alias = self::randomString();
        $email = $mailbox.'+'.$alias.'@email.com';

        EmailAssertions::assertPlusMailbox($mailbox, $email);
    }

    #[Test]
    #[DataProvider('hundredTimes')]
    public static function it_can_validate_plus_alias(): void
    {
        $mailbox = self::randomString();
        $alias = self::randomString();
        $email = $mailbox.'+'.$alias.'@email.com';

        EmailAssertions::assertPlusAlias($alias, $email);
    }
}
