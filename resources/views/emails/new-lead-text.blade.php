{{-- Текстовая версия письма: без HTML, поэтому вывод без экранирования. --}}
@if ($isTest)
Это тестовое письмо из админки сайта — настройки почты работают.

@endif
Новая заявка с сайта kbr-mebel.ru

Имя: {!! $lead->name !!}
Телефон: {!! $lead->tel !!}
Email: {!! $lead->mail !!}
Сообщение: {!! $lead->content !== '' ? $lead->content : '—' !!}
Получена: {!! optional($lead->created_at)->format('d.m.Y H:i') !!}

Все заявки: {!! route('message.index') !!}
