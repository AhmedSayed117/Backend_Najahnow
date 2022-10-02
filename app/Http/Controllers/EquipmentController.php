<?php

namespace App\Http\Controllers;

use App\Traits\GeneralTrait;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Auth;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class EquipmentController extends Controller
{
    use GeneralTrait;
    public function index()
    {


        $equipment = Equipment::paginate(10);
        if (!$equipment)
        {
            return  $this-> returnError("001",'No equipment found');
        }
        else{
            return $this->returnData('equipment',$equipment,'Equipment found');
        }
    }

    public function store(Request $request)

    {
        $this->authorize('store',User::class);
        $rules = [
            "name" => "required",
            'picture.*' => 'mimes:jpg,jpeg,png',
            "description"=> 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator,$code);
        }
        $equipmentInfo = $request->only('name','description','picture');
        $equipment = Equipment::create($equipmentInfo);

        return $this->returnData('equipment',$equipment,'Equipment Created successfully');
    }

    public function show($id)
    {
        $equipment = Equipment::find($id);

        if(!$equipment)
            return $this->returnError('002','Equipment is not found');
        else
            return $this->returnData('equipment',$equipment,'Equipment Retrieved successfully');
    }



    public function update(Request $request, $id)
    {
        //same authorization as store in user policy
        $this->authorize('store',User::class);

        $equipment = Equipment::find($id);

        if(!$equipment)
            return $this->returnError('002','Equipment is not found');
        else
        {
            $rules = [
                "name" => "required",
                "description"=> 'required',
                'picture.*' => 'mimes:jpg,jpeg,png',

            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($validator,$code);
            }
            $equipment->update($request->all());
            return $this->returnData('equipment',$equipment,'Equipment updated successfully');
        }
    }

    public function delete($id)
    {
        //same authorization as store in user policy
        $this->authorize('store',User::class);
        $equipment = Equipment::find($id);

        if (!Equipment::destroy($id))
        {
            return  $this-> returnError("001",'No memberships found');
        }
        else{
            return $this->returnData('Membership',$equipment,'Membership deleted');
        }
    }


}
