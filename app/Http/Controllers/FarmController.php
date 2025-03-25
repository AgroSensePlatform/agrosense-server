<?php

namespace App\Http\Controllers;

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
            'coordinates' => 'nullable',
        ]);

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
}
