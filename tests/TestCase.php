<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\Tests\Utils\Randomize;
use Astrotomic\PhpunitAssertions\Tests\Utils\Repeatable;
use PHPUnit\Framework\TestCase as PhpunitTestCase;

abstract class TestCase extends PhpunitTestCase
{
    use Randomize, Repeatable;
}