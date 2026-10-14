<?php

use Illuminate\Support\Facades\Validator;
use JeffersonGoncalves\Filament\CnpjField\Forms\Components\CnpjInput;

function cnpjInputProperty(CnpjInput $input, string $property): mixed
{
    return (new ReflectionProperty($input, $property))->getValue($input);
}

it('can be instantiated', function () {
    expect(CnpjInput::make('cnpj'))->toBeInstanceOf(CnpjInput::class);
});

it('has default configuration values', function () {
    $input = CnpjInput::make('cnpj');

    expect(cnpjInputProperty($input, 'mode'))->toBe('suffix')
        ->and(cnpjInputProperty($input, 'errorMessage'))->toBe('CNPJ inválido.')
        ->and(cnpjInputProperty($input, 'actionLabel'))->toBe('Buscar CNPJ')
        ->and(cnpjInputProperty($input, 'actionLabelHidden'))->toBeFalse()
        ->and(cnpjInputProperty($input, 'companyNameField'))->toBe('company_name')
        ->and(cnpjInputProperty($input, 'tradeNameField'))->toBe('trade_name')
        ->and(cnpjInputProperty($input, 'cityField'))->toBe('city')
        ->and(cnpjInputProperty($input, 'stateField'))->toBe('state')
        ->and(cnpjInputProperty($input, 'statusField'))->toBe('status');
});

it('can chain configuration methods', function () {
    $input = CnpjInput::make('cnpj')
        ->setMode('prefix')
        ->setErrorMessage('Custom error')
        ->setActionLabel('Custom label')
        ->setActionLabelHidden(true)
        ->setCompanyNameField('razao_social')
        ->setTradeNameField('nome_fantasia')
        ->setCityField('municipio')
        ->setStateField('uf')
        ->setStatusField('situacao_cadastral');

    expect(cnpjInputProperty($input, 'mode'))->toBe('prefix')
        ->and(cnpjInputProperty($input, 'errorMessage'))->toBe('Custom error')
        ->and(cnpjInputProperty($input, 'actionLabel'))->toBe('Custom label')
        ->and(cnpjInputProperty($input, 'actionLabelHidden'))->toBeTrue()
        ->and(cnpjInputProperty($input, 'companyNameField'))->toBe('razao_social')
        ->and(cnpjInputProperty($input, 'tradeNameField'))->toBe('nome_fantasia')
        ->and(cnpjInputProperty($input, 'cityField'))->toBe('municipio')
        ->and(cnpjInputProperty($input, 'stateField'))->toBe('uf')
        ->and(cnpjInputProperty($input, 'statusField'))->toBe('situacao_cadastral');
});

it('validates CNPJ check digits', function (?string $cnpj, bool $passes) {
    $rules = CnpjInput::make('cnpj')->setErrorMessage('bad')->getValidationRules();

    $validator = Validator::make(['cnpj' => $cnpj], ['cnpj' => $rules]);

    expect($validator->passes())->toBe($passes);
})->with([
    'numeric' => ['33.000.167/0001-01', true],
    'alphanumeric' => ['12.ABC.345/01DE-35', true],
    'alphanumeric lowercase' => ['12.abc.345/01de-35', true],
    'wrong check digit' => ['33.000.167/0001-02', false],
    'repeated digits' => ['11.111.111/1111-11', false],
    'empty' => [null, true],
]);
