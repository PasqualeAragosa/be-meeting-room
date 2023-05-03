<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function _construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create(array_merge(
            $validatedData->validated(),
            ['password' => bcrypt($request->password)]
        ));

        // Assegna un nuovo record della colonna 'team'
        $group = new Group();
        $group->user_id = User::orderBy('id', 'desc')->pluck('id')->first();
        $group->team = Group::orderBy('id', 'desc')->pluck('team')->first();
        $group->team++;
        $group->save();

        if (!$token = auth()->attempt($validatedData->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        if (!$token = auth()->attempt($validatedData->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $result = $this->createNewToken($token);

        return response()->json([
            'success' => $result->original['success'],
            'access_token' => $result->original['access_token']
        ]);
    }

    public function createNewToken($token)
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'espires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function profile()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'success' => true,
            'message' => 'User logged out'
        ]);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }
}
