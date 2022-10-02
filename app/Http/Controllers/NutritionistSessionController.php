<?php

namespace App\Http\Controllers;

use App\Models\Nutritionist;
use App\Models\NutritionistSession;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class NutritionistSessionController extends Controller
{
    use GeneralTrait;

    public function index()
    {
        $sessions = NutritionistSession::all();
        foreach($sessions as $session) {
            $session->nutritionist->user;
            $session->member->user;
        }
        if (!$sessions)
        {
            return  $this-> returnError("001",'No sessions found found');
        }
        else{
            return $this->returnData('sessions',$sessions,'sessions found');
        }
    }

    public function store(Request $request)
    {
        $rules = [
            "nutritionist_id" => "required|exists:App\Models\Nutritionist,id",
            "member_id" => "required|exists:App\Models\Member,id",
            "date"=>"required|date"
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator,$code);
        }
        $this->authorize('store',NutritionistSession::class);
        $sessionInfo = $request->only(['date']);
        $session = new NutritionistSession($sessionInfo);
        $session->nutritionist_id = $request->only(['nutritionist_id'])['nutritionist_id'];

        $session->member_id = $request->only(['member_id'])['member_id'];
        $session->save();

        return $this->returnData('Session',$session,'Session stored');
    }

    public function show($id)
    {
        $session = NutritionistSession::find($id);
        $Nutritionist = Nutritionist::find($session->nutritionist_id);
        $user = User::find($Nutritionist->user_id);
        $this->authorize('viewAny',$user);

        if(!$session)
            return $this->returnError('002','session is not found');
        else
        {
            $session->nutritionist->user;
            $session->member->user;
            return $this->returnData('session',$session,'session found ');
        }
    }

    public function update(Request $request, $id)
    {

        $rules = [
            "nutritionist_id" => "required|exists:App\Models\Nutritionist,id",
            "member_id" => "required|exists:App\Models\Member,id",
            "date"=>"required|date"
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator,$code);
        }

        $session = NutritionistSession::find($id);
        $Nutritionist = Nutritionist::find($session->nutritionist_id);
        $user = User::find($Nutritionist->user_id);
        $this->authorize('update',$session);

        if(!$session) {
            return $this->returnError('session is not found','002');
        }else{
            $sessionInfo = $request->only(['date']);
            $session->update($sessionInfo);
            $session->nutritionist_id = $request->only(['nutritionist_id'])['nutritionist_id'];
            $session->member_id = $request->only(['member_id'])['member_id'];

            $session->save();

            return $this->returnData('session',$session,'session is updated');
        }
    }

    public function delete($id)
    {
        $session = NutritionistSession::find($id);
        $Nutritionist = Nutritionist::find($session->nutritionist_id);
        $user = User::find($Nutritionist->user_id);
        $this->authorize('delete',$session);


        if (!NutritionistSession::destroy($id))
        {
            return  $this-> returnerror("001",'class not found');
        }
        else{
            return $this->returndata('membership',$session,'class deleted');
        }
    }
}
