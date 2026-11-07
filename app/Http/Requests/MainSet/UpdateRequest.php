<?php

namespace App\Http\Requests\MainSet;

use App\Http\Requests\AdminFormRequest;

class UpdateRequest extends AdminFormRequest
{
    protected array $labels = [
        'tel' => 'Телефон',
        'email' => 'Электронная почта',
        'telegram' => 'Telegram',
        'watsap' => 'WhatsApp',
        'map' => 'Метка на карте',
        'footer' => 'Подпись в футере',
        'contact_form_enabled' => 'Показывать форму заявки',
    ];

    /**
     * Поля Telegram и WhatsApp выводятся на сайте как ссылки, поэтому приводим их к URL:
     * «@username» / «username» → https://t.me/username, номер телефона → https://wa.me/79...
     */
    protected function prepareForValidation(): void
    {
        $telegram = trim((string) $this->input('telegram'));
        if (preg_match('/^@?([A-Za-z0-9_]{5,32})$/', $telegram, $m)) {
            $telegram = 'https://t.me/' . $m[1];
        }

        $watsap = trim((string) $this->input('watsap'));
        if (preg_match('/^[\d\s()+\-]{10,}$/', $watsap)) {
            $digits = preg_replace('/\D/', '', $watsap);
            if (strlen($digits) === 11 && $digits[0] === '8') {
                $digits = '7' . substr($digits, 1);
            }
            $watsap = 'https://wa.me/' . $digits;
        }

        $this->merge([
            'tel' => trim((string) $this->input('tel')),
            'email' => trim((string) $this->input('email')),
            'telegram' => self::addHttpsIfMissing($telegram),
            'watsap' => self::addHttpsIfMissing($watsap),
            // Чекбокс: снятая галочка не приходит в запросе вовсе — это «выключено».
            'contact_form_enabled' => $this->boolean('contact_form_enabled'),
        ]);
    }

    /** «t.me/…», «wa.me/…» без схемы → https://…; значения со схемой не трогаем. */
    private static function addHttpsIfMissing(string $value): string
    {
        $hasScheme = (bool) preg_match('#^[a-z][a-z0-9+.\-]*:#i', $value);
        $looksLikeDomainPath = (bool) preg_match('#^[\w.\-]+\.[a-z]{2,}/#i', $value);

        return !$hasScheme && $looksLikeDomainPath ? 'https://' . $value : $value;
    }

    public function rules(): array
    {
        $link = ['required', 'string', 'max:255', 'url', 'starts_with:https://,http://'];

        return [
            'tel' => ['required', 'string', 'max:30', 'regex:/^\+?[\d\s()\-]{6,}$/'],
            'email' => ['required', 'string', 'max:255', 'email'],
            'telegram' => $link,
            'watsap' => $link,
            'map' => ['required', 'string', 'max:255'],
            'footer' => ['required', 'string', 'max:255'],
            'contact_form_enabled' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return parent::messages() + [
            'tel.regex' => 'Телефон: только цифры, пробелы, скобки, дефис и «+» в начале.',
            'email.email' => 'Укажите корректный email.',
            'telegram.url' => 'Telegram: укажите ссылку https://t.me/… или username.',
            'telegram.starts_with' => 'Telegram: укажите ссылку https://t.me/… или username.',
            'watsap.url' => 'WhatsApp: укажите ссылку https://wa.me/… или номер телефона.',
            'watsap.starts_with' => 'WhatsApp: укажите ссылку https://wa.me/… или номер телефона.',
        ];
    }
}
