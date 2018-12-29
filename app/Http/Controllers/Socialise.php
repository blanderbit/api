<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Laravel\Socialite\Facades\Socialite;
//use Illuminate\Support\Facades\Session;
use App\Helpers\Helper;
use App\Session;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Validator;
use Socialite;
class Socialise extends Controller
{
     public function redirectToProvider(Request $request, $type)
    {
//        return response()->json('ok',200);
        return Socialite::driver($type)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request, $type)
    {\Log::info('asdasd');
        $user = Socialite::driver('google')->user();

        $user->password = str_random(8);
        $credential = array('password' => $user->password, 'email' => $user->email);
        $validate = Validator::make($credential,[
            'email' => 'required|unique:users|email',
        ]);

        \Log::info(!$validate->fails());
        if(!$validate->fails()){
            $reg = Helper::registration($user, $credential, 'sotial');
            return redirect(
                'http://localhost:4200/socialize/'.'ok/'.
                $reg['token'].'/'.$reg['password'].
                '/'.$reg['data']['email'].'/'.$type);

        } else {
            return redirect('http://localhost:4200/socialize/'.'err'.'/User already registered');
        }

    }
}


