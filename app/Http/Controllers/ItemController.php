<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\GeneralTrait;

class ItemController extends Controller
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

    public function index($item)
    {
        //
        $fetchedItem = Item::find($item);

        if ($fetchedItem == null) //status code shouldn't be 200
            return $this->returnError($this->getErrorCode('item not found'), 404, 'Item Not Found');

        return $this->returnData('item', $fetchedItem);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $user = auth()->user();
        $this->authorize('create', Item::class);

        $validation = Validator::make(request()->all(), [
            'title' => 'required|unique:item,title',
            'cal' => 'required|integer',
            'level' => 'required'
        ]);

        if ($validation->fails()) {
            $code = $this->returnCodeAccordingToInput($validation);
            return $this->returnValidationError($code, $validation);
        }

        Item::create([
            'title' => request('title'),
            'cal' => request('cal'),
            'description' => request('description'),
            'image' => request('image'),
            'nutritionist_id' => request('nutritionist_id'), //Must be changed
            'level' => request('level') //list the enum values only in frontend
        ]);

        return $this->returnSuccessMessage('Item Created Successfully!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if (request('text') != null) {
            $prefixItems = Item::where('title', 'LIKE', request('text') . '%')->get();
            $subStringItems = Item::where('title', 'LIKE', '_%' . request('text') . '%')->get();
            $theUnion = $prefixItems->merge($subStringItems);
            $paginatedItems = (collect($theUnion))->forPage(request('page'), 8)->all();

            return $this->returnData('items', $paginatedItems);
        }

        return $this->returnData('items', Item::paginate(8, ['*'], 'page', request('page')));
    }

    public function showSearch()
    {
        return Item::where('title', 'LIKE', request('prefix') . '%')->limit(5)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update($item)
    {

        $this->authorize('update', Item::class);

        $validation = Validator::make(request()->all(), [
            'title' => 'unique:item,title,'.$item,
            'cal' => 'integer',
            'level' => 'in:red,green,yellow'
        ]);

        if ($validation->fails()) {
            $code = $this->returnCodeAccordingToInput($validation);
            return $this->returnValidationError($code, $validation);
        }

        $Item = Item::find($item);
        if ($Item == null)
            return $this->returnError($this->getErrorCode('item not found'), 404, 'Item Not Found');

        $Item->update(request()->all());

        return $this->returnSuccessMessage("Item Updated Successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy($item)
    {
        $this->authorize('delete', Item::class);

        $fetchedItem = Item::find($item);

        if ($fetchedItem == null)
            return $this->returnError($this->getErrorCode('item not found'), 404, 'Item Not Found');

        $fetchedItem->delete();

        return $this->returnSuccessMessage("Item Deleted Successfully!");
    }
}
