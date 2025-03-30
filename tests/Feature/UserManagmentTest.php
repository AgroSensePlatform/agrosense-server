<?php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can update the authenticated user', function () {
    // Create a user and authenticate
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'oldemail@example.com',
    ]);
    $this->actingAs($user);

    // Define the updated data
    $updatedData = [
        'name' => 'New Name',
        'email' => 'newemail@example.com',
        'password' => 'newpassword',
    ];

    // Send a PUT request to update the user
    $response = $this->putJson('/api/user', $updatedData);

    // Assert the response status and structure
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'User updated successfully',
            'user' => [
                'name' => 'New Name',
                'email' => 'newemail@example.com',
            ],
        ]);

    // Assert the user was updated in the database
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'New Name',
        'email' => 'newemail@example.com',
    ]);

    // Assert the password was updated
    $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
});

it('can delete the authenticated user', function () {
    // Create a user and authenticate
    $user = User::factory()->create();
    $this->actingAs($user);

    // Send a DELETE request to delete the user
    $response = $this->deleteJson('/api/user');

    // Assert the response status and message
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'User deleted successfully',
        ]);

    // Assert the user was deleted from the database
    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});
