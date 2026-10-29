<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\IpAddressAssertions;
use PHPUnit\Framework\AssertionFailedError;

final class IpAddressAssertionsTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider validIpAddresses
     */
    public function it_can_validate_ip_addresses(string $address): void
    {
        IpAddressAssertions::assertValid($address);
    }

    public static function validIpAddresses(): array
    {
        return [
            'IPv4' => ['192.0.2.1'],
            'full IPv6' => ['2001:db8:0:0:0:0:0:1'],
            'compressed IPv6' => ['2001:db8::1'],
        ];
    }

    /** @test */
    public function it_can_validate_ipv4_addresses(): void
    {
        IpAddressAssertions::assertV4('203.0.113.42');
    }

    /** @test */
    public function it_can_validate_ipv6_addresses(): void
    {
        IpAddressAssertions::assertV6('2001:db8::42');
    }

    /**
     * @test
     *
     * @dataProvider invalidIpAddresses
     */
    public function it_rejects_invalid_ip_addresses($address): void
    {
        $this->expectException(AssertionFailedError::class);

        IpAddressAssertions::assertValid($address);
    }

    public static function invalidIpAddresses(): array
    {
        return [
            'not a string' => [123],
            'IPv4 octet out of range' => ['192.0.2.256'],
            'IPv4 with leading zeroes' => ['192.168.001.1'],
            'invalid IPv6 compression' => ['2001:db8:::1'],
            'hostname' => ['example.com'],
        ];
    }

    /** @test */
    public function it_rejects_ipv6_for_ipv4_assertion(): void
    {
        $this->expectException(AssertionFailedError::class);

        IpAddressAssertions::assertV4('2001:db8::1');
    }

    /** @test */
    public function it_rejects_ipv4_for_ipv6_assertion(): void
    {
        $this->expectException(AssertionFailedError::class);

        IpAddressAssertions::assertV6('192.0.2.1');
    }
}
