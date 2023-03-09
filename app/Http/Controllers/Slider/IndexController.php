<?php

namespace App\Http\Controllers\Slider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Slider;

class IndexController extends Controller
{
    public function __invoke() {
        $sliders = Slider::all();
        return view('slider.index', compact('sliders'));
    }
}
