<?php

use App\Models\User;
use App\Models\Farm;
use App\Models\Sensor;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list farms for a user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Farm::factory()->count(3)->create(['user_id' => $user->id]);


    $response = $this->getJson('/api/farms');
    //dd($response);

    $response->assertStatus(200)
        ->assertJsonCount(3);
});

it('can create a farm', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->postJson('/api/farms', [
        'name' => 'My Farm',
        'geometry'
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('name', 'My Farm');
});


it('can delete a farm', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $farm = Farm::factory()->create(['user_id' => $user->id]);

    $response = $this->deleteJson("/api/farms/{$farm->id}");

    $response->assertStatus(200)
        ->assertJson(['message' => 'Farm deleted successfully']);
});


it('can show a farm created by the user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $farm = Farm::factory()->create([
        'user_id' => $user->id,
        'name' => 'My Farm',
    ]);

    $response = $this->getJson("/api/farms/{$farm->id}");

    $response->assertStatus(200)
        ->assertJson([
            'id' => $farm->id,
            'name' => 'My Farm',
        ]);
});

it('cannot show a farm created by another user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $otherUser = User::factory()->create();
    $farm = Farm::factory()->create([
        'user_id' => $otherUser->id,
        'name' => 'Other User\'s Farm',
    ]);

    $response = $this->getJson("/api/farms/{$farm->id}");

    $response->assertStatus(403); // Forbidden
});


it('can update a farm created by the user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $farm = Farm::factory()->create([
        'user_id' => $user->id,
        'name' => 'Old Farm Name',
    ]);

    $response = $this->putJson("/api/farms/{$farm->id}", [
        'name' => 'Updated Farm Name',
        'coordinates' => '{"type":"Polygon","coordinates":[[[30,10],[40,40],[20,40],[10,20],[30,10]]]}', // Example geometry
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'id' => $farm->id,
            'name' => 'Updated Farm Name',
        ]);

    $this->assertDatabaseHas('farms', [
        'id' => $farm->id,
        'name' => 'Updated Farm Name',
    ]);
});

it('can retrieve sensors with their last measurement for a farm', function () {
    // Create a user and authenticate
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create a farm associated with the user
    $farm = Farm::factory()->create(['user_id' => $user->id]);

    // Create sensors for the farm
    $sensors = Sensor::factory()->count(3)->create(['farm_id' => $farm->id]);

    // Add measurements to the sensors
    foreach ($sensors as $sensor) {
        $sensor->measurements()->create([
            'humidity' => rand(10, 90),
            'timestamp' => now(),
        ]);
    }

    // Send a GET request to the sensors endpoint
    $response = $this->getJson("/api/farms/{$farm->id}/sensors");

    //dd($response->json());
    // Assert the response status and structure
    $response->assertStatus(200)
        ->assertJsonStructure([
            '*' => [
                'id',
                'code',
                'lat',
                'lon',
                'last_measurement',
                'created_at',
                'updated_at',
            ],
        ]);

    // Additional check to ensure last_measurement can be null
    $responseData = $response->json();
    foreach ($responseData as $sensor) {
        if ($sensor['last_measurement'] !== null) {
            $this->assertArrayHasKey('humidity', $sensor['last_measurement']);
        } else {
            $this->assertNull($sensor['last_measurement']);
        }
    }
});
