<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\ScriptAssertions;
use PHPUnit\Framework\AssertionFailedError;

final class ScriptAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function it_uses_symfony_intl(): void
    {
        require_once __DIR__.'/Fixtures/ScriptProviders/SymfonyIntl.php';

        ScriptAssertions::assertAlpha4('Cyrl');
        ScriptAssertions::assertAlpha4('Hans');
        ScriptAssertions::assertAlpha4('Latn');
    }

    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function it_rejects_an_unknown_script(): void
    {
        require_once __DIR__.'/Fixtures/ScriptProviders/SymfonyIntl.php';

        $this->expectException(AssertionFailedError::class);

        ScriptAssertions::assertAlpha4('Zzzz');
    }

    /**
     * @test
     *
     * @dataProvider malformedScripts
     */
    public function it_rejects_a_malformed_script($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        ScriptAssertions::assertAlpha4($actual);
    }

    public static function malformedScripts(): array
    {
        return [
            'not a string' => [123],
            'too short' => ['Lat'],
            'too long' => ['Latnn'],
            'lowercase' => ['latn'],
            'uppercase' => ['LATN'],
            'numeric' => ['1234'],
            'trailing newline' => ["Latn\n"],
            'trailing whitespace' => ['Latn '],
        ];
    }

    /** @test */
    public function it_requires_symfony_intl(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('symfony/intl');

        ScriptAssertions::assertAlpha4('Latn');
    }
}
