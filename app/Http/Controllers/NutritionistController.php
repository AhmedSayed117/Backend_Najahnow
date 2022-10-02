<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Traits\GeneralTrait;
use App\Http\Traits\UserTrait;
use App\Models\Nutritionist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class NutritionistController extends Controller
{

    use GeneralTrait;
    use UserTrait;
    public function fetchMyMembers(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $nutritionist = $user->nutritionist;
        $members = $nutritionist->members;
        $members->each(function($member) {
            $member->user;
        });
        return $this->returnData('members', $members, 200, "members fetched successfully");
    }

    public function index()
    {
        $Nutritionists = Nutritionist::with('user')->with('userinfo')->with('members')->get();
        if (!$Nutritionists) return $this->returnError("001",'No Nutritionists found');
        else return $this->returnData('Nutritionist',$Nutritionists,'Nutritionists found');
    }

    public function store(Request $request)
    {
        $this->authorize('store',User::class);
        $rules = [
            //user
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required",
            'number' => 'required|digits_between:10,11',
            "role" => ['required', Rule::in(['nutritionist']),],

            //nutritionist
            'is_checked' => 'required|boolean',

            //user info
            'bio' => ['string','required'],
            "gender" => ['required', Rule::in(['male', 'female']),],
            'age' => ['integer','required'],
            'photo' => ['required','mimes:jpg,jpeg,png,svg'],
            'branch_id' => 'required|exists:App\Models\Branch,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator,$code);
        }

        $user_id =$this->createUser($request);

        $NutritionistInfo = $request->only(['is_checked']);

        $Nutritionist = new Nutritionist($NutritionistInfo);

        $Nutritionist->user_id = $user_id;

        $Nutritionist->save();

        $info = User::where('id',$Nutritionist->user_id)->with('userInfos')->get()->first();

        $obj = [
            'Nutritionist'=>$Nutritionist,
            'nutritionistinfo'=>$info,
        ];

        return $this->returnData('Nutritionist',$obj,'Nutritionist created successfully');
    }

    public function show($id)
    {
        $Nutritionist = Nutritionist::find($id);
        if ($Nutritionist)
        {
            $user = User::find($Nutritionist->user_id);
            $info = User::where('id',$Nutritionist->user_id)->with('userInfos')->get();

            $this->authorize('viewAny',$user);
            return $this->returnData('Nutritionist',$info,'Nutritionist found');
        }
        return $this->returnError('002','Nutritionist is not found');
    }

    public function update(Request $request,$id): \Illuminate\Http\JsonResponse
    {
        $Nutritionist = Nutritionist::find($id);
        if ($Nutritionist)
        {
            $user = User::find($Nutritionist->user_id);

            $this->authorize('update',$user);

            $rules = [
                'name' => ['string'],
                'age' => ['integer'],
                'weight' => ['integer'],
                'height' => ['integer'],
                'bio' => ['string'],
                'email' => ['string', 'email', 'max:255', 'unique:users'],
                'password' => ['string'],
                'role' => [ Rule::in(['nutritionist']),],
                'gender' => [ Rule::in(['male', 'female']),],
                'photo' => ['mimes:jpg,jpeg,png,svg'],
                'number' => ['digits_between:10,11'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($validator,$code);
            }
            $this->updateUser($request,$Nutritionist->user_id);

            $info = User::where('id',$Nutritionist->user_id)->with('userInfos')->get();

            return $this->returnData('Nutritionist', $info, 'Nutritionist updated successfully');
        }
        else return $this->returnError('002','Nutritionist is not found');
    }

    public function delete($id)
    {
        $Nutritionist = Nutritionist::find($id);
        if ($Nutritionist){
            $user = User::find($Nutritionist->user_id);
            $this->authorize('delete',$user);
            User::destroy($Nutritionist->user_id);
            return $this->returnData('Nutritionist',$Nutritionist,'nutritionist deleted');
        }
        return  $this-> returnError("001",'Nutritionist not found');
    }

    public function assignMember(Request $request, $id)
    {

        $rules = [
            $id => ['exists:nutritionists,id'],
            'member_id' => ['required','exists:members,id'],
            'start_date' => ['string','required'],
            'end_date' => ['string','required'],
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }

        $nutritionist = Nutritionist::find($id);

        $memberId = $request->only('member_id');

        $sessionInfo = $request->only('start_date','end_date');

        if( $nutritionist )
        {
            $nutritionist->members()->attach($memberId,$sessionInfo);

            $obj = [
                'nutritionist'=>$nutritionist,
                'member'=>Member::find($request->member_id),
            ];
            return $this->returnData('member added',$obj,'nutritionist added successfully');

        }else{
            return $this->returnError('nutritionist or member is not valid');
        }
    }

    public function showMembers( $id ){
        $nutritionist = Nutritionist::find($id);
        if($nutritionist){
            $members = $nutritionist->members()->get();
            return $this->returnData('members', $members);
        }else{
            return $this->returnError('nutritionist is not found');
        }
    }
}
