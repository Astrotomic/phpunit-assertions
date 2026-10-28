# PHPUnit Assertions

[![Latest Version](http://img.shields.io/packagist/v/astrotomic/phpunit-assertions.svg?label=Release&style=for-the-badge)](https://packagist.org/packages/astrotomic/phpunit-assertions)
[![MIT License](https://img.shields.io/github/license/Astrotomic/phpunit-assertions.svg?label=License&color=blue&style=for-the-badge)](https://github.com/Astrotomic/phpunit-assertions/blob/master/LICENSE)
[![Offset Earth](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-green?style=for-the-badge)](https://forest.astrotomic.info)
[![Larabelles](https://img.shields.io/badge/Larabelles-%F0%9F%A6%84-lightpink?style=for-the-badge)](https://larabelles.com)

[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/Astrotomic/phpunit-assertions/phpunit.yml?style=flat-square&logoColor=white&logo=github&label=Tests)](https://github.com/Astrotomic/phpunit-assertions/actions?query=workflow%3Aphpunit)
[![Total Downloads](https://img.shields.io/packagist/dt/astrotomic/phpunit-assertions.svg?label=Downloads&style=flat-square)](https://packagist.org/packages/astrotomic/phpunit-assertions)
[![Trees](https://img.shields.io/ecologi/trees/astrotomic?style=flat-square)](https://forest.astrotomic.info)
[![Carbon](https://img.shields.io/ecologi/carbon/astrotomic?style=flat-square)](https://forest.astrotomic.info)

This package provides a set of common [PHPUnit](https://phpunit.de/) custom assertions.
Some require optional packages - check the `composer.json[suggest]` section for more details.

## Installation

```bash
composer require --dev astrotomic/phpunit-assertions
```

## Usage

Even if all assertions are in `trait`s I highly recommend you to don't `use` these traits in your test classes.
Instead you can access all assertions as static methods on the traits.

```php
\Astrotomic\PhpunitAssertions\StringLengthAssertions::assertSame(10, 'Astrotomic');
```

This will prevent any method name conflicts with core, your custom or other trait assertions.

## Assertions

### Array

```php
\Astrotomic\PhpunitAssertions\ArrayAssertions::assertIndexed(['foo', 'bar']);
\Astrotomic\PhpunitAssertions\ArrayAssertions::assertAssociative(['foo' => 'bar']);
\Astrotomic\PhpunitAssertions\ArrayAssertions::assertEquals(['foo', 'bar'], ['bar', 'foo']);
\Astrotomic\PhpunitAssertions\ArrayAssertions::assertSubset(['foo' => 'bar'], ['baz' => 'foo', 'foo' => 'bar']);
\Astrotomic\PhpunitAssertions\ArrayAssertions::assertContainsAll(['foo', 'bar'], ['baz', 'foo', 'lorem', 'ipsum', 'bar']);
```

### ASN

ASN assertions accept canonical decimal asplain notation with an optional case-insensitive `AS` prefix. `assertPublic()` excludes private-use, documentation, and reserved ASNs, but does not perform an assignment or routing lookup.

```php
\Astrotomic\PhpunitAssertions\AsnAssertions::assertValid('AS13335');
\Astrotomic\PhpunitAssertions\AsnAssertions::assertPrivate('64512');
\Astrotomic\PhpunitAssertions\AsnAssertions::assertDocumentation('64496');
\Astrotomic\PhpunitAssertions\AsnAssertions::assertPublic('13335');
```

### Bank Identifier

These assertions validate identifier structure and checksums. They do not verify that an identifier is registered or active.

```php
\Astrotomic\PhpunitAssertions\BankIdentifierAssertions::assertAbaRoutingNumber('021000021');
\Astrotomic\PhpunitAssertions\BankIdentifierAssertions::assertBic('DEUTDEFF');
\Astrotomic\PhpunitAssertions\BankIdentifierAssertions::assertIsin('US0378331005');
\Astrotomic\PhpunitAssertions\BankIdentifierAssertions::assertLei('506700GE1G29325QX363');
```

### Card BIN

These assertions validate structure only; they do not verify that an issuer has been assigned the identifier. IINs are eight digits. BINs may use the legacy six-digit or current eight-digit form.

```php
\Astrotomic\PhpunitAssertions\CardBinAssertions::assertIin('12345678');
\Astrotomic\PhpunitAssertions\CardBinAssertions::assertBin('123456');
\Astrotomic\PhpunitAssertions\CardBinAssertions::assertBin('12345678');
```

### Country

Install either `league/iso3166` or `symfony/intl`. Numeric country codes require `league/iso3166` or Symfony Intl 6.4 and newer.

```php
\Astrotomic\PhpunitAssertions\CountryAssertions::assertName('Germany');
\Astrotomic\PhpunitAssertions\CountryAssertions::assertAlpha2('DE');
\Astrotomic\PhpunitAssertions\CountryAssertions::assertAlpha3('DEU');
\Astrotomic\PhpunitAssertions\CountryAssertions::assertNumeric('276');
```

### Currency

Install one of `brick/money`, `moneyphp/money`, `alcohol/iso4217`, or `symfony/intl` to provide the ISO 4217 data. If several are installed, they are checked in that order.

```php
\Astrotomic\PhpunitAssertions\CurrencyAssertions::assertIso4217('EUR');
\Astrotomic\PhpunitAssertions\CurrencyAssertions::assertMinorUnits('JPY', 0);
```

### Date

`assertIso8601()` and `assertRfc3339()` accept the RFC 3339 profile of ISO 8601: an extended calendar date and time with seconds, a required `Z` or numeric offset, and optional fractional seconds.

```php
\Astrotomic\PhpunitAssertions\DateAssertions::assertFormat('Y-m-d', '2026-10-28');
\Astrotomic\PhpunitAssertions\DateAssertions::assertIso8601('2026-10-28T18:42:03.123Z');
\Astrotomic\PhpunitAssertions\DateAssertions::assertRfc3339('2026-10-28T18:42:03+01:00');
```

### Email

`composer require --dev egulias/email-validator:^3.0`

```php
\Astrotomic\PhpunitAssertions\EmailAssertions::assertValidLoose('gummibeer@astrotomic.info');
\Astrotomic\PhpunitAssertions\EmailAssertions::assertValidStrict('gummibeer@astrotomic.info');
\Astrotomic\PhpunitAssertions\EmailAssertions::assertDomain('astrotomic.info', 'gummibeer@astrotomic.info');
\Astrotomic\PhpunitAssertions\EmailAssertions::assertLocalPart('gummibeer', 'gummibeer@astrotomic.info');
\Astrotomic\PhpunitAssertions\EmailAssertions::assertPlusMailbox('gummibeer', 'gummibeer+news@astrotomic.info');
\Astrotomic\PhpunitAssertions\EmailAssertions::assertPlusAlias('news', 'gummibeer+news@astrotomic.info');
```

### Geographic

```php
\Astrotomic\PhpunitAssertions\GeographicAssertions::assertLatitude(53.551085);
\Astrotomic\PhpunitAssertions\GeographicAssertions::assertLongitude(9.993682);
\Astrotomic\PhpunitAssertions\GeographicAssertions::assertCoordinates([
    'lat' => 53.551085,
    'lng' => 9.993682,
]);

$hamburg = ['lat' => 53.551085, 'lng' => 9.993682];
$berlin = ['lat' => 52.520008, 'lng' => 13.404954];

\Astrotomic\PhpunitAssertions\GeographicAssertions::assertDistanceGreaterThanOrEqual(255_000, $hamburg, $berlin);
\Astrotomic\PhpunitAssertions\GeographicAssertions::assertDistanceLessThanOrEqual(256_000, $hamburg, $berlin);
```

### Hash

```php
$content = 'phpunit-assertions';
$checksum = hash('sha256', $content);
$signature = hash_hmac('sha256', $content, 'secret');

\Astrotomic\PhpunitAssertions\HashAssertions::assertMd5(hash('md5', $content));
\Astrotomic\PhpunitAssertions\HashAssertions::assertSha256($checksum);
\Astrotomic\PhpunitAssertions\HashAssertions::assertMatches('sha256', $content, $checksum);
\Astrotomic\PhpunitAssertions\HashAssertions::assertHmacMatches('sha256', $content, 'secret', $signature);
```

`assertHmacMatches()` uses hexadecimal signatures by default. Pass `base64`, `base64url`, or `binary` as its fifth argument for another encoding.

### HashID

`composer require --dev hashids/hashids:^4.0`

```php
\Astrotomic\PhpunitAssertions\HashidAssertions::assertHashIds('3kTMd', 2, 'this is my salt');
\Astrotomic\PhpunitAssertions\HashidAssertions::assertHashId('yr8', 'this is my salt');
```

### Hostname

Hostname assertions validate ASCII DNS syntax only; they do not resolve the hostname.

```php
\Astrotomic\PhpunitAssertions\HostnameAssertions::assertValid('localhost');
\Astrotomic\PhpunitAssertions\HostnameAssertions::assertFqdn('api.astrotomic.info');
```

### IP Address

```php
\Astrotomic\PhpunitAssertions\IpAddressAssertions::assertValid('2001:db8::1');
\Astrotomic\PhpunitAssertions\IpAddressAssertions::assertV4('192.0.2.1');
\Astrotomic\PhpunitAssertions\IpAddressAssertions::assertV6('2001:db8::1');
```

### IBAN

`assertFormat()` validates the unspaced electronic representation without checking country-specific rules or the checksum. `assertValid()` requires `jschaedl/iban-validation:^2.0` and uses its SWIFT-registry data.

```php
\Astrotomic\PhpunitAssertions\IbanAssertions::assertFormat('DE89370400440532013000');
\Astrotomic\PhpunitAssertions\IbanAssertions::assertValid('DE89370400440532013000');
```

### Language

Install either `astrotomic/iso639` or `symfony/intl`. ISO 639-2/B bibliographic codes specifically require `astrotomic/iso639`.

```php
\Astrotomic\PhpunitAssertions\LanguageAssertions::assertName('German');
\Astrotomic\PhpunitAssertions\LanguageAssertions::assertAlpha2('de');
\Astrotomic\PhpunitAssertions\LanguageAssertions::assertIso6392B('ger');
\Astrotomic\PhpunitAssertions\LanguageAssertions::assertIso6392T('deu');
```

### Locale

`assertBcp47()` accepts hyphen-separated BCP 47 language tags. `assertIcu()` accepts ICU locale IDs with hyphen or underscore separators and optional variants and keyword assignments. Both assertions validate their language, script, and alphabetic region components against the installed providers. Script subtags require `symfony/intl`.

`assertValid()` is the loose compatibility assertion: it passes when the value is valid in either format. Four-to-eight-letter BCP 47 primary languages, variants, extensions, private-use values, ICU variants, and numeric regions are validated structurally because the installed ISO providers do not cover their complete registries.

The component assertions validate the complete locale first, accept either format, and compare normalized language, country, or script values. Language values are compared lowercase, country values uppercase, and script values in title case.

```php
\Astrotomic\PhpunitAssertions\LocaleAssertions::assertBcp47('de-DE');
\Astrotomic\PhpunitAssertions\LocaleAssertions::assertBcp47('sr-Cyrl-RS');
\Astrotomic\PhpunitAssertions\LocaleAssertions::assertBcp47('en-US-u-ca-gregory');
\Astrotomic\PhpunitAssertions\LocaleAssertions::assertIcu('de_DE');
\Astrotomic\PhpunitAssertions\LocaleAssertions::assertIcu('de_DE@currency=EUR');
\Astrotomic\PhpunitAssertions\LocaleAssertions::assertValid('de-DE');
\Astrotomic\PhpunitAssertions\LocaleAssertions::assertValid('de_DE');
\Astrotomic\PhpunitAssertions\LocaleAssertions::assertLanguage('de', 'de_DE');
\Astrotomic\PhpunitAssertions\LocaleAssertions::assertCountry('DE', 'de-DE');
\Astrotomic\PhpunitAssertions\LocaleAssertions::assertScript('Latn', 'de_Latn_DE');
```

### Nullable Type

```php
\Astrotomic\PhpunitAssertions\NullableTypeAssertions::assertIsNullableString('Astrotomic');
\Astrotomic\PhpunitAssertions\NullableTypeAssertions::assertIsNullableInt(42);
\Astrotomic\PhpunitAssertions\NullableTypeAssertions::assertIsNullableFloat(42.5);
\Astrotomic\PhpunitAssertions\NullableTypeAssertions::assertIsNullableArray(['Astrotomic' => 'Gummibeer']);
\Astrotomic\PhpunitAssertions\NullableTypeAssertions::assertIsNullableBool(true);
```

### Phone Number

`composer require --dev giggsey/libphonenumber-for-php:^8.12`

```php
\Astrotomic\PhpunitAssertions\PhoneNumberAssertions::assertE164('+498001110550');
\Astrotomic\PhpunitAssertions\PhoneNumberAssertions::assertValid('+49 800 - 111 0 550');
\Astrotomic\PhpunitAssertions\PhoneNumberAssertions::assertValidForRegion('+49 800 - 111 0 550', 'DE');
```

### String

```php
\Astrotomic\PhpunitAssertions\StringLengthAssertions::assertSame(10, 'Astrotomic');
\Astrotomic\PhpunitAssertions\StringLengthAssertions::assertNotSame(8, 'Astrotomic');
\Astrotomic\PhpunitAssertions\StringLengthAssertions::assertLessThan(11, 'Astrotomic');
\Astrotomic\PhpunitAssertions\StringLengthAssertions::assertLessThanOrEqual(10, 'Astrotomic');
\Astrotomic\PhpunitAssertions\StringLengthAssertions::assertGreaterThan(9, 'Astrotomic');
\Astrotomic\PhpunitAssertions\StringLengthAssertions::assertGreaterThanOrEqual(10, 'Astrotomic');
```

### Script

`composer require --dev symfony/intl`

```php
\Astrotomic\PhpunitAssertions\ScriptAssertions::assertAlpha4('Latn');
\Astrotomic\PhpunitAssertions\ScriptAssertions::assertAlpha4('Cyrl');
```

### URL

```php
\Astrotomic\PhpunitAssertions\UrlAssertions::assertValidLoose('https://astrotomic.info');
\Astrotomic\PhpunitAssertions\UrlAssertions::assertScheme('https', 'https://astrotomic.info');
\Astrotomic\PhpunitAssertions\UrlAssertions::assertHost('astrotomic.info', 'https://astrotomic.info');
\Astrotomic\PhpunitAssertions\UrlAssertions::assertPath('/contributor/gummibeer/', 'https://astrotomic.info/contributor/gummibeer/');
\Astrotomic\PhpunitAssertions\UrlAssertions::assertQuery(['_' => '123', 'q' => 'search'], 'https://astrotomic.info?q=search&_=123');
\Astrotomic\PhpunitAssertions\UrlAssertions::assertQueryContains(['q' => 'search'], 'https://astrotomic.info?q=search&_=123');
\Astrotomic\PhpunitAssertions\UrlAssertions::assertComponent('gummibeer', 'https://gummibeer@astrotomic.info', PHP_URL_USER);
```

### ULID

```php
\Astrotomic\PhpunitAssertions\UlidAssertions::assertUlid('01ARZ3NDEKTSV4RRFFQ69G5FAV');
```

### Path

```php
\Astrotomic\PhpunitAssertions\PathAssertions::assertDirname('/foo/bar', '/foo/bar/image.jpg');
\Astrotomic\PhpunitAssertions\PathAssertions::assertBasename('image.jpg', '/foo/bar/image.jpg');
\Astrotomic\PhpunitAssertions\PathAssertions::assertFilename('image', '/foo/bar/image.jpg');
\Astrotomic\PhpunitAssertions\PathAssertions::assertExtension('jpg', '/foo/bar/image.jpg');
```

### UUID

`composer require --dev ramsey/uuid:^4.0`

```php
\Astrotomic\PhpunitAssertions\UuidAssertions::assertUuid('52d08e38-ad24-4960-af02-22e0f7e0db8d');
```

## Laravel Assertions

### Collection

```php
\Astrotomic\PhpunitAssertions\Laravel\CollectionAssertions::assertContains($collection, 'Astrotomic');
```

### HashID

`composer require --dev vinkla/hashids:^9.0`

```php
\Astrotomic\PhpunitAssertions\Laravel\HashidAssertions::assertHashIds('3kTMd', 2);
\Astrotomic\PhpunitAssertions\Laravel\HashidAssertions::assertHashId('yr8');
```

### Model

```php
\Astrotomic\PhpunitAssertions\Laravel\ModelAssertions::assertExists($model);
\Astrotomic\PhpunitAssertions\Laravel\ModelAssertions::assertSame($model, \App\Models\User::first());
\Astrotomic\PhpunitAssertions\Laravel\ModelAssertions::assertRelated($post, 'comments', $comment);
\Astrotomic\PhpunitAssertions\Laravel\ModelAssertions::assertRelated(
    $post, 
    'comments',
    \App\Models\Comment::class,
    \Illuminate\Database\Eloquent\Relations\HasMany::class
);
```

### Blade

`composer require --dev gajus/dindent:^2.0`

```php
\Astrotomic\PhpunitAssertions\Laravel\BladeAssertions::assertRenderEquals(
    "<p>Price: <code>99.99 €</code></p>",
    '<p>Price: <code>{{ number_format($price, 2) }} €</code></p>',
    ['price' => 99.99]
);
```
