<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;

class EditController extends Controller
{
    public function __invoke()
    {
        $settings = NotificationSetting::current();

        return view('notification.edit', [
            'settings' => $settings,
            'hasPassword' => filled($settings->smtp_password),
            'envMailer' => config('mail.default') . ' (' . (config('mail.mailers.' . config('mail.default') . '.host') ?: '—') . ')',
        ]);
    }
}
