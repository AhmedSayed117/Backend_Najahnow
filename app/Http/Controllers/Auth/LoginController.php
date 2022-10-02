<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Member;
use App\Models\Nutritionist;
use App\Models\Coach;
use App\Http\Controllers\Controller;

use App\Http\Traits\GeneralTrait;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;
    use GeneralTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth:api')->only('logout');/////////////////pug here
    }

    public function login(Request $request)
    {

        $creds = request()->only(['email', 'password']);
        $token = auth('api')->attempt($creds);
        if (!$token)
            return $this->returnError('E999', 401, 'incorrect email or password');
        $user =  User::where(['email' => $request['email']])->get()->first();
        $role = $user->role;
        $id = $user->id;
        $role_id = $id;
        if($role == 'member')
            $role_id = Member::where('user_id', $id)->get()->first()->id;
        else if($role == 'nutritionist')
            $role_id = Nutritionist::where('user_id', $id)->get()->first()->id;
        else if($role == 'admin')
            $role_id = null;
        else if($role == 'coach')
            $role_id = Coach::where('user_id', $id)->get()->first()->id;

        $name = $user->name;
//        Auth::login($user);
        // return response()->json(['token' => $token]);

        return $this->returnData('data', [
            'token' => $token,
            'role' =>  $role,
            'id' => $user->id,
            'role_id' => $role_id,
            'name' => $name,

        ],  200, 'logged in successfully');
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): \Illuminate\Http\JsonResponse
    {
//        auth();
        auth()->logout();
        return $this->returnSuccessMessage('logged out successfully');
    }
}
