<?php

namespace App\Http\Controllers;
use App\Profile;
use App\User;
use function GuzzleHttp\Promise\all;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfilsController extends Controller
{

    public function getProfile (Request $request)
    {
        $user_id = $request->get('user_id');
        $profile = Profile::where('user_id',$user_id)
             ->get()
             ->first()
             ->getOriginal();
        return response()
             ->json($profile, 200);
    }
    public function removeProfile (Request $request)
    {
//        return response()->json($request->user());
    }

    public function updateProfile (Request $request)
    {
        $request->validate([
            'email' => 'string|email|unique:users|required',
            'password' => 'string|required',
        ]);
        $user = Auth::user();

        $nick = $user->nickname === $request->get('nickname')? $user->nickname:$request->get('nickname');
        $password = $user->password === $request->get('password')? $user->nickname:$request->get('password');
        $email = $user->email === $request->get('email')? $user->nickname:$request->get('email');

//        $user_table = User::where('id', $request->get('user_id'))->get();
        $pattern = '/^\+380\d{3}\d{2}\d{2}\d{2}$/';

        if($request->get('number') != null){
            $number_pattern = preg_match($pattern, $request->get('number'));
            if($number_pattern == 0){
                return response()->json([
                    "message" => 'Wrong number'
                ]);
            }
        }

//        dd($user);
        if ( $user->update($request->only(['nickname', 'email', 'password'])) ) {
            return response()->json([
                'message' => 'Successfully updated user!',
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'Something went wrong on update user',
                'user' => $user
            ], 500);
        }
    }
}
