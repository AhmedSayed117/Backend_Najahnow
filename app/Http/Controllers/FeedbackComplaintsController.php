<?php

namespace App\Http\Controllers;

use App\Traits\GeneralTrait;

use App\Models\FeedbackComplaints;
use FeedbackComplaintsSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackComplaintsController extends Controller
{
    use GeneralTrait;
    /**
     * Get all feedbacks
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //authentication
        $user = $this->authenticateUser();
        // return($user['name']);
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize(FeedbackComplaints::class);

        $feedbacks = FeedbackComplaints::with('user')->get();
        if ($feedbacks->isEmpty())
            return $this->returnError('There are no feedbacks');
        return $this->returnData('feedbacks', $feedbacks);
    }

    /**
     * Get Feedback by ID
     *
     * @return \Illuminate\Http\Response
     */
    public function GetFeedbackByID(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize(FeedbackComplaints::class);

        $Feedback = FeedbackComplaints::find($request->feedbackID);
        if (!$Feedback)
            return $this->returnError('There is no FeedbackComplaints with this id');
        return $this->returnData('Feedback', $Feedback);
    }

    // Add new FeedbackComplaints

    public function AddFeedbackComplaints(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize('create',FeedbackComplaints::class);

        //validation
        $rules = [
            "title" => "required",
            "description" => "required",
            "user_id" => "exists:users,id",
            "type" => "required"
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }


        //$newFeedbackComplaints = FeedbackComplaints::create($request->all());
        $newFeedbackComplaints = new FeedbackComplaints($request->all());
        $newFeedbackComplaints->user()->associate(auth()->user());
        $newFeedbackComplaints->save();
        return $this->returnData('newFeedbackComplaints', $newFeedbackComplaints, "FeedbackAdded correctly");
    }


    // Edit existing FeedbackComplaints

    public function EditFeedbackComplaints(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $feedback = FeedbackComplaints::where('id', $request->feedbackID)->get()->first();
        $this->authorize('update',$feedback);

        //validation
        $rules = [
            "title" => "required",
            "description" => "required",
            "user_id" => "exists:users,id",
            "type" => "required"
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }


        if (FeedbackComplaints::where('id', $request->feedbackID)->update($request->all()))
            return $this->returnSuccessMessage("Feedback updated correctly");
        return $this->returnError('Failed to update Feedback');
    }


    // Delete FeedbackComplaints
    public function DeleteFeedbackComplaints(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $feedback = FeedbackComplaints::where('id', $request->feedbackID)->get()->first();
        $this->authorize('delete',$feedback);

        if (FeedbackComplaints::where('id', $request->feedbackID)->delete())
            return $this->returnSuccessMessage("Feedback deleted correctly");
        return $this->returnError('Failed to delete Feedback');
    }
}
