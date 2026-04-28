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
        $mainset = Cache::remember('mainset_gallery', 60, function () {
            return MainSet::first();
        });

        $seting = Cache::remember('seting галерея', 60, function () {
            return Seting::where('page', 'Галерея')->first();
        });

        $sliders = Cache::remember('sliders_gallery', 60, function () {
            return Slider::all();
        });

        $groups = $this->getGalleryGroups();

        return view('gallery', compact('mainset', 'groups', 'seting', 'sliders'));
    }

    public function galleryCategory(string $slug)
    {
        $mainset = Cache::remember('mainset_gallery', 60, function () {
            return MainSet::first();
        });

        $seting = Cache::remember('seting галерея', 60, function () {
            return Seting::where('page', 'Галерея')->first();
        });

        $sliders = Cache::remember('sliders_gallery', 60, function () {
            return Slider::all();
        });

        $groups = $this->getGalleryGroups();
        $selectedGroup = $groups->firstWhere('slug', $slug);

        abort_if(!$selectedGroup, 404);

        $seoData = [
            'title' => $selectedGroup->getDefinition()['seo_title'],
            'description' => $selectedGroup->getDefinition()['seo_description'],
            'keywords' => $selectedGroup->getDefinition()['seo_keywords'],
            'image' => optional($selectedGroup->slides->first())->getImage() ?? $seting->getImage(),
        ];

        return view('gallery', compact('mainset', 'groups', 'selectedGroup', 'seting', 'sliders', 'seoData'));
    }

    private function getGalleryGroups()
    {
        $groups = Cache::remember('groups', 60, function () {
            return Group::with('slides')->get();
        });

        $orderedSlugs = collect(Group::categoryDefinitions())->pluck('slug')->all();

        return $groups
            ->filter(fn (Group $group) => $group->slides->isNotEmpty())
            ->sortBy(function (Group $group) use ($orderedSlugs) {
                $position = array_search($group->slug, $orderedSlugs, true);

                return $position === false ? PHP_INT_MAX : $position;
            })
            ->values();
    }
}
