<?php

namespace App\Http\Controllers;

use App\Http\Traits\UserTrait;
use App\Http\Traits\GeneralTrait;
use App\Models\Coach;
use App\Models\Member;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\WorkoutSummary;
use App\UploadImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class MemberController extends Controller
{
    use GeneralTrait;
    use UserTrait;

    public function index()
    {
        $members = Member::with('userinfo')->with('user')->get();
        if (!$members) {
            return  $this->returnError("001", 404,'No members found');
        } else {
            return $this->returnData('Members', $members, 200);
        }
    }

    public function store(Request $request)
    {
        $this->authorize('store', User::class);

        $rules = [
            'name' => ['required','string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required'],
            'role' => ['required', Rule::in(['member']),],
            'gender' => ['required', Rule::in(['male', 'female']),],
            'photo' => ['required','mimes:jpg,jpeg,png,svg'],
            'branch_id' => ['required','exists:App\Models\Branch,id'],
            'number' => ['required','digits_between:10,11'],
            'is_checked' => ['required','boolean'],
            'medical_physical_history' => ['required','string'],
            'available_frozen_days' => ['required','integer'],
            'medical_allergic_history' => ['required','string'],
            'available_membership_days' => ['required','integer'],
            'active_days' => ['required','integer'],
            'membership_id'=>['required','integer','exists:App\Models\Membership,id'],
            'current_plan'=>['required','integer','exists:App\Models\Plan,id'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }

        $user_id = $this->createUser($request);

        $memberInfo = $request->only(['is_checked', 'start_date', 'end_date', 'medical_physical_history', 'available_frozen_days', 'medical_allergic_history', 'available_membership_days', 'active_days']);

        $member = new Member($memberInfo);
        $member->membership_id = $request->membership_id;
        $member->user_id = $user_id;
        $member->current_plan = $request->current_plan;
        $member->save();
        $info = User::where('id',$member->user_id)->with('userInfos')->get()->first();

        $obj = [
            'member'=>$member,
            'memberinfo'=>$info,
        ];

        return $this->returnData('member',$obj,200,'created Successfully');
    }

    public function show($id)
    {
        $member = Member::find($id);
        if ($member)
        {
            $user = User::find($member->user_id);
            $info = User::where('id',$member->user_id)->with('userInfos')->get();

            $this->authorize('viewAny', $user);

            return $this->returnData('user', $info, 200,'show successfully');
        }
        return $this->returnError('002',403,'member is not found');
    }

    public function update(Request $request,  $id)
    {
        $member = member::find($id);
        if ($member){

            $user = User::find($member->user_id);

            $this->authorize('update', $user);

            $rules = [
                'name' => ['string'],
                'weight' => ['integer'],
                'height' => ['integer'],
                'age' => ['integer'],
                'bio' => ['string'],
                'email' => ['string', 'email', 'max:255', 'unique:users'],
                'password' => ['string'],
                'role' => [ Rule::in(['member']),],
                'gender' => [ Rule::in(['male', 'female']),],
                'photo' => ['mimes:jpg,jpeg,png,svg'],
                'number' => ['digits_between:10,11'],
                'medical_physical_history' => ['string'],
                'medical_allergic_history' => ['string'],
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
            }

            $memberInfo = $request->only(['medical_physical_history', 'medical_allergic_history']);

            if($memberInfo)
                $member->update($memberInfo);

            $this->updateUser($request,$member->user_id);

            $info = Member::where('id',$member->id)->with('user')->with('userinfo')->get();
            return $this->returnData('user', $info, 200,'member updated successfully');

        }
        return $this->returnError('002', 404,'Member is not found');
    }


    public function delete($id)
    {
        $member = member::find($id);
        if ($member)
        {
            $user = User::find($member->user_id);
            $this->authorize('update', $user);

            User::destroy($member->user_id);
            return $this->returnSuccessMessage('deleted successfully');
        }
        return  $this->returnError("001", 200,'member not found');
    }

    public function storeWorkoutSummary(Request $request)
    {
        $user = auth()->user();
        $this->authorize('storeWorkoutSummary', Member::class);

        $rules = [
            'calories_burnt' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'duration' => ['required']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        };

        $workoutSummaryDetails = $request->only(['calories_burnt', 'date', 'duration']);
        $member = $user->member;
        $memberId = $member->id;
        $workoutSummaryDetails["member_id"] = $memberId;

        $workoutSummary = WorkoutSummary::create($workoutSummaryDetails);

        return $this->returnData('workout_summary', $workoutSummary, "Workout summary created successfully");
    }

    public function getMyWorkoutSummaries()
    {
        $user = auth()->user();
        $this->authorize('getMyWorkoutSummaries', Member::class);
        $memberWorkoutSummaries = $user->member->workoutSummaries;
        return $this->returnData('workout_summaries', $memberWorkoutSummaries, 'workout summaries fetched successfully');
    }

    public function fetchWeekGroups(Request $request)
    {
        $user = auth()->user();
        $member = $user->member;

        $starttime = request('start_time');
        $endtime = request('end_time');

        $weekGroups = DB::table('groups')->join('group_member', 'groups.id', '=', 'group_member.group_id')->select('groups.*', 'member_id', 'day')->where(['member_id' => $member->id])->whereBetween('day', [$starttime, $endtime])->orderBy('id')->get();

        return $this->returnData('Week Groups', $weekGroups, 'Groups fetched successfully');
    }

    //Rate coach //done
    public function rate_coach(Request $request)
    {
        $rate = $request-> rate;
        //rate from 1 to 5
        if ($rate <= 0 || $rate > 5)
            return $this->returnError("001","0000" ,'Number must be greater than 0 and less than or equal 5');

       $user = User::find($request->id)->get()->first();

        if (!$user)
            return $this->returnError("001", 'E99','You are Not Authenticated');

        $member = Member::where('user_id',$request->id)->get()->first();

        if (!$member)
            return $this->returnError("001", 'E99','You are Not member');

        $member->update([
            'rate_count'=>$rate
        ]);

        $coach = Coach::where('id',$member->coach_id)->get()->first();

        if (!$coach)
            return $this->returnError("001", 'E99','Not found');

        $all_rate_count = Member::where('coach_id', $coach->id)->get()->count();

        $all_rate = Member::where('coach_id', $coach->id)->get()->sum('rate_count');

        if ($all_rate_count){
            $coach->update([
                'avg_rate'=>$all_rate / $all_rate_count
            ]);
        }

        return $this->returnSuccessMessage("rate updated");
    }

    // function to calculate macros to the user //done
    public function MacrosCalc(Request $request)
    {
        $member = UserInfo::where("user_id",$request->id)->get()->first();

        if (!$member)
            return $this->returnError('002', "E99",'member is not found');

        //if ()

        $p =$member -> weight * 2;
        $c =$member -> calories / 8;
        $f =(($member -> calories) * 0.3)/9;

        $member->update([
            'Protein'=>$p,
            'Carbs'=>$c,
            'Fats'=>$f
        ]);

        return $this->returnData('calcMacro',
            [
                'Protein'=>$p,
                'Carbs'=>$c,
                'Fats'=>$f
            ]
            ,200,"macros successfully");
    }

    // function to calculate calories to the user //done
    public function CaloriesCalculator(Request $request)
    {
        $member = UserInfo::where('user_id',$request -> id)->get()->first();

        if (!$member)
            return $this->returnError('002', "E99",'member is not found');

        $weight = $member -> weight;
        $height = $member -> height;
        $age = $member -> age;
        $gender = $member -> gender;
        $activity_level = $member -> activity_level;

        if($gender == 1) // Male
        {
            $bmr = 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
            if($activity_level == 1) $new_cal = $bmr * 1.2;

            elseif ($activity_level == 2) $new_cal = $bmr * 1.375;

            elseif ($activity_level == 3) $new_cal = $bmr * 1.55;

            elseif ($activity_level == 4) $new_cal = $bmr * 1.725;

            elseif ($activity_level == 5) $new_cal = $bmr * 1.9;
        }
        else //Female
        {
            $bmr = 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
            if($activity_level == 1) $new_cal = $bmr * 1.2;

            elseif ($activity_level == 2) $new_cal = $bmr * 1.375;

            elseif ($activity_level == 3) $new_cal = $bmr * 1.55;

            elseif ($activity_level == 4) $new_cal = $bmr * 1.725;

            elseif ($activity_level == 5) $new_cal = $bmr * 1.9;
        }

        $member->update([
            'calories'=>$new_cal
        ]);

        return $this->returnData('updateCalories',$new_cal,200,'updated');

    }



    public function upload(Request $request){

//        $rules = [
//            'photo' => ['required','mimes:jpg,jpeg,png,svg'],
//        ];
//
//        $validator = Validator::make($request->all(), $rules);
//        if ($validator->fails()) {
//            $code = $this->returnCodeAccordingToInput($validator);
//            return $this->returnValidationError($code, $validator);
//        }
//
//        $musicName = time().$request->photo->getClientOriginalName();
//        $request->photo->move(public_path('photosUsers/'), $musicName);
//
//        $img = new UploadImage();
//        $img->photo = 'photosUsers/'.$musicName;
//        $img->save();

//        return $this->returnData('image',$img,200,'upload msg successfully');

        $request->validate([
            'photo' => 'required',
        ]);
        $input = $request->all();
        $image = $request->file('photo'); //image file from frontend

        $student   = app('firebase.firestore')->database()->collection('Images')->document('defT5uT7SDu9K5RFtIdl');
        $firebase_storage_path = 'Images/';
        $name     = $student->id();
        $localfolder = public_path('firebase-temp-uploads') .'/';
        $extension = $image->getClientOriginalExtension();
        $file      = $name. '.' . $extension;
        if ($image->move($localfolder, $file)) {
            $uploadedfile = fopen($localfolder.$file, 'r');
            app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);
        }

    }
    public function download(Request $request)
    {
        $rules = [
            'id' => ['required','exists:upload_images,id'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $img = UploadImage::find($request->id);

        return Response::download($img->photo);
    }
}
