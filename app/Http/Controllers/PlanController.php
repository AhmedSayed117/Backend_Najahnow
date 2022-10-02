<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\GeneralTrait;
use App\Models\Member;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    use GeneralTrait;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', Plan::class);

//        if (request('text') != null) {
//            $prefixPlans = Plan::with(['items', 'meals'])->where('title', 'LIKE', request('text') . '%')->get();
//            $subStringPlans = Plan::with(['items', 'meals'])->where('title', 'LIKE', '_%' . request('text') . '%')->get();
//            $theUnion = $prefixPlans->merge($subStringPlans);
//            $paginatedPlans = (collect($theUnion))->forPage(request('page'), 8)->all();
//
//            return $this->returnData('plans', $paginatedPlans);
//        }
        $plans = Plan::with('nutritionist')->with('currentMembers')->with('meals')->with('items')->get();
        if ($plans)return $this->returnData('plans',$plans);
        return $this->returnError("E0011",404,'not found');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', Plan::class);


        $validation = Validator::make($request->all(), [
            'title' => 'required|string|unique:plan,title',
            'description' => 'required|string',

            'items.*.id' => ['required', 'numeric', 'exists:item,id'],
            'items.*.day' => ['required'],
            'items.*.type' => ['required'],

            'meals.*.id' => ['required', 'numeric', 'exists:meal,id'],
            'meals.*.day' => ['required'],
            'meals.*.type' => ['required'],
        ]);

        if ($validation->fails()) {
            $code = $this->returnCodeAccordingToInput($validation);
            return $this->returnValidationError($code, $validation);
        }



        $plan = Plan::create([
            'title' => request('title'),
            'description' => request('description'),
            'nutritionist_id' => auth()->user()->nutritionist->id
        ]);


        $items = $request->items;

        $plan->items()->sync([]);
        foreach ($items as $item) {
            DB::table('item_plan')->insert([
                'item_id' => $item['id'],
                'plan_id' => $plan->id,
                'quantity' => 1,
                'day' => $item['day'],
                'type' => $item['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
//        $plan->items;

        $meals = $request->meals;
        $plan->meals()->sync([]);
        foreach ($meals as $meal) {
            DB::table('meal_plan')->insert([
                'meal_id' => $meal['id'],
                'plan_id' => $plan->id,
                'day' => $meal['day'],
                'type' => $meal['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
//        $plan->meals;

        return $this->returnData('plan', $plan, 200, 'plan is created successfully');
    }

    /**
     * AutoComplete when searching for a Plan
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function showSearch()
    {
        $this->authorize('viewAny', Plan::class);
        return $this->returnData('plans', Plan::where('title', 'LIKE', request('prefix') . '%')->limit(5)->get());
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($plan)
    {
        //
        $fetchedPlan = Plan::with(['items', 'meals'])->find($plan);

        if ($fetchedPlan == null) //status code shouldn't be 200
            return $this->returnError($this->getErrorCode('plan not found'), 404, 'Plan Not Found');

//        foreach ($fetchedPlan->meals as $meal) {
//            $meal->items;
//        }

        return $this->returnData('plan', $fetchedPlan, 200, 'plan is fetched successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Plan $plan)
    {

        $this->authorize('update', Plan::class);

        $validation = Validator::make($request->all(), [
            'title' => 'string',
            'description' => 'string',
            'items.*.id' => [ 'numeric', 'exists:item,id'],
            'items.*.day' => ['string'],
            'items.*.type' => ['string'],
            'meals.*.id' => [ 'numeric', 'exists:meal,id'],
            'meals.*.day' => ['string'],
            'meals.*.type' => ['string'],
        ]);

        if ($validation->fails()) {
            $code = $this->returnCodeAccordingToInput($validation);
            return $this->returnValidationError($code, $validation);
        }

        $planDetails = $request->only(['title', 'description']);
        $plan->update($planDetails);

        $items = $request->items;
        $plan->items()->sync([]);
        foreach ($items as $item) {
            DB::table('item_plan')->insert([
                'item_id' => $item['id'],
                'plan_id' => $plan->id,
                'quantity' => 1,
                'day' => $item['day'],
                'type' => $item['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
//        $plan->items;

        $meals = $request->meals;
        $plan->meals()->sync([]);
        foreach ($meals as $meal) {
            DB::table('meal_plan')->insert([
                'meal_id' => $meal['id'],
                'plan_id' => $plan->id,
                'day' => $meal['day'],
                'type' => $meal['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
//        $plan->meals;


        return $this->returnData('plan', $plan, 200, 'plan is updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($plan)
    {
        $this->authorize('delete', Plan::class);

        $fetchedPlan = Plan::find($plan);

        if ($fetchedPlan == null) //status code shouldn't be 200
            return $this->returnError($this->getErrorCode('plan not found'), 404, 'Plan Not Found');

        foreach ($fetchedPlan->currentMembers as $member) {
            $member->plan()->dissociate()->save();
        }

        $fetchedPlan->delete();

        return $this->returnSuccessMessage("Plan Deleted Successfully!");
    }
}
