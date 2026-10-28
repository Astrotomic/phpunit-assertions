<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\AsnAssertions;
use PHPUnit\Framework\AssertionFailedError;

final class AsnAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider validAsns
     */
    public function it_can_validate_asns($actual): void
    {
        AsnAssertions::assertValid($actual);
    }

    public static function validAsns(): array
    {
        return [
            'zero' => ['0'],
            '16-bit ASN' => ['13335'],
            '32-bit ASN' => ['65536'],
            'maximum' => ['4294967295'],
            'uppercase prefix' => ['AS13335'],
            'lowercase prefix' => ['as13335'],
            'integer' => [13335],
        ];
    }

    /**
     * @test
     *
     * @dataProvider invalidAsns
     */
    public function it_rejects_invalid_asns($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        AsnAssertions::assertValid($actual);
    }

    public static function invalidAsns(): array
    {
        return [
            'null' => [null],
            'boolean' => [true],
            'float' => [13335.0],
            'negative integer' => [-1],
            'negative string' => ['-1'],
            'positive sign' => ['+13335'],
            'empty' => [''],
            'prefix only' => ['AS'],
            'whitespace' => [' 13335 '],
            'leading zero' => ['013335'],
            'asdot notation' => ['1.10'],
            'decimal' => ['13335.0'],
            'greater than unsigned 32-bit' => ['4294967296'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider privateAsns
     */
    public function it_can_validate_private_asns($actual): void
    {
        AsnAssertions::assertPrivate($actual);
    }

    public static function privateAsns(): array
    {
        return [
            '16-bit lower boundary' => ['64512'],
            '16-bit upper boundary' => ['AS65534'],
            '32-bit lower boundary' => ['4200000000'],
            '32-bit upper boundary' => ['4294967294'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider nonPrivateAsns
     */
    public function it_rejects_non_private_asns($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        AsnAssertions::assertPrivate($actual);
    }

    public static function nonPrivateAsns(): array
    {
        return [
            'below 16-bit range' => ['64511'],
            'above 16-bit range' => ['65535'],
            'below 32-bit range' => ['4199999999'],
            'above 32-bit range' => ['4294967295'],
            'public' => ['13335'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider documentationAsns
     */
    public function it_can_validate_documentation_asns($actual): void
    {
        AsnAssertions::assertDocumentation($actual);
    }

    public static function documentationAsns(): array
    {
        return [
            '16-bit lower boundary' => ['64496'],
            '16-bit upper boundary' => ['AS64511'],
            '32-bit lower boundary' => ['65536'],
            '32-bit upper boundary' => ['65551'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider nonDocumentationAsns
     */
    public function it_rejects_non_documentation_asns($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        AsnAssertions::assertDocumentation($actual);
    }

    public static function nonDocumentationAsns(): array
    {
        return [
            'below 16-bit range' => ['64495'],
            'above 16-bit range' => ['64512'],
            'below 32-bit range' => ['65535'],
            'above 32-bit range' => ['65552'],
            'public' => ['13335'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider publicAsns
     */
    public function it_can_validate_public_asns($actual): void
    {
        AsnAssertions::assertPublic($actual);
    }

    public static function publicAsns(): array
    {
        return [
            '16-bit' => ['13335'],
            'special-purpose public ASN' => ['AS112'],
            '32-bit public space' => ['4199999999'],
            'integer' => [13335],
        ];
    }

    /**
     * @test
     *
     * @dataProvider nonPublicAsns
     */
    public function it_rejects_non_public_asns($actual): void
    {
        $this->expectException(AssertionFailedError::class);

        AsnAssertions::assertPublic($actual);
    }

    public static function nonPublicAsns(): array
    {
        return [
            'zero' => ['0'],
            'AS_TRANS' => ['23456'],
            'documentation' => ['64496'],
            'private' => ['64512'],
            '16-bit reserved' => ['65535'],
            '32-bit private' => ['4200000000'],
            'maximum reserved' => ['4294967295'],
        ];
    }
}
