<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credential = request(['email', 'password']);
        $profile = Helper::registration($request, $credential, 'local');
        return response()->json([
            'message' => 'Successfully created user!',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credential = request(['email', 'password']);
        $login = Helper::autorization($request, $credential, 'local');
        if($login['status'] == 'err'){
            return response()->json($login,401);
        };
        return response()->json($login,200);
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

}
