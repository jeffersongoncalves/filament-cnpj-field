<?php

namespace JeffersonGoncalves\Filament\CnpjField\Forms\Components;

use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Illuminate\Validation\ValidationException;
use JeffersonGoncalves\Cnpj\Models\Cnpj;
use JeffersonGoncalves\Cnpj\Support\CnpjSupport;
use Livewire\Component as Livewire;

class CnpjInput extends TextInput
{
    private string $mode = 'suffix';

    private string $errorMessage = 'CNPJ inválido.';

    private string $actionLabel = 'Buscar CNPJ';

    private bool $actionLabelHidden = false;

    private string $companyNameField = 'company_name';

    private string $tradeNameField = 'trade_name';

    private string $cityField = 'city';

    private string $stateField = 'state';

    private string $statusField = 'status';

    public function setMode(string $mode): static
    {
        $this->mode = $mode;

        return $this;
    }

    public function setErrorMessage(string $errorMessage): static
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    public function setActionLabel(string $actionLabel): static
    {
        $this->actionLabel = $actionLabel;

        return $this;
    }

    public function setActionLabelHidden(bool $actionLabelHidden): static
    {
        $this->actionLabelHidden = $actionLabelHidden;

        return $this;
    }

    public function setCompanyNameField(string $companyNameField): static
    {
        $this->companyNameField = $companyNameField;

        return $this;
    }

    public function setTradeNameField(string $tradeNameField): static
    {
        $this->tradeNameField = $tradeNameField;

        return $this;
    }

    public function setCityField(string $cityField): static
    {
        $this->cityField = $cityField;

        return $this;
    }

    public function setStateField(string $stateField): static
    {
        $this->stateField = $stateField;

        return $this;
    }

    public function setStatusField(string $statusField): static
    {
        $this->statusField = $statusField;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();
        // `*` accepts letters for the alphanumeric CNPJ; check digits stay numeric.
        $this->mask('**.***.***/****-99');
        $this->minLength(18);
        $this->rule(fn () => function (string $attribute, mixed $value, Closure $fail) {
            if (filled($value) && ! CnpjSupport::isValid($value)) {
                $fail($this->errorMessage);
            }
        });
        $this->dehydrateStateUsing(fn (?string $state) => filled($state) ? strtoupper($state) : $state);
        $this->afterStateUpdated(function ($state, Livewire $livewire, Set $set) {
            $this->findByCnpj($state, $livewire, $set);
        });
        $this->suffixAction(function () {
            if ($this->mode === 'suffix') {
                return $this->makeCnpjAction();
            }

            return null;
        });
        $this->prefixAction(function () {
            if ($this->mode === 'prefix') {
                return $this->makeCnpjAction();
            }

            return null;
        });
    }

    private function makeCnpjAction(): Action
    {
        $action = Action::make('search-action-'.$this->getKey())
            ->label($this->actionLabel)
            ->icon('heroicon-o-magnifying-glass')
            ->action(function ($state, Livewire $livewire, Set $set) {
                $this->findByCnpj($state, $livewire, $set);
            })
            ->cancelParentActions();

        if (! $this->actionLabelHidden) {
            $action->button();
        }

        return $action;
    }

    private function findByCnpj($state, Livewire $livewire, Set $set): void
    {
        $livewire->validateOnly($this->getStatePath());
        $cnpj = Cnpj::findByCnpj($state);
        if (blank($cnpj['cnpj'])) {
            throw ValidationException::withMessages([
                $this->getStatePath() => $this->errorMessage,
            ]);
        }
        $set($this->companyNameField, $cnpj['razao_social']);
        $set($this->tradeNameField, $cnpj['nome_fantasia']);
        $set($this->cityField, $cnpj['municipio']);
        $set($this->stateField, $cnpj['uf']);
        $set($this->statusField, $cnpj['situacao_cadastral']);
    }
}
