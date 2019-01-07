<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use \Carbon\Carbon ;
use App\User;
use Illuminate\Support\Facades\Auth;


class UsersControllerAPI extends Controller
{
    public function index(Request $request){

        return UserResource::collection(User::paginate(10));

    }

    public function managerListing(Request $request){

        return UserResource::collection(User::where('type','manager')->paginate(10));

    }

    public function show($id)
    {
        return new UserResource(User::find($id));
    }

    public function getUserByToken($token)
    {

        return new UserResource(User::where('remember_token',$token)->first());
    }

    public function store(Request $request)
    {
        $request->validate([
                'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
                'username' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
                'email' => 'required|email|unique:users,email',
                'type' => 'required|alpha',
                'password' => 'min:3'
            ]);
        $user = new User();
        $user->fill($request->all());
        $user->password = Hash::make($user->password);
        $user->save();
        return response()->json(new UserResource($user), 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'email' => 'required|email|unique:users,email,'.$id,
            'age' => 'integer|between:18,75',
            'photo_url' => 'required'
        ]);
        $user = User::findOrFail($id);
        $user->update($request->all());
        return new UserResource($user);
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'min:3'
            ]);
        $user = User::findOrFail($id);
        $userData = $request->only(["password"]);
        $userData['password'] = Hash::make($userData['password']);
        User::find($id)->update($userData);
        //$user->password= Hash::make($user->password);
        return new UserResource($user);

    }

    public function blocked(Request $request, $id)
    {

        $user = User::findOrFail($id);
        if ($user->blocked == 1) {
            $user->blocked = 0;
        }else{
            $user->blocked = 1;
        }
        $user->save();
        return new UserResource($user);

    }

    public function activeShift(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->shift_active == 1){
            $user->shift_active = 0;
            $user->last_shift_end = Carbon::now();
        }else{
            $user->shift_active = 1;
            $user->last_shift_start = Carbon::now();
        }

        $user->save();
        return new UserResource($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }

    public function emailAvailable(Request $request)
    {
        $totalEmail = 1;
        if ($request->has('email') && $request->has('id')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->where('id', '<>', $request->id)->count();
        } else if ($request->has('email')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->count();
        }
        return response()->json($totalEmail == 0);
    }

    public function myProfile(Request $request)
    {
        return new UserResource($request->user());
    }

    public function getBlockedUsers(Request $request)
    {
        return UserResource::collection(User::where('blocked',1)->get());
    }

    public function getUnblockedUsers()
    {
        return UserResource::collection(User::where('blocked',0)->get());

    }

}
