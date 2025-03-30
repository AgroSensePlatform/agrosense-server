<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:4',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request)
{
    $user = $request->user();

    $validated = $request->validate([
        'name' => 'sometimes|string',
        'email' => 'sometimes|string|email|unique:users,email,' . $user->id,
        'password' => 'sometimes|string|min:4',
    ]);

    if (isset($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    }

    $user->update($validated);

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user,
    ]);
}

    public function delete(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
