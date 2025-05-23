<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;


use Illuminate\Http\Request;
use App\Models\Farm;

class FarmController extends Controller
{
    public function index(Request $request)
    {
        $farms = $request->user()->farms;
        return response()->json($farms);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'coordinates' => 'nullable|array',
        ]);

        // Convert coordinates to JSON if provided
        if (isset($validated['coordinates'])) {
            $validated['coordinates'] = json_encode($validated['coordinates']);
        }

        //     // Log the request data
        // Log::info('Farm creation request:', [
        //     'user_id' => $request->user()->id,
        //     'data' => $validated,
        // ]);

        $farm = $request->user()->farms()->create($validated);

        return response()->json($farm, 201);
    }

    public function show(Farm $farm)
    {
        $this->authorize('view', $farm);

        return response()->json($farm);
    }

    public function destroy(Farm $farm)
    {
        $this->authorize('delete', $farm);

        $farm->delete();

        return response()->json(['message' => 'Farm deleted successfully']);
    }


    public function update(Request $request, Farm $farm)
    {
        $this->authorize('update', $farm);

        $validated = $request->validate([
            'name' => 'required|string',
            'coordinates' => 'nullable',
        ]);

        $farm->update($validated);

        return response()->json($farm);
    }


    public function sensors(Farm $farm)
    {
        $this->authorize('view', $farm);

        $sensors = $farm->sensors()->with('latestMeasurement')->get();

        return response()->json($sensors);
    }
}
