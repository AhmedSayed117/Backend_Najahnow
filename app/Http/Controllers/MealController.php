<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\GeneralTrait;

class MealController extends Controller
{
    use GeneralTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($meal)
    {
        $fetchedMeal = Meal::with('items')->find($meal);

        if ($fetchedMeal == null) //status code shouldn't be 200
            return $this->returnError($this->getErrorCode('meal not found'), 404, 'Meal Not Found');

        return $this->returnData('meal', $fetchedMeal);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        $this->authorize('create', Meal::class);

        $validation = Validator::make(request()->all(), [
            'title' => 'required|unique:meal,title',
            'description' => ['required'],
            'items.*.id' => ['required', 'numeric', 'exists:item,id'],
            'items.*.quantity' => ['required', 'numeric'],
        ]);

        if ($validation->fails()) {
            $code = $this->returnCodeAccordingToInput($validation);
            return $this->returnValidationError($code, $validation);
        }

        $meal = Meal::create([
            'title' => request('title'),
            'description' => request('description'),
            'nutritionist_id' => $user->nutritionist->id
        ]);

        $items = $request->items;
        $attachedArray = [];
        foreach ($items as $item) {
            $itemId = $item['id'];
            $quantity = $item['quantity'];
            $attachedArray[$itemId] = ['quantity' => $quantity];
        }
        $meal->items()->sync($attachedArray);
        $meal->items;

        return $this->returnData('meal', $meal, 201, 'meal is created successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ModelsMeal  $modelsMeal
     * @return \Illuminate\Http\Response
     */
    public function show(Meal $meal)
    {
        if (request('text') != null) {
            $prefixMeals = Meal::with('items')->where('title', 'LIKE', request('text') . '%')->get();
            $subStringMeals = Meal::with('items')->where('title', 'LIKE', '_%' . request('text') . '%')->get();
            $theUnion = $prefixMeals->merge($subStringMeals);
            $paginatedMeals = (collect($theUnion))->forPage(request('page'), 8)->all();

            return $this->returnData('meals', $paginatedMeals);
        }

        return $this->returnData('meals', Meal::with('items')->paginate(8, ['*'], 'page', request('page')));
    }

    public function showSearch()
    {
        return Meal::where('title', 'LIKE', request('prefix') . '%')->limit(5)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ModelsMeal  $modelsMeal
     * @return \Illuminate\Http\Response
     */
    public function edit(meal $meal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ModelsMeal  $modelsMeal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meal $meal)
    {
        $this->authorize('update', $meal);

        $validation = Validator::make(request()->all(), [
            'title' => 'required',
            'description' => ['required'],
            'items.*.id' => ['required', 'numeric', 'exists:item,id'],
            'items.*.quantity' => ['required', 'numeric'],
        ]);

        if ($validation->fails()) {
            $code = $this->returnCodeAccordingToInput($validation);
            return $this->returnValidationError($code, $validation);
        }

        $mealDetails = $request->only(['title', 'description']);
        $meal->update($mealDetails);

        $items = $request->items;
        $attachedArray = [];
        foreach ($items as $item) {
            $itemId = $item['id'];
            $quantity = $item['quantity'];
            $attachedArray[$itemId] = ['quantity' => $quantity];
        }
        $meal->items()->sync($attachedArray);
        $meal->items;

        return $this->returnData('meal', $meal, 200, 'meal is updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ModelsMeal  $modelsMeal
     * @return \Illuminate\Http\Response
     */
    public function destroy($meal)
    {
        $this->authorize('delete', Meal::class);

        $fetchedMeal = Meal::find($meal);

        if ($fetchedMeal == null)
            return $this->returnError($this->getErrorCode('meal not found'), 404, 'Meal Not Found');

        $fetchedMeal->delete();

        return $this->returnSuccessMessage("Meal Deleted Successfully!");
    }
}
