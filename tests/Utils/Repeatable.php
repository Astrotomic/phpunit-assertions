<?php

namespace Astrotomic\PhpunitAssertions\Tests\Utils;

trait Repeatable
{
    public function hundredTimes(): iterable
    {
        for ($i = 0; $i < 100; $i++) {
            yield [];
        }
    }

    public function thousandTimes(): iterable
    {
        for ($i = 0; $i < 1000; $i++) {
            yield [];
        }
    }
}
