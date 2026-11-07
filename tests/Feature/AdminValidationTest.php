<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminValidationTest extends TestCase
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

    /** Раньше: 500 из-за NOT NULL image_src. Теперь — понятная ошибка формы. */
    public function test_slide_without_image_is_rejected_with_message(): void
    {
        $this->from(route('slider.create'))
            ->post(route('slider.store'), ['title' => 't', 'description' => 'd'])
            ->assertRedirect(route('slider.create'))
            ->assertSessionHasErrors(['image_src' => 'Заполните поле «Изображение».']);

        $this->assertDatabaseCount('sliders', 0);
    }

    public function test_post_and_seting_without_image_are_rejected(): void
    {
        $this->post(route('post.store'), ['title' => 't', 'description' => 'd'])
            ->assertSessionHasErrors('image_src');
        $this->post(route('seting.store'), ['page' => 'p', 'title' => 't', 'descriptions' => 'd', 'keywords' => 'k'])
            ->assertSessionHasErrors('favicon');
    }

    public function test_valid_slide_is_created(): void
    {
        $this->post(route('slider.store'), [
            'title' => 't',
            'description' => 'd',
            'image_src' => UploadedFile::fake()->image('a.webp'),
        ])->assertRedirect(route('slider.index'))->assertSessionHasNoErrors();

        $this->assertDatabaseCount('sliders', 1);
    }

    public function test_svg_and_non_images_are_rejected(): void
    {
        $svg = UploadedFile::fake()->createWithContent('x.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>');
        $pdf = UploadedFile::fake()->create('x.pdf', 10, 'application/pdf');

        foreach ([$svg, $pdf] as $file) {
            $this->post(route('slider.store'), ['title' => 't', 'description' => 'd', 'image_src' => $file])
                ->assertSessionHasErrors('image_src');
        }

        $this->assertDatabaseCount('sliders', 0);
    }

    public function test_too_large_image_is_rejected(): void
    {
        $big = UploadedFile::fake()->image('big.jpg')->size(6000);

        $this->post(route('slider.store'), ['title' => 't', 'description' => 'd', 'image_src' => $big])
            ->assertSessionHasErrors(['image_src' => 'Файл «Изображение» — не больше 5 МБ.']);
    }

    public function test_too_long_title_is_rejected(): void
    {
        $this->post(route('group.store'), ['title' => Str::repeat('а', 256)])
            ->assertSessionHasErrors(['title' => 'Поле «Название» — не больше 255 символов.']);
    }

    public function test_update_without_image_is_allowed(): void
    {
        $post = Post::create(['title' => 't', 'description' => 'd', 'image_src' => 'images/x.jpg']);

        $this->patch(route('post.update', $post), ['title' => 'new', 'description' => 'd'])
            ->assertSessionHasNoErrors();

        $this->assertSame('new', $post->refresh()->title);
    }
}
