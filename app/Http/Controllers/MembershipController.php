<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use GeneralTrait;
    public function index()
    {
        $memberships = Membership::with('members')->with('branch')->get();
        if (!$memberships)
        {
            return  $this-> returnError("001",'No memberships found');
        }
        else{
            return $this->returnData('Membership',$memberships,'Memberships found');
        }
    }



    public function store(Request $request)
    {
//        $this->authorize('store',User::class);
        $rules = [
            "title" => "required|string",
            'branch_id' => 'required|exists:App\Models\Branch,id',
            "duration" => "required|numeric",
            "description" => "required|string",
            "price" => "required|numeric",
            "limit_of_frozen_days" => 'required|numeric',
            "available_classes" => 'required|numeric',
            'discount' => 'numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator,$code);
        }

        $membershipInfo = $request->all();
        $membership = new Membership($membershipInfo);


        $membership->branch_id=$request->branch_id;
        $membership->save();

        return $this->returnData('Membership',$membership,'Memberships found');
    }

    public function show($id)
    {
        $membership = Membership::find($id);

        if(!$membership)
            return $this->returnError('002','membership is not found');
        else
            return $this->returnData('user',$membership,'membership found ');
    }


    public function update(Request $request, $id)
    {
        //same authorization as store in user policy
        $this->authorize('store',User::class);

        $rules = [
            "title" => "string",
            'branch_id' => 'exists:App\Models\Branch,id',
            "duration" => "numeric",
            "description" => "string",
            "price" => "numeric",
            "limit_of_frozen_days" => 'numeric',
            "available_classes" => 'numeric',
            'discount' => 'numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator,$code);
        }

        $membership = Membership::find($id);

        if(!$membership) return $this->returnError('002','Membership is not found');

        $membership->update($request->all());
        return $this->returnData('Membership', $membership, 'membership updated successfully');
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        //same authorization as store in user policy
        $this->authorize('store',User::class);
        $memberships = Membership::find($id);

        if (!Membership::destroy($id))
        {
            return  $this-> returnError("001",'No memberships found');
        }
        else{
            return $this->returnData('Membership',$memberships,'Membership deleted');
        }
    }
}
