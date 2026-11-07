@extends('layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Уведомления о заявках</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Админка</a></li>
                        <li class="breadcrumb-item active">Уведомления о заявках</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if (!$settings->email_enabled)
                        <div class="callout callout-warning">
                            Письма о заявках выключены. Заявки по-прежнему сохраняются в разделе «Заявки».
                        </div>
                    @elseif ($settings->recipientList() === [])
                        <div class="callout callout-warning">
                            Не указано, куда присылать заявки, — письма не отправляются. Заявки сохраняются в разделе «Заявки».
                        </div>
                    @else
                        <div class="callout callout-info">
                            Письма о новых заявках приходят на: <strong>{{ implode(', ', $settings->recipientList()) }}</strong>.
                            Отправка через
                            @if ($settings->usesCustomSmtp())
                                SMTP <strong>{{ $settings->smtp_host }}</strong>.
                            @else
                                почту из настроек сервера (<code>.env</code>: {{ $envMailer }}).
                            @endif
                        </div>
                    @endif

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Куда присылать заявки</h3>
                        </div>
                        <form action="{{ route('notification.update') }}" method="post" autocomplete="off">
                            @csrf
                            @method('PATCH')
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="email_enabled" name="email_enabled" value="1"
                                            @checked(old('email_enabled', $settings->email_enabled ?? true))>
                                        <label class="custom-control-label" for="email_enabled">Отправлять письмо о каждой новой заявке</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="recipients">Кому отправлять</label>
                                    <input type="text" class="form-control" id="recipients" name="recipients" maxlength="1000"
                                        value="{{ old('recipients', $settings->recipients) }}" placeholder="vad.tretiakoff@yandex.ru">
                                    <small class="form-text text-muted">
                                        Один или несколько адресов через запятую (до {{ \App\Models\NotificationSetting::MAX_RECIPIENTS }}).
                                        В письме есть имя, телефон, email и текст заявки; «Ответить» в почте — сразу клиенту.
                                    </small>
                                </div>

                                <hr>
                                <h5 class="mb-1">Почтовый ящик для отправки (SMTP)</h5>
                                <p class="text-muted mb-3">
                                    Необязательно. Если оставить «SMTP-сервер» пустым, письма уходят через почту, настроенную на сервере (<code>.env</code>).
                                    Надёжнее отправлять через свой ящик — тогда письма реже попадают в «Спам».
                                </p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtp_host">SMTP-сервер</label>
                                            <input type="text" class="form-control" id="smtp_host" name="smtp_host" maxlength="255"
                                                value="{{ old('smtp_host', $settings->smtp_host) }}" placeholder="smtp.yandex.ru">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="smtp_port">Порт</label>
                                            <input type="number" class="form-control" id="smtp_port" name="smtp_port" min="1" max="65535"
                                                value="{{ old('smtp_port', $settings->smtp_port) }}" placeholder="465">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="smtp_encryption">Шифрование</label>
                                            <select class="form-control" id="smtp_encryption" name="smtp_encryption">
                                                @foreach (\App\Models\NotificationSetting::ENCRYPTIONS as $value => $label)
                                                    <option value="{{ $value }}" @selected((string) old('smtp_encryption', $settings->smtp_encryption ?? 'ssl') === (string) $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtp_username">Логин SMTP</label>
                                            <input type="text" class="form-control" id="smtp_username" name="smtp_username" maxlength="255"
                                                value="{{ old('smtp_username', $settings->smtp_username) }}" placeholder="vad.tretiakoff@yandex.ru" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="smtp_password">Пароль SMTP</label>
                                            <input type="password" class="form-control" id="smtp_password" name="smtp_password" maxlength="255"
                                                placeholder="{{ $hasPassword ? 'Сохранён — оставьте пустым, чтобы не менять' : 'Пароль приложения' }}" autocomplete="new-password">
                                            @if ($hasPassword)
                                                <div class="custom-control custom-checkbox mt-1">
                                                    <input type="checkbox" class="custom-control-input" id="smtp_password_clear" name="smtp_password_clear" value="1">
                                                    <label class="custom-control-label font-weight-normal" for="smtp_password_clear">Удалить сохранённый пароль</label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="from_address">Адрес отправителя</label>
                                            <input type="email" class="form-control" id="from_address" name="from_address" maxlength="255"
                                                value="{{ old('from_address', $settings->from_address) }}" placeholder="по умолчанию — логин SMTP">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="from_name">Имя отправителя</label>
                                            <input type="text" class="form-control" id="from_name" name="from_name" maxlength="255"
                                                value="{{ old('from_name', $settings->from_name) }}" placeholder="Сайт kbr-mebel.ru">
                                        </div>
                                    </div>
                                </div>

                                <div class="callout callout-light mb-0">
                                    <strong>Яндекс Почта:</strong> сервер <code>smtp.yandex.ru</code>, порт <code>465</code>, SSL,
                                    логин — адрес почты целиком. Пароль — не от почты, а <em>пароль приложения</em>:
                                    Яндекс ID → Безопасность → Пароли приложений → «Почта». В настройках почты Яндекса
                                    (Все настройки → Почтовые программы) должен быть включён доступ по протоколу IMAP/SMTP.
                                    <br><strong>Mail.ru:</strong> <code>smtp.mail.ru</code>, порт <code>465</code>, SSL, тоже пароль для внешнего приложения.
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Сохранить
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="card">
                        <div class="card-body d-flex flex-wrap align-items-center">
                            <form action="{{ route('notification.test') }}" method="post" class="mr-3 mb-2">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary" @disabled($settings->recipientList() === [])>
                                    <i class="fas fa-paper-plane mr-1"></i> Отправить тестовое письмо
                                </button>
                            </form>
                            <span class="text-muted mb-2">Отправляется по <strong>сохранённым</strong> настройкам — сначала нажмите «Сохранить».</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
