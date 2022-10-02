<?php

namespace App\Http\Controllers;

use App\Traits\GeneralTrait;

use App\Models\Supplementary;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SupplementaryController extends Controller
{
    use GeneralTrait;
    /**
     * Get all supplementaries
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supplementaries = Supplementary::all();
        if ($supplementaries->isEmpty())
            return $this->returnError('There are no supplementaries');
        return $this->returnData('supplementaries', $supplementaries);
    }

    /**
     * Get Supplementary by ID
     *
     * @return \Illuminate\Http\Response
     */
    public function GetSupplementaryByID(Request $request)
    {
        $Supplementary = Supplementary::find($request->supplementaryID);
        if (!$Supplementary)
            return $this->returnError('There is no Supplementary with this id');
        return $this->returnData('Supplementary', $Supplementary);
    }


    // Add new Supplementary

    public function AddSupplementary(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize(Supplementary::class);

        //validation
        $rules = [
            "title" => "required",
            "description" => "required",
            "price" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }


        $newSupplementary = Supplementary::create($request->all());
        return $this->returnData('newSupplementary', $newSupplementary, "Supplementary Added correctly");
    }


    // Edit existing Supplementary

    public function EditSupplementary(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize(Supplementary::class);

        //validation
        $rules = [
            "title" => "required",
            "description" => "required",
            "price" => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }


        if (Supplementary::where('id', $request->supplementaryID)->update($request->all()))
            return $this->returnSuccessMessage("Supplementary updated correctly");
        return $this->returnError('Failed to update Supplementary');
    }


    // Delete Supplementary
    public function DeleteSupplementary(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize(Supplementary::class);

        if (Supplementary::where('id', $request->supplementaryID)->delete())
            return $this->returnSuccessMessage("Supplementary deleted correctly");
        return $this->returnError('Failed to delete Supplementary');
    }

    // Get Branch Supplementaries
    public function GetBranchSupplementaries(Request $request)
    {
        $branch = Branch::find($request->branchID);
        if ($branch != NULL) {
            $supp = $branch->supplementaries;
            if ($supp->isEmpty())
                return $this->returnError('No supplementaries in this branch');
            return $this->returnData('supplementaries', $supp);
        }
        return $this->returnError('Wrong Branch ID');
    }

    public function AddSupplementaryToBranch(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize('create', Supplementary::class);

        $Branch = Branch::find($request->branchID);
        if (!$Branch) {
            return $this->returnError('Branch not found');
        }

        if ($Branch->supplementaries->contains('id', request('supplementaryID')))
            return $this->returnError('Branch already has the supplementary!');
        $Branch->supplementaries()->attach(request('supplementaryID'), ['quantity' => request('quantity')]);

        return $this->returnSuccessMessage("Supplementary added correctly to branch");
    }

    public function RemoveSupplementaryFromBranch(Request $request)
    {
        //authentication
        $user = $this->authenticateUser();
        if (!$user) {
            return response()->json(['error' => 'An error occurred']);
        }

        //authorization
        $this->authorize(Supplementary::class);

        $Branch = Branch::find($request->branchID);
        if (!$Branch) {
            return $this->returnError('Branch not found');
        }
        if (!$Branch->supplementaries->contains('id', request('supplementaryID')))
            return $this->returnError('Branch has not the supplementary!');
        $Branch->supplementaries()->detach(request('supplementaryID'));

        return $this->returnSuccessMessage("Supplementary removed correctly from branch");
    }
}
