<?php

namespace App\Http\Controllers;
use App\Traits\GeneralTrait;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    use GeneralTrait;
    /**
     * Get all Announcments
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize(Announcement::class);

        $announcments = Announcement::all();
        if($announcments->isEmpty())
            return $this->returnError('There are no announcments');
        return $this->returnData('announcments',$announcments);
    }

    /**
     * Get Announcment by ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetAnnouncmentByID(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize(Announcement::class);

        $announcment = Announcement::find($request->announcmentID);
        if(!$announcment)
            return $this->returnError('There is no announcement with this id');
        return $this->returnData('announcment',$announcment);
    }

    // Add new Announcement

    public function AddAnnouncement(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();

        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize(Announcement::class);

        //validation
        $rules = [
            "title" => "required",
            "description" => "required",
            "datetime" => "required",
            "receiver_type" => "required"
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator,$code);
        }
        $sender_id = auth()->user()->id;

        $newAnnouncement = Announcement::create([
            "title"=>$request->title,
            "description"=>$request->description,
            "datetime"=>$request->datetime,
            "receiver_type"=>$request->receiver_type,
            "sender_id"=>$sender_id
        ]);
        return $this->returnData('newAnnouncement',$newAnnouncement,"Announcement Added successfully");
    }


    // Edit existing Announcement

    public function EditAnnouncement(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize(Announcement::class);
        $announcement = Announcement::find($request->announcementID);
        $this->authorize('update', $announcement);

        //validation
        $rules = [
            "title" => "string",
            "description" => "string",
            "datetime" => "string",
            "receiver_type" => "string"
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator,$code);
        }

        $a = Announcement::where('id',$request->announcementID)->update($request->all());
        if($a)
            return $this->returnData('Announcements',$a,"Announcement updated correctly");
        return $this->returnError('Failed to update Announcement');
    }


    // Delete announcement
    public function DeleteAnnouncement(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize(Announcement::class);
        $announcement = Announcement::find($request->announcementID);
        $this->authorize('update', $announcement);

        if(Announcement::where('id',$request->announcementID)->delete())
            return $this->returnSuccessMessage("Announcement deleted correctly");
        return $this->returnError('Failed to delete Announcement');
    }
}
