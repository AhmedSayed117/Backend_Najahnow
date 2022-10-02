<?php

namespace App\Http\Controllers;

use App\Http\Traits\GeneralTrait;
use App\Models\Exercise;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AudioController extends Controller
{

    use GeneralTrait;

    public function fetchMusic(Request $request)
    {
        $rules = [];
        if ($request->type ==="music" || $request->type ==="gif" ||$request->type ==="image")
        {
            $rules=[
                'id' => ['required','exists:exercises,id'],
                'type'=>['required','string'],
            ];
        }else if($request->type ==="user"){
            $rules=[
                'id' => ['required','exists:users,id'],
                'type'=>['required','string'],
            ];
        } else return $this->returnError('404',404,'type must be [user|music|gif|image]');

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $exercise = Exercise::find($request->id);

        if ($request->type ==="music" && file_exists($exercise->music))
            return Response::download($exercise->music);
        else if($request->type ==="gif" && file_exists($exercise->gif))
            return Response::download($exercise->gif);
        else if($request->type ==="image" && file_exists($exercise->image))
            return Response::download($exercise->image);
        else if($request->type ==="user")
        {
            $user = UserInfo::where('user_id',$request->id)->get()->first();
            if(file_exists($user->photo))
                return Response::download($user->photo);
            return Response::download("images/1633002070.png");
        }
        else return $this->returnError('404',404,'type must be [user|music|gif|image]');
    }
}
