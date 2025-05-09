<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function index(Request $request)
    {
        //if no user is authenticated, return an empty array
        if (!$request->user()) {
            return response()->json([]);
        }

        $sensors = $request->user()->sensors()->with('latestMeasurement')->get();

        return response()->json($sensors);
    }
    public function test(Request $request)
    {
        //get all sensors of user 35
        $sensors = Sensor::where('user_id', 35)->with('latestMeasurement')->get();

        return response()->json($sensors);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'code' => 'required|string|unique:sensors,code',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);

        $sensor = Sensor::create([
            'user_id' => $request->user()->id,
            'farm_id' => $validated['farm_id'],
            'code' => $validated['code'],
            'lat' => $validated['lat'],
            'lon' => $validated['lon'],
        ]);

        return response()->json($sensor, 201);
    }

    public function show(Sensor $sensor)
    {
        $this->authorize('view', $sensor);

        $sensor->load(['measurements' => function ($query) {
            $query->latest('timestamp')->limit(100);
        }]);

        return response()->json($sensor);
    }

    public function destroy(Sensor $sensor)
    {
        $this->authorize('delete', $sensor);

        $sensor->delete();

        return response()->json(['message' => 'Sensor deleted successfully']);
    }


    public function update(Request $request, Sensor $sensor)
    {
        $this->authorize('update', $sensor);

        $validated = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);

        $sensor->update([
            'farm_id' => $validated['farm_id'],
            'lat' => $validated['lat'],
            'lon' => $validated['lon'],
        ]);

        return response()->json([
            'message' => 'Sensor updated successfully',
            'sensor' => $sensor,
        ], 200);
    }


    public function scan(Request $request)
    {
        $validated = $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'code' => 'required|string',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);

        // Check if the sensor already exists
        $sensor = Sensor::where('code', $validated['code'])->first();

        if ($sensor) {
            // Use the update method to update the existing sensor
            return $this->update($request, $sensor);
        }

        // Call the store method to create a new sensor
        return $this->store($request);
    }
}
