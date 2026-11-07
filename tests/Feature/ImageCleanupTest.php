<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Seting;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageCleanupTest extends TestCase
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

    private function storedFile(string $name = 'old.jpg'): string
    {
        return UploadedFile::fake()->image($name)->store('images/2026-01-01', 'public');
    }

    public function test_replacing_slide_image_deletes_old_file(): void
    {
        $old = $this->storedFile();
        $slider = Slider::create(['title' => 't', 'description' => 'd', 'image_src' => $old]);

        $this->patch(route('slider.update', $slider), [
            'title' => 't',
            'description' => 'd',
            'image_src' => UploadedFile::fake()->image('new.jpg'),
        ])->assertRedirect(route('slider.index'));

        $slider->refresh();
        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($slider->image_src);
        $this->assertNotSame($old, $slider->image_src);
    }

    public function test_update_without_new_image_keeps_file(): void
    {
        $old = $this->storedFile();
        $post = Post::create(['title' => 't', 'description' => 'd', 'image_src' => $old]);

        $this->patch(route('post.update', $post), ['title' => 'new title', 'description' => 'd'])
            ->assertRedirect(route('post.index'));

        $this->assertSame($old, $post->refresh()->image_src);
        Storage::disk('public')->assertExists($old);
    }

    public function test_deleting_records_deletes_files(): void
    {
        $slideFile = $this->storedFile('s.jpg');
        $postFile = $this->storedFile('p.jpg');
        $setingFile = $this->storedFile('f.png');

        $slider = Slider::create(['title' => 't', 'description' => 'd', 'image_src' => $slideFile]);
        $post = Post::create(['title' => 't', 'description' => 'd', 'image_src' => $postFile]);
        $seting = Seting::create(['page' => 'p', 'title' => 't', 'descriptions' => 'd', 'keywords' => 'k', 'favicon' => $setingFile]);

        $this->delete(route('slider.delete', $slider));
        $this->delete(route('post.delete', $post));
        $this->delete(route('seting.delete', $seting));

        Storage::disk('public')->assertMissing([$slideFile, $postFile, $setingFile]);
    }

    public function test_paths_outside_images_dir_are_never_deleted(): void
    {
        Storage::disk('public')->put('keep.txt', 'x');
        $post = Post::create(['title' => 't', 'description' => 'd', 'image_src' => 'keep.txt']);

        $post->delete();

        Storage::disk('public')->assertExists('keep.txt');
    }

    public function test_prune_command_removes_only_orphans(): void
    {
        $used = $this->storedFile('used.jpg');
        $orphan = $this->storedFile('orphan.jpg');
        Slider::create(['title' => 't', 'description' => 'd', 'image_src' => $used]);

        $this->artisan('images:prune')->assertSuccessful();
        Storage::disk('public')->assertExists($orphan);

        $this->artisan('images:prune --force')->assertSuccessful();
        Storage::disk('public')->assertMissing($orphan);
        Storage::disk('public')->assertExists($used);
    }
}
