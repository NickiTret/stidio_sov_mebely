<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\UpdateRequest;
use App\Models\NotificationSetting;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request)
    {
        $data = $request->validated();

        // Пароль в форму не выводится: пустое поле — оставить сохранённый.
        if (blank($data['smtp_password'] ?? null)) {
            unset($data['smtp_password']);
        }

        if ($request->boolean('smtp_password_clear')) {
            $data['smtp_password'] = null;
        }

        $settings = NotificationSetting::current();
        $settings->fill($data)->save();

        return redirect()
            ->route('notification.edit')
            ->with('success', 'Настройки уведомлений сохранены. Проверьте их кнопкой «Отправить тестовое письмо».');
    }
}
