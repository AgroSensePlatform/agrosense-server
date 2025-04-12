<?php

use App\Models\User;
use App\Models\Sensor;



it('has the correct fillable attributes', function () {
    $user = new User();
    expect($user->getFillable())->toBe(['name', 'email', 'password']);
});

it('hides the correct attributes for serialization', function () {
    $user = new User();
    expect($user->getHidden())->toBe(['password', 'remember_token']);
});



it('can create a user instance', function () {
    $user = User::factory()->make();

    expect($user)->toBeInstanceOf(User::class);
});

it('can create a user', function () {
    $user = User::factory()->create();

    expect($user)->toBeInstanceOf(User::class);
});

it('a user has sensors', function () {
    $user = User::factory()->create();
    $sensors = Sensor::factory()->count(3)->create(['user_id' => $user->id]);

    expect($user->sensors)->toHaveCount(3);
    expect($user->sensors->pluck('id')->toArray())->toEqual($sensors->pluck('id')->toArray());
});
