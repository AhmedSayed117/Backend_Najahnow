<?php

namespace App\Http\Traits;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

trait UserTrait
{

    private function createUser(Request $request)
    {

        $userGeneralInfo = $request->only(['number','name','email','role']);
        $newPassword = bcrypt(request('password'));
        $userGeneralInfo['password'] = $newPassword;

        $photoName = time().$request->photo->getClientOriginalName();
        $request->photo->move(public_path('photosUsers'), $photoName);

        $user = User::create($userGeneralInfo);
        //create row in userInfo table

        $userInfo = new userInfo($request->only(['gender','photo','bio','age']));
        $userInfo->user_id = $user->id;
        $userInfo->branch_id=$request->only(['branch_id'])['branch_id'];

        $userInfo->photo = 'photosUsers/' . $photoName;

        $userInfo->save();

        //insert photo in firebase


        return $user->id;
    }

    private function updateUser(Request $request,$id)
    {
        $user = User::find($id);

        if(!$user)
            return $this->returnError('002','user is not found');
        else
        {
            $userGeneralInfo = $request->only(['number','name']);

            if($userGeneralInfo)
                $user->update($userGeneralInfo);

            if ($request['password'])
            {
                $user->password = Hash::make($request['password']);
                $user->save();
            }

            $userInfo = UserInfo::where('user_id',$id)->get()->first();

            $updatedUserInfo = $request->only('bio','weight','height','age');

            if($updatedUserInfo && $userInfo)
                $userInfo->update($updatedUserInfo);

            if ($request['photo'])
            {
                try {
                    unlink($userInfo->photo);

                    $photoName = time().$request->photo->getClientOriginalName();
                    $request->photo->move(public_path('photosUsers'), $photoName);
                    $userInfo->photo = 'photosUsers/'.$photoName;
                    $userInfo->save();
                }catch(\Exception $e)
                {
                    $photoName = time().$request->photo->getClientOriginalName();
                    $request->photo->move(public_path('photosUsers'), $photoName);
                    $userInfo->photo = 'photosUsers/'.$photoName;
                    $userInfo->save();
                }
            }
            return $this->returnData('user',$user,200,'user updated successfully');
        }
    }
}
