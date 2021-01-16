# PHPUnit Assertions

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

### Country

`composer require --dev league/iso3166`

```php
\Astrotomic\PhpunitAssertions\CountryAssertions::assertCountryName('Germany');
\Astrotomic\PhpunitAssertions\CountryAssertions::assertCountryAlpha2('DE');
\Astrotomic\PhpunitAssertions\CountryAssertions::assertCountryAlpha3('DEU');
\Astrotomic\PhpunitAssertions\CountryAssertions::assertCountryNumeric('276');
```

### Email

`composer require --dev egulias/email-validator`

```php
\Astrotomic\PhpunitAssertions\EmailAssertions::assertValidLoose('gummibeer@astrotomic.info');
\Astrotomic\PhpunitAssertions\EmailAssertions::assertValidStrict('gummibeer@astrotomic.info');
\Astrotomic\PhpunitAssertions\EmailAssertions::assertDomain('astrotomic.info', 'gummibeer@astrotomic.info');
\Astrotomic\PhpunitAssertions\EmailAssertions::assertLocalPart('gummibeer', 'gummibeer@astrotomic.info');
```

### Geographic

```php
\Astrotomic\PhpunitAssertions\GeographicAssertions::assertLatitude(53.551085);
\Astrotomic\PhpunitAssertions\GeographicAssertions::assertLongitude(9.993682);
\Astrotomic\PhpunitAssertions\GeographicAssertions::assertCoordinates([
    'lat' => 53.551085,
    'lng' => 9.993682,
]);
```

### HashID

`composer require --dev hashids/hashids`

```php
\Astrotomic\PhpunitAssertions\HashidAssertions::assertHashIds('3kTMd', 2, 'this is my salt');
\Astrotomic\PhpunitAssertions\HashidAssertions::assertHashId('yr8', 'this is my salt');
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

`composer require --dev giggsey/libphonenumber-for-php`

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

### URL

```php
\Astrotomic\PhpunitAssertions\UrlAssertions::assertValidLoose('https://astrotomic.info');
\Astrotomic\PhpunitAssertions\UrlAssertions::assertScheme('https', 'https://astrotomic.info');
\Astrotomic\PhpunitAssertions\UrlAssertions::assertHost('astrotomic.info', 'https://astrotomic.info');
\Astrotomic\PhpunitAssertions\UrlAssertions::assertPath('/contributor/gummibeer/', 'https://astrotomic.info/contributor/gummibeer/');
\Astrotomic\PhpunitAssertions\UrlAssertions::assertComponent('gummibeer', 'https://gummibeer@astrotomic.info', PHP_URL_USER);
```

### UUID

`composer require --dev ramsey/uuid`

```php
\Astrotomic\PhpunitAssertions\UuidAssertions::assertUuid('52d08e38-ad24-4960-af02-22e0f7e0db8d');
```

## Laravel Assertions

### Collection

```php
\Astrotomic\PhpunitAssertions\Laravel\CollectionAssertions::assertContains('Astrotomic', collect(['Astrotomic', 'Gummibeer']));
```

### HashID

`composer require --dev vinkla/hashids`

```php
\Astrotomic\PhpunitAssertions\Laravel\HashidAssertions::assertHashIds('3kTMd', 2);
\Astrotomic\PhpunitAssertions\Laravel\HashidAssertions::assertHashId('yr8');
```

### Model

```php
\Astrotomic\PhpunitAssertions\Laravel\ModelAssertions::assertExists($model);
\Astrotomic\PhpunitAssertions\Laravel\ModelAssertions::assertSame($model, Model::first());
```