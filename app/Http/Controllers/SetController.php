<?php

namespace App\Http\Controllers;

use App\Http\Traits\GeneralTrait;
use App\Models\Exercise;
use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SetController extends Controller
{
    use GeneralTrait;


    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function index()
    {
        $page = request('page');
        $sets = DB::table('sets')->join('coaches', 'coach_id', '=', 'coaches.id')->join('users', 'user_id', '=', 'users.id')->select('sets.*', 'name')->orderBy('id');
        
        if (request('text') != null) {
            $prefixSets = $sets->where('title', 'LIKE', request('text') . '%')->get();
            $subStringSets = $sets->where('title', 'LIKE', '_%' . request('text') . '%')->get();
            $theUnion = $prefixSets->merge($subStringSets)->unique();
            $sets = (collect($theUnion));
            if($page != null) {
                $sets->forPage(request('page'), 8)->all();
            } 
            return $this->returnData('data', ['sets' => $sets, 'name' => auth()->user()->name], 200, 'sets found');
            
        }
        if($page != null) {

            $sets = $sets->paginate(8);
        } else {
            $sets = $sets->get();
        }
        
        
        if (!$sets) {
            return  $this->returnError("001", 404, 'No sets found');
        } else {
            return $this->returnData('data', ['sets' => $sets, 'name' => auth()->user()->name], 200, 'sets found');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $this->authorize(Set::class);
        $rules = [
            'title' => ['required'],
            'description' => ['required', 'min:5'],
            'exercises.*.id' => ['required', 'numeric', 'exists:exercises,id'],
            'exercises.*.break_duration' => ['required', 'date_format:H:i']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        };


        $setdetails = $request->only(['description', 'title']);

        $coach = $user->coach;
        $coachId = $coach->id;
        $setdetails["coach_id"] = $coachId;

        $set = Set::create($setdetails);

        $exercises = $request->exercises;
        // foreach ($exercises as $exercise) {
        //     $exerciseFromTable = Exercise::find($exercise['id']);
        //     if ($exerciseFromTable->coach_id !== $coachId) {
        //         return $this->returnError('E099', 400, 'Trying to add wrong exercise!');
        //     }
        // }


        // $attachedArray = [];
        // foreach ($exercises as $exercise) {
        //     $attachedArray[$exercise['id']] = ['break_duration' => $exercise['break_duration']];
        // }

        // $set->exercises()->sync([]);
        // $set->exercises()->attach($attachedArray);

        foreach ($exercises as $exercise) {
            DB::table('exercise_set')->insert([
                'exercise_id' => $exercise['id'],
                'set_id' => $set->id,
                'break_duration' => $exercise['break_duration'],
                'created_at' => now()->subSeconds(),
                'updated_at' => now(),
            ]);
        }

        return $this->returnData('set', $set, 201, "Set created successfully");
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Set $set)
    {
        return $this->returnData('set', $set, 201, "Set fetched successfully");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Set $set)
    {
        $user = auth()->user();
        $this->authorize($set);
        $rules = [
            'title' => ['required'],
            'description' => ['required', 'min:5'],
            'exercises.*.id' => ['required', 'numeric'],
            'exercises.*.break_duration' => ['required', 'date_format:H:i']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        };

        $setdetails = $request->only(['description', 'title']);


        $coach = $user->coach;
        $coachId = $coach->id;
        $setdetails["coach_id"] = $coachId;
        
        $set->update($setdetails);

        $exercises = $request->exercises;
        // foreach ($exercises as $exercise) {
        //     $exerciseFromTable = Exercise::find($exercise['id']);
        //     if ($exerciseFromTable->coach_id !== $coachId) {
        //         return $this->returnError('E099', 400, 'Trying to add wrong exercise!');
        //     }
        // }

        $set->exercises()->sync([]);
        foreach ($exercises as $exercise) {
            DB::table('exercise_set')->insert([
                'exercise_id' => $exercise['id'],
                'set_id' => $set->id,
                'break_duration' => $exercise['break_duration'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $this->returnData('set', $set, 200, "Set updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Set $set)
    {
        $this->authorize($set);
        $set->delete();
        return $this->returnSuccessMessage('Set deleted successfully', 200);
    }


    public function showDetails(Set $set)
    {
        $set->exercises;
        $set->coach->user;
        return $this->returnData('set', $set, 200, "Set details fetched successfully");
    }
}
