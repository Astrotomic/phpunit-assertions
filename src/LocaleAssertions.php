<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\AssertionFailedError;

final class LocaleAssertions
{
    private const GRANDFATHERED = [
        'art-lojban',
        'cel-gaulish',
        'en-gb-oed',
        'i-ami',
        'i-bnn',
        'i-default',
        'i-enochian',
        'i-hak',
        'i-klingon',
        'i-lux',
        'i-mingo',
        'i-navajo',
        'i-pwn',
        'i-tao',
        'i-tay',
        'i-tsu',
        'no-bok',
        'no-nyn',
        'sgn-be-fr',
        'sgn-be-nl',
        'sgn-ch-de',
        'zh-guoyu',
        'zh-hakka',
        'zh-min',
        'zh-min-nan',
        'zh-xiang',
    ];

    private const LANGUAGE_TAG_PATTERN = '/^(?<language>[A-Za-z]{2,8})(?<extlangs>(?:-[A-Za-z]{3}){0,3})(?:-(?<script>[A-Za-z]{4}))?(?:-(?<region>[A-Za-z]{2}|[0-9]{3}))?(?<variants>(?:-(?:[A-Za-z0-9]{5,8}|[0-9][A-Za-z0-9]{3}))*)(?<extensions>(?:-[0-9A-WY-Za-wy-z](?:-[A-Za-z0-9]{2,8})+)*)(?<privateuse>(?:-[xX](?:-[A-Za-z0-9]{1,8})+)?)$/D';

    public static function assertBcp47($actual): void
    {
        PHPUnit::assertIsString($actual);

        if (
            in_array(strtolower($actual), self::GRANDFATHERED, true)
            || preg_match('/^[xX](?:-[A-Za-z0-9]{1,8})+$/D', $actual) === 1
        ) {
            return;
        }

        $matches = [];
        PHPUnit::assertSame(
            1,
            preg_match(self::LANGUAGE_TAG_PATTERN, $actual, $matches, PREG_UNMATCHED_AS_NULL),
            sprintf('Failed asserting that "%s" is a well-formed BCP 47 language tag.', $actual)
        );

        PHPUnit::assertTrue(
            strlen($matches['language']) <= 3 || $matches['extlangs'] === '',
            sprintf('Failed asserting that "%s" has valid extended-language placement.', $actual)
        );

        self::assertUniqueSubtags(
            $matches['variants'],
            '/-([A-Za-z0-9]{5,8}|[0-9][A-Za-z0-9]{3})/',
            'variant',
            $actual
        );
        self::assertUniqueSubtags(
            $matches['extensions'],
            '/-([0-9A-WY-Za-wy-z])(?:-[A-Za-z0-9]{2,8})+/',
            'extension singleton',
            $actual
        );

        $language = strtolower($matches['language']);

        if ($language === 'und') {
            // BCP 47 reserves "und" for an undetermined language.
        } elseif (strlen($language) === 2) {
            LanguageAssertions::assertAlpha2($language);
        } elseif (strlen($language) === 3) {
            LanguageAssertions::assertIso6392T($language);
        }

        if ($matches['extlangs'] !== '') {
            foreach (explode('-', ltrim($matches['extlangs'], '-')) as $extlang) {
                LanguageAssertions::assertIso6392T(strtolower($extlang));
            }
        }

        if ($matches['script'] !== null) {
            ScriptAssertions::assertAlpha4(ucfirst(strtolower($matches['script'])));
        }

        if ($matches['region'] !== null && ctype_alpha($matches['region'])) {
            CountryAssertions::assertAlpha2(strtoupper($matches['region']));
        }
    }

