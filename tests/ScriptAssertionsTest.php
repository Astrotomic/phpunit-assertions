<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\ScriptAssertions;
use Composer\Autoload\ClassLoader;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;

final class ScriptAssertionsTest extends TestCase
{
    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_uses_symfony_intl(): void
    {
        require_once __DIR__.'/Fixtures/ScriptProviders/SymfonyIntl.php';

        ScriptAssertions::assertAlpha4('Cyrl');
        ScriptAssertions::assertAlpha4('Hans');
        ScriptAssertions::assertAlpha4('Latn');
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_accepts_registered_codes_excluded_by_symfony(): void
    {
        require_once __DIR__.'/Fixtures/ScriptProviders/SymfonyIntl.php';

        ScriptAssertions::assertAlpha4('Zzzz');
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_rejects_an_unregistered_script(): void
    {
        require_once __DIR__.'/Fixtures/ScriptProviders/SymfonyIntl.php';

        $this->expectException(AssertionFailedError::class);

        ScriptAssertions::assertAlpha4('Abcd');
    }

    #[Test]
    #[DataProvider('malformedScripts')]
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

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_requires_symfony_intl(): void
    {
        self::assertIsString('preload PHPUnit constraints');
        self::assertMatchesRegularExpression('/\A.+\z/', 'value');
        self::assertTrue(true);
        class_exists(AssertionFailedError::class);

        $loaders = ClassLoader::getRegisteredLoaders();

        foreach ($loaders as $loader) {
            $loader->unregister();
        }

        try {
            require_once __DIR__.'/../src/ScriptAssertions.php';

            $this->expectException(AssertionFailedError::class);
            $this->expectExceptionMessage('symfony/intl');

            ScriptAssertions::assertAlpha4('Latn');
        } finally {
            foreach ($loaders as $loader) {
                $loader->register(true);
            }
        }
    }
}
