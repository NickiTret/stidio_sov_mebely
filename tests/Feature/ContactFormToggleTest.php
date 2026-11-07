<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\MainSet;
use App\Models\Slider;
use App\Models\User;
use App\Support\SeoLandingPages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Переключатель «Показывать форму заявки на сайте» в «Настройках сайта». */
class ContactFormToggleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        MainSet::create([
            'tel' => '8 (928) 078-28-94',
            'email' => 'info@kbr-mebel.ru',
            'telegram' => 'https://t.me/kbrmebel',
            'watsap' => 'https://wa.me/79280782894',
            'map' => 'Нальчик',
            'footer' => 'KBR Mebel',
        ]);

        $group = Group::create(['title' => 'Кухни']);
        Slider::create(['title' => 'Кухня 1', 'description' => 'd', 'image_src' => 'images/k.jpg', 'group_id' => $group->id]);
    }

    private function pages(): array
    {
        return [
            route('home'),
            route('gallery'),
            route('contacts'),
            route('service.page', SeoLandingPages::slugs()[0]),
        ];
    }

    private function submitMessage()
    {
        return $this->post(route('message.store'), [
            'name' => 'Иван',
            'tel' => '+7 (928) 000-00-00',
            'mail' => 'ivan@example.com',
            'content' => '',
            'form_started_at' => time() - 10,
            'consent' => '1',
        ]);
    }

    public function test_form_is_shown_by_default(): void
    {
        $this->assertTrue(MainSet::first()->isContactFormEnabled());

        foreach ($this->pages() as $url) {
            $this->get($url)->assertOk()->assertSee('js-contact-form', false);
        }

        $this->get(route('home'))->assertSee('data-custom-open="modal-contacts"', false);
    }

    public function test_admin_can_hide_form_everywhere(): void
    {
        $admin = new User(['name' => 'Admin', 'email' => 'admin@test.local']);
        $admin->password = 'x';
        $admin->is_admin = 1;
        $admin->save();

        // Снятый чекбокс в запрос не попадает вовсе.
        $this->actingAs($admin)->patch(route('mainset.update'), [
            'tel' => '8 (928) 078-28-94',
            'email' => 'info@kbr-mebel.ru',
            'telegram' => 'https://t.me/kbrmebel',
            'watsap' => 'https://wa.me/79280782894',
            'map' => 'Нальчик',
            'footer' => 'KBR Mebel',
        ])->assertSessionHasNoErrors();

        $this->assertFalse(MainSet::first()->isContactFormEnabled());

        foreach ($this->pages() as $url) {
            $this->get($url)
                ->assertOk()
                ->assertDontSee('js-contact-form', false)
                ->assertDontSee('id="contact-form"', false)
                ->assertDontSee('data-custom-open="modal-contacts"', false);
        }

        // Вместо кнопки, открывающей форму, — звонок.
        $this->get(route('home'))->assertSee('href="tel:+79280782894"', false);

        $this->submitMessage()->assertNotFound();
        $this->assertDatabaseCount('messages', 0);

        // Включаем обратно.
        $this->actingAs($admin)->patch(route('mainset.update'), [
            'tel' => '8 (928) 078-28-94',
            'email' => 'info@kbr-mebel.ru',
            'telegram' => 'https://t.me/kbrmebel',
            'watsap' => 'https://wa.me/79280782894',
            'map' => 'Нальчик',
            'footer' => 'KBR Mebel',
            'contact_form_enabled' => '1',
        ])->assertSessionHasNoErrors();

        $this->get(route('home'))->assertSee('js-contact-form', false);
        $this->submitMessage()->assertSessionHasNoErrors()->assertRedirect();
        $this->assertDatabaseCount('messages', 1);
    }
}
