<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\HostnameAssertions;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

final class HostnameAssertionsTest extends TestCase
{
    #[Test]
    #[DataProvider('validHostnames')]
    public function it_can_validate_hostnames(string $hostname): void
    {
        HostnameAssertions::assertValid($hostname);
    }

    public static function validHostnames(): array
    {
        return [
            'single label' => ['localhost'],
            'multiple labels' => ['api.example.com'],
            'uppercase labels' => ['API.EXAMPLE.COM'],
            'hyphenated label' => ['phpunit-assertions.example'],
            'absolute form' => ['api.example.com.'],
            'maximum total length' => [
                str_repeat('a', 63).'.'.str_repeat('b', 63).'.'.str_repeat('c', 63).'.'.str_repeat('d', 61),
            ],
        ];
    }

    #[Test]
    #[DataProvider('invalidHostnames')]
    public function it_rejects_invalid_hostnames($hostname): void
    {
        $this->expectException(AssertionFailedError::class);

        HostnameAssertions::assertValid($hostname);
    }

    public static function invalidHostnames(): array
    {
        return [
            'not a string' => [123],
            'empty' => [''],
            'empty label' => ['api..example.com'],
            'leading hyphen' => ['-api.example.com'],
            'trailing hyphen' => ['api-.example.com'],
            'underscore' => ['api_server.example.com'],
            'non-ASCII' => ['münchen.example'],
            'label too long' => [str_repeat('a', 64).'.example'],
            'hostname too long' => [
                str_repeat('a', 63).'.'.str_repeat('b', 63).'.'.str_repeat('c', 63).'.'.str_repeat('d', 63),
            ],
            'multiple trailing dots' => ['api.example.com..'],
            'trailing newline' => ["api.example.com\n"],
            'trailing whitespace' => ['api.example.com '],
        ];
    }

    #[Test]
    #[DataProvider('validFqdns')]
    public function it_can_validate_fully_qualified_hostnames(string $hostname): void
    {
        HostnameAssertions::assertFqdn($hostname);
    }

    public static function validFqdns(): array
    {
        return [
            'relative notation' => ['api.example.com'],
            'absolute notation' => ['api.example.com.'],
        ];
    }

    #[Test]
    #[DataProvider('invalidFqdns')]
    public function it_rejects_invalid_fully_qualified_hostnames($hostname): void
    {
        $this->expectException(AssertionFailedError::class);

        HostnameAssertions::assertFqdn($hostname);
    }

    public static function invalidFqdns(): array
    {
        return [
            'not a string' => [123],
            'single label' => ['localhost'],
            'empty label' => ['api..example.com'],
            'trailing newline' => ["api.example.com\n"],
            'trailing whitespace' => ['api.example.com '],
        ];
    }
}
