<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\LocaleAssertions;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\Test;

final class LocaleAssertionsTest extends TestCase
{
    #[Test]
    #[DataProvider('validBcp47Locales')]
    public function it_can_validate_bcp47_locales(string $actual): void
    {
        LocaleAssertions::assertBcp47($actual);
    }

    public static function validBcp47Locales(): array
    {
        return [
            'language' => ['de'],
            'language and region' => ['de-DE'],
            'case insensitive' => ['DE-de'],
            'undetermined language' => ['und'],
            'three-letter language' => ['deu'],
            'reserved components and private use' => ['qaa-Qaaa-QM-x-southern'],
            'variant' => ['de-CH-1901'],
            'extension' => ['en-US-u-ca-gregory'],
            'private use suffix' => ['de-DE-x-phonebk'],
            'private use only' => ['x-company'],
            'grandfathered' => ['i-klingon'],
            'numeric region' => ['es-419'],
            'registered extlang component' => ['en-deu'],
            'multiple registered extlang components' => ['de-deu-deu'],
        ];
    }

    #[Test]
    #[DataProvider('invalidBcp47Locales')]
    public function it_rejects_invalid_bcp47_locales($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        LocaleAssertions::assertBcp47($actual);
    }

    public static function invalidBcp47Locales(): array
    {
        return [
            'not a string' => [123],
            'empty' => [''],
            'underscore separator' => ['de_DE'],
            'duplicate variant' => ['sl-rozaj-rozaj'],
            'duplicate extension singleton' => ['en-a-foo-a-bar'],
            'extension without value' => ['en-a'],
            'private use without value' => ['en-x'],
            'extlang after reserved language' => ['qaaa-abc'],
            'unregistered language' => ['zz-DE'],
            'unregistered country' => ['de-AB'],
            'unvalidated long language' => ['qaaa'],
            'trailing newline' => ["de-DE\n"],
            'trailing whitespace' => ['de-DE '],
        ];
    }

    #[Test]
    #[DataProvider('validIcuLocales')]
    public function it_can_validate_icu_locales(string $actual): void
    {
        LocaleAssertions::assertIcu($actual);
    }

    public static function validIcuLocales(): array
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
            'reserved components' => ['qaa_Qaaa_QM'],
        ];
    }

    #[Test]
    #[DataProvider('invalidIcuLocales')]
    public function it_rejects_invalid_icu_locales($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        LocaleAssertions::assertIcu($actual);
    }

    public static function invalidIcuLocales(): array
    {
        return [
            'not a string' => [123],
            'empty' => [''],
            'short language' => ['a_DE'],
            'long language' => ['language_DE'],
            'POSIX encoding suffix' => ['de_DE.UTF-8'],
            'empty keyword list' => ['de_DE@'],
            'keyword without value' => ['de_DE@currency'],
            'duplicate keyword' => ['de_DE@currency=EUR;currency=USD'],
            'multiple keyword separators' => ['de_DE@currency=EUR@calendar=gregorian'],
            'empty variant' => ['de_DE_'],
            'unregistered components' => ['zz_ZZ'],
            'trailing newline' => ["de_DE\n"],
            'trailing whitespace' => ['de_DE '],
        ];
    }

    #[Test]
    #[DataProvider('validLocales')]
    public function it_can_accept_either_supported_locale(string $actual): void
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
            'reserved BCP 47 components' => ['qaa-Qaaa-QM-x-southern'],
        ];
    }

    #[Test]
    #[DataProvider('invalidLocales')]
    public function it_rejects_values_that_are_neither_valid_locale_form($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        LocaleAssertions::assertValid($actual);
    }

    public static function invalidLocales(): array
    {
        return [
            'not a string' => [123],
            'empty' => [''],
            'malformed' => ['not a locale!'],
            'short language' => ['a_ZZ'],
            'unregistered components' => ['zz_ZZ'],
        ];
    }

    #[Test]
    #[DataProvider('localeLanguages')]
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
            'three-letter language' => ['deu', 'deu-US'],
            'reserved language' => ['QAA', 'qaa-Qaaa-QM-x-southern'],
            'ICU root locale' => ['root', 'root'],
            'undetermined language' => ['und', 'und'],
        ];
    }

    #[Test]
    #[DataProvider('localeRegions')]
    public function it_can_assert_the_region_component(string $expected, string $actual): void
    {
        LocaleAssertions::assertRegion($expected, $actual);
    }

    public static function localeRegions(): array
    {
        return [
            'BCP 47 country region' => ['DE', 'de-DE'],
            'ICU country region' => ['DE', 'de_DE'],
            'normalized expected value' => ['de', 'de_DE'],
            'numeric macro-region' => ['419', 'es-419'],
            'reserved region' => ['qm', 'qaa-Qaaa-QM-x-southern'],
        ];
    }

    #[Test]
    #[DataProvider('localeScripts')]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_can_assert_the_script_component(string $expected, string $actual): void
    {
        require_once __DIR__.'/Fixtures/ScriptProviders/SymfonyIntl.php';

        LocaleAssertions::assertScript($expected, $actual);
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_validates_every_component_with_detected_providers(): void
    {
        require_once __DIR__.'/Fixtures/LanguageProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/CountryProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/ScriptProviders/SymfonyIntl.php';

        LocaleAssertions::assertBcp47('zh-cmn-Hans-CN');
        LocaleAssertions::assertIcu('zh_Hans_CN');
    }

    #[Test]
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function it_rejects_an_unregistered_script(): void
    {
        require_once __DIR__.'/Fixtures/LanguageProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/CountryProviders/SymfonyIntl.php';
        require_once __DIR__.'/Fixtures/ScriptProviders/SymfonyIntl.php';

        $this->expectException(AssertionFailedError::class);

        LocaleAssertions::assertBcp47('de-Abcd-DE');
    }

    public static function localeScripts(): array
    {
        return [
            'BCP 47' => ['latn', 'de-Latn-DE'],
            'ICU' => ['LATN', 'de_Latn_DE'],
            'reserved script' => ['qaaa', 'qaa-Qaaa-QM-x-southern'],
        ];
    }

    #[Test]
    #[DataProvider('mismatchedLocaleComponents')]
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
            'different region' => ['assertRegion', 'US', 'de_DE'],
            'missing region' => ['assertRegion', 'DE', 'de'],
            'ICU variant is not a region' => ['assertRegion', 'DE', 'de__PHONEBOOK'],
            'missing script' => ['assertScript', 'Latn', 'de_DE'],
        ];
    }

    #[Test]
    #[DataProvider('invalidComponentLocales')]
    public function it_validates_the_complete_locale_before_asserting_a_component(string $actual): void
    {
        $this->expectException(AssertionFailedError::class);

        LocaleAssertions::assertLanguage('de', $actual);
    }

    public static function invalidComponentLocales(): array
    {
        return [
            'malformed' => ['not a locale!'],
            'unregistered language' => ['zz_DE'],
            'unregistered country' => ['de_AB'],
        ];
    }
}
