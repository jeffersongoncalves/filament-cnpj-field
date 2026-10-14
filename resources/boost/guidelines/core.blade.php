## Filament CNPJ Field

A Filament form field for Brazilian company registrations (CNPJ), including the alphanumeric format, with automatic mask formatting (99.999.999/9999-99), check digit validation, company lookup via the Minha Receita API, database caching, and auto-population of company fields. Requires Filament 3.0 and PHP 8.2+.

### Installation

@verbatim
<code-snippet name="Install the plugin" lang="bash">
composer require jeffersongoncalves/filament-cnpj-field
</code-snippet>
@endverbatim

### Publish & Run Migrations

@verbatim
<code-snippet name="Publish and run CNPJ cache migrations" lang="bash">
php artisan vendor:publish --tag=cnpj-migrations
php artisan migrate
</code-snippet>
@endverbatim

### Basic Usage

@verbatim
<code-snippet name="Use CnpjInput in a form" lang="php">
use JeffersonGoncalves\Filament\CnpjField\Forms\Components\CnpjInput;

CnpjInput::make('cnpj')
    ->required()
    ->setCompanyNameField('company_name')
    ->setTradeNameField('trade_name')
    ->setCityField('city')
    ->setStateField('state')
    ->setStatusField('status');
</code-snippet>
@endverbatim

### Key Methods

- `setMode(string $mode)` - Button position: `'suffix'` (default) or `'prefix'`
- `setActionLabel(string $label)` - Custom search button label (default: `'Buscar CNPJ'`)
- `setActionLabelHidden(bool $hidden)` - Show/hide action label (default: `false`)
- `setErrorMessage(string $message)` - Error message for invalid or not found CNPJ (default: `'CNPJ inválido.'`)
- `setCompanyNameField(string $field)` - Field filled with `razao_social` (default: `'company_name'`)
- `setTradeNameField(string $field)` - Field filled with `nome_fantasia` (default: `'trade_name'`)
- `setCityField(string $field)` - Field filled with `municipio` (default: `'city'`)
- `setStateField(string $field)` - Field filled with `uf` (default: `'state'`)
- `setStatusField(string $field)` - Field filled with `situacao_cadastral` (default: `'status'`)

### Architecture

- **Namespace**: `JeffersonGoncalves\Filament\CnpjField`
- **Component**: `CnpjInput` extends Filament `TextInput`
- **Service Provider**: `CnpjFieldServiceProvider` (auto-discovered)
- **Depends on**: `jeffersongoncalves/laravel-cnpj` for validation, API lookup and caching

### Best Practices

- Always publish and run the migrations for database-backed CNPJ caching
- Map field names to match your form schema (e.g., `setCompanyNameField('razao_social')`)
- Use `->live(onBlur: true)` to look up the company automatically when the field loses focus
- The value is dehydrated in uppercase, so alphanumeric CNPJs are stored consistently
