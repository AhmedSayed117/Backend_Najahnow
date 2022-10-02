<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Meal;
use App\Models\Item;
use App\Http\Traits\GeneralTrait;

class MealItemController extends Controller
{
    use GeneralTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    //
    public function addItem($mealID)
    {
        $this->authorize('update', Meal::class);

        $validation = Validator::make(request()->all(),[ 
            'itemId' => 'required|exists:item,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        if($validation->fails())
        {
             $code = $this->returnCodeAccordingToInput($validation);
             return $this->returnValidationError($code,$validation);
        }

        $meal = Meal::find($mealID);

        if($meal == null)
            return $this->returnError($this->getErrorCode('meal not found'), 404, 'Meal Not Found');

        if($meal->items->contains('id', request('itemId')))
            return $this->returnError($this->getErrorCode('item exists'), 404, 'Item already exists in the meal!');

        $meal->items()->attach(request('itemId'), ['quantity' => request('quantity')]);

        return $this->returnSuccessMessage("Meal Item Added Successfully!");
    }

    public function removeItem($mealID)
    {
        $this->authorize('update', Meal::class);

        $validation = Validator::make(request()->all(),[ 
            'itemId' => 'required|exists:item,id'
        ]);

        if($validation->fails())
        {
             $code = $this->returnCodeAccordingToInput($validation);
             return $this->returnValidationError($code,$validation);
        }

        $meal = Meal::find($mealID);

        if($meal == null)
            return $this->returnError($this->getErrorCode('meal not found'), 404, 'Meal Not Found');

        if(!$meal->items->contains('id', request('itemId')))
            return $this->returnError($this->getErrorCode('item not found'), 404, 'Item does not exist in the meal!');

        $meal->items()->detach(request('itemId'));

        return $this->returnSuccessMessage("Meal Item Removed Successfully!");
    }

    public function updateItem($mealID)
    {
        $this->authorize('update', Meal::class);

        $validation = Validator::make(request()->all(),[ 
            'itemId' => 'required|exists:item,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        if($validation->fails())
        {
             $code = $this->returnCodeAccordingToInput($validation);
             return $this->returnValidationError($code,$validation);
        }

        $meal = Meal::find($mealID);

        if($meal == null)
            return $this->returnError($this->getErrorCode('meal not found'), 404, 'Meal Not Found');

        if(!$meal->items->contains('id', request('itemId')))
            return $this->returnError($this->getErrorCode('item not found'), 404, 'Item does not exist in the meal!');
        
        $meal->items()->updateExistingPivot(request('itemId'), ['quantity' => request('quantity')] );

        return $this->returnSuccessMessage("Meal Item Updated Successfully!");
    }
}
