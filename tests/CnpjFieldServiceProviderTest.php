<?php

use JeffersonGoncalves\Filament\CnpjField\CnpjFieldServiceProvider;
use Spatie\LaravelPackageTools\Package;

it('can be instantiated', function () {
    $provider = new CnpjFieldServiceProvider(app());

    expect($provider)->toBeInstanceOf(CnpjFieldServiceProvider::class);
});

it('has correct package name', function () {
    $provider = new CnpjFieldServiceProvider(app());

    $package = new Package;
    $provider->configurePackage($package);

    expect($package->name)->toBe('filament-cnpj-field');
});
