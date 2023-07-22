<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;


class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password'=> 'required',
            'token_name' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();

        if(!$user || !\Hash::check($request->password, $user->password)){
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        //TODO: Check if user is verified
        if(!$user->email_verified_at){
            return response([
                'message' => ['Please verify your email address.']
            ], 404);
        }

        return new AuthResource($user);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $newUser = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => \Hash::make($data['password'])
        ]);

        //TODO: Send verification email
        $newUser->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'User created successfully. Please verify your email address.',
            'user' => $newUser
        ], 201);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
