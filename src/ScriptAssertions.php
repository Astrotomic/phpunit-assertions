<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;
use Symfony\Component\Intl\Scripts;

final class ScriptAssertions
{
    /**
     * Registered ISO 15924 codes intentionally excluded from Symfony's script-name data.
     *
     * @see https://www.unicode.org/iso15924/iso15924-codes.html
     */
    private const SYMFONY_EXCLUSIONS = [
        'Zzzz',
    ];

    public static function assertAlpha4($actual): void
    {
        PHPUnit::assertIsString($actual);
        PHPUnit::assertMatchesRegularExpression(
            '/\A[A-Z][a-z]{3}\z/',
            $actual,
            'Failed asserting that the value is a canonical ISO 15924 alpha-4 script code.'
        );

        if (! class_exists(Scripts::class)) {
            PHPUnit::fail('Script assertions require symfony/intl.');
        }

        PHPUnit::assertTrue(
            Scripts::exists($actual) || in_array($actual, self::SYMFONY_EXCLUSIONS, true),
            sprintf('Failed asserting that "%s" is a valid ISO 15924 script code.', $actual)
        );
    }
}
