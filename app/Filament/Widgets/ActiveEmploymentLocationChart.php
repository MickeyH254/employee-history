<?php

namespace App\Filament\Widgets;

use App\Models\EmployeeInfo;
use App\Models\Location;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ActiveEmploymentLocationChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Active employees per Location';

    private array $locations;
    private array $data;

    public function __construct()
    {
        $this->locations = Location::all()->pluck('location')->toArray();
        $this->data = EmployeeInfo::with('location')
            ->whereNull('end_date')
            ->select('location_id', DB::raw('count(*) as employee_count'))
            ->groupBy('location_id')
            ->get()->pluck('employee_count')->toArray();
    }

    protected function getData(): array
    {
        return [
            'labels' => $this->locations,
            'datasets' => [[
                'label'=> 'Active employees per Location',
                'data'=> $this->data,
                'backgroundColor' => [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
                'hoverOffset' =>  4
            ]]
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
