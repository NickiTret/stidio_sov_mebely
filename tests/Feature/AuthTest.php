<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(bool $admin): User
    {
        $user = new User(['name' => 'Test', 'email' => $admin ? 'admin@test.local' : 'user@test.local']);
        $user->password = Hash::make('secret-password');
        $user->is_admin = $admin ? 1 : 0;
        $user->save();

        return $user;
    }

    public function test_registration_is_closed(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register', ['name' => 'x', 'email' => 'x@x.ru', 'password' => '1', 'password_confirmation' => '1'])
            ->assertNotFound();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_admin_can_login_and_logout(): void
    {
        $this->makeUser(true);

        $this->post('/login', ['email' => 'admin@test.local', 'password' => 'secret-password'])
            ->assertRedirect(route('admin'));
        $this->assertAuthenticated();

        $this->get('/logout')->assertStatus(405);
        $this->post('/logout')->assertRedirect(route('login.create'));
        $this->assertGuest();
    }

    public function test_non_admin_is_not_kept_logged_in(): void
    {
        $this->makeUser(false);

        $this->post('/login', ['email' => 'user@test.local', 'password' => 'secret-password'])
            ->assertRedirect(route('login.create'));
        $this->assertGuest();
        $this->get('/admin')->assertNotFound();
    }

    public function test_login_is_throttled_after_five_failures(): void
    {
        $this->makeUser(true);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => 'admin@test.local', 'password' => 'wrong']);
        }

        $this->post('/login', ['email' => 'admin@test.local', 'password' => 'secret-password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
