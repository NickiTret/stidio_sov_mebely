<?php

namespace App\Http\Requests\Notification;

use App\Http\Requests\AdminFormRequest;
use App\Models\NotificationSetting;
use Closure;
use Illuminate\Validation\Rule;

class UpdateRequest extends AdminFormRequest
{
    protected array $labels = [
        'email_enabled' => 'Отправлять письма о заявках',
        'recipients' => 'Кому отправлять',
        'smtp_host' => 'SMTP-сервер',
        'smtp_port' => 'Порт',
        'smtp_encryption' => 'Шифрование',
        'smtp_username' => 'Логин SMTP',
        'smtp_password' => 'Пароль SMTP',
        'from_address' => 'Адрес отправителя',
        'from_name' => 'Имя отправителя',
    ];

    protected function prepareForValidation(): void
    {
        $this->merge([
            // Снятый чекбокс в запрос не приходит — это «выключено».
            'email_enabled' => $this->boolean('email_enabled'),
            'recipients' => trim((string) $this->input('recipients')),
            'smtp_host' => trim((string) $this->input('smtp_host')),
            'smtp_username' => trim((string) $this->input('smtp_username')),
            'smtp_encryption' => (string) $this->input('smtp_encryption'),
        ]);
    }

    public function rules(): array
    {
        return [
            'email_enabled' => ['boolean'],
            'recipients' => [
                'nullable', 'string', 'max:1000', 'required_if:email_enabled,true',
                function (string $attribute, mixed $value, Closure $fail) {
                    $emails = (new NotificationSetting(['recipients' => $value]))->recipientList();

                    if (count($emails) > NotificationSetting::MAX_RECIPIENTS) {
                        $fail('Не больше ' . NotificationSetting::MAX_RECIPIENTS . ' адресов.');
                    }

                    foreach ($emails as $email) {
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $fail("Адрес «{$email}» указан неверно.");
                        }
                    }
                },
            ],
            'smtp_host' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z0-9.\-]+$/'],
            'smtp_port' => ['nullable', 'integer', 'between:1,65535'],
            'smtp_encryption' => [Rule::in(array_keys(NotificationSetting::ENCRYPTIONS))],
            'smtp_username' => ['nullable', 'string', 'max:255', 'required_with:smtp_host'],
            'smtp_password' => ['nullable', 'string', 'max:255'],
            'from_address' => ['nullable', 'email', 'max:255'],
            'from_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return parent::messages() + [
            'recipients.required_if' => 'Укажите хотя бы один адрес, куда присылать заявки, или выключите отправку писем.',
            'smtp_host.regex' => 'SMTP-сервер: только имя сервера, например smtp.yandex.ru.',
            'smtp_port.integer' => 'Порт — число, например 465.',
            'smtp_port.between' => 'Порт — число от 1 до 65535.',
            'smtp_username.required_with' => 'Укажите логин SMTP (обычно это адрес почты целиком).',
            'from_address.email' => 'Адрес отправителя указан неверно.',
            'in' => 'Выберите значение из списка.',
        ];
    }
}
