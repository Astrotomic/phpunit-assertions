<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\ISO639\ISO639;
use Astrotomic\PhpunitAssertions\LanguageAssertions;
use PHPUnit\Framework\AssertionFailedError;

final class LanguageAssertionsTest extends TestCase
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
        require_once __DIR__.'/Fixtures/LanguageProviders/SymfonyIntl.php';

        LanguageAssertions::assertName('German');
        LanguageAssertions::assertAlpha2('de');
        LanguageAssertions::assertIso6392T('deu');
    }

    /**
     * @test
     *
     * @dataProvider languageName
     */
    public static function it_can_validate_language_by_name(string $actual): void
    {
        LanguageAssertions::assertName($actual);
    }

    /**
     * @test
     *
     * @dataProvider languageAlpha2
     */
    public static function it_can_validate_language_by_alpha2(string $actual): void
    {
        LanguageAssertions::assertAlpha2($actual);
    }

    /**
     * @test
     *
     * @dataProvider languageIso639_2B
     */
    public static function it_can_validate_language_by_iso639_2b(string $actual): void
    {
        LanguageAssertions::assertIso6392B($actual);
    }

    /**
     * @test
     *
     * @dataProvider languageIso639_2T
     */
    public static function it_can_validate_language_by_iso639_2t(string $actual): void
    {
        LanguageAssertions::assertIso6392T($actual);
    }

    /**
     * @test
     *
     * @dataProvider invalidIso639_2Codes
     */
    public function it_rejects_invalid_iso639_2b_codes($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        LanguageAssertions::assertIso6392B($actual);
    }

    /**
     * @test
     *
     * @dataProvider invalidIso639_2Codes
     */
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
