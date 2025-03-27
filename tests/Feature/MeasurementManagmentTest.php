<?php
use App\Models\Sensor;
use App\Models\Measurement;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can post a measurement from a sensor', function () {
    // Create a sensor
    $sensor = Sensor::factory()->create([
        'code' => 'SENSOR12345',
    ]);

    // Define the measurement data to be sent
    $measurementData = [
        'code' => 'SENSOR12345', // Sensor's unique code
        'humidity' => 45.67, // Example humidity value
    ];

    // Send a POST request to the endpoint
    $response = $this->postJson('/api/measurements', $measurementData);

    // Assert the response status and structure
    $response->assertStatus(201)
        ->assertJson([
            'message' => 'Measurement recorded successfully',
        ]);

    // Assert the measurement exists in the database
    $this->assertDatabaseHas('measurements', [
        'sensor_id' => $sensor->id,
        'humidity' => 45.67,
    ]);
});

it('returns an error if the sensor code does not exist', function () {
    // Define the measurement data with a non-existent sensor code
    $measurementData = [
        'code' => 'NON_EXISTENT_CODE',
        'humidity' => 45.67,
    ];

    // Send a POST request to the endpoint
    $response = $this->postJson('/api/measurements', $measurementData);

    // Assert the response status and error message
    $response->assertStatus(404)
        ->assertJson([
            'message' => 'Sensor not found',
        ]);

    // Assert no measurement was created in the database
    $this->assertDatabaseMissing('measurements', [
        'humidity' => 45.67,
    ]);
});
