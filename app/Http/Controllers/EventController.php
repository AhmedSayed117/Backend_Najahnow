<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;


class EventController extends Controller
{
    use GeneralTrait;

    /**
     * Get All events
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::all();
        if ($events->isEmpty())
            return $this->returnError('There are no events');
        return $this->returnData('events', $events);
    }

    /**
     * Get an event by ID
     *
     * @return \Illuminate\Http\Response
     */
    public function GetEventByID(Request $request)
    {
        $event = Event::find($request->eventID);
        if (!$event)
            return $this->returnError('There is no event with this id');
        return $this->returnData('event', $event);
    }

    /**
     * Add new event
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function AddEvent(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize(Event::class);

        //validation
        $rules = [
            "title" => "required",
            "description" => "required",
            "start_time" => "required",
            "tickets_available" => "required",
            "price" => "required",
            "status" => "required"
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }


        $newEvent = Event::create($request->all());
        return $this->returnData('newEvent', $newEvent, "Event Added correctly");
    }

    /**
     * Edit existing event
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function EditEvent(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize(Event::class);

        //validation
        $rules = [
            "title" => "required",
            "description" => "required",
            "tickets_available" => "required",
            "price" => "required",
            "status" => "required"
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }


        if (Event::where('id', $request->eventID)->update($request->all()))
            return $this->returnSuccessMessage("Event updated correctly");
        return $this->returnError('Failed to update Event');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function DeleteEvent(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize(Event::class);

        if (Event::where('id', $request->eventID)->delete())
            return $this->returnSuccessMessage("Event deleted correctly");
        return $this->returnError('Failed to delete Event');
    }

    public function GetUserEvents(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //$user = User::find($request->userID);
        //$user = auth()->user();
        if (!$user) {
            return $this->returnError('User not found');
        }

        return $this->returnData('events', $user->events);
    }

    public function GetUpcomingUserEvents(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //$user = User::find($request->userID);
        if (!$user) {
            return $this->returnError('User not found');
        }
        $currentTime = date("Y-m-d H:i:s", strtotime(date("H:i:s")) + 2 * 3600);
        $events = $user->events->where('start_time', '>', $currentTime);
        if ($events->isEmpty())
            return $this->returnError('No Upcoming events');
        return $this->returnData('events', $events);
    }

    public function GetPastUserEvents(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //$user = User::find($request->userID);
        if (!$user) {
            return $this->returnError('User not found');
        }
        $currentTime = date("Y-m-d H:i:s", strtotime(date("H:i:s")) + 2 * 3600);
        $events = $user->events->where('start_time', '<', $currentTime);
        if ($events->isEmpty())
            return $this->returnError('No Previous events');
        return $this->returnData('events', $events);
    }

    public function RegisterInEvent(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //$user = User::find($request->userID);
        $event = Event::find($request->eventID);
        if (!$user) {
            return $this->returnError('User not found');
        }
        if (!$event) {
            return $this->returnError('Event not found');
        }

        if ($user->events->contains('id', request('eventID')))
            return $this->returnError('User already registered in the event!');
        $user->events()->attach(request('eventID'), ['status' => request('status')]);
        $event->tickets_available--;
        $event->save();
        // Event::where('id', $request->eventID)->update($request->all());

        return $this->returnSuccessMessage("Congratulation you are registered !!");
    }

    public function CancelRegisteration(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //$user = User::find($request->userID);
        $event = Event::find($request->eventID);
        if (!$user) {
            return $this->returnError('User not found');
        }
        if (!$event) {
            return $this->returnError('Event not found');
        }


        if (!$user->events->contains('id', request('eventID')))
            return $this->returnError('User is not registered in the event!');
        $user->events()->detach(request('eventID'));

        return $this->returnSuccessMessage("Registeration cancelled correctly");
    }
}
