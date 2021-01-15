<?php

namespace Astrotomic\PhpunitAssertions\Tests;

use Astrotomic\PhpunitAssertions\PhoneNumberAssertions;

final class PhoneNumberAssertionsTest extends TestCase
{
    /**
     * @test
     * @dataProvider thousandTimes
     */
    public static function it_can_validate_e164(): void
    {
        PhoneNumberAssertions::assertE164(preg_replace('/([^\d+])/', '', self::randomPhoneNumber()['number']));
    }

    /**
     * @test
     * @dataProvider thousandTimes
     */
    public static function it_can_validate_phone_number(): void
    {
        PhoneNumberAssertions::assertValid(self::randomPhoneNumber()['number']);
    }

    /**
     * @test
     * @dataProvider thousandTimes
     */
    public static function it_can_validate_phone_number_for_region(): void
    {
        $number = self::randomPhoneNumber();
        PhoneNumberAssertions::assertValidForRegion($number['code'], $number['number']);
    }

    protected static function randomPhoneNumber(): array
    {
        return self::randomElement([
            // https://shaun.net/resources/test-phone-numbers/
            ['number' => '+61 2 9192 0995', 'code' => 'AU'],
            ['number' => '+64 9 887 6986', 'code' => 'NZ'],
            ['number' => '+61 1800 801 920', 'code' => 'AU'],
            ['number' => '+44 20 8759 9036', 'code' => 'GB'],
            ['number' => '+1 800 444 4444', 'code' => 'US'],
            ['number' => '+1 213 621 0002', 'code' => 'US'],
            ['number' => '+1 914 232 9901', 'code' => 'US'],
            // https://howtophoneto.com/test/
            ['number' => '+61 2 91011948', 'code' => 'AU'],
            ['number' => '+61 02 8335 4600', 'code' => 'AU'],
            ['number' => '+61 2 8229 4333', 'code' => 'AU'],
            ['number' => '+61 3 8641 9083', 'code' => 'AU'],
            ['number' => '+61 3 9683 9999', 'code' => 'AU'],
            ['number' => '+61 (02) 9293 9262', 'code' => 'AU'],
            ['number' => '(+61 2) 9293 9270', 'code' => 'AU'],
            ['number' => '+61 1300 368999', 'code' => 'AU'],
            ['number' => '+61 03 9640 0999', 'code' => 'AU'],
            ['number' => '+64 (9) 379-0861', 'code' => 'NZ'],
            ['number' => '+64-9-977-2232', 'code' => 'NZ'],
            ['number' => '+64 9 977 2237', 'code' => 'NZ'],
            ['number' => '+64 4 924 2424', 'code' => 'NZ'],
            ['number' => '+64 4 470 3142', 'code' => 'NZ'],
            ['number' => '+64 4 473 11 33', 'code' => 'NZ'],
            ['number' => '+64 4 915 6666', 'code' => 'NZ'],
            ['number' => '+64 0508 500 499', 'code' => 'NZ'],
            ['number' => '+672 3 22147', 'code' => 'NF'],
            ['number' => '+675 327 3396', 'code' => 'PG'],
            ['number' => '(+675) 320 1212', 'code' => 'PG'],
            ['number' => '(+675) 322 0888', 'code' => 'PG'],
            // https://www.zamic.com/page/5d916f06cb9ed42094
            ['number' => '+1 628 246 2222', 'code' => 'US'],
            ['number' => '+1 202 762 1401', 'code' => 'US'],
            // https://www.placetel.de/ratgeber/testrufnummer
            ['number' => '+49 89 721010 99701', 'code' => 'DE'],
            ['number' => '+49 89 721010 99702', 'code' => 'DE'],
            ['number' => '+49 89 721010 99703', 'code' => 'DE'],
            // https://www.smartphonevergleich.de/telefonpaul/
            ['number' => '+49 30-58583772', 'code' => 'DE'],
            ['number' => '+49 176-34636276', 'code' => 'DE'],
            // https://servicenummern.telekom.de/weitere-informationen/zeitansage/
            ['number' => '+49 180 4 100 100', 'code' => 'DE'],
            ['number' => '+49 800 330 0800', 'code' => 'DE'],
        ]);
    }
}