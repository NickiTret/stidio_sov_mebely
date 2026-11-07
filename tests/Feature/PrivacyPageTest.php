<?php

namespace Tests\Feature;

use App\Models\MainSet;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Документы /privacy и /consent, их редактор в админке и галочка согласия (п.15 в CLAUDE.md). */
class PrivacyPageTest extends TestCase
{
    use RefreshDatabase;

    private const TEXT = "1. Общие положения\n"
        . "Настоящая политика определяет порядок обработки персональных данных.\n\n"
        . "2. Какие данные мы собираем\r\n"
        . "Имя, телефон и email из формы заявки <script>alert(1)</script>.";

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
    }

    private function actingAsAdmin(): void
    {
        $admin = new User(['name' => 'Admin', 'email' => 'admin@test.local']);
        $admin->password = 'x';
        $admin->is_admin = 1;
        $admin->save();
        $this->actingAs($admin);
    }

    public function test_page_is_hidden_until_text_is_filled(): void
    {
        $this->get(route('privacy'))->assertNotFound();
        $this->get(route('home'))->assertOk()->assertDontSee('href="' . route('privacy') . '"', false);
        $this->get(route('sitemap'))->assertDontSee(route('privacy'));
    }

    public function test_admin_saves_text_and_it_is_published(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.page.edit', 'privacy'))->assertOk()->assertSee('Политика обработки персональных данных');

        $this->patch(route('admin.page.update', 'privacy'), [
            'title' => 'Политика обработки персональных данных',
            'content' => self::TEXT,
        ])->assertRedirect(route('admin.page.edit', 'privacy'))->assertSessionHas('success');

        $this->assertSame(1, Page::count());

        $this->get(route('privacy'))
            ->assertOk()
            ->assertSee('<h2>1. Общие положения</h2>', false)
            ->assertSee('<h2>2. Какие данные мы собираем</h2>', false)
            ->assertSee('<p>Настоящая политика определяет порядок обработки персональных данных.</p>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);

        // Ссылки: у галочки согласия в CTA, в модальной форме и в футере.
        $home = $this->get(route('home'))->getContent();
        $this->assertSame(3, substr_count($home, 'href="' . route('privacy') . '"'));
        $this->assertStringNotContainsString('href="' . route('consent') . '"', $home);

        $this->get(route('sitemap'))->assertSee(route('privacy'));
    }

    public function test_clearing_text_hides_page_again(): void
    {
        $this->actingAsAdmin();
        $this->patch(route('admin.page.update', 'privacy'), ['title' => 'Политика', 'content' => self::TEXT]);
        $this->get(route('privacy'))->assertOk();

        $this->patch(route('admin.page.update', 'privacy'), ['title' => 'Политика', 'content' => ''])->assertSessionHas('success');

        $this->get(route('privacy'))->assertNotFound();
        $this->assertSame(1, Page::count());
    }

    public function test_title_is_required(): void
    {
        $this->actingAsAdmin();

        $this->patch(route('admin.page.update', 'privacy'), ['title' => '', 'content' => self::TEXT])
            ->assertSessionHasErrors(['title' => 'Заполните поле «Заголовок».']);

        $this->assertSame(0, Page::count());
    }

    public function test_consent_page_is_edited_separately_and_linked_from_forms(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.page.edit', 'consent'))->assertOk()->assertSee('Согласие на обработку персональных данных');
        $this->get('/admin/pages/unknown')->assertNotFound();

        $this->patch(route('admin.page.update', 'consent'), [
            'title' => 'Согласие на обработку персональных данных',
            'content' => "1. Оператор\nТретьяков В. И.",
        ])->assertRedirect(route('admin.page.edit', 'consent'));

        $this->get(route('consent'))->assertOk()->assertSee('<h2>1. Оператор</h2>', false);
        $this->get(route('privacy'))->assertNotFound();

        $home = $this->get(route('home'))->getContent();
        $this->assertSame(2, substr_count($home, 'href="' . route('consent') . '"'));
        $this->assertSame(2, substr_count($home, 'name="consent"'));

        $this->get(route('sitemap'))->assertSee(route('consent'))->assertDontSee(route('privacy'));
    }

    public function test_form_requires_consent_checkbox(): void
    {
        $payload = [
            'name' => 'Иван',
            'tel' => '+7 (928) 000-00-00',
            'mail' => 'ivan@example.com',
            'content' => '',
            'form_started_at' => time() - 10,
        ];

        $this->post(route('message.store'), $payload)
            ->assertSessionHasErrors(['consent' => 'Подтвердите согласие на обработку персональных данных.']);
        $this->assertDatabaseCount('messages', 0);

        $this->post(route('message.store'), $payload + ['consent' => '1'])->assertSessionHasNoErrors();
        $this->assertDatabaseCount('messages', 1);
    }
}
