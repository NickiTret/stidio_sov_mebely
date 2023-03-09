<?php

namespace App\Http\Controllers\Slider;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

use App\Models\Slider;

class EditController extends Controller
{
    public function __invoke(Slider $slider) {
        $groups = Group::all();
        return view('slider.edit', compact('slider', 'groups'));
    }
}
