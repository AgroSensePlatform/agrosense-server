<?php
use App\Models\Farm;
use App\Models\Sensor;


it('can create a farm instance', function () {
    $farm = Farm::factory()->make();

    expect($farm)->toBeInstanceOf(Farm::class);
});

it('can create a farm in the database', function () {
    $farm = Farm::factory()->create();

    expect($farm)->toBeInstanceOf(Farm::class)
        ->and($farm->name)->not->toBeEmpty();
});


it('a farm can have sensors', function () {
    $farm = Farm::factory()->create();
    $sensors = Sensor::factory()->count(3)->create(['farm_id' => $farm->id]);

    expect($farm->sensors)->toHaveCount(3)
        ->and($farm->sensors->pluck('id')->sort()->values())
        ->toEqual($sensors->pluck('id')->sort()->values());
});
