<?php

namespace App\Http\Controllers;

use App\Http\Traits\GeneralTrait;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ExerciseController extends Controller
{
    use GeneralTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function index()
    {
        $page = request('page');
        $exercises = DB::table('exercises')->join('coaches', 'coach_id', '=', 'coaches.id')->join('users', 'user_id', '=', 'users.id')->select('exercises.*', 'name')->orderBy('id');
        // $exercises = DB::table('exercises')->join('coaches','coach_id' , '=' , 'coaches.id')->join('users','user_id','=','users.id')->select('exercises.*','name')->paginate(5);
        if (request('text') != null) {
            $prefixExercises = $exercises->where('title', 'LIKE', request('text') . '%')->get();
            $subStringExercises = $exercises->where('title', 'LIKE', '_%' . request('text') . '%')->get();
            $theUnion = $prefixExercises->merge($subStringExercises)->unique();
            $exercises = (collect($theUnion));
            if($page != null) {
                $exercises->forPage(request('page'), 8)->all();
            }
            return $this->returnData('data', ['exercises' => $exercises, 'name' => auth()->user()->name], 200, 'exercises found');

        }
        if($page != null) {

            $exercises = $exercises->paginate(8);
        } else {
            $exercises = $exercises->get();
        }

        if (!$exercises) {
            return  $this->returnError("001", 404, 'No exercises found');
        } else {
            return $this->returnData('data', ['exercises' => $exercises, 'name' => auth()->user()->name], 200, 'exercises found');
        }
    }






    public function store(Request $request)
    {
        $user = auth()->user();
        $this->authorize(Exercise::class);

        $rules = [
            'description' => ['required', 'min:5'],
            'duration' => ['required', 'date_format:H:i'],
            'gif' => ['required','mimes:jpeg,png,jpg,gif,svg|max:2048'],
            'cal_burnt' => ['required', 'numeric'],
            'title' => ['required'],
            'reps' => ['required', 'numeric'],
            'image' => ['required','mimes:jpeg,png,jpg,gif,svg|max:2048'],
            'equipment_id' => ['nullable', 'numeric'],
            'music' => ['required','mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav'],
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $validatedExerciseDetails = request()->only([
            'description',
            'duration',
            'gif',
            'cal_burnt',
            'title',
            'reps',
            'image',
            'music'
        ]);

        $equipmentId = $request->only('equipment_id');

        $coach = $user->coach;
        $coachId = $coach->id;
        $validatedExerciseDetails['coach_id'] = $coachId;


        $musicName = time().$request->music->getClientOriginalName();
        $request->music->move(public_path('exercise/audios'), $musicName);


        $imageName = time().$request->image->getClientOriginalName();
        $request->image->move(public_path('exercise/img'), $imageName);


        $gifName = time().$request->gif->getClientOriginalName();
        $request->gif->move(public_path('exercise/gif'), $gifName);


        $validatedExerciseDetails['music'] = 'exercise/audios/' . $musicName;
        $validatedExerciseDetails['gif'] = 'exercise/gif/' . $gifName;
        $validatedExerciseDetails['image'] = 'exercise/img/' . $imageName;

        $exercise = Exercise::create($validatedExerciseDetails);

        if ($equipmentId)
        {
            $exercise->equipments()->sync($equipmentId);
        }

//        $exercise->equipments;
//        $exercise->coach->user;

        // return $exercise;
        return $this->returnData('exercise', $exercise, 201, "Exercise created successfully");
    }

    public function show(Exercise $exercise)
    {
        return $this->returnData('exercise', $exercise, 200);
    }


    public function update(Request $request, Exercise $exercise)
    {
        $user = auth()->user();
        $this->authorize($exercise);
        $rules = [
            'description' => ['required', 'min:5'],
            'duration' => ['required', 'date_format:H:i'],
            'gif' => ['required'],
            'cal_burnt' => ['required', 'numeric'],
            'title' => ['required'],
            'reps' => ['required', 'numeric'],
            'image' => ['required'],
            'music'=>['required'],
            'equipment_id' => ['nullable', 'numeric']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        };

        $exercisedetails = $request->only(['description', 'duration', 'gif', 'cal_burnt', 'title', 'reps', 'image','music']);
        $equipment_id = $request->input(['equipment_id']);
        $coach = $user->coach;
        $coachId = $coach->id;
        $exercisedetails["coach_id"] = $coachId;

        if($exercisedetails['image'] != $exercise->image) {
            $path = public_path('storage').'/'.$exercise->image;
            File::delete($path);
        }
        if($exercisedetails['gif'] != $exercise->gif) {
            $path = public_path('storage').'/'.$exercise->gif;
            File::delete($path);
        }if($exercisedetails['music'] != $exercise->music) {
            $path = public_path('storage').'/'.$exercise->music;
            File::delete($path);
        }



        if ($equipment_id) {
            $exercise->equipments()->sync($equipment_id);
        }

        $exercise->update($exercisedetails);

        return $this->returnData('exercise', $exercise, 200, "Exercise updated successfully");
    }


    public function destroy(Exercise $exercise)
    {
        $this->authorize($exercise);
        $exercise->delete();
        return $this->returnSuccessMessage('Exercise deleted successfully', 200);
    }

    public function showDetails(Exercise $exercise)
    {
        return $this->returnData('exercise', $exercise, 200, "exercise details fetched successfully");
    }
}
