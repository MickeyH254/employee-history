<?php

namespace Database\Seeders;

use App\Models\EmployeeInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the number of dummy records you want to create
        $numberOfRecords = 10;

        // Use factory to create dummy data
        EmployeeInfo::factory($numberOfRecords)->create();
    }
}
