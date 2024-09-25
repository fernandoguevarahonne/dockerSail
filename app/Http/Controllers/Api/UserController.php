<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    public function register(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return new UserResource($user);
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'data.attributes.email' => 'required|email',
            'data.attributes.password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            $user->token = $token;
            Log::info('User ' . $user->name . ' logged in authorized proyect');
            return new UserResource($user);
        } else {
            Log::error('User ' . $request->email . ' tried to log in unauthorized proyect');
            return response(['status' => 0, 'message' => 'Las credenciales no son validas, verifica tus datos'], Response::HTTP_UNAUTHORIZED);

        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response(['status' => 1, 'message' => 'Logout exitoso'], Response::HTTP_OK);
    }
}
