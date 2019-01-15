<?php

namespace App\Http\Controllers;
use App\Profile;
use App\User;
use App\Country;
use function GuzzleHttp\Promise\all;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
class ProfilsController extends Controller
{

    public function getProfile (Request $request, $id)
    {

        $profile = Profile::where('user_id',$id)
             ->first();
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
        $credential = [
            'email'=> $request['data']['email'],
            'password' =>$request['password']
        ];
        $validate = Validator::make($credential,[
            'email' => 'string|email|required',
//            'password' => 'string|required',
        ]);
        $user = User::find($id);
//        $password = Hash::make($request['password']);
//        if(!Hash::check($request['password'], $user->password)){
//            return response()->json(['message' => 'Incorrect password'],401);
//        };

        if(!$validate->fails()){
            $user = Auth::user();
            $profile = Profile::where('user_id',$id)->first();
            $data_profile = $request->all()['data'];
            array_splice( $data_profile, 15);
            $data_user =[$data_profile['email'], $data_profile['nickname']];
//            $pattern = '^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$';
//
//            $number_pattern = preg_match($pattern, $request->get('number'));
//            if($number_pattern == 0 && $request->get('number') != null){
//                return response()->json([
//                    "message" => 'Wrong number'
//                ]);
//            }

            if ( $user->update($data_user)) {
                $profile->update($data_profile);
                return response()->json([
                    'message' => 'Successfully updated user!',
                    "profile" => $profile
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Something went wrong on update user',
                    'user' => $user
                ], 500);
            }
        } else {
            return response()->json([
                $validate->errors()
            ], 500);
        }
    }
    public function fileUpload(Request $request){
        $unix_timestamp_name = now()->timestamp.str_random(14);
        $server = "http://127.0.0.1:8000";
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $validate = Validator::make(["image"=> $file->getSize()],[
                'image' => 'max:4000000',
            ]);
            if($validate->fails()){
                return response()->json($validate->errors(), 400);
            };
            $file->move(public_path() . '/images',
                $unix_timestamp_name.'-'.$file->getClientOriginalName());
            return response()->json([
                'message' => 'upload successfully',
                'link'    => $server.'/images/'.$unix_timestamp_name.'-'
                    .$file->getClientOriginalName(),
            ]);
        } else {
            return response()->json([
                'message' => 'file isn`t image',
            ], 400);
        }
    }

    public function getCountry(Request $request){
        $country = Country::where('def', 1)
            ->join('country_flags', 'short', '=', 'country_flags.iso')
            ->join('CountryPhoneCods', 'full_eng', '=', 'CountryPhoneCods.TitleEN')
            ->select('full_eng', 'iso', 'flag_uri', 'PhoneCode')
            ->get();
        return response()->json(['data' => $country], 200);
    }

}
