<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\Measurement;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'humidity' => 'required|numeric|min:0|max:100',
        ]);

        // Find the sensor by its code
        $sensor = Sensor::where('code', $validated['code'])->first();

        if (!$sensor) {
            return response()->json(['message' => 'Sensor not found'], 404);
        }

        // Create the measurement
        $measurement = Measurement::create([
            'sensor_id' => $sensor->id,
            'humidity' => $validated['humidity'],
            'timestamp' => now(),
        ]);

        return response()->json([
            'message' => 'Measurement recorded successfully',
            'measurement' => $measurement,
        ], 201);
    }
}
