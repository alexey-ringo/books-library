<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginAuthRequest;
use App\Http\Requests\RegisterAuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Auth;

class AuthController extends Controller
{

    public function login(LoginAuthRequest $request)
    {
        if(!Auth::attempt($request->validated())) {

            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $success['token'] = $request->user()->createToken('MyApp')->accessToken;

        return response()->json(['success' => $success]);

    }

    public function register(RegisterAuthRequest $request)
    {
        $input = $request->validated();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return response()->json(['success' => $success]);
    }

    /**
     * Logout admin (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $user = $request->user('api');

        $user->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function loggedUser(Request $request) {
        return response()->json(['data' => $request->user('api')]);
    }
}
