<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\LocaleAssertions;
use PHPUnit\Framework\AssertionFailedError;

final class LocaleAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider validLanguageTagsWithoutScripts
     */
    public function it_can_validate_bcp47_language_tags_without_scripts(string $actual): void
    {
        LocaleAssertions::assertBcp47($actual);
    }

    public static function validLanguageTagsWithoutScripts(): array
    {
        return [
            'language' => ['de'],
            'language and region' => ['de-DE'],
            'case insensitive' => ['DE-de'],
            'undetermined language' => ['und'],
            'ISO 639-2 language' => ['deu-DE'],
            'variant' => ['de-CH-1901'],
            'extension' => ['en-US-u-ca-gregory'],
            'private use suffix' => ['de-DE-x-phonebk'],
            'private use only' => ['x-company'],
            'grandfathered' => ['i-klingon'],
            'numeric region' => ['es-419'],
            'reserved language' => ['qaaa'],
        ];
    }

    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function it_can_validate_a_bcp47_language_tag_with_a_script(): void
    {
        require_once __DIR__.'/Fixtures/CountryProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/LanguageProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/ScriptProviders/SymfonyIntl.php';

        LocaleAssertions::assertBcp47('de-Latn-DE');
        LocaleAssertions::assertBcp47('DE-lATN-de');
        LocaleAssertions::assertBcp47('zh-cmn-Hans-CN');
    }

    /**
     * @test
     *
     * @dataProvider invalidLanguageTags
     */
    public function it_rejects_invalid_bcp47_language_tags($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        LocaleAssertions::assertBcp47($actual);
    }

    public static function invalidLanguageTags(): array
    {
        return [
            'not a string' => [123],
            'empty' => [''],
            'underscore separator' => ['de_DE'],
            'unknown language' => ['zz-DE'],
            'unknown region' => ['de-ZZ'],
            'duplicate variant' => ['sl-rozaj-rozaj'],
            'duplicate extension singleton' => ['en-a-foo-a-bar'],
            'extension without value' => ['en-a'],
            'private use without value' => ['en-x'],
            'extended language after reserved language' => ['qaaa-abc'],
            'unknown extended language' => ['ab-abc-ab'],
            'trailing newline' => ["de-DE\n"],
            'trailing whitespace' => ['de-DE '],
        ];
    }

    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function it_rejects_an_unknown_script_in_a_bcp47_language_tag(): void
    {
        require_once __DIR__.'/Fixtures/CountryProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/LanguageProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/ScriptProviders/SymfonyIntl.php';

        $this->expectException(AssertionFailedError::class);

        LocaleAssertions::assertBcp47('de-Zzzz-DE');
    }

    /**
     * @test
     *
     * @dataProvider validIcuLocaleIdsWithoutScripts
     */
    public function it_can_validate_icu_locale_ids_without_scripts(string $actual): void
    {
        LocaleAssertions::assertIcu($actual);
    }

    public static function validIcuLocaleIdsWithoutScripts(): array
    {
        return [
            'underscore separator' => ['de_DE'],
            'hyphen separator' => ['de-DE'],
            'case insensitive' => ['DE_de'],
            'variant' => ['de_DE_POSIX'],
            'variant without region' => ['de__PHONEBOOK'],
            'keyword' => ['de_DE@currency=EUR'],
            'multiple keywords' => ['de_DE@currency=EUR;calendar=gregorian'],
            'numeric region' => ['es_419'],
            'root locale' => ['root'],
            'undetermined language' => ['und'],
        ];
    }

    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function it_can_validate_an_icu_locale_id_with_a_script(): void
    {
        require_once __DIR__.'/Fixtures/CountryProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/LanguageProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/ScriptProviders/SymfonyIntl.php';

        LocaleAssertions::assertIcu('de_Latn_DE');
        LocaleAssertions::assertIcu('DE-lATN-de');
        LocaleAssertions::assertIcu('zh_Hans_CN');
    }

    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function it_rejects_an_unknown_script_in_an_icu_locale_id(): void
    {
        require_once __DIR__.'/Fixtures/CountryProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/LanguageProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/ScriptProviders/SymfonyIntl.php';

        $this->expectException(AssertionFailedError::class);

        LocaleAssertions::assertIcu('de_Zzzz_DE');
    }

    /**
     * @test
     *
     * @dataProvider invalidIcuLocaleIds
     */
    public function it_rejects_invalid_icu_locale_ids($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        LocaleAssertions::assertIcu($actual);
    }

    public static function invalidIcuLocaleIds(): array
    {
        return [
            'not a string' => [123],
            'empty' => [''],
            'unknown language' => ['zz_DE'],
            'unknown region' => ['de_ZZ'],
            'POSIX encoding suffix' => ['de_DE.UTF-8'],
            'empty keyword list' => ['de_DE@'],
            'keyword without value' => ['de_DE@currency'],
            'duplicate keyword' => ['de_DE@currency=EUR;currency=USD'],
            'multiple keyword separators' => ['de_DE@currency=EUR@calendar=gregorian'],
            'empty variant' => ['de_DE_'],
            'trailing newline' => ["de_DE\n"],
            'trailing whitespace' => ['de_DE '],
        ];
    }

    /**
     * @test
     *
     * @dataProvider validLocales
     */
    public function it_can_accept_either_supported_locale_standard(string $actual): void
    {
        LocaleAssertions::assertValid($actual);
    }

    public static function validLocales(): array
    {
        return [
            'BCP 47' => ['de-DE'],
            'ICU' => ['de_DE'],
            'ICU keyword' => ['de_DE@currency=EUR'],
            'private-use BCP 47' => ['x-company'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider invalidLocales
     */
    public function it_rejects_values_that_match_neither_locale_standard($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        LocaleAssertions::assertValid($actual);
    }

    public static function invalidLocales(): array
    {
        return [
            'not a string' => [123],
            'empty' => [''],
            'unknown components' => ['zz_ZZ'],
            'malformed' => ['not a locale!'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider localeLanguages
     */
    public function it_can_assert_the_language_component(string $expected, string $actual): void
    {
        LocaleAssertions::assertLanguage($expected, $actual);
    }

    public static function localeLanguages(): array
    {
        return [
            'BCP 47' => ['de', 'de-DE'],
            'ICU' => ['de', 'de_DE'],
            'normalized expected value' => ['DE', 'de_DE'],
            'ISO 639-2 language' => ['deu', 'deu_DE'],
            'ICU root locale' => ['root', 'root'],
            'undetermined language' => ['und', 'und'],
            'hyphenated ICU with variants' => ['de', 'de-abc-DE'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider localeCountries
     */
    public function it_can_assert_the_country_component(string $expected, string $actual): void
    {
        LocaleAssertions::assertCountry($expected, $actual);
    }

    public static function localeCountries(): array
    {
        return [
            'BCP 47' => ['DE', 'de-DE'],
            'ICU' => ['DE', 'de_DE'],
            'normalized expected value' => ['de', 'de_DE'],
        ];
    }

    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function it_can_assert_locale_components_with_a_script(): void
    {
        require_once __DIR__.'/Fixtures/CountryProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/LanguageProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/ScriptProviders/SymfonyIntl.php';

        LocaleAssertions::assertLanguage('de', 'de-Latn-DE');
        LocaleAssertions::assertCountry('DE', 'de_Latn_DE');
        LocaleAssertions::assertScript('latn', 'de_Latn_DE');
        LocaleAssertions::assertScript('LATN', 'de-Latn-DE');
    }

    /**
     * @test
     *
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function it_rejects_a_missing_locale_script(): void
    {
        require_once __DIR__.'/Fixtures/CountryProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/LanguageProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/ScriptProviders/SymfonyIntl.php';

        $this->expectException(AssertionFailedError::class);

        LocaleAssertions::assertScript('Latn', 'de_DE');
    }

    /**
     * @test
     *
     * @dataProvider mismatchedLocaleComponents
     */
    public function it_rejects_mismatched_or_missing_locale_components(
        string $component,
        string $expected,
        string $actual
    ): void {
        $this->expectException(AssertionFailedError::class);

        LocaleAssertions::{$component}($expected, $actual);
    }

    public static function mismatchedLocaleComponents(): array
    {
        return [
            'different language' => ['assertLanguage', 'en', 'de_DE'],
            'different country' => ['assertCountry', 'US', 'de_DE'],
            'missing country' => ['assertCountry', 'DE', 'de'],
            'ICU variant is not a country' => ['assertCountry', 'DE', 'de-abc-DE'],
        ];
    }

    /** @test */
    public function it_validates_the_locale_before_asserting_a_component(): void
    {
        $this->expectException(AssertionFailedError::class);

        LocaleAssertions::assertLanguage('de', 'zz_ZZ');
    }
}
