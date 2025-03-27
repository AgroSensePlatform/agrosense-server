<?php
use App\Models\Sensor;
use App\Models\User;
use App\Models\Farm;
use App\Models\Measurement;

it('has the correct fillable attributes', function () {
    $sensor = new Sensor();
    expect($sensor->getFillable())->toBe(['user_id', 'farm_id', 'code', 'lat', 'lon']);
});

it('belongs to a user', function () {
    $user = User::factory()->create();
    $sensor = Sensor::factory()->create(['user_id' => $user->id]);

    expect($sensor->user)->toBeInstanceOf(User::class)
        ->and($sensor->user->id)->toBe($user->id);
});

it('belongs to a farm', function () {
    $farm = Farm::factory()->create();
    $sensor = Sensor::factory()->create(['farm_id' => $farm->id]);

    expect($sensor->farm)->toBeInstanceOf(Farm::class)
        ->and($sensor->farm->id)->toBe($farm->id);
});


it('has many measurements', function () {
    $sensor = Sensor::factory()->create();

    $measurement1 = Measurement::factory()->create(['sensor_id' => $sensor->id]);
    $measurement2 = Measurement::factory()->create(['sensor_id' => $sensor->id]);

    expect($sensor->measurements)->toHaveCount(2)
        ->and($sensor->measurements->pluck('id'))->toContain($measurement1->id, $measurement2->id);
});
