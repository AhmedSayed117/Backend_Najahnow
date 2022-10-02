<?php

namespace App\Http\Controllers;

use App\Traits\GeneralTrait;
use App\Models\Answer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    use GeneralTrait;
    /**
     * Get all Answers
     *
     * @return JsonResponse
     */
    public function index()
    {

        $answers = Answer::all();
        if ($answers->isEmpty())
            return $this->returnError('There are no answers');
        return $this->returnData('answers', $answers);
    }

    /**
     * Get answer by ID
     *
     * @return JsonResponse
     */
    public function GetanswerByID(Request $request)
    {
        $answer = Answer::find($request->answerID);
        if (!$answer)
            return $this->returnError('There is no answer with this id');
        return $this->returnData('answer', $answer);
    }

    /**
     * Get answer by ID
     *
     * @return JsonResponse
     */

    public function GetAnswersForQuestion(Request $request)
    {
        $answers = Answer::where('question_id', (string)$request->questionID)->get();
        if ($answers->isEmpty())
            return $this->returnError('There is no answer for this question');
        return $this->returnData('answers', $answers);
    }

    // Add new Answer

    /**
     * @throws AuthorizationException
     */
    public function AddAnswer(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize('create',Answer::class);

        //validation
        $rules = [
            "body" => "required",
            "user_id" => "exists:users,id",
            "question_id" => "exists:questions,id"
        ];
        $validator = Validator::make($request->all(), $rules);
        //$validator ['user_id'] = $user->id ;
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }


        //$newAnswer = Answer::create($request->all());
        $newAnswer = new Answer($request->all());
        $newAnswer->user()->associate(auth()->user());
        $newAnswer->save();
        return $this->returnData('newAnswer', $newAnswer, "Answer Added correctly");
    }


    // Edit existing Answer

    /**
     * @throws AuthorizationException
     */
    public function EditAnswer(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $answer = Answer::find($request->answerID);
        $this->authorize('update', $answer);

        //validation
        $rules = [
            "body" => "required",
            "user_id" => "exists:users,id",
            "question_id" => "exists:questions,id"
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }


        if (Answer::where('id', $request->answerID)->update($request->all()))
            return $this->returnSuccessMessage("Answer updated correctly");
        return $this->returnError('Failed to update Answer');
    }


    // Delete Answer

    /**
     * @throws AuthorizationException
     */
    public function DeleteAnswer(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $answer = Answer::find($request->answerID);
        $this->authorize('delete', $answer);

        if (Answer::where('id', $request->answerID)->delete())
            return $this->returnSuccessMessage("Answer deleted correctly");
        return $this->returnError('Failed to delete Answer');
    }
}
