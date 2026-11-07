<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\MainSet;
use App\Models\Seting;
use App\Models\Slider;
use App\Models\User;
use App\Support\SiteCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteCacheTest extends TestCase
{
    use RefreshDatabase;

    private array $mainset = [
        'tel' => '+7 (928) 000-00-01',
        'email' => 'info@kbr-mebel.ru',
        'telegram' => 'https://t.me/kbrmebel',
        'watsap' => 'https://wa.me/79280000001',
        'map' => 'Нальчик',
        'footer' => 'KBR Mebel',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $admin = new User(['name' => 'Admin', 'email' => 'admin@test.local']);
        $admin->password = 'x';
        $admin->is_admin = 1;
        $admin->save();
        $this->actingAs($admin);
    }

    public function test_mainset_change_in_admin_is_visible_immediately(): void
    {
        MainSet::create($this->mainset);
        $this->assertSame('+7 (928) 000-00-01', SiteCache::mainset()->tel); // прогрели кеш

        $this->patch(route('mainset.update'), ['tel' => '+7 (928) 999-99-99'] + $this->mainset)
            ->assertSessionHasNoErrors();

        $this->assertSame('+7 (928) 999-99-99', SiteCache::mainset()->tel);
    }

    public function test_seting_change_is_visible_immediately(): void
    {
        $seting = Seting::create(['page' => 'Главная', 'title' => 'Старый', 'descriptions' => 'd', 'keywords' => 'k', 'favicon' => 'images/x.png']);
        $this->assertSame('Старый', SiteCache::seting('Главная')->title);

        $seting->update(['title' => 'Новый']);

        $this->assertSame('Новый', SiteCache::seting('Главная')->title);
    }

    public function test_slide_and_group_changes_are_visible_immediately(): void
    {
        $group = Group::create(['title' => 'Кухни']);
        $this->assertCount(0, SiteCache::groupsWithSlides()->first()->slides);
        $this->assertCount(0, SiteCache::sliders());

        $slide = Slider::create(['title' => 'Фото', 'description' => 'd', 'image_src' => 'images/x.jpg', 'group_id' => $group->id]);
        $this->assertCount(1, SiteCache::groupsWithSlides()->first()->slides);
        $this->assertCount(1, SiteCache::sliders());

        $slide->delete();
        $this->assertCount(0, SiteCache::sliders());

        $group->delete();
        $this->assertCount(0, SiteCache::groupsWithSlides());
    }
}
