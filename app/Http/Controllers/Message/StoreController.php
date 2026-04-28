<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreRequest;
use App\Models\Message;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $this->ensureRequestIsNotRateLimited($request->ip());
        $redirectTo = $this->resolveRedirectUrl($request->input('redirect_to'));

        $data = $request->validated();
        $data['content'] = $data['content'] ?? '';
        unset($data['website'], $data['form_started_at'], $data['redirect_to']);
        Message::create($data);
        $this->sendTelegramNotification($data);

        return redirect($redirectTo)->with('success', 'Заявка отправлена. Мы свяжемся с вами в ближайшее время.');
    }

    private function sendTelegramNotification(array $data): void
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (empty($token) || empty($chatId)) {
            return;
        }

        $text = implode("\n", [
            'Новая заявка с сайта kbr-mebel.ru',
            'Имя: ' . $data['name'],
            'Телефон: ' . $data['tel'],
            'Email: ' . $data['mail'],
            'Сообщение: ' . $data['content'],
        ]);

        try {
            Http::timeout(10)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
            ]);
        } catch (\Throwable $exception) {
            Log::warning('Telegram notification failed', [
                'message' => $exception->getMessage(),
            ]);
        }
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

    private function resolveRedirectUrl(?string $redirectTo): string
    {
        if (is_string($redirectTo) && $redirectTo !== '') {
            $appUrl = rtrim(config('app.url'), '/');

            if (str_starts_with($redirectTo, $appUrl) || str_starts_with($redirectTo, '/')) {
                return $redirectTo;
            }
        }

        return url()->previous() ?: route('home');
    }
}
