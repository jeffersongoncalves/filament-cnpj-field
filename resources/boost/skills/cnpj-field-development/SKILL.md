---
name: cnpj-field-development
description: Build and work with the Filament CNPJ Field plugin, including Brazilian CNPJ input (numeric and alphanumeric), check digit validation, automatic company lookup via Minha Receita, field mapping, and database caching.
---

# CNPJ Field Development

## When to use this skill

Use this skill when:
- Adding a Brazilian CNPJ field to a Filament form
- Auto-populating company fields (razão social, nome fantasia, município, UF, situação cadastral) from a CNPJ lookup
- Supporting the alphanumeric CNPJ format
- Customizing the search button position, label, and error messages
- Troubleshooting Minha Receita API or caching issues

## Architecture

### Namespace
```
JeffersonGoncalves\Filament\CnpjField
```

### Key Classes

| Class | Path | Description |
|-------|------|-------------|
| `CnpjInput` | `src/Forms/Components/CnpjInput.php` | Form field with CNPJ mask, check digit validation, and company lookup |
| `CnpjFieldServiceProvider` | `src/CnpjFieldServiceProvider.php` | Package service provider |

### Dependencies
- `filament/filament: ^4.8`
- `jeffersongoncalves/laravel-cnpj: ^1.0` (validation, Minha Receita API, caching, model)
- `spatie/laravel-package-tools: ^1.14.0`

## Installation

```bash
composer require jeffersongoncalves/filament-cnpj-field
php artisan vendor:publish --tag=cnpj-migrations
php artisan migrate
```

## Usage

```php
use JeffersonGoncalves\Filament\CnpjField\Forms\Components\CnpjInput;

CnpjInput::make('cnpj')
    ->label('CNPJ')
    ->required()
    ->live(onBlur: true)
    ->setMode('suffix')
    ->setActionLabel('Buscar CNPJ')
    ->setActionLabelHidden(false)
    ->setErrorMessage('CNPJ não encontrado.')
    ->setCompanyNameField('razao_social')
    ->setTradeNameField('nome_fantasia')
    ->setCityField('municipio')
    ->setStateField('uf')
    ->setStatusField('situacao_cadastral');
```

## Default Field Mapping

| Method | Default Value | Source key |
|--------|---------------|------------|
| `setCompanyNameField()` | `'company_name'` | `razao_social` |
| `setTradeNameField()` | `'trade_name'` | `nome_fantasia` |
| `setCityField()` | `'city'` | `municipio` |
| `setStateField()` | `'state'` | `uf` |
| `setStatusField()` | `'status'` | `situacao_cadastral` |

## Alphanumeric CNPJ

Since July 2026, the first 12 characters may contain uppercase letters (`A-Z`); the last 2 check digits remain numeric. The mask (`**.***.***/****-99`) accepts letters, validation uses `CnpjSupport::isValid()`, and the value is dehydrated in uppercase.

## Troubleshooting

### CNPJ Not Found
**Cause**: Invalid check digits, company not found, or the Minha Receita API is unavailable / rate limited.
**Solution**: Check the CNPJ with `CnpjSupport::isValid()`. Consider a self-hosted Minha Receita instance via `CNPJ_URL`.

### Company Fields Not Populating
**Cause**: Field name mismatch between the `CnpjInput` mapping and the form schema, or the field is not `live()` and the button was not clicked.
**Solution**: Ensure the names passed to the `set*Field()` methods match the `TextInput::make()` names in your form schema.

### Migration Not Found
**Cause**: Migrations not published.
**Solution**: Run `php artisan vendor:publish --tag=cnpj-migrations` then `php artisan migrate`.
