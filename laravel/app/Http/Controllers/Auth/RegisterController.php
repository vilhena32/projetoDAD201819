<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Auth\Notifications;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;





use DB;



use App\Mail\GmailExample;



use Illuminate\Foundation\Auth\User as Authenticatable;



class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */



    use RegistersUsers;


    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
        return view('vue.index');
  
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['unique:users', 'required', 'max:25'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:3', 'confirmed'],
            'photo' => [],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        
        //dd($user);
        $user->save();
        Mail::to($user->email)->send(new GmailExample($user)); 

    
    
    }
    

   /* function sendEmail(User $user)
        {
          /*  return $this->from('mail@example.com', 'Restaurant Management')
            ->subject('Reset your password')
            ->markdown('mails.example')
            ->with([
                'name' => $user->name,
                'link' => 'http://www.projeto.dad'
            ]);*/

 //           Mail::to($user->email)->send(new GmailExample($user));
//}
}
