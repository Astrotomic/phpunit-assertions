<?php

namespace Astrotomic\PhpunitAssertions\Tests\Laravel;

use Astrotomic\PhpunitAssertions\Tests\Utils\Randomize;
use Astrotomic\PhpunitAssertions\Tests\Utils\Repeatable;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use Randomize, Repeatable;
}