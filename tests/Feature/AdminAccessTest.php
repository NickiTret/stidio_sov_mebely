<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\MainSet;
use App\Models\Message;
use App\Models\Post;
use App\Models\Seting;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Перебирает все маршруты админки из роутера, а не из ручного списка:
 * новый маршрут автоматически попадёт под проверку.
 */
class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /** Значения для параметров маршрутов ({group}, {post}, ...). */
    private array $params = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Превью главной (как и публичные страницы) рассчитывает на заполненные настройки сайта.
        MainSet::create([
            'tel' => '+79280000000',
            'email' => 'info@example.com',
            'telegram' => 'https://t.me/test',
            'watsap' => 'https://wa.me/79280000000',
            'map' => 'Нальчик',
            'footer' => 'KBR Mebel',
        ]);

        $group = Group::create(['title' => 'Кухни']);
        $this->params = [
            'group' => $group->id,
            'slider' => Slider::create(['title' => 't', 'description' => 'd', 'image_src' => 'images/s.jpg', 'group_id' => $group->id])->id,
            'post' => Post::create(['title' => 't', 'description' => 'd', 'image_src' => 'images/p.jpg'])->id,
            'seting' => Seting::create(['page' => 'Главная', 'title' => 't', 'descriptions' => 'd', 'keywords' => 'k', 'favicon' => 'images/f.png'])->id,
            'message' => Message::create(['name' => 'n', 'tel' => '+79280000000', 'mail' => 'm@example.com', 'content' => ''])->id,
            'pageSlug' => 'privacy',
        ];
    }

    private function makeUser(bool $admin): User
    {
        $user = new User(['name' => 'U', 'email' => ($admin ? 'admin' : 'user') . '@test.local']);
        $user->password = 'x';
        $user->is_admin = $admin ? 1 : 0;
        $user->save();

        return $user;
    }

    /** @return array<int, array{0: string, 1: string}> [метод, url] */
    private function adminRoutes(?string $onlyMethod = null): array
    {
        $result = [];

        foreach (Route::getRoutes() as $route) {
            /** @var RoutingRoute $route */
            if (!in_array('admin', $route->gatherMiddleware(), true)) {
                continue;
            }

            $method = collect($route->methods())->reject(fn ($m) => $m === 'HEAD')->first();
            if ($onlyMethod !== null && $method !== $onlyMethod) {
                continue;
            }

            $uri = preg_replace_callback('/\{(\w+)\??\}/', function ($m) {
                $this->assertArrayHasKey($m[1], $this->params, "Нет тестового значения для параметра {{$m[1]}}");

                return $this->params[$m[1]];
            }, $route->uri());

            $result[] = [$method, '/' . ltrim($uri, '/')];
        }

        return $result;
    }

    public function test_admin_section_routes_are_all_protected(): void
    {
        foreach (Route::getRoutes() as $route) {
            if (preg_match('#^(admin|message)(/|$)#', $route->uri())) {
                $this->assertContains('admin', $route->gatherMiddleware(), "Маршрут {$route->uri()} без middleware admin");
            }
        }

        $this->assertGreaterThan(30, count($this->adminRoutes()), 'Подозрительно мало маршрутов админки');
    }

    public function test_guest_gets_404_everywhere(): void
    {
        foreach ($this->adminRoutes() as [$method, $url]) {
            $this->call($method, $url)->assertNotFound();
        }

        $this->assertDatabaseCount('groups', 1);
        $this->assertDatabaseCount('messages', 1);
    }

    public function test_non_admin_user_gets_404_everywhere(): void
    {
        $this->actingAs($this->makeUser(false));

        foreach ($this->adminRoutes() as [$method, $url]) {
            $this->call($method, $url)->assertNotFound();
        }

        $this->assertDatabaseCount('groups', 1);
        $this->assertDatabaseCount('messages', 1);
    }

    public function test_admin_can_open_every_admin_page(): void
    {
        $this->actingAs($this->makeUser(true));

        foreach ($this->adminRoutes('GET') as [, $url]) {
            $response = $this->get($url);
            $this->assertSame(200, $response->getStatusCode(), "GET $url вернул {$response->getStatusCode()}");
        }
    }
}
