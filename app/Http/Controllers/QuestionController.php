<?php

namespace App\Http\Controllers;

use App\Traits\GeneralTrait;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    use GeneralTrait;
    /**
     * Get all Questions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
//        $questions = Question::with('user')->with('answers')->get();
        $questions = Question::get();
        if (!$questions)
            return $this->returnError('There are no questions');
        return $this->returnData('questions', $questions);
    }

    /**
     * Get Question by ID
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function GetQuestionByID(Request $request)
    {
        $Question = Question::find($request->questionID);
        if (!$Question)
            return $this->returnError('There is no Question with this id');
        return $this->returnData('question', $Question);
    }


    // Add new Question
    public function AddQuestion(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //validation
        $rules = [
            "body" => "required",
            "title" => "required"
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }

        //$newQuestion = Question::create($request->all());
        $newQuestion = new Question($request->all());
        $newQuestion->user()->associate(auth()->user());
        $newQuestion->save();
        return $this->returnData('newQuestion', $newQuestion, "Question Added correctly");
    }


    // Edit existing Question
    public function EditQuestion(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        $question = Question::where('id', $request->questionID)->get()->first();
        //authorization
        $this->authorize('update',$question);

        //validation
        $rules = [
            "body" => "string",
            "title" => "string",
            "user_id" => "exists:users,id",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }

        $q = Question::where('id', $request->questionID)->update($request->all());
        if ($q)
            return $this->returnData('questions',$q,"Question updated correctly");
        return $this->returnError('Failed to update Question');
    }

    // Delete Question
    public function DeleteQuestion(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        $question = Question::where('id', $request->questionID)->get()->first();
        //authorization
        $this->authorize('delete',$question);

        if (Question::where('id', $request->questionID)->delete())
            return $this->returnSuccessMessage("Question deleted correctly");
        return $this->returnError('Failed to delete Question');
    }
}
