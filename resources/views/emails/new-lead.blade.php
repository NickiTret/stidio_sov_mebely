<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новая заявка с сайта</title>
</head>
<body style="margin:0;padding:24px;background:#f7f3ea;font-family:Arial,Helvetica,sans-serif;color:#242424;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e6dfd2;">
        <tr>
            <td style="padding:24px 28px;">
                @if ($isTest)
                    <p style="margin:0 0 16px;padding:10px 14px;background:#fff6d6;border:1px solid #f0d98a;font-size:14px;">
                        Это тестовое письмо из админки сайта — настройки почты работают.
                    </p>
                @endif

                <h1 style="margin:0 0 20px;font-size:22px;">Новая заявка с сайта kbr-mebel.ru</h1>

                <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;font-size:15px;line-height:1.5;">
                    <tr>
                        <td style="padding:6px 0;width:110px;color:#777;">Имя</td>
                        <td style="padding:6px 0;"><strong>{{ $lead->name }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#777;">Телефон</td>
                        <td style="padding:6px 0;"><a href="tel:{{ preg_replace('/[^\d+]/', '', (string) $lead->tel) }}" style="color:#242424;">{{ $lead->tel }}</a></td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#777;">Email</td>
                        <td style="padding:6px 0;">{{ $lead->mail }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#777;vertical-align:top;">Сообщение</td>
                        <td style="padding:6px 0;white-space:pre-line;">{{ $lead->content !== '' ? $lead->content : '—' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;color:#777;">Получена</td>
                        <td style="padding:6px 0;">{{ optional($lead->created_at)->format('d.m.Y H:i') }}</td>
                    </tr>
                </table>

                <p style="margin:24px 0 0;font-size:13px;color:#777;">
                    Чтобы ответить клиенту, нажмите «Ответить» — письмо уйдёт на его адрес.
                    Все заявки: <a href="{{ route('message.index') }}" style="color:#777;">{{ route('message.index') }}</a>
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
