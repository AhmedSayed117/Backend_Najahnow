<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Equipment;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use GeneralTrait;
    public function index()
    {
        $equepments = Branch::with('userInfos')->with('equipments')->get();

        if (!$equepments)
        {
            return  $this-> returnError("001",'No Branches found');
        }
        else{
            return $this->returnData('Branch',$equepments,'Branches found');
        }
    }


    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->authorize('store',User::class);
        $rules = [
            "location" => "required",
            "crowd_meter" => "required|numeric",
            'picture' => 'mimes:svg,jpg,jpeg,png,tmp',
            'phone_number' => 'required|digits_between:10,11',
            'info' => 'required',
            'members_number' => "required|numeric",
            'coaches_number' => "required|numeric"
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator,$code);
        }

        $branchInfo = $request->all();
        $branch = new Branch($branchInfo);
        $branch->save();
        return $this->returnData('Branch',$branch,'Branch saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $branch = Branch::where('id',$id)->with('equipments')->get();
        if(!$branch)
            return $this->returnError('002','Branch is not found');
        else
            return $this->returnData('Branch',$branch,'Branch found ');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->authorize('store',User::class);
        $branch = Branch::find($id);

        if(!$branch)
            return $this->returnError('002','Membership is not found');
        else {
            $rules = [
                "location" => "required",
                "crowd_meter" => "required|numeric",
                'picture' => 'mimes:,svg,jpg,jpeg,png,tmp',
                'phone_number' => 'required|digits_between:10,11',
                'info' => 'required',
                'members_number' => "required|numeric",
                'coaches_number' => "required|numeric",
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($validator,$code);
            }
            $branchGeneralInfo = $request->all();
            $branch->update($branchGeneralInfo);
            return $this->returnData('Branch', $branch, 'Branch updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $this->authorize('store',User::class);
        $branch = Branch::find($id);

        if (!$branch::destroy($id))
        {
            return  $this-> returnError("001",'No Branches found');
        }
        else{
            return $this->returnData('Branch',$branch,'Branch deleted');
        }
    }

    public function assignEquipment(Request $request, $id)
    {

        $this->authorize('store',User::class);

        $rules = [
            "equipment_id" => ["required",'exists:equipments,id'],
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator,$code);
        }

        $branch = Branch::find($id);

        $equipmentId = $request->only('equipment_id');
        //dd($equipmentId);
        if($branch)
        {
            $branch->equipments()->attach($equipmentId,['quantity'=>'7']);
            $obj = [
                'branch'=>$branch,
                'equipment'=>Equipment::find($request->equipment_id)
            ];
            return $this->returnData('assignEquipment',$obj,'added successfully');
        }
        return $this->returnError('branch or equipment is not valid');
    }

    public function showEquipments( $id ){
        $branch = Branch::find($id);
        $equipment = $branch->equipments()->get();
        if($equipment)
            return $this->returnData('equipment', $equipment);
        return $this->returnError("equipment is not found","009");
    }
}
