<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sensor;
use App\Models\Measurement;

class SensorAndMeasurementSeeder extends Seeder
{
    public function run()
    {
        // Create 5 sensors for the farm with id 33 and user with id 35
        $sensors = Sensor::factory()->count(5)->create([
            'farm_id' => 33,
            'user_id' => 35,
        ]);

        // For each sensor, create 5 random measurements
        foreach ($sensors as $sensor) {
            Measurement::factory()->count(5)->create([
                'sensor_id' => $sensor->id,
            ]);
        }
    }
}
