<?php

namespace App\Http\Controllers;

use App\Http\Traits\GeneralTrait;
use App\Models\Exercise;
use App\Models\Group;
use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    use GeneralTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {

        $page = request('page');
        $groups = DB::table('groups')->join('coaches', 'coach_id', '=', 'coaches.id')->join('users', 'user_id', '=', 'users.id')->select('groups.*', 'name')->orderBy('id');
        
        if (request('text') != null) {
            $prefixgroups = $groups->where('title', 'LIKE', request('text') . '%')->get();
            $subStringgroups = $groups->where('title', 'LIKE', '_%' . request('text') . '%')->get();
            $theUnion = $prefixgroups->merge($subStringgroups)->unique();
            $groups = (collect($theUnion));
            if($page != null) {
                $groups->forPage(request('page'), 8)->all();
            } 
            return $this->returnData('data', ['groups' => $groups, 'name' => auth()->user()->name], 200, 'groups found');
            
        }
        if($page != null) {

            $groups = $groups->paginate(8);
        } else {
            $groups = $groups->get();
        }

        if (!$groups) {
            return  $this->returnError("001", 404, 'No groups found');
        } else {
            return $this->returnData('data', ['groups' => $groups, 'name' => auth()->user()->name], 200, 'groups found');
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
        $this->authorize(Group::class);
        $rules = [
            'title' => ['required'],
            'description' => ['required', 'min:5'],
            'exercises.*.id' => ['required', 'numeric', 'exists:exercises,id'],
            'exercises.*.break_duration' => ['required', 'date_format:H:i'],
            'sets.*.id' => ['required', 'numeric', 'exists:sets,id'],
            'sets.*.break_duration' => ['required', 'date_format:H:i']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        };

        $groupdetails = $request->only(['description', 'title']);
        $coach = $user->coach;
        $coachId = $coach->id;
        $groupdetails["coach_id"] = $coachId;

        $group = Group::create($groupdetails);

        $exercises = $request->exercises;
        $sets = $request->sets;

        $group->exercises()->sync([]);
        foreach ($exercises as $exercise) {
            DB::table('exercise_group')->insert([
                'exercise_id' => $exercise['id'],
                'group_id' => $group->id,
                'break_duration' => $exercise['break_duration'],
                'order' => $exercise['order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $group->sets()->sync([]);
        foreach ($sets as $set) {
            DB::table('set_group')->insert([
                'set_id' => $set['id'],
                'group_id' => $group->id,
                'break_duration' => $set['break_duration'],
                'order' => $set['order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $this->returnData('group', $group, 201, "group created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return $this->returnData('group', $group, 200, "group retrieved successfully");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $user = auth()->user();
        $this->authorize($group);
        $rules = [
            'title' => ['required'],
            'description' => ['required', 'min:5'],
            'exercises.*.id' => ['required', 'numeric', 'exists:exercises,id'],
            'exercises.*.break_duration' => ['required', 'date_format:H:i'],
            'sets.*.id' => ['required', 'numeric', 'exists:sets,id'],
            'sets.*.break_duration' => ['required', 'date_format:H:i']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        };

        $groupdetails = $request->only(['description', 'title', 'coach_id']);


        $coach = $user->coach;
        $coachId = $coach->id;
        $exercisedetails["coach_id"] = $coachId;

        $group->update($groupdetails);

        $exercises = $request->exercises;
        $sets = $request->sets;

        $group->exercises()->sync([]);
        foreach ($exercises as $exercise) {
            DB::table('exercise_group')->insert([
                'exercise_id' => $exercise['id'],
                'group_id' => $group->id,
                'break_duration' => $exercise['break_duration'],
                'order' => $exercise['order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $group->sets()->sync([]);
        foreach ($sets as $set) {
            DB::table('set_group')->insert([
                'set_id' => $set['id'],
                'group_id' => $group->id,
                'break_duration' => $set['break_duration'],
                'order' => $set['order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $this->returnData('group', $group, 200, "Group updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $this->authorize($group);
        $group->delete();
        return $this->returnSuccessMessage('group deleted successfully', 200);
    }

    public function showDetails(Group $group)
    {
        $group->exercises;
        $group->sets;
        foreach ($group->sets as $set) {
            $set->exercises;
        }
        return $this->returnData('group', $group, 200, "group details fetched successfully");
    }
}
