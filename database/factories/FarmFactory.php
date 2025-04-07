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

    /**
     * Configure the model factory to create a farm with name "Ntarodou" and specific coordinates.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function ntarodou()
    {
        return $this->state(function (array $attributes) {
            $coordinates = [
                ['lat' => 37.07174174719329, 'lon' => 25.35567662146066],
                ['lat' => 37.07170126390636, 'lon' => 25.35579732086633],
                ['lat' => 37.07165436033718, 'lon' => 25.355952888989194],
                ['lat' => 37.0716181571797, 'lon' => 25.356068223976834],
                ['lat' => 37.07171463940781, 'lon' => 25.356108457112057],
                ['lat' => 37.07171463940781, 'lon' => 25.356108457112057],
                ['lat' => 37.07193310612035, 'lon' => 25.3561701479194],
                ['lat' => 37.071969665785375, 'lon' => 25.35602799084161],
                ['lat' => 37.07203618650545, 'lon' => 25.355778545403226],
                ['lat' => 37.07189511967404, 'lon' => 25.355738312268002],
            ];

            return [
                'name' => 'Ntarodou',
                'coordinates' => json_encode($coordinates),
            ];
        });
    }
}