    public static function assertIcu($actual): void
    {
        PHPUnit::assertIsString($actual);

        $sections = explode('@', $actual);
        PHPUnit::assertLessThanOrEqual(
            2,
            count($sections),
            sprintf('Failed asserting that "%s" is a well-formed ICU locale ID.', $actual)
        );

        $base = $sections[0];
        $keywords = $sections[1] ?? null;

        PHPUnit::assertMatchesRegularExpression(
            '/^[A-Za-z0-9_-]+$/D',
            $base,
            sprintf('Failed asserting that "%s" has a valid ICU locale base name.', $actual)
        );

        $subtags = explode('_', str_replace('-', '_', $base));
        $language = array_shift($subtags);

        PHPUnit::assertMatchesRegularExpression(
            '/^(?:root|[A-Za-z]{2,3})$/D',
            $language,
            sprintf('Failed asserting that "%s" has a valid ICU language component.', $actual)
        );

        $normalizedLanguage = strtolower($language);

        if (! in_array($normalizedLanguage, ['root', 'und'], true)) {
            if (strlen($normalizedLanguage) === 2) {
                LanguageAssertions::assertAlpha2($normalizedLanguage);
            } else {
                LanguageAssertions::assertIso6392T($normalizedLanguage);
            }
        }

        if (isset($subtags[0]) && preg_match('/^[A-Za-z]{4}$/D', $subtags[0]) === 1) {
            ScriptAssertions::assertAlpha4(ucfirst(strtolower(array_shift($subtags))));
        }

        if (isset($subtags[0]) && preg_match('/^(?:[A-Za-z]{2}|[0-9]{3})$/D', $subtags[0]) === 1) {
            $region = array_shift($subtags);

            if (ctype_alpha($region)) {
                CountryAssertions::assertAlpha2(strtoupper($region));
            }
        } elseif (($subtags[0] ?? null) === '') {
            array_shift($subtags);
        }

        foreach ($subtags as $variant) {
            PHPUnit::assertMatchesRegularExpression(
                '/^[A-Za-z0-9]+$/D',
                $variant,
                sprintf('Failed asserting that "%s" has valid ICU variant components.', $actual)
            );
        }

        if ($keywords !== null) {
            self::assertIcuKeywords($keywords, $actual);
        }
    }

    public static function assertValid($actual): void
    {
        self::validStandard($actual);
    }

    public static function assertLanguage(string $expected, $actual): void
    {
        $standard = self::validStandard($actual);
        $expected = strtolower($expected);

        PHPUnit::assertMatchesRegularExpression(
            '/^(?:root|[a-z]{2,3})$/D',
            $expected,
            'Failed asserting that the expected locale language is valid.'
        );

        if (! in_array($expected, ['root', 'und'], true)) {
            if (strlen($expected) === 2) {
                LanguageAssertions::assertAlpha2($expected);
            } else {
                LanguageAssertions::assertIso6392T($expected);
            }
        }

        PHPUnit::assertSame(
            $expected,
            self::components($actual, $standard)['language'],
            sprintf('Failed asserting that the locale language is "%s".', $expected)
        );
    }

    public static function assertCountry(string $expected, $actual): void
    {
        $standard = self::validStandard($actual);
        $expected = ctype_alpha($expected) ? strtoupper($expected) : $expected;

        PHPUnit::assertMatchesRegularExpression(
            '/^(?:[A-Z]{2}|[0-9]{3})$/D',
            $expected,
            'Failed asserting that the expected locale country is valid.'
        );

        if (ctype_alpha($expected)) {
            CountryAssertions::assertAlpha2($expected);
        } else {
            CountryAssertions::assertNumeric($expected);
        }

        PHPUnit::assertSame(
            $expected,
            self::components($actual, $standard)['country'],
            sprintf('Failed asserting that the locale country is "%s".', $expected)
        );
    }

    public static function assertScript(string $expected, $actual): void
    {
        $standard = self::validStandard($actual);
        $expected = ucfirst(strtolower($expected));

        ScriptAssertions::assertAlpha4($expected);

        PHPUnit::assertSame(
            $expected,
            self::components($actual, $standard)['script'],
            sprintf('Failed asserting that the locale script is "%s".', $expected)
        );
    }

