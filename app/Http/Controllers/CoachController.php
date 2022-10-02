<?php

namespace App\Http\Controllers;

use App\Http\Traits\GeneralTrait;
use App\Http\Traits\UserTrait;
use App\Models\Classes;
use App\Models\Coach;
use App\Models\Event;
use App\Models\Exercise;
use App\Models\Group;
use App\Models\Member;
use App\Models\PrivateSession;
use App\Models\Set;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Policies\UserPolicy;
use App\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class CoachController extends Controller
{
    use GeneralTrait;
    use UserTrait;

   public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function fetchExercises(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $this->authorize('isCoach', $user);
        $coach = $user->coach;
        $exercises = $coach->exercises()->paginate(5);
        return $this->returnData('exercises', $exercises, 200, "Exercises fetched successfully");
    }

    public function fetchSets(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $this->authorize('isCoach', $user);
        $coach = $user->coach;
        $sets = $coach->sets()->paginate(5);
        return $this->returnData('sets', $sets, 200, "Sets fetched successfully");
    }

    public function fetchGroups(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $this->authorize('isCoach', $user);
        $coach = $user->coach;
        $groups = $coach->groups()->paginate(5);
        return $this->returnData('groups', $groups, 200, "Groups fetched successfully");
    }

    public function fetchPrivateSessions(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $this->authorize('isCoach', $user);
        $coach = $user->coach;
        $privateSessions = $coach->privateSessions()->paginate(5);
        return $this->returnData('privateSessions', $privateSessions, 200, "Private sessions fetched successfully");
    }

    public function fetchMembers(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $this->authorize('isCoach', $user);
        $coach = $user->coach;
        $members = $coach->members;
        $members->each(function($member) {
            $member->user;
        });
        return $this->returnData('members', $members, 200, "members fetched successfully");
    }

    public function fetchClasses(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $this->authorize('isCoach', $user);
        $coach = $user->coach;
        $classes = $coach->classes;

        return $this->returnData('classes', $classes, 200, "classes fetched successfully");
    }

    public function fetchSchedule(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        $this->authorize('isCoach', $user);
        $coach = $user->coach;

        $privateSessions = DB::table('private_sessions')->join('session_member','private_sessions.id', '=', 'session_member.session_id')->join('members','members.id', '=', 'member_id')->join('users','user_id','=','users.id')->select('private_sessions.*', 'name', 'status','datetime')->where(['coach_id' => $user->coach->id, 'status' => 'booked'])->orderBy('id')->get();
        $classes = $coach->classes()->whereHas('members')->get(['title', 'duration', 'date']);
        $events = Event::all();
        return $this->returnData('schedule', [
            'sessions' => $privateSessions,
            'classes' => $classes,
            'events' => $events,
        ], 200, "Schedule fetched successfully");
    }

    public function assignGroupsToMember(Request $request) {
        $user = auth()->user();
        $this->authorize('isCoach', $user);
        $member = $request->only('member_id');
        $groups = $request->groups;

       foreach($groups as $group) {

            DB::table('group_member')->insert([
                'group_id' => $group['group_id'],
                'member_id' => $member['member_id'],
                'day' => $group['date'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

             $group->members()->attach($member['member_id'], ['day' => $group['date']]);
        };

        return $this->returnSuccessMessage($msg = "Assigned successfully", 200);
    }

    public function index()
    {
        $coaches = Coach::with('user')->with('userinfo')->with('memberss')->get();
        if (!$coaches) return  $this->returnError("001", 404, 'No coaches found');
        else return $this->returnData('Coaches', $coaches,200, 'Coaches found');
    }


    public function store(Request $request)
    {
        $this->authorize('store',User::class);

        $rules = [
            //user
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required",
            'number' => 'required|digits_between:10,11',
            "role" => ['required', Rule::in(['coach']),],

            //coaches
            'is_checked' => 'required|boolean',

            //user info
            'bio' => ['string','required'],
            "gender" => ['required', Rule::in(['male', 'female']),],
            'age' => ['integer','required'],
            'photo' => ['required','mimes:jpg,jpeg,png,svg'],
            'branch_id' => 'required|exists:App\Models\Branch,id',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }

        $user_id = $this->createUser($request);

        $coachesInfo = $request->only(['is_checked']);
        $coach = new Coach($coachesInfo);

        $coach->user_id = $user_id;
        $coach->save();

        $info = User::where('id',$coach->user_id)->with('userInfos')->get()->first();

        $obj = [
            'coach'=>$coach,
            'coachinfo'=>$info,
        ];

        return $this->returnData('Coaches', $obj,200, 'Coach successfully');
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $coaches = Coach::where('id',$id)->with('userinfo')->get();
        if (!$coaches) return $this->returnError('002', 404, 'Coach is not found');
        else return $this->returnData('Coaches', $coaches,200, 'Coach found ');
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $this->authorize('update',User::class);

        $coaches = Coach::find($id);

        if (!$coaches)
            return $this->returnError('002', 404, 'Coach is not found');
        else {
            $rules = [
                'name' => ['string'],
                'age' => ['integer'],
                'weight' => ['integer'],
                'height' => ['integer'],
                'bio' => ['string'],
                'email' => ['string', 'email', 'max:255', 'unique:users'],
                'password' => ['string'],
                'role' => [ Rule::in(['coach']),],
                'gender' => [ Rule::in(['male', 'female']),],
                'photo' => ['mimes:jpg,jpeg,png,svg'],
                'number' => ['digits_between:10,11'],
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code,$validator);
            }
            else
            {
                $this->updateUser($request,$coaches->user_id);

                $info = Coach::where('id',$coaches->id)->with('user')->with('userinfo')->get();

                return $this->returnData('Coaches', $info,200, 'Coach Updated');
            }
        }
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $coaches = Coach::find($id);

        if ($coaches)
        {
            $user = User::find($coaches->user_id);

            $this->authorize('delete',$user);

            User::destroy($coaches->user_id);

            return $this->returnData('Coaches', $coaches,200, 'Coach deleted');
        }
        return  $this->returnError("001", 404, 'No Coaches found');
    }

    public function assignMember(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $rules = [
            $id => ['exists:coaches,id'],
            'member_id' => ['required','exists:members,id'],
            'start_date' => ['string','required'],
            'end_date' => ['string','required'],
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code,$validator);
        }
        $coach = Coach::find($id);

        $memberId = $request->only('member_id');

        $info = $request->only('start_date', 'end_date');

        if ($coach)
        {
            $coach->memberss()->attach($memberId,$info);

            $obj = [
                'coach'=>$coach,
                'member'=>Member::find($request->member_id),
            ];
            return $this->returnData('member added',$obj,200,'member added successfully');
        } else {
            return $this->returnError('000', 404, 'coach or member is not valid');
        }
    }

    public function showMembers($id): \Illuminate\Http\JsonResponse
    {
        $coach = Coach::find($id);
        if ($coach) {
            $members = $coach->members()->get();
            return $this->returnData('members', $members,200,"Member Found");
        } else {
            return $this->returnError('000', 404, 'coach is not found');
        }
    }
}
