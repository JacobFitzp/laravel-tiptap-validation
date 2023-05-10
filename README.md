# Laravel Tiptap validation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jacobfitzp/laravel-tiptap-validation.svg?style=flat-square)](https://packagist.org/packages/jacobfitzp/laravel-tiptap-validation)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jacobfitzp/laravel-tiptap-validation/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jacobfitzp/laravel-tiptap-validation/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jacobfitzp/laravel-tiptap-validation/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jacobfitzp/laravel-tiptap-validation/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jacobfitzp/laravel-tiptap-validation.svg?style=flat-square)](https://packagist.org/packages/jacobfitzp/laravel-tiptap-validation)

Configurable Laravel validation rule for [Tiptap editor](https://tiptap.dev/) content.

```php
$rules = [
    'tiptap_content' => [
        'required',
        TiptapValidationRule::create()
            ->whitelist()
            ->nodes('text', 'paragraph')
            ->marks('bold', 'italic', 'link'),
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

## Usage

Simply call `TiptapValidationRule::create()` within your rules.

```php
Validator::make($data, [
    'content' => TiptapValidationRule::create(),
])
```

This will create a basic rule which validates the structure and format of your Tiptap content.

### Blacklist nodes / marks

```php
Validator::make($data, [
    'content' => TiptapValidationRule::create()
        ->blacklist()
        // The node types you want to blacklist
        ->nodes([ ... ])
        // The mark types you want to blacklist
        ->marks([ ... ]),
])
```

### Whitelist nodes / marks

```php
Validator::make($data, [
    'content' => TiptapValidationRule::create()
        ->whitelist()
        // The node types you want to whitelist
        ->nodes([ ... ])
        // The mark types you want to whitelist
        ->marks([ ... ]),
])
```

###  Extension

Instead of having to configure the rule each time, you can create an extension that has your default preferences set.

```php
class MyCustomTiptapValidationRule extends TiptapValidationRule
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
