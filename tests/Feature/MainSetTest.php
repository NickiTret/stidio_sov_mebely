<?php

namespace Tests\Feature;

use App\Models\MainSet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MainSetTest extends TestCase
{
    use RefreshDatabase;

    private array $valid = [
        'tel' => '+7 (928) 078-28-94',
        'email' => 'info@kbr-mebel.ru',
        'telegram' => 'https://t.me/kbrmebel',
        'watsap' => 'https://wa.me/79280782894',
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

    public function test_first_save_creates_single_record_and_second_updates_it(): void
    {
        $this->post(route('mainset.store'), $this->valid)->assertSessionHasNoErrors();
        $this->patch(route('mainset.update'), ['footer' => 'Новая подпись'] + $this->valid)->assertSessionHasNoErrors();

        $this->assertDatabaseCount('main_sets', 1);
        $this->assertSame('Новая подпись', MainSet::first()->footer);
        $this->get(route('mainset.index'))->assertOk()->assertSee('Новая подпись');
    }

    public function test_username_and_phone_are_normalized_to_links(): void
    {
        $this->post(route('mainset.store'), [
            'telegram' => '@kbr_mebel',
            'watsap' => '8 (928) 078-28-94',
        ] + $this->valid)->assertSessionHasNoErrors();

        $mainset = MainSet::first();
        $this->assertSame('https://t.me/kbr_mebel', $mainset->telegram);
        $this->assertSame('https://wa.me/79280782894', $mainset->watsap);

        $this->patch(route('mainset.update'), [
            'telegram' => 't.me/kbrmebel',
            'watsap' => 'wa.me/79280782894',
        ] + $this->valid)->assertSessionHasNoErrors();

        $mainset->refresh();
        $this->assertSame('https://t.me/kbrmebel', $mainset->telegram);
        $this->assertSame('https://wa.me/79280782894', $mainset->watsap);
    }

    public function test_javascript_links_and_bad_email_are_rejected(): void
    {
        $this->post(route('mainset.store'), [
            'telegram' => 'javascript:alert(1)',
            'watsap' => 'ftp://example.com',
            'email' => 'not-an-email',
            'tel' => '<b>123</b>',
        ] + $this->valid)->assertSessionHasErrors(['telegram', 'watsap', 'email', 'tel']);

        $this->assertDatabaseCount('main_sets', 0);
    }

    public function test_extra_fields_are_ignored(): void
    {
        $this->post(route('mainset.store'), ['id' => 99, 'created_at' => '2000-01-01'] + $this->valid)
            ->assertSessionHasNoErrors();

        $this->assertNotSame(99, MainSet::first()->id);
    }
}
