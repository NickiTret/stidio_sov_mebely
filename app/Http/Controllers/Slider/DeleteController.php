<?php

namespace App\Http\Controllers\Slider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Slider;

class DeleteController extends Controller
{
    public function __invoke(Slider $slider) {

        $slider->delete();

        return redirect()->route('slider.index');
    }
}
