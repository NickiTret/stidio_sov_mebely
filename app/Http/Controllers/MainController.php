<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\MainSet;
use App\Models\Seting;
use App\Models\Slider;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MainController extends Controller
{
    public function index()
    {
        // Кэшируем MainSet
        $mainset = Cache::remember('mainset', 60, function () {
            return MainSet::first();
        });

        // Кэшируем Seting для главной страницы
        $seting = Cache::remember('seting главная', 60, function () {
            return Seting::where('page', 'Главная')->first();
        });

        // Кэшируем Sliders
        $sliders = Cache::remember('sliders', 60, function () {
            return Slider::all();
        });

        // Кэшируем Posts, выбирая только нужные поля
        $posts = Cache::remember('posts', 60, function () {
            return Post::all();
        });

        return view('welcome', compact('mainset', 'seting', 'sliders', 'posts'));
    }

    public function gallery()
    {
        // Кэшируем MainSet
        $mainset = Cache::remember('mainset_gallery', 60, function () {
            return MainSet::first();
        });

        // Кэшируем Seting для галереи
        $seting = Cache::remember('seting галерея', 60, function () {
            return Seting::where('page', 'Галерея')->first();
        });

        // Кэшируем Sliders
        $sliders = Cache::remember('sliders_gallery', 60, function () {
            return Slider::all();
        });

        // Кэшируем Groups с использованием eager loading
        $groups = Cache::remember('groups', 60, function () {
            return Group::with('slides')->get();
        });

        return view('gallery', compact('mainset', 'groups', 'seting', 'sliders'));
    }
}
