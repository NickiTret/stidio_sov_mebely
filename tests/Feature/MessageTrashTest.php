<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTrashTest extends TestCase
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

    private function makeMessages(int $count): void
    {
        for ($i = 1; $i <= $count; $i++) {
            Message::create(['name' => "Клиент $i", 'tel' => '+7928000000' . $i, 'mail' => "c$i@example.com", 'content' => '']);
        }
    }

    public function test_delete_all_requires_typed_confirmation(): void
    {
        $this->makeMessages(3);

        foreach (['', 'да', 'удали'] as $wrong) {
            $this->delete(route('message.delete-all'), ['confirmation' => $wrong])
                ->assertRedirect(route('message.index'))
                ->assertSessionHas('error');
        }

        $this->assertSame(3, Message::count());
    }

    public function test_delete_all_moves_to_trash_and_restore_all_brings_back(): void
    {
        $this->makeMessages(3);

        $this->delete(route('message.delete-all'), ['confirmation' => ' удалить '])
            ->assertSessionHas('success');

        $this->assertSame(0, Message::count());
        $this->assertSame(3, Message::onlyTrashed()->count());

        $this->patch(route('message.restore-all'))->assertSessionHas('success');

        $this->assertSame(3, Message::count());
    }

    public function test_single_message_can_be_restored(): void
    {
        $this->makeMessages(1);
        $message = Message::first();

        $this->delete(route('message.delete', $message));
        $this->assertSoftDeleted($message);

        $this->patch(route('message.restore', $message->id))->assertSessionHas('success');
        $this->assertNotSoftDeleted($message);
    }

    public function test_index_and_trash_tabs_show_right_records(): void
    {
        $this->makeMessages(2);
        Message::where('name', 'Клиент 2')->first()->delete();

        $this->get(route('message.index'))
            ->assertOk()
            ->assertSee('Клиент 1')
            ->assertDontSee('Клиент 2')
            ->assertSee('Удалить все заявки');

        $this->get(route('message.index', ['trash' => 1]))
            ->assertOk()
            ->assertSee('Клиент 2')
            ->assertDontSee('Клиент 1')
            ->assertSee('Восстановить все');
    }
}
