<?php
use App\Models\Measurement;
use App\Models\Sensor;

it('has the correct fillable attributes', function () {
    $measurement = new Measurement();
    expect($measurement->getFillable())->toBe(['sensor_id', 'humidity', 'timestamp']);
});

it('belongs to a sensor', function () {
    $sensor = Sensor::factory()->create();
    $measurement = Measurement::factory()->create(['sensor_id' => $sensor->id]);

    expect($measurement->sensor)->toBeInstanceOf(Sensor::class)
        ->and($measurement->sensor->id)->toBe($sensor->id);
});
