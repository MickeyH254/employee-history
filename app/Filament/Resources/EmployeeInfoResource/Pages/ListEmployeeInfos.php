<?php

namespace App\Filament\Resources\EmployeeInfoResource\Pages;

use App\Filament\Resources\EmployeeInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeInfos extends ListRecords
{
    protected static string $resource = EmployeeInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
