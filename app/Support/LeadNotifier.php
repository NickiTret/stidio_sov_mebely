<?php

namespace App\Support;

use App\Mail\NewLeadMail;
use App\Models\Message;
use App\Models\NotificationSetting;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Письма о новых заявках. Настройки — NotificationSetting («Уведомления о заявках» в админке):
 * получатели, вкл/выкл и, при желании, свой SMTP. Без своего SMTP — почта из .env (MAIL_*).
 */
class LeadNotifier
{
    /** Имя динамического мейлера, собранного из настроек админки. */
    public const MAILER = 'admin_smtp';

    /** Уведомить о заявке. Ошибка почты не должна ломать отправку формы — только пишем в лог. */
    public static function notify(Message $message): void
    {
        $settings = NotificationSetting::current();

        if (!$settings->email_enabled || $settings->recipientList() === []) {
            return;
        }

        try {
            self::send($settings, new NewLeadMail($message));
        } catch (\Throwable $exception) {
            Log::warning('Не удалось отправить письмо о заявке', [
                'message_id' => $message->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /** Тестовое письмо из админки. Исключение пробрасывается — админ видит текст ошибки SMTP. */
    public static function sendTest(NotificationSetting $settings): void
    {
        $message = new Message([
            'name' => 'Тестовая заявка',
            'tel' => '+7 (900) 000-00-00',
            'mail' => 'test@example.com',
            'content' => 'Это тестовое письмо из админки сайта. Если вы его читаете — уведомления о заявках работают.',
        ]);
        $message->created_at = now();

        self::send($settings, new NewLeadMail($message, isTest: true));
    }

    private static function send(NotificationSetting $settings, Mailable $mail): void
    {
        [$fromAddress, $fromName] = self::from($settings);

        if ($fromAddress) {
            $mail->from($fromAddress, $fromName);
        }

        self::mailer($settings)->to($settings->recipientList())->send($mail);
    }

    private static function mailer(NotificationSetting $settings): Mailer
    {
        if (!$settings->usesCustomSmtp()) {
            return Mail::mailer();
        }

        config(['mail.mailers.' . self::MAILER => [
            'transport' => 'smtp',
            // smtps — SSL с самого начала (465); smtp — STARTTLS, если сервер его предлагает (587).
            'scheme' => $settings->smtp_encryption === 'ssl' ? 'smtps' : 'smtp',
            'host' => $settings->smtp_host,
            'port' => $settings->smtp_port ?: ($settings->smtp_encryption === 'ssl' ? 465 : 587),
            'username' => $settings->smtp_username,
            'password' => $settings->smtp_password,
            'timeout' => 10,
        ]]);

        // Мейлер кешируется внутри MailManager — сбрасываем, чтобы взять свежие настройки.
        Mail::purge(self::MAILER);

        return Mail::mailer(self::MAILER);
    }

    /** @return array{0: ?string, 1: ?string} */
    private static function from(NotificationSetting $settings): array
    {
        $name = $settings->from_name ?: 'Сайт kbr-mebel.ru';

        if ($settings->from_address) {
            return [$settings->from_address, $name];
        }

        // Яндекс, Mail.ru и т.п. принимают письма только от адреса, под которым вошли в SMTP.
        if ($settings->usesCustomSmtp() && filter_var($settings->smtp_username, FILTER_VALIDATE_EMAIL)) {
            return [$settings->smtp_username, $name];
        }

        return [null, null]; // из .env (MAIL_FROM_ADDRESS)
    }
}
