# PHPUnit Assertions

[![Latest Version](http://img.shields.io/packagist/v/astrotomic/phpunit-assertions.svg?label=Release&style=for-the-badge)](https://packagist.org/packages/astrotomic/phpunit-assertions)
[![MIT License](https://img.shields.io/github/license/Astrotomic/phpunit-assertions.svg?label=License&color=blue&style=for-the-badge)](https://github.com/Astrotomic/phpunit-assertions/blob/master/LICENSE)
[![Offset Earth](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-green?style=for-the-badge)](https://forest.astrotomic.info)
[![Larabelles](https://img.shields.io/badge/Larabelles-%F0%9F%A6%84-lightpink?style=for-the-badge)](https://larabelles.com)

[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/Astrotomic/phpunit-assertions/run-tests?style=flat-square&logoColor=white&logo=github&label=Tests)](https://github.com/Astrotomic/phpunit-assertions/actions?query=workflow%3Apest)
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

### Country

`composer require --dev league/iso3166:^3.0`

```php
\Astrotomic\PhpunitAssertions\CountryAssertions::assertName('Germany');
\Astrotomic\PhpunitAssertions\CountryAssertions::assertAlpha2('DE');
\Astrotomic\PhpunitAssertions\CountryAssertions::assertAlpha3('DEU');
\Astrotomic\PhpunitAssertions\CountryAssertions::assertNumeric('276');
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
```

### HashID

`composer require --dev hashids/hashids:^4.0`

```php
\Astrotomic\PhpunitAssertions\HashidAssertions::assertHashIds('3kTMd', 2, 'this is my salt');
\Astrotomic\PhpunitAssertions\HashidAssertions::assertHashId('yr8', 'this is my salt');
```

### Language

`composer require --dev astrotomic/iso639:^1.0`

```php
\Astrotomic\PhpunitAssertions\LanguageAssertions::assertName('German');
\Astrotomic\PhpunitAssertions\LanguageAssertions::assertAlpha2('de');
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

### URL

```php
\Astrotomic\PhpunitAssertions\UrlAssertions::assertValidLoose('https://astrotomic.info');
\Astrotomic\PhpunitAssertions\UrlAssertions::assertScheme('https', 'https://astrotomic.info');
\Astrotomic\PhpunitAssertions\UrlAssertions::assertHost('astrotomic.info', 'https://astrotomic.info');
\Astrotomic\PhpunitAssertions\UrlAssertions::assertPath('/contributor/gummibeer/', 'https://astrotomic.info/contributor/gummibeer/');
\Astrotomic\PhpunitAssertions\UrlAssertions::assertQuery(['_' => '123', 'q' => 'search'], 'https://astrotomic.info?q=search&_=123');
\Astrotomic\PhpunitAssertions\UrlAssertions::assertComponent('gummibeer', 'https://gummibeer@astrotomic.info', PHP_URL_USER);
```

### Path

```php
\Astrotomic\PhpunitAssertions\PathAssertions::assertDirname('/foo/bar', '/foo/bar/image.jpg');
\Astrotomic\PhpunitAssertions\PathAssertions::assertBasename('image.jpg', '/foo/bar/image.jpg');
\Astrotomic\PhpunitAssertions\PathAssertions::assertFilename('image', '/foo/bar/image.jpg');
\Astrotomic\PhpunitAssertions\PathAssertions::assertExtension('jpg', '/foo/bar/image.jpg');
\Astrotomic\PhpunitAssertions\PathAssertions::assertOsAgnosticPath(__DIR__ . '/resources/css/main.css', __DIR__ . '\resources\css\main.css');
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