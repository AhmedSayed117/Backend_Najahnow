<?php

namespace App\Http\Controllers;

use App\Http\Traits\GeneralTrait;
use App\Models\PrivateSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Member;
use App\Models\User;
use DateTime;

class PrivateSessionController extends Controller
{
    use GeneralTrait;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index() {
        $user = auth()->user();

            $sessions = DB::table('private_sessions')->join('coaches','coach_id' , '=' , 'coaches.id')->join('users','user_id','=','users.id')->select('private_sessions.*', 'name')->orderBy('id');
            $page = request('page');
            if (request('text') != null) {
                $prefixSessions = $sessions->where('title', 'LIKE', request('text') . '%')->get();
                $subStringSessions = $sessions->where('title', 'LIKE', '_%' . request('text') . '%')->get();
                $theUnion = $prefixSessions->merge($subStringSessions)->unique();
                $sessions = (collect($theUnion));
                if($page != null) {
                    $sessions->forPage(request('page'), 8)->all();
                } 
                return $this->returnData('Private Sessions',$sessions, 200, 'sessions found');
                
            }
            if($page != null) {
    
                $sessions = $sessions->paginate(5);
            } else {
                $sessions = $sessions->get();
            }
       
            if (!$sessions)
        {
            return  $this-> returnError("001", 404,'No private sessions found');
        }
        else{
            return $this->returnData('Private Sessions',$sessions,200,'Sessions found');
        }

    }
    
    public function showMySessions() {
        // $sessions = DB::table('private_sessions')->paginate(5);
        $user = auth()->user();
        $sessions = DB::table('private_sessions')->join('coaches','coach_id' , '=' , 'coaches.id')->join('users','user_id','=','users.id')->select('private_sessions.*', 'name')->where(['name' => $user->name])->orderBy('id');
        $page = request('page');
        if (request('text') != null) {
            $prefixSessions = $sessions->where('title', 'LIKE', request('text') . '%')->get();
            $subStringSessions = $sessions->where('title', 'LIKE', '_%' . request('text') . '%')->get();
            $theUnion = $prefixSessions->merge($subStringSessions)->unique();
            $sessions = (collect($theUnion));
            if($page != null) {
                $sessions->forPage(request('page'), 8)->all();
            } 
            return $this->returnData('Private Sessions',$sessions, 200, 'sessions found');
            
        }
        if($page != null) {

            $sessions = $sessions->paginate(5);
        } else {
            $sessions = $sessions->get();
        }
        if (!$sessions)
        {
            return  $this-> returnError("001", 404,'No private sessions found');
        }
        else{
            return $this->returnData('Private Sessions',$sessions,200,'Sessions found');
        }

    }
    public function showBookedSessions() {
        // $sessions = DB::table('private_sessions')->paginate(5);
        $user = auth()->user();
        if($user->role == 'coach') {

            $sessions = DB::table('private_sessions')->join('session_member','private_sessions.id', '=', 'session_member.session_id')->join('members','members.id', '=', 'member_id')->join('users','user_id','=','users.id')->select('private_sessions.*', 'name', 'status','datetime')->where(['coach_id' => $user->coach->id])->orderBy('id');
        } else if ($user->role == 'member') {
            $sessions = DB::table('private_sessions')->join('session_member','private_sessions.id', '=', 'session_member.session_id')->join('coaches','coaches.id', '=', 'coach_id')->join('users','user_id','=','users.id')->select('private_sessions.*', 'status', 'member_id', 'datetime')->where(['member_id' => $user->member->id])->orderBy('id'); 
        }
        $page = request('page');
        if (request('text') != null) {
            $prefixSessions = $sessions->where('title', 'LIKE', request('text') . '%')->get();
            $subStringSessions = $sessions->where('title', 'LIKE', '_%' . request('text') . '%')->get();
            $theUnion = $prefixSessions->merge($subStringSessions)->unique();
            $sessions = (collect($theUnion));
            if($page != null) {
                $sessions->forPage(request('page'), 8)->all();
            } 
            return $this->returnData('Private Sessions',$sessions, 200, 'sessions found');
            
        }
        if($page != null) {

            $sessions = $sessions->paginate(5);
        } else {
            $sessions = $sessions->get();
        }
        if (!$sessions)
        {
            return  $this-> returnError("001", 404,'No private sessions found');
        }
        else{
            return $this->returnData('Private Sessions',$sessions,200,'Sessions found');
        }

    }
    public function showRequestedSessions() {
        // $sessions = DB::table('private_sessions')->paginate(5);
        //should be an admin
        $sessions = DB::table('private_sessions')->join('session_member', 'private_sessions.id', '=', 'session_member.session_id')->join('members','member_id', '=', 'members.id')->join('users', 'user_id', '=', 'users.id')->select('private_sessions.*','name','status')->where('status','pending')->orderBy('id');
        $page = request('page');
        if (request('text') != null) {
            $prefixSessions = $sessions->where('title', 'LIKE', request('text') . '%')->get();
            $subStringSessions = $sessions->where('title', 'LIKE', '_%' . request('text') . '%')->get();
            $theUnion = $prefixSessions->merge($subStringSessions)->unique();
            $sessions = (collect($theUnion));
            if($page != null) {
                $sessions->forPage(request('page'), 8)->all();
            } 
            return $this->returnData('Private Sessions',$sessions, 200, 'sessions found');
            
        }
        if($page != null) {

            $sessions = $sessions->paginate(5);
        } else {
            $sessions = $sessions->get();
        }   
        
        if (!$sessions)
        {
            return  $this-> returnError("001", 404,'No private sessions found');
        }
        else{
            return $this->returnData('Private Sessions',$sessions,200,'Sessions found');
        }

    }

    

