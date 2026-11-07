<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Page;
use App\Support\HomePreviewContent;
use App\Support\SeoLandingPages;
use App\Support\SiteCache;

class MainController extends Controller
{
    public function index()
    {
        $mainset = SiteCache::mainset();

        $seting = SiteCache::seting('Главная');

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
        $mainset = SiteCache::mainset();

        $seting = SiteCache::seting('Галерея');

        $sliders = SiteCache::sliders();

        $groups = $this->getGalleryGroups();
        $previewContent = HomePreviewContent::load();

        return view('gallery', compact('mainset', 'groups', 'seting', 'sliders', 'previewContent'));
    }

    public function galleryCategory(string $slug)
    {
        $mainset = SiteCache::mainset();

        $seting = SiteCache::seting('Галерея');

        $sliders = SiteCache::sliders();

        $groups = $this->getGalleryGroups();
        $previewContent = HomePreviewContent::load();
        $selectedGroup = $groups->firstWhere('slug', $slug);

        abort_if(!$selectedGroup, 404);

        $seoData = [
            'title' => $selectedGroup->getDefinition()['seo_title'],
            'description' => $selectedGroup->getDefinition()['seo_description'],
            'keywords' => $selectedGroup->getDefinition()['seo_keywords'],
            'image' => optional($selectedGroup->slides->first())->getImage() ?? $seting?->getImage(),
        ];

        return view('gallery', compact('mainset', 'groups', 'selectedGroup', 'seting', 'sliders', 'seoData', 'previewContent'));
    }

    public function servicePage(string $landingSlug)
    {
        $landing = SeoLandingPages::find($landingSlug);

        abort_if(!$landing, 404);

        $mainset = SiteCache::mainset();

        $seting = SiteCache::seting('Главная');

        $groups = $this->getGalleryGroups();
        $previewContent = HomePreviewContent::load();
        $selectedGroup = $groups->firstWhere('slug', $landing['gallery_slug']);
        $relatedGroups = $groups
            ->reject(fn (Group $group) => $group->slug === $landing['gallery_slug'])
            ->take(4)
            ->values();
        $seoImage = optional($selectedGroup?->slides->first())->getImage() ?? $seting?->getImage();

        $seoData = [
            'title' => $landing['seo_title'],
            'description' => $landing['seo_description'],
            'keywords' => $landing['seo_keywords'],
            'image' => $seoImage,
        ];

        $schemaService = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $landing['title'],
            'description' => $landing['seo_description'],
            'areaServed' => [
                '@type' => 'City',
                'name' => 'Нальчик',
            ],
            'provider' => [
                '@type' => 'FurnitureStore',
                'name' => 'Студия Современной Мебели',
                'url' => url('/'),
                'telephone' => $mainset?->tel,
            ],
        ];

        return view('service-page', compact(
            'mainset',
            'seting',
            'groups',
            'previewContent',
            'landing',
            'selectedGroup',
            'relatedGroups',
            'seoData',
            'schemaService'
        ));
    }

    public function contacts()
    {
        $mainset = SiteCache::mainset();

        $seting = SiteCache::seting('Главная');

        $groups = $this->getGalleryGroups();
        $previewContent = HomePreviewContent::load();
        $seoData = [
            'title' => 'Контакты мебельной студии в Нальчике | KBR Mebel',
            'description' => 'Контакты KBR Mebel в Нальчике: телефон, WhatsApp, email, адрес, карта, замер и расчет мебели на заказ.',
            'keywords' => 'контакты KBR Mebel, мебель на заказ Нальчик контакты, студия современной мебели Нальчик',
        ];

        return view('contacts', compact('mainset', 'seting', 'groups', 'previewContent', 'seoData'));
    }

    public function privacy()
    {
        // Пока текст не заполнен в админке, страницы нет (и ссылок на неё тоже).
        $page = SiteCache::page(Page::PRIVACY);

        abort_if(!$page, 404);

        $mainset = SiteCache::mainset();

        $seting = SiteCache::seting('Главная');

        $groups = $this->getGalleryGroups();
        $previewContent = HomePreviewContent::load();
        $seoData = [
            'title' => $page->title . ' | KBR Mebel',
            'description' => $page->title . ' — как KBR Mebel (Нальчик) собирает, использует и защищает данные из заявок на сайте.',
            'keywords' => 'политика конфиденциальности, персональные данные, KBR Mebel',
        ];

        return view('privacy', compact('mainset', 'seting', 'groups', 'previewContent', 'seoData', 'page'));
    }

    public function sitemap()
    {
        $urls = collect([
            route('home'),
            route('gallery'),
            route('contacts'),
        ]);

        if (SiteCache::page(Page::PRIVACY)) {
            $urls->push(route('privacy'));
        }

        foreach (SeoLandingPages::all() as $landing) {
            $urls->push(route('service.page', $landing['slug']));
        }

        foreach ($this->getGalleryGroups() as $group) {
            $urls->push(route('gallery.category', $group->slug));
        }

        return response()
            ->view('sitemap', ['urls' => $urls->unique()->values()])
            ->header('Content-Type', 'application/xml');
    }

    public function homePreview()
    {
        $mainset = SiteCache::mainset();

        $seting = SiteCache::seting('Главная');

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
        return SiteCache::galleryGroups();
    }
}
