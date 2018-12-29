<?php
/**
 * Created by PhpStorm.
 * User: cubex
 * Date: 20.12.18
 * Time: 15:44
 */
namespace App\Helpers;
use App\User;
use App\Session;
use App\Profile;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class Helper
{
    public static function registration($data, $credentials, $name) {
        $user = User::firstOrCreate([
            'email'    => $data->email], [
            'nickname' => $data->nickname,
            'password' => bcrypt($data->password),
        ]);
        $profile = Profile::firstOrCreate(['email' => $data->email],[
            "nickname"   => $user->nickname,
            "user_id"    => $user->id,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at,
        ]);
        $send_profile = $profile->where('email', $data->email)->first()->toArray();
        if($name == 'sotial'){
            $oldtoken = DB::table('oauth_access_tokens')->where('user_id', $user->id);
            if($oldtoken->first() != null){
                $oldtoken->delete();
            }
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->save();
            return ([
             'status' => 'ok',
             'data' => (array) $send_profile,
             'password' => $data->password,
             'token' => $tokenResult->accessToken
            ]);
        } else if ($name == 'local'){
            return (['status' => 'ok']);
        }
    }
    public static function autorization($data, $credentials, $name){
        if (!Auth::attempt($credentials)){
            return ([
                'status' => 'err',
                'message' => 'Unauthorized'
            ]);
        }
        $user = $data->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($data->remember_me){
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        return ([
             'message'=> 'you are logged in',
             'status' => 'ok',
             'user_id' => $user->id,
             'token' => $tokenResult->accessToken,
        ]);
    }

}
