<?php

namespace App\Filament\Resources\EmployeeInfoResource\Pages;

use App\Filament\Resources\EmployeeInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeInfo extends EditRecord
{
    protected static string $resource = EmployeeInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
