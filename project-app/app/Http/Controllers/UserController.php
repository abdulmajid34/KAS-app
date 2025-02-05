<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Validation\ValidatesRequests;

class UserController extends Controller
{
    use ValidatesRequests;

    // show list users
    public function index()
    {
        return UserResource::collection(User::all());
    }

    // create users
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'username' => 'required|max:12|unique:users,username',
                'password' => 'required|min:6',
                'nama_akun' => 'required',
                'role' => 'required',
                'status' => 'required',
            ]);
            $user = User::create([
                'username' => $validatedData['username'],
                'password' => bcrypt($validatedData['password']),
                'nama_akun' => $validatedData['nama_akun'],
                'role' => $validatedData['role'],
                'status' => $validatedData['status'],
            ]);

            return new UserResource($user);
        } catch (\Illuminate\Validation\ValidationException $err) {
            return response()->json(['error' => 'Validation failed', 'messages' => $err->errors()], 422);
        }
    }

    // show user by id
    public function show(User $user)
    {
        return new UserResource($user);
    }

    // update users
    public function update(Request $request, User $user) {}

    // delete users
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
