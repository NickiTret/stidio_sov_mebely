<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreRequest;
use App\Models\Message;
use App\Support\LeadNotifier;
use App\Support\SiteCache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        // Форма выключена в «Настройках сайта» — заявки с сайта не принимаем (в т.ч. от ботов).
        abort_unless(SiteCache::mainset()->isContactFormEnabled(), 404);

        $this->ensureRequestIsNotRateLimited($request->ip());
        $redirectTo = $this->resolveRedirectUrl($request->input('redirect_to'), $request->getHost());

        $data = $request->validated();
        $data['content'] = $data['content'] ?? '';
        unset($data['website'], $data['form_started_at'], $data['redirect_to'], $data['consent']);
        $message = Message::create($data);

        // Письмо о заявке (получатели и SMTP — «Уведомления о заявках» в админке).
        // Telegram больше не используется: это была трансграничная передача ПД.
        LeadNotifier::notify($message);

        return redirect($redirectTo)->with('success', 'Заявка отправлена. Мы свяжемся с вами в ближайшее время.');
    }

    private function ensureRequestIsNotRateLimited(?string $ip): void
    {
        $key = 'contact-form:' . ($ip ?: 'unknown');
        $maxAttempts = 5;
        $decaySeconds = 600;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'tel' => 'Слишком много заявок с этого адреса. Попробуйте еще раз через ' . ceil($seconds / 60) . ' мин.',
            ]);
        }

        RateLimiter::hit($key, $decaySeconds);
    }

    /**
     * Куда вернуть посетителя после отправки формы. Разрешены только страницы этого сайта:
     * абсолютный адрес с нашим хостом или путь от корня. Результат всегда относительный
     * («/path?query#fragment»), поэтому увести на чужой домен через redirect_to нельзя.
     */
    private function resolveRedirectUrl(?string $redirectTo, string $currentHost): string
    {
        // Referer тоже проверяем: url()->previous() берёт его из заголовка как есть.
        foreach ([$redirectTo, url()->previous()] as $candidate) {
            $path = $this->toLocalPath($candidate, $currentHost);

            if ($path !== null) {
                return $path;
            }
        }

        return route('home', [], false);
    }

    private function toLocalPath(?string $url, string $currentHost): ?string
    {
        if (!is_string($url) || $url === '' || preg_match('/[\x00-\x1F\x7F\\\\]/', $url)) {
            return null;
        }

        $parts = parse_url($url);

        if ($parts === false) {
            return null;
        }

        if (isset($parts['host']) || isset($parts['scheme'])) {
            $scheme = strtolower($parts['scheme'] ?? '');
            $host = strtolower($parts['host'] ?? '');
            $allowedHosts = array_filter([
                strtolower($currentHost),
                strtolower((string) parse_url((string) config('app.url'), PHP_URL_HOST)),
            ]);

            if (!in_array($scheme, ['http', 'https'], true)
                || !in_array($host, $allowedHosts, true)
                || isset($parts['user'])
                || isset($parts['pass'])) {
                return null;
            }
        }

        $path = $parts['path'] ?? '/';

        // «//evil.com» и «/\evil.com» браузер трактует как другой домен.
        if (!str_starts_with($path, '/') || str_starts_with($path, '//')) {
            return null;
        }

        return $path
            . (isset($parts['query']) ? '?' . $parts['query'] : '')
            . (isset($parts['fragment']) ? '#' . $parts['fragment'] : '');
    }
}
