<?php

namespace Database\Factories;

use App\Models\Building;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Building>
 */
class BuildingFactory extends Factory
{
    protected $model = Building::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'address' => $this->faker->address(),
//             'latitude' => $this->faker->latitude(),
            'latitude' => $this->faker->randomFloat(6, 55.550009, 56.000000),
//             'longitude' => $this->faker->longitude(),
            'longitude' => $this->faker->randomFloat(6, 37.300009, 37.910000),
        ];
    }
}
