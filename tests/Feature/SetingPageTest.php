<?php

namespace Tests\Feature;

use App\Models\Seting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SetingPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $admin = new User(['name' => 'Admin', 'email' => 'admin@test.local']);
        $admin->password = 'x';
        $admin->is_admin = 1;
        $admin->save();
        $this->actingAs($admin);
    }

    private function payload(array $override = []): array
    {
        return $override + [
            'page' => 'Галерея',
            'title' => 'SEO',
            'descriptions' => 'd',
            'keywords' => 'k',
            'favicon' => UploadedFile::fake()->image('f.png'),
        ];
    }

    public function test_free_text_page_is_rejected(): void
    {
        $this->post(route('seting.store'), $this->payload(['page' => '/gallery/kuhni']))
            ->assertSessionHasErrors(['page' => 'Выберите страницу из списка.']);

        $this->assertDatabaseCount('setings', 0);
    }

    public function test_page_from_list_is_saved_and_used_on_site(): void
    {
        $this->post(route('seting.store'), $this->payload())->assertSessionHasNoErrors();

        $this->assertTrue(Seting::first()->isUsedOnSite());
    }

    public function test_second_record_for_same_page_is_rejected(): void
    {
        $this->post(route('seting.store'), $this->payload())->assertSessionHasNoErrors();
        $this->post(route('seting.store'), $this->payload())->assertSessionHasErrors('page');

        $this->assertDatabaseCount('setings', 1);
    }

    public function test_editing_keeps_own_page_and_fixes_legacy_value(): void
    {
        $legacy = Seting::create(['page' => '/gallery', 'title' => 't', 'descriptions' => 'd', 'keywords' => 'k', 'favicon' => 'images/f.png']);

        $this->get(route('seting.index'))->assertSee('не используется на сайте');
        $this->get(route('seting.edit', $legacy))->assertOk()->assertSee('Сейчас эти настройки на сайте не применяются', false);

        // Старое значение сохранить нельзя — нужно выбрать страницу.
        $this->patch(route('seting.update', $legacy), $this->payload(['page' => '/gallery', 'favicon' => null]))
            ->assertSessionHasErrors('page');

        $this->patch(route('seting.update', $legacy), $this->payload(['favicon' => null]))
            ->assertSessionHasNoErrors();
        $this->assertSame('Галерея', $legacy->refresh()->page);

        // Повторное сохранение своей же страницы не считается дублем.
        $this->patch(route('seting.update', $legacy), $this->payload(['title' => 'Новый', 'favicon' => null]))
            ->assertSessionHasNoErrors();
    }
}
