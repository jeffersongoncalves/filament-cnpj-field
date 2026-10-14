<div class="filament-hidden">

![Filament CNPJ Field](https://raw.githubusercontent.com/jeffersongoncalves/filament-cnpj-field/2.x/art/jeffersongoncalves-filament-cnpj-field.png)

</div>

# Filament CNPJ Field

[![Buy Me A Coffee](https://img.shields.io/badge/Buy%20Me%20A%20Coffee-support-FFDD00?style=flat-square&logo=buy-me-a-coffee&logoColor=black)](https://buymeacoffee.com/jeffersongoncalves)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/filament-cnpj-field.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-cnpj-field)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-cnpj-field/fix-php-code-style-issues.yml?branch=2.x&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/filament-cnpj-field/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3A2.x)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/filament-cnpj-field.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-cnpj-field)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/filament-cnpj-field.svg?style=flat-square)](LICENSE.md)

The Filament CNPJ Field is a custom input component for Brazilian company registrations (CNPJ - Cadastro Nacional da Pessoa Jurídica). It handles CNPJ formatting, check digit validation and automatic company lookup through the [Minha Receita](https://minhareceita.org) API, powered by [jeffersongoncalves/laravel-cnpj](https://github.com/jeffersongoncalves/laravel-cnpj).

### What it does:

- **CNPJ Formatting**: Applies the standard CNPJ mask (`99.999.999/9999-99`) as users type
- **Alphanumeric CNPJ**: Accepts the new alphanumeric format introduced by Receita Federal (e.g. `12.ABC.345/01DE-35`)
- **Validation**: Validates the check digits before any lookup, so invalid CNPJs never hit the API
- **Company Lookup**: Fetches the company and fills the company name, trade name, city, state and registration status fields
- **Caching**: Results are cached in the database by `laravel-cnpj`

## Requirements

- PHP 8.2 or higher
- Filament 4.0
- Laravel 12.0 or higher

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/filament-cnpj-field
```

Publish and run the migration file:

```bash
php artisan vendor:publish --tag=cnpj-migrations
php artisan migrate
```

Optionally publish the `laravel-cnpj` config file (API URL, timeout, SSL verification, cache TTL):

```bash
php artisan vendor:publish --tag=cnpj-config
```

## Usage

```php
use JeffersonGoncalves\Filament\CnpjField\Forms\Components\CnpjInput;

// Basic usage
CnpjInput::make('cnpj')
    ->required(),

// Advanced usage with all available options
CnpjInput::make('cnpj')
    ->required()
    ->setMode('suffix') // 'prefix' or 'suffix' (default: 'suffix')
    ->setActionLabel('Buscar CNPJ') // Custom search button label
    ->setActionLabelHidden(false) // Show/hide action label (default: false)
    ->setErrorMessage('CNPJ não encontrado.') // Custom error message
    ->setCompanyNameField('company_name') // Field to populate with the razão social
    ->setTradeNameField('trade_name') // Field to populate with the nome fantasia
    ->setCityField('city') // Field to populate with the município
    ->setStateField('state') // Field to populate with the UF
    ->setStatusField('status'), // Field to populate with the situação cadastral
```

The lookup runs when the search button is clicked, or on every state update when the field is `->live()` / `->live(onBlur: true)`.

### Available Methods

#### `setMode(string $mode)`

Sets the position of the search action button.
- `'suffix'` (default): Places the button after the input field
- `'prefix'`: Places the button before the input field

#### `setActionLabel(string $label)`

Customizes the label text for the search action button.
- Default: `'Buscar CNPJ'`

#### `setActionLabelHidden(bool $hidden)`

Controls whether the action button label is visible.
- `true`: Shows only the magnifying glass icon
- `false` (default): Shows both icon and label

#### `setErrorMessage(string $message)`

Sets the error message used when the CNPJ is invalid or not found.
- Default: `'CNPJ inválido.'`

#### Field Mapping Methods

These methods specify which form fields are populated with the company information:

| Method | Default | Minha Receita data |
|--------|---------|--------------------|
| `setCompanyNameField(string $field)` | `'company_name'` | `razao_social` |
| `setTradeNameField(string $field)` | `'trade_name'` | `nome_fantasia` |
| `setCityField(string $field)` | `'city'` | `municipio` |
| `setStateField(string $field)` | `'state'` | `uf` |
| `setStatusField(string $field)` | `'status'` | `situacao_cadastral` |

### Usage Examples

#### CNPJ with Portuguese Field Names

```php
CnpjInput::make('cnpj')
    ->label('CNPJ')
    ->required()
    ->setCompanyNameField('razao_social')
    ->setTradeNameField('nome_fantasia')
    ->setCityField('municipio')
    ->setStateField('uf')
    ->setStatusField('situacao_cadastral')
```

#### CNPJ with Prefix Button and Custom Labels

```php
CnpjInput::make('cnpj')
    ->label('CNPJ')
    ->setMode('prefix')
    ->setActionLabel('Consultar')
    ->setErrorMessage('CNPJ não encontrado ou inválido.')
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- [Minha Receita](https://github.com/cuducos/minha-receita) by Eduardo Cuducos
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
