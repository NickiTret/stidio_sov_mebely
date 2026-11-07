<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Настройки писем о новых заявках — одна запись, страница «Уведомления о заявках» в админке.
 * Отправка — App\Support\LeadNotifier.
 */
class NotificationSetting extends Model
{
    public const ENCRYPTIONS = [
        'ssl' => 'SSL (обычно порт 465)',
        'tls' => 'TLS / STARTTLS (обычно порт 587)',
        '' => 'Без шифрования',
    ];

    public const MAX_RECIPIENTS = 5;

    protected $fillable = [
        'email_enabled', 'recipients', 'smtp_host', 'smtp_port', 'smtp_encryption',
        'smtp_username', 'smtp_password', 'from_address', 'from_name',
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'smtp_port' => 'integer',
        'smtp_password' => 'encrypted',
    ];

    /** Пароль никогда не попадает в массивы/JSON (в т.ч. в old() формы). */
    protected $hidden = ['smtp_password'];

    public static function current(): self
    {
        return static::first() ?? new static(['email_enabled' => true]);
    }

    /** @return string[] адреса получателей из строки «a@x.ru, b@y.ru» */
    public function recipientList(): array
    {
        return collect(preg_split('/[\s,;]+/', (string) $this->recipients))
            ->map(fn (string $email) => trim($email))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /** Свой SMTP задан в админке; иначе письма идут через почту из .env. */
    public function usesCustomSmtp(): bool
    {
        return trim((string) $this->smtp_host) !== '';
    }
}
