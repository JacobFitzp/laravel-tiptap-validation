# Laravel Tiptap validation

<img src="https://banners.beyondco.de/Laravel%20Tiptap%20validation.png?theme=light&packageManager=composer+require&packageName=jacobfitzp%2Flaravel-tiptap-validation&pattern=architect&style=style_1&description=Back-end+Tiptap+editor+validation+rules&md=1&showWatermark=0&fontSize=75px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg" />

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jacobfitzp/laravel-tiptap-validation.svg?style=flat-square)](https://packagist.org/packages/jacobfitzp/laravel-tiptap-validation)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jacobfitzp/laravel-tiptap-validation/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jacobfitzp/laravel-tiptap-validation/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jacobfitzp/laravel-tiptap-validation/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jacobfitzp/laravel-tiptap-validation/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jacobfitzp/laravel-tiptap-validation.svg?style=flat-square)](https://packagist.org/packages/jacobfitzp/laravel-tiptap-validation)

Configurable Laravel validation rule for [Tiptap editor](https://tiptap.dev/) content.

```php
$rules = [
    'tiptap_content' => [
        'required',
        TiptapValidation::content()
            ->whitelist()
            ->nodes('text', 'paragraph')
            ->marks('bold', 'italic', 'link'),
        TiptapValidation::containsText()
            ->between(18, 256),
    ],
];
```

Validate Tiptap content in your back-end to prevent unwanted elements and styling from being used.

### Please note

This package only works with JSON output, not the raw HTML. You can read more about outputting Tiptap JSON content [here](https://tiptap.dev/guide/output#option-1-json).

## Installation

You can install the package via composer:

```bash
composer require jacobfitzp/laravel-tiptap-validation
```

And then add the below service provider to `config/app.php`
```php
JacobFitzp\LaravelTiptapValidation\TiptapValidationServiceProvider::class
```

## Usage

### TiptapContent

The `TiptapContent` rule is used to validate the basic structure and format, as well as limit what nodes and marks are allowed.

Simply call `TiptapValidation::content()` within your rules.

```php
Validator::make($data, [
    'content' => TiptapValidation::content(),
])
```

You can also specify the nodes and marks you want to whitelist or blacklist:

#### Blacklist nodes / marks

```php
Validator::make($data, [
    'content' => TiptapValidation::content()
        ->blacklist()
        // The node types you want to blacklist
        ->nodes([ ... ])
        // The mark types you want to blacklist
        ->marks([ ... ]),
])
```

#### Whitelist nodes / marks

```php
Validator::make($data, [
    'content' => TiptapValidation::content()
        ->whitelist()
        // The node types you want to whitelist
        ->nodes([ ... ])
        // The mark types you want to whitelist
        ->marks([ ... ]),
])
```

####  Extension

Instead of having to configure the rule each time, you can create an extension that has your default preferences set.

```php
class MyCustomTiptapValidationRule extends TiptapContent
{
    protected TiptapValidationRuleMode $mode = TiptapValidationRuleMode::WHITELIST;
    
    protected array $nodes = ['text', 'paragraph', 'table'];
    
    protected array $marks = ['italic', 'link'];
}
```

This can then be used without the need for further configuration:

```php
Validator::make($data, [
    'content' => MyCustomTiptapValidationRule::create(),
])
```

### TiptapContainsText 

The `TiptapContainsText` rule is used for verifying that the content contains text, and meets an optional character count requirements.

```php
Validator::make($data, [
    'content' => TiptapValidation::containsText()
        // Minimum character requirement
        ->minimum(12)
        // Maximum character requirement
        ->maximum(156)
        // Minimum and maximum character requirement
        ->between(12, 156),
])
```

### Configuration

#### Error messages

First publish the translation files:

```bash
php artisan vendor:publish --provider="JacobFitzp\LaravelTiptapValidation\TiptapValidationServiceProvider" --tag="tiptap-validation-translations"
```

And then you can configure the error messages in `lang/vendor/tiptap-validation/messages.php`

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jacob Fitzpatrick](https://github.com/JacobFitzp)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
