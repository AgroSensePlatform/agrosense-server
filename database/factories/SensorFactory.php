<?php

namespace Database\Factories;

use App\Models\Sensor;
use App\Models\User;
use App\Models\Farm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sensor>
 */
class SensorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sensor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Create a user for the sensor
            'farm_id' => Farm::factory(), // Create a farm for the sensor
            'code' => $this->faker->unique()->uuid, // Unique sensor code
            'lat' => $this->faker->latitude, // Random latitude
            'lon' => $this->faker->longitude, // Random longitude
        ];
    }
}
