<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\MainSet;
use App\Models\Seting;
use App\Models\Slider;
use App\Support\SeoLandingPages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        MainSet::create([
            'tel' => '+7 (928) 078-28-94',
            'email' => 'info@kbr-mebel.ru',
            'telegram' => 'https://t.me/kbrmebel',
            'watsap' => 'https://wa.me/79280782894',
            'map' => 'Нальчик',
            'footer' => 'KBR Mebel',
        ]);

        foreach (['Главная', 'Галерея'] as $page) {
            Seting::create(['page' => $page, 'title' => "SEO $page", 'descriptions' => 'd', 'keywords' => 'k', 'favicon' => 'images/f.png']);
        }

        $group = Group::create(['title' => 'Кухни']);
        Slider::create(['title' => 'Кухня 1', 'description' => 'd', 'image_src' => 'images/k.jpg', 'group_id' => $group->id]);
    }

    public function test_public_pages_render(): void
    {
        $urls = [
            route('home'),
            route('gallery'),
            route('gallery.category', 'kuhni'),
            route('contacts'),
            route('sitemap'),
            route('service.page', SeoLandingPages::slugs()[0]),
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_admin_change_shows_on_site_without_waiting_for_cache(): void
    {
        $this->get(route('contacts'))->assertSee('+7 (928) 078-28-94');

        MainSet::first()->update(['tel' => '+7 (999) 111-22-33']);

        $this->get(route('contacts'))->assertSee('+7 (999) 111-22-33');
    }
}
