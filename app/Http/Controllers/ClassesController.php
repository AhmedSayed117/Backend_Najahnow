<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Coach;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use GeneralTrait;

    public function index()
    {
        $classes = Classes::with('coaches')->with('members')->get();
        if (!$classes) {
            return $this->returnerror("001",'no classes found');
        } else{
            return $this->returndata('classes', $classes ,'class found');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->authorize('store',Classes::class);

        $rules = [
            "description" => "required",
            "title" => "required",
            "link" => "required",
            "level" => "required",
            "capacity" => "required|numeric",
            "price" => "required|numeric",
            "duration" => "required|numeric",
            "date" =>"required|date",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator,$code);
        }

        $classInfo = $request->all();

        $class = new Classes($classInfo);
        $class->save();

//         attaching the class to the signed in coach
        if(auth()->user()->role=='coach')
            auth()->user()->coach->classes()->attach($class->id);

        $classwithusercreator = [
            'class'=>$class ,
            'user'=>auth()->user(),
        ];
        return $this->returnData('class',$classwithusercreator,'class was created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Classes  $classes
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $classes = Classes::find($id);

        if(!$classes)
            return $this->returnerror('002','class is not found');
        else
            return $this->returndata('class',$classes,'class found ');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Classes  $classes
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $class = Classes::find($id);
        $this->authorize('update',$class);

        if(!$class)
            return $this->returnError('002','Class is not found');
        else {
            $rules = [
                "description" => "string",
                "title" => "string",
                "link" => "string",
                "level" => "string",
                "capacity" => "numeric",
                "price" => "numeric",
                "duration" => "numeric",
                "date" =>"date",
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($validator,$code);
            }
            $ClassGeneralInfo = $request->all();
            $class->update($ClassGeneralInfo);
            return $this->returnData('class', $class, 'Class updated successfully');
        }
    }

    public function self(){

        try {
            $user = auth()->userOrFail();
        } catch(UserNotDefinedException $e){
            return response()->json(['error'=> $e->getMessage()]);
        }

        return $user->member->classes;
    }

    public function delete($id)
    {
        $class = Classes::find($id);
        $this->authorize('delete',$class);

        if (!Classes::destroy($id))
        {
            return  $this-> returnerror("001",'class not found');
        }
        else{
            return $this->returndata('membership',$class,'class deleted');
        }
    }

    public function assignCoach(Request $request, $id)
    {

        $rules = [
            $id => "exists:classes,id",
            'coach_id'=>'required|exists:coaches,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator,$code);
        }

        $class = Classes::find($id);

        $coach = $request->only('coach_id');

        $class->coaches()->attach($coach);

        $coachobj = Coach::find($request->coach_id);

        $obj = [
            "class"=>$class,
            "coach"=>$coachobj,
        ];
        return $this->returnData('assignCoach',$obj,'Assigned successfully');

    }
    public function assignMember(Request $request,$id)
    {
        $rules = [
            $id => "exists:classes,id",
            'member_id'=>'required|exists:members,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator,$code);
        }

        $class = Classes::find($id);

        $member = Member::find($request->member_id);

        $class->members()->attach($member,['favourite'=>1]);

        $obj = [
            'class'=>$class,
            'member'=>$member,
        ];

        return $this->returnData('assignMember',$obj,"Assigned successfully");
    }

    public function showMembers($id){
        $class = Classes::find($id);
        if($class){
            $members = $class->members()->get();
            return $this->returnData('members', $members);
        }else{
            return $this->returnError('class is not found');
        }
    }

    public function showCoaches( $id ){
        $class = Classes::find($id);
        if($class){
            $coaches = $class->coaches()->get();
            return $this->returnData('coaches', $coaches);
        }else{
            return $this->returnError('class is not found');
        }
    }
}
