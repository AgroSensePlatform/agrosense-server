<?php

namespace Database\Factories;

use App\Models\Farm;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Farm>
 */
class FarmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Creates a new user by default
            'name' => $this->faker->word() . ' Farm',

            // Example coordinates: an array of 3-5 lat/lon points
            'coordinates' => json_encode([
                ['lat' => $this->faker->latitude(37, 38), 'lon' => $this->faker->longitude(21, 23)],
                ['lat' => $this->faker->latitude(37, 38), 'lon' => $this->faker->longitude(21, 23)],
                ['lat' => $this->faker->latitude(37, 38), 'lon' => $this->faker->longitude(21, 23)],
            ]),
        ];
    }
}
