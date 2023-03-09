<?php

namespace App\Http\Controllers\Slider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Slider\StoreRequest;
use App\Models\Group;
use App\Models\Slider;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request) {

        $data = $request->validated();
        $data['image_src'] = Slider::uploadImage($request);
        $group = $data['group_id'];
        unset($data['group_id']);


        $slider = Slider::create($data);


        return redirect()->route('slider.index');

    }
}
