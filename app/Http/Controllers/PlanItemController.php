<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Item;
use App\Http\Traits\GeneralTrait;


class PlanItemController extends Controller
{
    use GeneralTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    //
    public function addItem($planID)
    {
        $this->authorize('update',Plan::class);

        $validation = Validator::make(request()->all(),[ 
            'itemId' => 'required|exists:item,id',
            'time' => 'required|date_format:H:i:s',
            'day' => 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'quantity' => 'required|integer|min:0|max:10'
        ]);

        if($validation->fails()){
            $code = $this->returnCodeAccordingToInput($validation);
            return $this->returnValidationError($code,$validation);
        }

        $plan = Plan::find($planID);

        $isFound = $plan->items->contains(function  ($val, $key) {
            return $val->info->item_id == request('itemId') && $val->info->day == request('day') &&  $val->info->time == request('time') ;
        });

        if($isFound)
            return $this->returnError($this->getErrorCode('item exists'),400,'Item Already Exists In Plan');

        
        $plan->items()->attach(request('itemId'), ['time' => request('time'), 'day' => request('day'), 'quantity' => request('quantity')]);

        return $this->returnSuccessMessage("Item Added Successfully!");

    }

    public function removeItem($planID)
    {
        $this->authorize('update',Plan::class);

        $validation = Validator::make(request()->all(),[ 
            'plan_item_id' => 'required|integer'
        ]);

        if($validation->fails())
        {
            $code = $this->returnCodeAccordingToInput($validation);
            return $this->returnValidationError($code,$validation);
        }
        

        $plan = Plan::find($planID);

        $itemToDelete = $plan->items->first(function  ($val, $key) {
            return $val->info->id == request('plan_item_id');
        });

        if(!$itemToDelete)
            return $this->returnError($this->getErrorCode('item not found'),404,'Item Not Found In Plan');


        $plan->items()->wherePivot('id', $itemToDelete->info->id)->detach($itemToDelete);

        return $this->returnSuccessMessage("Item Deleted From Plan Successfully!");

    }

    public function updateItem($planID)
    {
        $this->authorize('update',Plan::class);

        $validation = Validator::make(request()->all(),[ 
            "plan_item_id" => 'required',
            'itemId' => 'exists:item,id',
            'time' => 'date_format:H:i:s',
            'day' => 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'quantity' => 'integer'
        ]);

        if($validation->fails()){
            $code = $this->returnCodeAccordingToInput($validation);
            return $this->returnValidationError($code,$validation);
        }

        $plan = Plan::find($planID);

        $itemToUpdate = $plan->items->first(function  ($val, $key) {
            return $val->info->id == request('plan_item_id');
        });

        if(!$itemToUpdate)
            return $this->returnError($this->getErrorCode('item not found'),404,'Item Not Found In Plan');
        

        
        if(request('itemId')){
            $isFound = $plan->items->first(function  ($val, $key) use($itemToUpdate){
                return $val->info->item_id == request('itemId') 
                && $val->info->day == $itemToUpdate->info->day 
                &&  $val->info->type == $itemToUpdate->info->type ;
            });
            if($isFound)
                return $this->returnError($this->getErrorCode('item exists'),400,'Item Already Found In Plan');

        }
        $update = [];

        if(request('time')){
            $update['time'] = request('time');
        }
        if(request('day')){
            $update['day'] = request('day');
        }
        if(request('quantity')){
            $update['quantity'] = request('quantity');
        }
        if(request('itemId')){
            $update['item_id'] = request('itemId');
        }

        $plan->items()->wherePivot('id',$itemToUpdate->info->id)->updateExistingPivot($itemToUpdate, $update);
        
        return $this->returnSuccessMessage("Item Updated Successfully!");

    }
}
