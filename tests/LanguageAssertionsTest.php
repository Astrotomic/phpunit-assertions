<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\ISO639\ISO639;
use Astrotomic\PhpunitAssertions\LanguageAssertions;

final class LanguageAssertionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider languageName
     */
    public static function it_can_validate_language_by_name(string $actual): void
    {
        LanguageAssertions::assertName($actual);
    }

    /**
     * @test
     * @dataProvider languageAlpha2
     */
    public static function it_can_validate_language_by_alpha2(string $actual): void
    {
        LanguageAssertions::assertAlpha2($actual);
    }

    public function languageName(): iterable
    {
        return array_map(fn (array $country): array => [$country[ISO639::KEY_NAME]], (new ISO639())->all());
    }

    public function languageAlpha2(): iterable
    {
        return array_map(fn (array $country): array => [$country[ISO639::KEY_639_1]], (new ISO639())->all());
    }
}
