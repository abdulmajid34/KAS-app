<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // register
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users',
            'nama_akun' => 'nullable|string|max:255',
            'password' => 'required|string|min:6',
            'role' => 'in:admin,ketua_kelas,bendahara,siswa',
        ]);

        $user = User::create([
            'username' => $request->username,
            'nama_akun' => $request->nama_akun,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'siswa',
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users,username|max:12',
            'password' => 'required|min:6',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        // $userData = $user->only('id', 'username', 'nama_akun', 'role');

        return response()->json(['token' => $token, 'user' => $user]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
