<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Member;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;

class PlanMemberController extends Controller
{
    use GeneralTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    //
    public function addPlan($memberID)
    {
        $validation = Validator::make(request()->all(),[ 
            'plan_id' => 'required|exists:plan,id',
            'duration' => 'required|string'
        ]);

        if($validation->fails()){
            $code = $this->returnCodeAccordingToInput($validation);
            return $this->returnValidationError($code,$validation);
        }
        
        $member = Member::find($memberID);

        if(!$member)
            return $this->returnError($this->getErrorCode('member not found'),404,'Member Not Found');

        $this->authorize('addPlan',$member);
            
        $planToAdd = Plan::find(request('plan_id'));
        $planToAdd->currentMembers()->save($member);

        $member->plans()->attach(request('plan_id'), ['duration' => request('duration')]);

        return $this->returnSuccessMessage("Member Assigned To Plan Successfully!");

    }

    public function removePlan($memberID)
    {
        $member = Member::find($memberID);

        if(!$member){
            return $this->returnError($this->getErrorCode('member not found'),404,'Member Not Found');
        }
        $this->authorize('removePlan',$member);

        $member->plan()->dissociate();
        $member->save();

        return $this->returnSuccessMessage("Member's Plan Removed Successfully!");

    }

    public function updatePlan($memberID)
    {
        $validation = Validator::make(request()->all(),[ 
            'duration' => 'required|string'
        ]);

        if($validation->fails()){
            $code = $this->returnCodeAccordingToInput($validation);
            return $this->returnValidationError($code,$validation);
        }

        $member = Member::find($memberID);

        if(!$member){
            return $this->returnError($this->getErrorCode('member not found'),404,'Member Not Found');
        }
        $this->authorize('updatePlan',$member);

        $plan = $member->plans()->get()->last();
        
        $member->plans()->wherePivot('id',$plan->pivot->id)->updateExistingPivot($plan, ['duration' => request('duration')] );

        return $this->returnSuccessMessage("Member's Plan Updated Successfully!");

    }

    public function getActivePlan($memberID)
    {
        $member = Member::find($memberID);

        if(!$member){
            return $this->returnError($this->getErrorCode('member not found'),404,'Member Not Found');
        }

        $this->authorize('getActivePlan',$member);

        $plan = $member->plan()->with(['items', 'meals'])->get();

        if($plan != '[]')
            return $this->returnData('plan', $plan[0]);
        else
            return $this->returnData('plan', $plan);
    }

    public function getPlans($memberID)
    {
        $member = Member::find($memberID);

        if(!$member){
            return $this->returnError($this->getErrorCode('member not found'),404,'Member Not Found');
        }
        $this->authorize('plans',$member);

        $plans = $member->plans()->with(['items', 'meals'])->get();

        return $this->returnData('plans', $plans);
    }
}
