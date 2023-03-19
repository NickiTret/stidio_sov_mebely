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

        // if ($request->hasFile('image_src')) {
        //     Storage::delete($slider->image_src);
        //     $folder = date('Y-m-d');
        //     $data['image_src'] = $request->file('image_src')->store("images/{$folder}");
        // }
        $data['image_src'] = Slider::uploadImage($request);

        $slider->update($data);

        $sliders = Slider::all();

        return view('slider.index', compact('slider', 'sliders'));
    }
}
