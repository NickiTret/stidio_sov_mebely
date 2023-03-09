<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MainSet;
use App\Models\Seting;
use App\Models\Slider;
use App\Models\Post;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function __invoke() {
        $mainset = MainSet::first();
        $seting = Seting::first();
        $sliders = Slider::all();
        $posts = Post::all();
        return view('welcome', compact('mainset', 'seting', 'sliders', 'posts'));
    }
}
