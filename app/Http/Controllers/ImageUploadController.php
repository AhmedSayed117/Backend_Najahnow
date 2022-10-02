<?php

namespace App\Http\Controllers;

use App\Http\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class ImageUploadController extends Controller
{
    use GeneralTrait;
    public function postImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $imageName = time().$request->image->getClientOriginalName();

        $request->image->move(public_path('images'), $imageName);

        return  $this->returnData('image',$imageName , 200, 'Image stored successfully');

    }
    public function fetchImage($fileName)
    {
        $path = public_path('images').'/'.$fileName;
        return Response::download($path);
    }
}
