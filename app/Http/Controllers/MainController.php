<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\MainSet;
use App\Models\Seting;
use App\Models\Slider;
use App\Models\Post;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index() {
        $mainset = MainSet::first();
        $seting = Seting::where('page', 'Главная')->first();
        $sliders = Slider::all();
        $posts = Post::all();
        return view('welcome', compact('mainset', 'seting', 'sliders', 'posts'));
    }

    public function gallery() {
        $mainset = MainSet::first();
        $seting = Seting::where('page', 'Галерея')->first();
        $sliders = Slider::all();
        $groups = Group::all();
        return view('gallery', compact('mainset', 'groups', 'seting', 'sliders'));
    }
}
