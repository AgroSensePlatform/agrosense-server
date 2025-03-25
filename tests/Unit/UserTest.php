<?php

use App\Models\User;



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
