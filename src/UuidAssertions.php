<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;
use Ramsey\Uuid\Uuid;

trait UuidAssertions
{
    public static function assertUuid($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertTrue(Uuid::isValid($actual));
    }
}