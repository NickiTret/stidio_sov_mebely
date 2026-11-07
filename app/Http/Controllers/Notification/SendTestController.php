<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use App\Support\LeadNotifier;
use Illuminate\Support\Str;

class SendTestController extends Controller
{
    public function __invoke()
    {
        $settings = NotificationSetting::current();
        $recipients = $settings->recipientList();

        if ($recipients === []) {
            return redirect()->route('notification.edit')
                ->with('error', 'Сначала укажите и сохраните адрес, куда присылать заявки.');
        }

        try {
            LeadNotifier::sendTest($settings);
        } catch (\Throwable $exception) {
            return redirect()->route('notification.edit')
                ->with('error', 'Письмо не отправлено. Ошибка почтового сервера: ' . Str::limit($exception->getMessage(), 400));
        }

        return redirect()->route('notification.edit')
            ->with('success', 'Тестовое письмо отправлено на ' . implode(', ', $recipients) . '. Если его нет во «Входящих» — проверьте «Спам».');
    }
}
