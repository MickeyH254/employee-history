<?php

namespace App\Filament\Widgets;

use App\Models\EmployeeInfo;
use App\Models\Location;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AverageTimeSpentPerLocationChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Average time spent per location in days';

    private array $averageTimePerLocation;

    private array $labels;

    public function __construct()
    {

        $this->averageTimePerLocation = EmployeeInfo::with('location')
            ->select('location_id', DB::raw('AVG(DATEDIFF(end_date, start_date)) AS average_time_spent'))
            ->groupBy('location_id')
            ->get()->pluck('average_time_spent')->toArray();

        $this->labels = Location::all()->pluck('location')->toArray();
//        dd($this->averageTimePerLocation);
    }

    protected function getData(): array
    {
        return [
            'labels' => $this->labels,
            'datasets' => [[
                'label' => 'Average time spent per location',
                'data' => $this->averageTimePerLocation,
                'backgroundColor' => [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                ],
                'borderColor' => [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)'
                ],
                'borderWidth'=> 1
              ]]
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
