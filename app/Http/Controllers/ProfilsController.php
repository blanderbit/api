<?php

namespace App\Http\Controllers;
use App\Profile;
use App\User;
use function GuzzleHttp\Promise\all;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfilsController extends Controller
{

    public function getProfile (Request $request, $id)
    {

        $profile = Profile::find($id)
             ->getOriginal();
        return response()
             ->json($profile, 200);
    }


    public function removeProfile (Request $request, $id)
    {
        $user = Auth::user();
        $profile = Profile::find($id);
        $profile->delete();
        $user->delete();
        $request->user()->token()->revoke();
        return response()->json([
            "message" => "User delete successfully"
        ],200);

    }

    public function updateProfile (Request $request, $id)
    {
        $old_profile = Profile::find($id);

        $request->validate([
            'email' => 'string|email|required',
            'password' => 'string|required',
        ]);

        $user = Auth::user();
        $profile = Profile::find($id);

        $pattern = '/^\+380\d{3}\d{2}\d{2}\d{2}$/';

        if($request->get('number') != null){
            $number_pattern = preg_match($pattern, $request->get('number'));
            if($number_pattern == 0){
                return response()->json([
                    "message" => 'Wrong number'
                ]);
            }
        }
        if ( $user->update($request->only(['nickname', 'email', 'password'])) ) {
            $profile->name= $request->get('name') == null ? $old_profile->name :$request->get('name');
            $profile->nickname= $request->get('nickname') == null ? $old_profile->nickname :$request->get('nickname');
            $profile->number= $request->get('number') == null ? $old_profile->number :$request->get('number');
            $profile->surname= $request->get('surname') == null? $old_profile->surname :$request->get('surname');
            $profile->last_name= $request->get('last_name') == null? $old_profile->last_name :$request->get('last_name');
            $profile->email= $request->get('email') == null? $old_profile->email :$request->get('email');
            $profile->update();
            return response()->json([
                'message' => 'Successfully updated user!',
                'user' => $user,
                "profile" => $profile
            ], 200);
        } else {
            return response()->json([
                'message' => 'Something went wrong on update user',
                'user' => $user
            ], 500);
        }
    }
}
