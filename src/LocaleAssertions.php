<?php

namespace Astrotomic\PhpunitAssertions;

use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\AssertionFailedError;
use Symfony\Component\Intl\Languages;

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

    private const BCP47_PATTERN = '/^(?<language>[A-Za-z]{2,8})(?<extlangs>(?:-[A-Za-z]{3}){0,3})(?:-(?<script>[A-Za-z]{4}))?(?:-(?<region>[A-Za-z]{2}|[0-9]{3}))?(?<variants>(?:-(?:[A-Za-z0-9]{5,8}|[0-9][A-Za-z0-9]{3}))*)(?<extensions>(?:-[0-9A-WY-Za-wy-z](?:-[A-Za-z0-9]{2,8})+)*)(?<privateuse>(?:-[xX](?:-[A-Za-z0-9]{1,8})+)?)\z/';

    public static function assertBcp47($actual): void
    {
        self::assertRegisteredComponents(self::bcp47Components($actual));
    }

    public static function assertIcu($actual): void
    {
        self::assertRegisteredComponents(self::icuComponents($actual));
    }

    public static function assertValid($actual): void
    {
        self::validComponents($actual);
    }

    public static function assertLanguage(string $expected, $actual): void
    {
        $components = self::validComponents($actual);
        $expected = strtolower($expected);

        PHPUnit::assertMatchesRegularExpression(
            '/^(?:root|[a-z]{2,8})\z/',
            $expected,
            'Failed asserting that the expected locale language has a valid structure.'
        );
        PHPUnit::assertSame(
            $expected,
            $components['language'],
            sprintf('Failed asserting that the locale language is "%s".', $expected)
        );
    }

    public static function assertRegion(string $expected, $actual): void
    {
        $components = self::validComponents($actual);
        $expected = ctype_alpha($expected) ? strtoupper($expected) : $expected;

        PHPUnit::assertMatchesRegularExpression(
            '/^(?:[A-Z]{2}|[0-9]{3})\z/',
            $expected,
            'Failed asserting that the expected locale region has a valid structure.'
        );
        PHPUnit::assertSame(
            $expected,
            $components['region'],
            sprintf('Failed asserting that the locale region is "%s".', $expected)
        );
    }

    public static function assertScript(string $expected, $actual): void
    {
        $components = self::validComponents($actual);
        $expected = ucfirst(strtolower($expected));

        PHPUnit::assertMatchesRegularExpression(
            '/^[A-Z][a-z]{3}\z/',
            $expected,
            'Failed asserting that the expected locale script has a valid structure.'
        );
        PHPUnit::assertSame(
            $expected,
            $components['script'],
            sprintf('Failed asserting that the locale script is "%s".', $expected)
        );
    }

    /**
     * @return array{language: ?string, extlangs: list<string>, script: ?string, region: ?string}
     */
    private static function validComponents($actual): array
    {
        try {
            $components = self::bcp47Components($actual);
            self::assertRegisteredComponents($components);

            return $components;
        } catch (AssertionFailedError $exception) {
        }

        try {
            $components = self::icuComponents($actual);
            self::assertRegisteredComponents($components);

            return $components;
        } catch (AssertionFailedError $exception) {
        }

        PHPUnit::fail(
            'Failed asserting that the value is a valid BCP 47 or ICU locale.'
        );
    }

    /**
     * @return array{language: ?string, extlangs: list<string>, script: ?string, region: ?string}
     */
    private static function bcp47Components($actual): array
    {
        PHPUnit::assertIsString($actual);

        if (
            in_array(strtolower($actual), self::GRANDFATHERED, true)
            || preg_match('/^[xX](?:-[A-Za-z0-9]{1,8})+\z/', $actual) === 1
        ) {
            return ['language' => null, 'extlangs' => [], 'script' => null, 'region' => null];
        }

        $matches = [];
        PHPUnit::assertSame(
            1,
            preg_match(self::BCP47_PATTERN, $actual, $matches, PREG_UNMATCHED_AS_NULL),
            sprintf('Failed asserting that "%s" has a valid BCP 47 structure.', $actual)
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

        return [
            'language' => strtolower($matches['language']),
            'extlangs' => $matches['extlangs'] === ''
                ? []
                : array_map('strtolower', explode('-', ltrim($matches['extlangs'], '-'))),
            'script' => $matches['script'] === null ? null : ucfirst(strtolower($matches['script'])),
            'region' => self::normalizeRegion($matches['region']),
        ];
    }

    /**
     * @return array{language: string, extlangs: list<string>, script: ?string, region: ?string}
     */
    private static function icuComponents($actual): array
    {
        PHPUnit::assertIsString($actual);

        $sections = explode('@', $actual);
        PHPUnit::assertLessThanOrEqual(
            2,
            count($sections),
            sprintf('Failed asserting that "%s" has a valid ICU locale structure.', $actual)
        );

        $base = $sections[0];
        PHPUnit::assertMatchesRegularExpression(
            '/^[A-Za-z0-9_-]+\z/',
            $base,
            sprintf('Failed asserting that "%s" has a valid ICU locale base name.', $actual)
        );

        $subtags = explode('_', str_replace('-', '_', $base));
        $language = strtolower(array_shift($subtags));
        PHPUnit::assertMatchesRegularExpression(
            '/^(?:root|[a-z]{2,3})\z/',
            $language,
            sprintf('Failed asserting that "%s" has a valid ICU language component.', $actual)
        );

        $script = null;
        $region = null;

        if (isset($subtags[0]) && preg_match('/^[A-Za-z]{4}\z/', $subtags[0]) === 1) {
            $script = ucfirst(strtolower(array_shift($subtags)));
        }

        if (isset($subtags[0]) && preg_match('/^(?:[A-Za-z]{2}|[0-9]{3})\z/', $subtags[0]) === 1) {
            $region = self::normalizeRegion(array_shift($subtags));
        } elseif (($subtags[0] ?? null) === '') {
            array_shift($subtags);
        }

        foreach ($subtags as $variant) {
            PHPUnit::assertMatchesRegularExpression(
                '/^[A-Za-z0-9]+\z/',
                $variant,
                sprintf('Failed asserting that "%s" has valid ICU variant components.', $actual)
            );
        }

        if (isset($sections[1])) {
            self::assertIcuKeywords($sections[1], $actual);
        }

        return ['language' => $language, 'extlangs' => [], 'script' => $script, 'region' => $region];
    }

    /**
     * @param  array{language: ?string, extlangs: list<string>, script: ?string, region: ?string}  $components
     */
    private static function assertRegisteredComponents(array $components): void
    {
        if ($components['language'] !== null) {
            self::assertRegisteredLanguage($components['language']);
        }

        foreach ($components['extlangs'] as $extlang) {
            self::assertRegisteredLanguage($extlang);
        }

        if ($components['script'] !== null && ! self::isPrivateScript($components['script'])) {
            ScriptAssertions::assertAlpha4($components['script']);
        }

        if ($components['region'] === null || self::isPrivateRegion($components['region'])) {
            return;
        }

        if (ctype_digit($components['region'])) {
            self::assertRegisteredNumericRegion($components['region']);

            return;
        }

        CountryAssertions::assertAlpha2($components['region']);
    }

    private static function assertRegisteredLanguage(string $language): void
    {
        if (
            in_array($language, ['root', 'und'], true)
            || (strlen($language) === 3 && $language >= 'qaa' && $language <= 'qtz')
        ) {
            return;
        }

        if (strlen($language) === 2) {
            LanguageAssertions::assertAlpha2($language);

            return;
        }

        if (strlen($language) === 3) {
            if (class_exists(Languages::class) && Languages::alpha3CodeExists($language)) {
                return;
            }

            LanguageAssertions::assertIso6392T($language);

            return;
        }

        PHPUnit::fail(
            sprintf('No installed language provider can validate BCP 47 language subtag "%s".', $language)
        );
    }

    private static function assertRegisteredNumericRegion(string $region): void
    {
        try {
            CountryAssertions::assertNumeric($region);

            return;
        } catch (AssertionFailedError $exception) {
        }

        if (class_exists(\Locale::class)) {
            $name = \Locale::getDisplayRegion('und_'.$region, 'en');

            PHPUnit::assertIsString($name);
            PHPUnit::assertNotSame(
                '',
                $name,
                sprintf('Failed asserting that "%s" is a valid UN M.49 region code.', $region)
            );
            PHPUnit::assertNotSame(
                $region,
                $name,
                sprintf('Failed asserting that "%s" is a valid UN M.49 region code.', $region)
            );

            return;
        }

        PHPUnit::fail(
            sprintf('Failed asserting that "%s" is a valid ISO 3166-1 country or UN M.49 region code.', $region)
        );
    }

    private static function isPrivateScript(string $script): bool
    {
        return $script >= 'Qaaa' && $script <= 'Qabx';
    }

    private static function isPrivateRegion(string $region): bool
    {
        return $region === 'AA'
            || ($region >= 'QM' && $region <= 'QZ')
            || ($region >= 'XA' && $region <= 'XZ')
            || $region === 'ZZ';
    }

    private static function normalizeRegion(?string $region): ?string
    {
        return $region === null
            ? null
            : (ctype_alpha($region) ? strtoupper($region) : $region);
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
                '/^(?<name>[A-Za-z][A-Za-z0-9_-]*)=(?<value>[A-Za-z0-9_-]+)\z/',
                $keyword,
                sprintf('Failed asserting that "%s" has valid ICU keyword assignments.', $actual)
            );

            preg_match('/^(?<name>[A-Za-z][A-Za-z0-9_-]*)=/', $keyword, $matches);
            $names[] = strtolower($matches['name']);
        }

        PHPUnit::assertSame(
            count($names),
            count(array_unique($names)),
            sprintf('Failed asserting that "%s" has no duplicate ICU keywords.', $actual)
        );
    }
}