    private static function validStandard($actual): string
    {
        try {
            self::assertBcp47($actual);

            return 'bcp47';
        } catch (AssertionFailedError $exception) {
        }

        try {
            self::assertIcu($actual);

            return 'icu';
        } catch (AssertionFailedError $exception) {
        }

        PHPUnit::fail(
            'Failed asserting that the value is a valid BCP 47 language tag or ICU locale ID.'
        );
    }

    /**
     * @return array{language: ?string, script: ?string, country: ?string}
     */
    private static function components(string $actual, string $standard): array
    {
        return $standard === 'bcp47'
            ? self::bcp47Components($actual)
            : self::icuComponents($actual);
    }

    /**
     * @return array{language: ?string, script: ?string, country: ?string}
     */
    private static function bcp47Components(string $actual): array
    {
        if (
            in_array(strtolower($actual), self::GRANDFATHERED, true)
            || preg_match('/^[xX](?:-[A-Za-z0-9]{1,8})+$/D', $actual) === 1
        ) {
            return ['language' => null, 'script' => null, 'country' => null];
        }

        preg_match(self::LANGUAGE_TAG_PATTERN, $actual, $matches, PREG_UNMATCHED_AS_NULL);

        return [
            'language' => strtolower($matches['language']),
            'script' => $matches['script'] === null ? null : ucfirst(strtolower($matches['script'])),
            'country' => $matches['region'] === null
                ? null
                : (ctype_alpha($matches['region']) ? strtoupper($matches['region']) : $matches['region']),
        ];
    }

    /**
     * @return array{language: ?string, script: ?string, country: ?string}
     */
    private static function icuComponents(string $actual): array
    {
        $base = explode('@', $actual, 2)[0];
        $subtags = explode('_', str_replace('-', '_', $base));
        $language = strtolower(array_shift($subtags));
        $script = null;
        $country = null;

        if (isset($subtags[0]) && preg_match('/^[A-Za-z]{4}$/D', $subtags[0]) === 1) {
            $script = ucfirst(strtolower(array_shift($subtags)));
        }

        if (isset($subtags[0]) && preg_match('/^(?:[A-Za-z]{2}|[0-9]{3})$/D', $subtags[0]) === 1) {
            $region = array_shift($subtags);
            $country = ctype_alpha($region) ? strtoupper($region) : $region;
        }

        return compact('language', 'script', 'country');
    }

    private static function assertUniqueSubtags(
        ?string $value,
        string $pattern,
        string $description,
        string $actual
    ): void {
        preg_match_all($pattern, $value ?? '', $matches);
        $subtags = array_map('strtolower', $matches[1]);

        PHPUnit::assertSame(
            count($subtags),
            count(array_unique($subtags)),
            sprintf('Failed asserting that "%s" has no duplicate %s subtags.', $actual, $description)
        );
    }

    private static function assertIcuKeywords(string $keywords, string $actual): void
    {
        PHPUnit::assertNotSame(
            '',
            $keywords,
            sprintf('Failed asserting that "%s" has a nonempty ICU keyword list.', $actual)
        );

        $names = [];

        foreach (explode(';', $keywords) as $keyword) {
            PHPUnit::assertMatchesRegularExpression(
                '/^(?<name>[A-Za-z][A-Za-z0-9_-]*)=(?<value>[A-Za-z0-9_-]+)$/D',
                $keyword,
                sprintf('Failed asserting that "%s" has valid ICU keyword assignments.', $actual)
            );

            preg_match(
                '/^(?<name>[A-Za-z][A-Za-z0-9_-]*)=(?<value>[A-Za-z0-9_-]+)$/D',
                $keyword,
                $matches
            );
            $names[] = strtolower($matches['name']);
        }

        PHPUnit::assertSame(
            count($names),
            count(array_unique($names)),
            sprintf('Failed asserting that "%s" has no duplicate ICU keywords.', $actual)
        );
    }
}
