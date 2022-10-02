<?php

namespace App\Http\Controllers;

use App\Models\Fitness_Summary;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;


class FitnessSummaryController extends Controller
{
    use GeneralTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($fitness_summary)
    {
        //
        $FitSummary = Fitness_Summary::find($fitness_summary);

        if ($FitSummary == null)
            return $this->returnError($this->getErrorCode('fitness summary not found'), 404, 'Fitness summary does not exist');

        return $this->returnData('fitness summary', $FitSummary);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        $this->authorize('create', Fitness_Summary::class);

        $validation = Validator::make(request()->all(), [
            'BMI' => 'required',
            'weight' => 'required',
            'muscle_ratio' => 'required',
            'height' => 'required',
            'fat_ratio' => 'required',
            'fitness_ratio' => 'required',
            'total_body_water' => 'required',
            'dry_lean_bath' => 'required',
            'body_fat_mass' => 'required',
            'opacity_ratio' => 'required',
            'protein' => 'required',
            'SMM' => 'required',
        ]);
        if($user->role == 'member') {
            $memberID = $user->member->id;
        } else {
            $memberID = request('member_id');
        }

        if ($validation->fails()) {
            $code = $this->returnCodeAccordingToInput($validation);
            return $this->returnValidationError($code, $validation);
        }

        if (Member::find($memberID) == null)
            return $this->returnError($this->getErrorCode('member not found'), 404, 'Member with id: ' . request('member_id') . ' does not exist!');

       $summary =  Fitness_Summary::create([
            'BMI' => request('BMI'),
            'member_id' => $memberID,
            'weight' => request('weight'),
            'muscle_ratio' => request('muscle_ratio'),
            'height' => request('height'),
            'fat_ratio' => request('fat_ratio'),
            'fitness_ratio' => request('fitness_ratio'),
            'total_body_water' => request('total_body_water'),
            'dry_lean_bath' => request('dry_lean_bath'),
            'body_fat_mass' => request('body_fat_mass'),
            'opacity_ratio' => request('opacity_ratio'),
            'protein' => request('protein'),
            'SMM' => request('SMM')
        ]);

        return $this->returnData('Fitness Summary', $summary, 201, "Fitness Summary created successfully");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fitness_Summary  $fitness_Summary
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // $startDate = request('startDate');
        // $endDate = request('endDate');
        $memberID = request('memID');

        $user = auth()->user();

        if($user->role == 'member') {
            $memberID = $user->member->id;
        }

        $summaries = null;
        $page = request('page');
        if($memberID == null)
        {
            // if ($startDate == null && $endDate == null) 
                $summaries =  DB::table('fitness_summary')->join('members', 'member_id', '=', 'members.id')->join('users', 'user_id', '=', 'users.id')->select('fitness_summary.*', 'name')->orderBy('id');
            // else if ($startDate != null && $endDate != null)
            //     $summaries = Fitness_Summary::whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->paginate(8, ['*'], 'page', request('page'));
            // else if ($startDate != null)
            //     $summaries = Fitness_Summary::whereDate('created_at', '>=', $startDate)->paginate(8, ['*'], 'page', request('page'));
            // else if ($endDate != null)
            //     $summaries = Fitness_Summary::whereDate('created_at', '<=', $endDate)->paginate(8, ['*'], 'page', request('page'));
        }
        else
        {
            // if ($startDate == null && $endDate == null)
                $summaries =  DB::table('fitness_summary')->where('member_id', $memberID);
            // else if ($startDate != null && $endDate != null)
            //     $summaries = Fitness_Summary::where('member_id', $memberID)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->paginate(8, ['*'], 'page', request('page'));
            // else if ($startDate != null)
            //     $summaries = Fitness_Summary::where('member_id', $memberID)->whereDate('created_at', '>=', $startDate)->paginate(8, ['*'], 'page', request('page'));
            // else if ($endDate != null)
            //     $summaries = Fitness_Summary::where('member_id', $memberID)->whereDate('created_at', '<=', $endDate)->paginate(8, ['*'], 'page', request('page'));
        }

        // $summaries = $summaries->each(function ($summary) {
        //     $summary->member->user;
        // });
        if($page != null) {

            $summaries = $summaries->paginate(5);
        } else {
            $summaries = $summaries->get();
        }

        return $this->returnData('fitness summaries', $summaries);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fitness_Summary  $fitness_Summary
     * @return \Illuminate\Http\Response
     */
    public function edit(Fitness_Summary $fitness_Summary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fitness_Summary  $fitness_Summary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $fitness_Summary)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fitness_Summary  $fitness_Summary
     * @return \Illuminate\Http\Response
     */
    public function destroy($fitness_summary)
    {
        $this->authorize('delete', auth()->user(), Fitness_Summary::class);

        $FitSummary = Fitness_Summary::find($fitness_summary);

        if ($FitSummary == null)
            return $this->returnError($this->getErrorCode('fitness summary not found'), 404, 'Fitness Summary Not Found');

        $FitSummary->delete();

        return $this->returnSuccessMessage('Fitness Summary Deleted Successfully!');
    }
}
