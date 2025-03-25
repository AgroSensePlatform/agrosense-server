<?php
use App\Models\Farm;

it('can create a farm instance', function () {
    $farm = Farm::factory()->make();

    expect($farm)->toBeInstanceOf(Farm::class);
});

it('can create a farm in the database', function () {
    $farm = Farm::factory()->create();

    expect($farm)->toBeInstanceOf(Farm::class)
        ->and($farm->name)->not->toBeEmpty();
});
