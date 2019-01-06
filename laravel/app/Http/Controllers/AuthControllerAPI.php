<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Events\Registered;
use App\Mail\GmailExample;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\User as UserResource;
use Carbon\Carbon as Carbon;


class AuthControllerAPI extends Controller
{
    
    public function register(Request $request)
    {   
        //dd($request);
        $request->validate([
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'username' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ 0-9]+$/', //falta adicionar REGEX para numero
            'email' => 'required|email|unique:users,email',
            'type' => 'required|alpha',
            'password' => 'min:3',
            'photo_url' => 'required'

            
        ]);
            

        

        $user = new User();
        $user->fill($request->all());
        $user->password = Hash::make($user->password);
        $user->save();
        $token = uniqid();
        $user->blocked=1;
        $user->remember_token= $token;
        $user->photo_url = $request->photo_url;
        response()->json(new UserResource($user), 201);
        $user->save();
        Mail::to($user->email)->send(new GmailExample($user));
        
        return response()->json(['status' => 201, 'user'=>$user]);
    }


    public function resetPassword(Request $request)
    {
        return Redirect::to('http://projeto.dad/#/resetPassword/');
    }

    public function activateAccount($token)
    {

        $user= User::where('remember_token',$token)->first();
        $user->blocked = 0;
       // dd($user);
       if($user->email_verfied_at==null)
       {
           $user->email_verified_at = Carbon::now();
       }
        
        $user->save();
        return redirect('/#/resetpassword/'.$token);
        //return view('vue.index');

    }

    public function getFileName(Request $request){
        $path = $request->file('file')->store('profiles', 'public');
        $path = str_replace("profiles/","",$path);
        return $path;
    }
}
