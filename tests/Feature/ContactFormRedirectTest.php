<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class ContactFormRedirectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake(); // не отправлять уведомления в настоящий Telegram
        RateLimiter::clear('contact-form:127.0.0.1');
    }

    private function submit(?string $redirectTo, array $headers = [])
    {
        RateLimiter::clear('contact-form:127.0.0.1');

        return $this->withHeaders($headers)->post(route('message.store'), [
            'name' => 'Иван',
            'tel' => '+79280000000',
            'mail' => 'ivan@example.com',
            'content' => 'Нужна кухня',
            'website' => '',
            'form_started_at' => time() - 10,
            'consent' => '1',
            'redirect_to' => $redirectTo,
        ]);
    }

    private function assertRedirectsToOwnSite($response, string $expectedPath): void
    {
        $response->assertSessionHasNoErrors()->assertStatus(302);
        $location = $response->headers->get('Location');

        $this->assertSame('localhost', parse_url($location, PHP_URL_HOST), "Редирект ушёл на чужой хост: $location");
        $this->assertSame($expectedPath, substr($location, strlen('http://localhost')) ?: '/');
    }

    public function test_own_page_url_is_kept_with_query_and_fragment(): void
    {
        $this->assertRedirectsToOwnSite(
            $this->submit('http://localhost/gallery/kuhni?page=2#contact-form'),
            '/gallery/kuhni?page=2#contact-form'
        );
        $this->assertDatabaseCount('messages', 1);
    }

    public function test_https_vs_http_mismatch_is_allowed_for_same_host(): void
    {
        $this->assertRedirectsToOwnSite($this->submit('https://localhost/contacts#contact-form'), '/contacts#contact-form');
    }

    public function test_relative_path_is_allowed(): void
    {
        $this->assertRedirectsToOwnSite($this->submit('/contacts'), '/contacts');
    }

    /** @dataProvider foreignTargets */
    public function test_foreign_targets_fall_back_to_home(string $target): void
    {
        $this->assertRedirectsToOwnSite($this->submit($target), '/');
    }

    public static function foreignTargets(): array
    {
        return [
            'protocol-relative' => ['//evil.com/path'],
            'backslash' => ['/\evil.com'],
            'other host' => ['https://evil.com/'],
            'prefix of our host' => ['http://localhost.evil.com/'],
            'userinfo trick' => ['http://localhost@evil.com/'],
            'javascript' => ['javascript:alert(1)'],
            'newline' => ["/ok\r\nLocation: https://evil.com"],
        ];
    }

    public function test_foreign_referer_is_not_used_as_fallback(): void
    {
        $this->assertRedirectsToOwnSite($this->submit(null, ['Referer' => 'https://evil.com/']), '/');
    }

    public function test_own_referer_is_used_as_fallback(): void
    {
        $this->assertRedirectsToOwnSite($this->submit(null, ['Referer' => 'http://localhost/contacts']), '/contacts');
    }
}
