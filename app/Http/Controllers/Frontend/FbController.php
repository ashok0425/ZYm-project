<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\User;
use Validator;
use Socialite;
use Exception;
use Auth;

class FbController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookSignin()
    {
        try {
    
            $user = Socialite::driver('facebook')->user();
            $facebookId = User::where('facebook_id', $user->id)->first();
   
            if($facebookId){
                Auth::login($facebookId);
             
                return redirect()->intended(session()->get('url'));
            }else{
                
                $createUser = User::create([
                    'name' => $user->name,
                    'email' => 'email'.rand(),
                    'facebook_id' => $user->id,
                    'password' => encrypt('john123')
                ]);
    
                Auth::login($createUser);
               
                return redirect()->intended(session()->get('url'));
            }
    
        } catch (Exception $exception) {
            $notification=array(
                'messege'=>'Email registered already!',
                'alert-type'=>'error'
                 );
            return redirect()->route('login')->with($notification);
        }
    }
}