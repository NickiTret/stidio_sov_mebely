<?php

namespace App\Http\Controllers\Slider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Slider\UpdateRequest;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Slider $slider) {

        $data = $request->validated();


        $data['image_src'] = Slider::uploadImage($request, $slider->image_src);

        $slider->update($data);

        $sliders = Slider::all();

        return view('slider.index', compact('slider', 'sliders'));
    }
}
