<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;
use Symfony\Component\Intl\Scripts;

final class ScriptAssertions
{
    public static function assertAlpha4($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            '/^[A-Z][a-z]{3}\z/',
            $actual,
            'Failed asserting that the value is a canonical ISO 15924 alpha-4 script code.'
        );

        if (! class_exists(Scripts::class)) {
            PHPUnit::fail('Script assertions require symfony/intl.');
        }

        PHPUnit::assertTrue(
            Scripts::exists($actual),
            sprintf('Failed asserting that "%s" is a valid ISO 15924 script code.', $actual)
        );
    }
}
