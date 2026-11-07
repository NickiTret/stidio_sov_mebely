<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\MainSet;
use App\Models\Seting;
use App\Models\Slider;
use App\Models\User;
use App\Support\SeoLandingPages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Аудит фронта 2026-11-07, пункты 12–14 в CLAUDE.md.
 */
class FrontendResilienceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $group = Group::create(['title' => 'Кухни']);
        Slider::create(['title' => 'Кухня 1', 'description' => 'd', 'image_src' => 'images/k.jpg', 'group_id' => $group->id]);
        Group::create(['title' => 'Спальни']); // без фото — на сайте её быть не должно
    }

    private function publicUrls(): array
    {
        return [
            route('home'),
            route('gallery'),
            route('gallery.category', 'kuhni'),
            route('contacts'),
            route('sitemap'),
            route('service.page', SeoLandingPages::slugs()[0]),
        ];
    }

    private function createMainSet(string $tel = '8 (928) 078-28-94'): void
    {
        MainSet::create([
            'tel' => $tel,
            'email' => 'info@kbr-mebel.ru',
            'telegram' => 'https://t.me/kbrmebel',
            'watsap' => 'https://wa.me/79280782894',
            'map' => 'Нальчик',
            'footer' => 'KBR Mebel',
        ]);
    }

    private function createSetings(): void
    {
        foreach (array_keys(Seting::PAGES) as $page) {
            Seting::create(['page' => $page, 'title' => "SEO $page", 'descriptions' => 'd', 'keywords' => 'k', 'favicon' => 'images/f.png']);
        }
    }

    // п.12
    public function test_pages_render_without_seo_settings(): void
    {
        $this->createMainSet();

        foreach ($this->publicUrls() as $url) {
            $this->get($url)->assertOk();
        }
    }

    // п.12
    public function test_pages_render_on_empty_database(): void
    {
        foreach ($this->publicUrls() as $url) {
            $this->get($url)->assertOk();
        }
    }

    // п.12
    public function test_seo_settings_used_on_site_cannot_be_deleted(): void
    {
        $this->createSetings();
        $unused = Seting::create(['page' => '/gallery/kuhni', 'title' => 't', 'descriptions' => 'd', 'keywords' => 'k', 'favicon' => 'images/x.png']);

        $admin = new User(['name' => 'Admin', 'email' => 'admin@test.local']);
        $admin->password = 'x';
        $admin->is_admin = 1;
        $admin->save();
        $this->actingAs($admin);

        $used = Seting::where('page', 'Главная')->first();

        $this->delete(route('seting.delete', $used))
            ->assertRedirect(route('seting.index'))
            ->assertSessionHas('error');
        $this->assertModelExists($used);

        $this->delete(route('seting.delete', $unused))->assertSessionHas('success');
        $this->assertModelMissing($unused);
    }

    // п.13
    public function test_menu_and_sitemap_link_only_categories_with_photos(): void
    {
        $this->createMainSet();
        $this->createSetings();

        $sitemap = $this->get(route('sitemap'))->assertOk()->getContent();
        $this->assertStringContainsString(route('gallery.category', 'kuhni'), $sitemap);
        $this->assertStringNotContainsString(route('gallery.category', 'spalni'), $sitemap);

        $home = $this->get(route('home'))->getContent();
        $this->assertStringContainsString('href="' . route('gallery.category', 'kuhni') . '"', $home);
        $this->assertStringNotContainsString('href="' . route('gallery.category', 'spalni') . '"', $home);

        preg_match_all('#<loc>(.*?)</loc>#', $sitemap, $matches);
        foreach ($matches[1] as $url) {
            $this->get($url)->assertOk();
        }
    }

    // п.14
    public function test_header_phone_comes_from_site_settings(): void
    {
        $this->createMainSet('8 (999) 111-22-33');
        $this->createSetings();

        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('href="tel:+79991112233"', false)
            ->assertSee('8 (999) 111-22-33')
            ->assertDontSee('+79280782894');
    }
}
