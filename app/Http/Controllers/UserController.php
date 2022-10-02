<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use GeneralTrait;

//    public function __construct()
//    {
//       $this->middleware('auth:api')->only('store');
//    }
    /**
     * @throws AuthorizationException
     */
    public function index()
    {

        $arr = array();
        $this->authorize('index',User::class);

        $users = User::get();

        foreach ($users as $user){
            $userinf = UserInfo::where('user_id',$user->id)->get()->first();

            if ($userinf)
            {
                $merged = array_merge($user->toArray(), $userinf->toArray());
                array_push($arr, $merged);
            }
        }

        if (!$users)
        {
            return  $this-> returnError("001",'No users found');
        }
        else{
            return $this->returnData('User',$arr,'User found');
        }
    }


    /**
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        //$this->authorize('store',User::class);

        $rules = [
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required",
            "role" => ['required', Rule::in(['admin', 'member','nutritionist','coach']),],
            "gender" => ['required', Rule::in(['male', 'female']),],
            'photo' => ['required','mimes:jpg,jpeg,png,svg'],
            'branch_id' => 'required|exists:App\Models\Branch,id',
            'number' => 'required|digits_between:10,11',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator,$code);
        }

        $userGeneralInfo = $request->only(['number','name','email','role']);
        $newPassword = bcrypt(request('password'));
        $userGeneralInfo['password'] = $newPassword;



        $user = User::create($userGeneralInfo);

        $photoName = time().$request->photo->getClientOriginalName();
        $request->photo->move(public_path('photosUsers'), $photoName);


        $userInfo = new UserInfo($request->only(['gender','photo','bio']));

        $userInfo->branch_id = $request->only(['branch_id'])['branch_id']; //
        $userInfo->user_id = $user->id;
        $userInfo->photo = 'photosUsers/' . $photoName;

        $userInfo->save();
        unset( $userInfo->user_id);

        $userinf = UserInfo::where('user_id',$user->id)->get()->first();
        $merged = array_merge($user->toArray(), $userinf->toArray());
        return $this->returnData('user',$merged,'user saved ');
    }


    public function show($id)
    {
        $userFound = User::find($id);
        $this->authorize('viewAny',$userFound);


        if(!$userFound)
            return $this->returnError('002','user is not found');
        else
            $userInfo = UserInfo::find($id);
        unset( $userInfo->user_id );

        $merged = array_merge($userFound->toArray(), $userInfo->toArray());
        return $this->returnData('user',$merged,'user found ');
    }


    public function update(Request $request , $id)
    {
        $user = User::find($id);
        $this->authorize('update',$user);

        $rules = [
            "email" => "email|unique:users",
            "role" => [Rule::in(['admin', 'member','nutritionist','coach']),],
            "gender" => [Rule::in(['male', 'female']),],
            'photo' => ['required','mimes:jpg,jpeg,png'],
            'branch_id' => 'exists:App\Models\Branch,id',
            'number' => 'digits_between:10,11',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator,$code);
        }

        if(!$user)
            return $this->returnError('002','user is not found');
        else {
            $userGeneralInfo = $request->only('name', 'number', 'email', 'password', 'role');
            $user->update($userGeneralInfo);
            $userInfo = UserInfo::find($id);
            $updatedUserInfo = $request->only(['gender', 'photo', 'bio']);
            $userInfo->update($updatedUserInfo);
            $userInfo->branch_id = $request->only(['branch_id'])['branch_id'];
            $merged = array_merge($user->toArray(), $userInfo->toArray());
            return $this->returnData('user', $merged, 'user updated successfully');
        }
    }


    /**
     * @throws AuthorizationException
     */
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);
        $this->authorize('delete',$user);

        if (!User::destroy($id))
        {
            return  $this-> returnError("001",'User not found');
        }
        else{
            return $this->returnData('User',$user,'User deleted');
        }
    }
}
