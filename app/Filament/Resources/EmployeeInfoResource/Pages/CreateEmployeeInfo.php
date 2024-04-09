<?php

namespace App\Filament\Resources\EmployeeInfoResource\Pages;

use App\Filament\Resources\EmployeeInfoResource;
use App\Models\EmployeeInfo;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeInfo extends CreateRecord
{
    protected static string $resource = EmployeeInfoResource::class;

    protected function beforeCreate(): void
    {
        $employeeInfo = EmployeeInfo::active()->where('user_id', $this->data['user_id'] ?? 0)->get();
        if (count($employeeInfo) > 0) {
            Notification::make()
                ->title('The employee has an active employment')
                ->body('If you want to create a new active employment, please terminate the previous one')
                ->danger()
                ->send();
            $this->halt();
        }
    }
}
