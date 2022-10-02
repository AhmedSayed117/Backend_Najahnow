<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Meal;
use App\Http\Traits\GeneralTrait;


class PlanMealController extends Controller
{
    use GeneralTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    //
    public function addMeal($planID)
    {
        $this->authorize('update',Plan::class);

        $validation = Validator::make(request()->all(),[ 
            'mealId' => 'required|exists:meal,id',
            'type' => 'required|in:breakfast,lunch,dinner,snack',
            'day' => 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday'
        ]);

        if($validation->fails()){

            $code = $this->returnCodeAccordingToInput($validation);
            return $this->returnValidationError($code,$validation);
        }

        $plan = Plan::find($planID);

        $isFound = $plan->meals->contains(function  ($val, $key) {
            return $val->info->meal_id == request('mealId') && $val->info->day == request('day') &&  $val->info->type == request('type') ;
        });

        if($isFound)
            return $this->returnError($this->getErrorCode('meal exists'),400,'Meal Already Exists In Plan');

        
        $plan->meals()->attach(request('mealId'), ['type' => request('type'), 'day' => request('day')]);

        return $this->returnSuccessMessage("Meal Added Successfully!");
    }

    public function removeMeal($planID)
    {
        $this->authorize('update',Plan::class);

        $validation = Validator::make(request()->all(),[ 
            'plan_meal_id' => 'required|integer'
        ]);

        if($validation->fails()){

            $code = $this->returnCodeAccordingToInput($validation);
            return $this->returnValidationError($code,$validation);
        }


        $plan = Plan::find($planID);

        $mealToDelete = $plan->meals->first(function  ($val, $key) {
            return $val->info->id == request('plan_meal_id');
        });

        if(!$mealToDelete)
            return $this->returnError($this->getErrorCode('meal not found'),404,'Meal Not Found In Plan');

        $plan->meals()->wherePivot('id', request('plan_meal_id'))->detach();

        return $this->returnSuccessMessage("Meal Deleted From Plan Successfully!");

    }

    public function updateMeal($planID)
    {
        $this->authorize('update',Plan::class);

        $validation = Validator::make(request()->all(),[
            "plan_meal_id" => 'required',
            'mealId' => 'exists:meal,id',
            'type' => 'in:breakfast,lunch,dinner,snack',
            'day' => 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday'

        ]);

        if($validation->fails()){

            $code = $this->returnCodeAccordingToInput($validation);
            return $this->returnValidationError($code,$validation);
        }

        $plan = Plan::find($planID);

        $mealToUpdate = $plan->meals->first(function  ($val, $key) {
            return $val->info->id == request('plan_meal_id');
        });

        
        if(!$mealToUpdate)
            return $this->returnError($this->getErrorCode('meal not found'),404,'Meal Not Found In Plan');
            

        if(request('mealId')){
            $isFound = $plan->meals->first(function  ($val, $key) {
                return 
                $val->info->meal_id == request('mealId')
                && $val->info->day == $mealToUpdate->info->day
                &&  $val->info->type == $mealToUpdate->info->type ;
            });
            if($isFound)
                return $this->returnError($this->getErrorCode('meal exists'),400,'Meal Already Found In Plan');

        }

        $update = [];

        if(request('type')){
            $update['type'] = request('type');
        }
        if(request('day')){
            $update['day'] = request('day');
        }
        
        if(request('mealId')){
            $update['meal_id'] = request('mealId');
        }

        $plan->meals()->wherePivot('id',$mealToUpdate->info->id)->updateExistingPivot($mealToUpdate, $update );
       
        return $this->returnSuccessMessage("Meal Updated Successfully!");

    }
}
