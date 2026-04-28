<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\MainSet;
use App\Models\Seting;
use App\Models\Slider;
use App\Support\HomePreviewContent;
use Illuminate\Support\Facades\Cache;

class MainController extends Controller
{
    public function index()
    {
        $mainset = Cache::remember('mainset', 60, function () {
            return MainSet::first();
        });

        $seting = Cache::remember('seting главная', 60, function () {
            return Seting::where('page', 'Главная')->first();
        });

        $groups = $this->getGalleryGroups();
        $previewContent = HomePreviewContent::load();
        $heroConfig = $previewContent['hero'] ?? HomePreviewContent::defaults()['hero'];
        $showcaseConfig = $previewContent['showcase'] ?? HomePreviewContent::defaults()['showcase'];
        $featuredGroups = $groups->take(4)->values();
        $primaryGroups = collect($heroConfig['quick_category_slugs'] ?? [])
            ->map(fn (string $slug) => $groups->firstWhere('slug', $slug))
            ->filter()
            ->values();
        $showcaseGroups = collect($showcaseConfig['category_slugs'] ?? [])
            ->map(fn (string $slug) => $groups->firstWhere('slug', $slug))
            ->filter()
            ->values();

        if ($primaryGroups->isEmpty()) {
            $primaryGroups = $groups->take(4)->values();
        }

        if ($showcaseGroups->isEmpty()) {
            $showcaseGroups = $featuredGroups;
        }

        $highlightImage = optional($primaryGroups->first()?->slides->first())->getImage()
            ?? optional($featuredGroups->first()?->slides->first())->getImage()
            ?? $seting?->getImage();

        return view('welcome', compact(
            'mainset',
            'seting',
            'groups',
            'featuredGroups',
            'primaryGroups',
            'showcaseGroups',
            'highlightImage',
            'previewContent'
        ));
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
        $previewContent = HomePreviewContent::load();

        return view('gallery', compact('mainset', 'groups', 'seting', 'sliders', 'previewContent'));
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
        $previewContent = HomePreviewContent::load();
        $selectedGroup = $groups->firstWhere('slug', $slug);

        abort_if(!$selectedGroup, 404);

        $seoData = [
            'title' => $selectedGroup->getDefinition()['seo_title'],
            'description' => $selectedGroup->getDefinition()['seo_description'],
            'keywords' => $selectedGroup->getDefinition()['seo_keywords'],
            'image' => optional($selectedGroup->slides->first())->getImage() ?? $seting->getImage(),
        ];

        return view('gallery', compact('mainset', 'groups', 'selectedGroup', 'seting', 'sliders', 'seoData', 'previewContent'));
    }

    public function homePreview()
    {
        $mainset = Cache::remember('mainset_preview', 60, function () {
            return MainSet::first();
        });

        $seting = Cache::remember('seting_preview', 60, function () {
            return Seting::where('page', 'Главная')->first();
        });

        $groups = $this->getGalleryGroups();
        $previewContent = HomePreviewContent::load();
        $heroConfig = $previewContent['hero'] ?? HomePreviewContent::defaults()['hero'];
        $showcaseConfig = $previewContent['showcase'] ?? HomePreviewContent::defaults()['showcase'];
        $featuredGroups = $groups->take(4)->values();
        $primaryGroups = collect($heroConfig['quick_category_slugs'] ?? [])
            ->map(fn (string $slug) => $groups->firstWhere('slug', $slug))
            ->filter()
            ->values();
        $showcaseGroups = collect($showcaseConfig['category_slugs'] ?? [])
            ->map(fn (string $slug) => $groups->firstWhere('slug', $slug))
            ->filter()
            ->values();

        if ($primaryGroups->isEmpty()) {
            $primaryGroups = $groups->take(4)->values();
        }

        if ($showcaseGroups->isEmpty()) {
            $showcaseGroups = $featuredGroups;
        }

        $highlightImage = optional($primaryGroups->first()?->slides->first())->getImage()
            ?? optional($featuredGroups->first()?->slides->first())->getImage()
            ?? $seting?->getImage();

        $seoData = [
            'title' => 'Preview: новая главная | KBR Mebel',
            'description' => 'Закрытый preview-экран новой главной страницы KBR Mebel.',
            'keywords' => 'preview, мебель на заказ, KBR Mebel',
            'image' => $highlightImage,
        ];

        return view('preview.home', compact(
            'mainset',
            'seting',
            'groups',
            'featuredGroups',
            'primaryGroups',
            'showcaseGroups',
            'highlightImage',
            'seoData',
            'previewContent'
        ));
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
