<?php

namespace Astrotomic\PhpunitAssertions\Tests\Laravel;

use Astrotomic\PhpunitAssertions\Tests\Utils\Randomize;
use Astrotomic\PhpunitAssertions\Tests\Utils\Repeatable;
use Illuminate\Config\Repository as ConficContract;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use Randomize, Repeatable;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(ConficContract::class)->set('database.default', 'testing');
        $this->app->make(ConficContract::class)->set('database.connections.testing.database', ':memory:');
    }
}
