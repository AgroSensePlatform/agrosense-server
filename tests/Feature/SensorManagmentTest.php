<?php
use App\Models\User;
use App\Models\Farm;
use App\Models\Sensor;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can add a new sensor to a farm', function () {
    // Create a user and authenticate
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create a farm associated with the user
    $farm = Farm::factory()->create(['user_id' => $user->id]);

    // Define the sensor data to be sent in the request
    $sensorData = [
        'farm_id' => $farm->id,
        'code' => 'SENSOR12345', // Example sensor code from QR code
        'lat' => 37.7749, // Example latitude
        'lon' => -122.4194, // Example longitude
    ];

    // Send a POST request to the endpoint to add the sensor
    $response = $this->postJson('/api/sensors', $sensorData);

    // Assert the response status and structure
    $response->assertStatus(201)
        ->assertJson([
            'farm_id' => $farm->id,
            'code' => 'SENSOR12345',
            'lat' => 37.7749,
            'lon' => -122.4194,
        ]);

    // Assert the sensor exists in the database
    $this->assertDatabaseHas('sensors', [
        'farm_id' => $farm->id,
        'code' => 'SENSOR12345',
        'lat' => 37.7749,
        'lon' => -122.4194,
    ]);
});



it('allows a user to view their own sensor', function () {
    $user = User::factory()->create();
    $sensor = Sensor::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $response = $this->getJson("/api/sensors/{$sensor->id}");

    $response->assertStatus(200);
});

it('denies a user from viewing another user\'s sensor', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $sensor = Sensor::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($user);

    $response = $this->getJson("/api/sensors/{$sensor->id}");

    $response->assertStatus(403); // Forbidden
});


it('creates a new sensor if the code from qrcode does not exist', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $farm = Farm::factory()->create(['user_id' => $user->id]);

    $sensorData = [
        'farm_id' => $farm->id,
        'code' => 'NEW_SENSOR_CODE',
        'lat' => 37.7749,
        'lon' => -122.4194,
    ];

    $response = $this->postJson('/api/sensors/scan', $sensorData);

    $response->assertStatus(201);

    $this->assertDatabaseHas('sensors', [
        'farm_id' => $farm->id,
        'code' => 'NEW_SENSOR_CODE',
        'lat' => 37.7749,
        'lon' => -122.4194,
    ]);
});

it('updates an existing sensor if the code from qrcode already exists', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $farm = Farm::factory()->create(['user_id' => $user->id]);

    $existingSensor = Sensor::factory()->create([
        'user_id' => $user->id,
        'farm_id' => $farm->id,
        'code' => 'EXISTING_SENSOR_CODE',
        'lat' => 10.0000,
        'lon' => 20.0000,
    ]);

    $updatedSensorData = [
        'farm_id' => $farm->id,
        'code' => 'EXISTING_SENSOR_CODE',
        'lat' => 37.7749,
        'lon' => -122.4194,
    ];

    $response = $this->postJson('/api/sensors/scan', $updatedSensorData);

    $response->assertStatus(200);

    $this->assertDatabaseHas('sensors', [
        'id' => $existingSensor->id,
        'farm_id' => $farm->id,
        'code' => 'EXISTING_SENSOR_CODE',
        'lat' => 37.7749,
        'lon' => -122.4194,
    ]);
});
