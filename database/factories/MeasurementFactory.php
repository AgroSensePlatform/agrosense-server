<?php

namespace Database\Factories;
use App\Models\Measurement;
use App\Models\Sensor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Measurement>
 */
class MeasurementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sensor_id' => Sensor::factory(), // Create a sensor for the measurement
            'humidity' => $this->faker->randomFloat(2, 0, 100), // Random humidity between 0% and 100%
            'timestamp' => now(), // Current timestamp
        ];
    }
}
