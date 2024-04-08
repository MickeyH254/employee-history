<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Pages\Auth\Register as RegisterAlias;

class Register extends RegisterAlias
{

    protected function getStaffNumberFormComponent(): Component
    {
        return TextInput::make('staff_no')
            ->label("Staff Number")
            ->unique('users', 'staff_no')
            ->required()
            ->numeric();
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getStaffNumberFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}
