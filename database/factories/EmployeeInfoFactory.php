<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeInfo>
 */
class EmployeeInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'staff_no' => $this->faker->unique()->randomNumber(4),
            'place_of_work' => $this->faker->word(),
            'position' => $this->faker->word(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->optional()->date(),
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
