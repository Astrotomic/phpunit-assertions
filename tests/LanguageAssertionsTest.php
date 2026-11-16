<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\ISO639\ISO639;
use Astrotomic\PhpunitAssertions\LanguageAssertions;
use Composer\Autoload\ClassLoader;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;

final class LanguageAssertionsTest extends TestCase
{
    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_falls_back_to_symfony_intl(): void
    {
        self::assertIsString('preload PHPUnit constraints');
        self::assertSame(2, 2);
        self::assertTrue(true);

        $loaders = ClassLoader::getRegisteredLoaders();

        foreach ($loaders as $loader) {
            $loader->unregister();
        }

        try {
            require_once __DIR__.'/Fixtures/LanguageProviders/SymfonyIntl.php';
            require_once __DIR__.'/../src/StringLengthAssertions.php';
            require_once __DIR__.'/../src/LanguageAssertions.php';

            LanguageAssertions::assertName('German');
            LanguageAssertions::assertAlpha2('de');
            LanguageAssertions::assertIso6392T('deu');
        } finally {
            foreach ($loaders as $loader) {
                $loader->register(true);
            }
        }
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_keeps_astrotomic_iso639_as_primary_when_both_are_installed(): void
    {
        require_once __DIR__.'/Fixtures/LanguageProviders/SymfonyIntl.php';

        $this->expectException(AssertionFailedError::class);

        LanguageAssertions::assertAlpha2('zz');
    }

    #[Test]
    #[DataProvider('languageName')]
    public static function it_can_validate_language_by_name(string $actual): void
    {
        LanguageAssertions::assertName($actual);
    }

    #[Test]
    #[DataProvider('languageAlpha2')]
    public static function it_can_validate_language_by_alpha2(string $actual): void
    {
        LanguageAssertions::assertAlpha2($actual);
    }

    #[Test]
    #[DataProvider('languageIso639_2B')]
    public static function it_can_validate_language_by_iso639_2b(string $actual): void
    {
        LanguageAssertions::assertIso6392B($actual);
    }

    #[Test]
    #[DataProvider('languageIso639_2T')]
    public static function it_can_validate_language_by_iso639_2t(string $actual): void
    {
        LanguageAssertions::assertIso6392T($actual);
    }

    #[Test]
    #[DataProvider('invalidIso639_2Codes')]
    public function it_rejects_invalid_iso639_2b_codes($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        LanguageAssertions::assertIso6392B($actual);
    }

    #[Test]
    #[DataProvider('invalidIso639_2Codes')]
    public function it_rejects_invalid_iso639_2t_codes($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        LanguageAssertions::assertIso6392T($actual);
    }

    public static function languageName(): iterable
    {
        return array_map(fn (array $country): array => [$country[ISO639::KEY_NAME]], (new ISO639)->all());
    }

    public static function languageAlpha2(): iterable
    {
        return array_map(fn (array $country): array => [$country[ISO639::KEY_639_1]], (new ISO639)->all());
    }

    public static function languageIso639_2B(): iterable
    {
        return array_map(fn (array $language): array => [$language[ISO639::KEY_639_2B]], (new ISO639)->all());
    }

    public static function languageIso639_2T(): iterable
    {
        return array_map(fn (array $language): array => [$language[ISO639::KEY_639_2T]], (new ISO639)->all());
    }

    public static function invalidIso639_2Codes(): array
    {
        return [
            'not a string' => [123],
            'too short' => ['de'],
            'too long' => ['deut'],
            'unknown' => ['zzz'],
        ];
    }
}
