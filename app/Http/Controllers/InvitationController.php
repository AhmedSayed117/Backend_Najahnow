<?php

namespace App\Http\Controllers;

use App\Traits\GeneralTrait;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvitationController extends Controller
{
    use GeneralTrait;
    /**
     * Get all invitations
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize('viewAny',Invitation::class);

        $invitations = Invitation::with('user')->get();
        if ($invitations->isEmpty())
            return $this->returnError('There are no invitations');
        return $this->returnData('invitations', $invitations);
    }

    /**
     * Get Invitation by ID
     *
     * @return \Illuminate\Http\Response
     */
    public function GetInvitationByID(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }


        $Invitation = Invitation::find($request->invitationID);
        if (!$Invitation)
            return $this->returnError('There is no Invitation with this id');
        return $this->returnData('Invitation', $Invitation);
    }

    // Add new Invitation

    public function AddInvitation(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize('create', Invitation::class);

        //validation
        $rules = [
            "name" => "required",
            "number" => "required|max:11|min:11",
            "user_id" => "exists:users,id",

        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }


        //$newInvitation = Invitation::create($request->all());
        $newInvitation = new Invitation($request->all());
        $newInvitation->user()->associate(auth()->user());
        $newInvitation->save();
        return $this->returnData('newInvitation', $newInvitation, "Invitation Added correctly");
    }


    // Edit existing Invitation

    public function EditInvitation(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $invitation = Invitation::where('id', $request->invitationID)->get()->first();
        $this->authorize('update',$invitation);

        //validation
        $rules = [
            "name" => "required",
            "number" => "required|max:11|min:11",
            "user_id" => "exists:users,id",

        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }


        if (Invitation::where('id', $request->invitationID)->update($request->all()))
            return $this->returnSuccessMessage("Invitation updated correctly");
        return $this->returnError('Failed to update Invitation');
    }


    // Delete Invitation
    public function DeleteInvitation(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $invitation = Invitation::where('id', $request->invitationID)->get()->first();
        $this->authorize('delete',$invitation);

        if (Invitation::where('id', $request->invitationID)->delete())
            return $this->returnSuccessMessage("Invitation deleted correctly");
        return $this->returnError('Failed to delete Invitation');
    }
}
