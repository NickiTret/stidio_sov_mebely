<?php

namespace Tests\Feature;

use App\Mail\NewLeadMail;
use App\Models\Message;
use App\Models\NotificationSetting;
use App\Models\User;
use App\Support\LeadNotifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

/** Письма о заявках вместо Telegram и их настройки в админке (п.25 в CLAUDE.md). */
class LeadNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $admin = new User(['name' => 'Admin', 'email' => 'admin@test.local']);
        $admin->password = 'x';
        $admin->is_admin = 1;
        $admin->save();

        return $admin;
    }

    private function submitLead()
    {
        RateLimiter::clear('contact-form:127.0.0.1');

        return $this->post(route('message.store'), [
            'name' => 'Иван',
            'tel' => '+7 (928) 000-00-00',
            'mail' => 'ivan@example.com',
            'content' => 'Нужна кухня 3 метра',
            'form_started_at' => time() - 10,
            'consent' => '1',
        ]);
    }

    public function test_lead_is_emailed_to_recipients_with_reply_to_client(): void
    {
        Mail::fake();
        NotificationSetting::create(['email_enabled' => true, 'recipients' => 'owner@example.com, manager@example.com']);

        $this->submitLead()->assertSessionHasNoErrors();

        Mail::assertSent(NewLeadMail::class, function (NewLeadMail $mail) {
            return $mail->hasTo('owner@example.com')
                && $mail->hasTo('manager@example.com')
                && $mail->hasReplyTo('ivan@example.com')
                && $mail->lead->name === 'Иван';
        });
    }

    public function test_no_email_when_disabled_or_no_recipients(): void
    {
        Mail::fake();

        $this->submitLead(); // настроек нет вовсе
        $settings = NotificationSetting::create(['email_enabled' => false, 'recipients' => 'owner@example.com']);
        $this->submitLead();

        Mail::assertNothingSent();
        $this->assertDatabaseCount('messages', 2);
    }

    public function test_mail_failure_does_not_break_form(): void
    {
        // Несуществующий SMTP: отправка падает, но заявка сохраняется и посетитель видит «отправлено».
        NotificationSetting::create([
            'email_enabled' => true,
            'recipients' => 'owner@example.com',
            'smtp_host' => '127.0.0.1',
            'smtp_port' => 1,
            'smtp_encryption' => '',
            'smtp_username' => 'user@example.com',
            'smtp_password' => 'secret',
        ]);

        $this->submitLead()->assertSessionHasNoErrors()->assertSessionHas('success');
        $this->assertDatabaseCount('messages', 1);
    }

    public function test_admin_saves_settings_password_is_encrypted_and_kept(): void
    {
        $this->actingAs($this->admin());

        $this->get(route('notification.edit'))->assertOk()->assertSee('Уведомления о заявках');

        $form = [
            'email_enabled' => '1',
            'recipients' => 'owner@example.com',
            'smtp_host' => 'smtp.yandex.ru',
            'smtp_port' => '465',
            'smtp_encryption' => 'ssl',
            'smtp_username' => 'owner@yandex.ru',
            'smtp_password' => 'app-password-123',
        ];

        $this->patch(route('notification.update'), $form)->assertSessionHasNoErrors()->assertRedirect(route('notification.edit'));

        $raw = DB::table('notification_settings')->value('smtp_password');
        $this->assertNotSame('app-password-123', $raw);
        $this->assertSame('app-password-123', NotificationSetting::first()->smtp_password);

        // Пароль не выводится в форму; пустое поле — сохранённый не меняется.
        $this->get(route('notification.edit'))->assertDontSee('app-password-123');
        $this->patch(route('notification.update'), ['smtp_password' => ''] + $form)->assertSessionHasNoErrors();
        $this->assertSame('app-password-123', NotificationSetting::first()->smtp_password);

        // Явное удаление пароля.
        $this->patch(route('notification.update'), ['smtp_password' => '', 'smtp_password_clear' => '1'] + $form);
        $this->assertNull(NotificationSetting::first()->smtp_password);
    }

    public function test_validation(): void
    {
        $this->actingAs($this->admin());

        $this->patch(route('notification.update'), ['email_enabled' => '1', 'recipients' => ''])
            ->assertSessionHasErrors('recipients');

        $this->patch(route('notification.update'), ['email_enabled' => '1', 'recipients' => 'owner@example.com, not-an-email'])
            ->assertSessionHasErrors(['recipients' => 'Адрес «not-an-email» указан неверно.']);

        $this->patch(route('notification.update'), ['email_enabled' => '1', 'recipients' => 'a@x.ru', 'smtp_host' => 'smtp.yandex.ru'])
            ->assertSessionHasErrors('smtp_username');

        $this->patch(route('notification.update'), ['recipients' => ''])->assertSessionHasNoErrors(); // выключено — адрес не обязателен
        $this->assertFalse(NotificationSetting::first()->email_enabled);
    }

    public function test_send_test_email_from_admin(): void
    {
        Mail::fake();
        $this->actingAs($this->admin());

        $this->post(route('notification.test'))->assertSessionHas('error'); // адресов нет

        NotificationSetting::create(['email_enabled' => true, 'recipients' => 'owner@example.com']);
        $this->post(route('notification.test'))->assertSessionHas('success');

        Mail::assertSent(NewLeadMail::class, fn (NewLeadMail $mail) => $mail->isTest && $mail->hasTo('owner@example.com'));
    }

    public function test_send_test_shows_smtp_error(): void
    {
        $this->actingAs($this->admin());
        NotificationSetting::create([
            'recipients' => 'owner@example.com',
            'smtp_host' => '127.0.0.1',
            'smtp_port' => 1,
            'smtp_encryption' => '',
            'smtp_username' => 'user@example.com',
        ]);

        $this->post(route('notification.test'))->assertSessionHas('error', fn (string $text) => str_starts_with($text, 'Письмо не отправлено.'));
    }

    public function test_email_renders(): void
    {
        $lead = new Message(['name' => 'Иван <b>', 'tel' => '+7 (928) 000-00-00', 'mail' => 'ivan@example.com', 'content' => "Кухня\nи шкаф"]);
        $lead->created_at = now();

        $html = (new NewLeadMail($lead))->render();

        $this->assertStringContainsString('Иван &lt;b&gt;', $html);
        $this->assertStringContainsString('tel:+79280000000', $html);
    }
}
