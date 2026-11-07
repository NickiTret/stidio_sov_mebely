<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = new User(['name' => 'Admin', 'email' => 'admin@test.local']);
        $admin->password = 'x';
        $admin->is_admin = 1;
        $admin->save();
        $this->actingAs($admin);
    }

    public function test_empty_group_is_deleted(): void
    {
        $group = Group::create(['title' => 'Кухни']);

        $this->delete(route('group.delete', $group))
            ->assertRedirect(route('group.index'))
            ->assertSessionHas('success');

        $this->assertModelMissing($group);
    }

    public function test_group_with_slides_is_not_deleted(): void
    {
        $group = Group::create(['title' => 'Кухни']);
        $slide = Slider::create(['title' => 'Фото', 'description' => 'd', 'image_src' => 'images/x.jpg', 'group_id' => $group->id]);

        $this->delete(route('group.delete', $group))
            ->assertRedirect(route('group.index'))
            ->assertSessionHas('error');

        $this->assertModelExists($group);
        $this->assertModelExists($slide);
    }

    public function test_index_shows_disabled_delete_for_non_empty_group(): void
    {
        $group = Group::create(['title' => 'Кухни']);
        Slider::create(['title' => 'Фото', 'description' => 'd', 'image_src' => 'images/x.jpg', 'group_id' => $group->id]);

        $this->get(route('group.index'))
            ->assertOk()
            ->assertSee('сначала перенесите или удалите их', false)
            ->assertDontSee('action="' . route('group.delete', $group) . '"', false);
    }
}
