<?php

use App\Models\User;
use App\Models\Farm;
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