    public function store(Request $request)
    {
        $user = auth()->user();
        $this->authorize(PrivateSession::class);
        $rules = [
            'description' => ['required', 'min:5'],
            'link' => ['required', 'url'],
            'duration' => ['required', 'date_format:H:i'],
            'title' => ['required'],
            'price' => ['required', 'numeric'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        };

        $sessionDetails = $request->only(['description', 'link', 'duration', 'title', 'price']);

        $coach = $user->coach;
        $coachId = $coach->id;
        $sessionDetails["coach_id"] = $coachId;

        $session = PrivateSession::create($sessionDetails);

        return $this->returnData('Private Session', $session, 201, "Private Session created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PrivateSession $session)
    {
        return $this->returnData('Private Session', $session, 201, "Private Session fetched successfully");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PrivateSession $session)
    {
        // needs authorization for coach
        // needs validation for the request
        $sessionDetails = $request->only(['description', 'link', 'duration', 'title', 'price']);

        $user = auth()->user();
        $this->authorize($session);
        $coach = $user->coach;
        $coachId = $coach->id;
        $sessiondetails["coach_id"] = $coachId;

        $session->update($sessionDetails);

        return $this->returnData('Private Session', $session, 200, "Private Session updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrivateSession $session)
    {
        $this->authorize($session);
        $session->delete();
        return $this->returnSuccessMessage('Private Session deleted successfully', 200);
    }

    public function requestSession(PrivateSession $session, Request $request)
    {
        $user = auth()->user();
        if ($session->members->contains($user->member)) {
            return $this->returnError('E099', 400, 'Session is already requested!');
        }

        $this->authorize('requestSession', $session);
        $member = $user->member;
        $memberId = $member->id;


        $session->members()->attach($memberId, ['datetime' => now(), 'status' => 'pending']);
        return $this->returnSuccessMessage('Session requested successfully', 200);;
    }

    public function cancelSession(PrivateSession $session, Request $request)
    {
        $user = auth()->user();

        $this->authorize('cancelSession', $session);
        $member = $user->member;
        $memberId = $member->id;

        $session->members()->detach($memberId);
        return $this->returnSuccessMessage('Session cancelled successfully', 200);
    }
    public function acceptSession(PrivateSession $session, Request $request)
    {
        $user = auth()->user();
        if ($session->members->isEmpty()) {
            return $this->returnError('E099', 400, 'Session is not booked!');
        }
        $this->authorize('acceptSession', $session);
        $details = $request->only(['member' , 'datetime']);
        $user = User::where('name', $details['member'])->first();
        $member = $user->member;
        $session->members()->sync(array($member->id => ['status' => 'booked', 'datetime' => $details['datetime']]));
        // dd($session_member);
        // $session_member->status = 'accepted';
        return $this->returnSuccessMessage('Session accepted successfully', 200);
    }
    public function rejectSession(PrivateSession $session, Request $request)
    {
        $user = auth()->user();
        if ($session->members->isEmpty()) {
            return $this->returnError('E099', 400, 'Session is not booked!');
        }

        $this->authorize('rejectSession', $session);
        $details = $request->only(['member']);
        $user = User::where('name', $details['member'])->first();
        $member = $user->member;
        $session->members()->sync(array($member->id => ['status' => 'rejected']));
        return $this->returnSuccessMessage('Session rejected successfully', 200);
    }

    public function showDetails(PrivateSession $session)
    {
        return $this->returnData('Private Session', $session, 200, "session details fetched successfully");
    }
}
