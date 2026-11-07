@extends('layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Политика конфиденциальности</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Админка</a></li>
                        <li class="breadcrumb-item active">Политика конфиденциальности</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if ($page->hasContent())
                        <div class="callout callout-success">
                            Страница опубликована: <a href="{{ route('privacy') }}" target="_blank" rel="noopener">{{ route('privacy') }}</a>.
                            Ссылка на неё стоит под формами заявки и в футере сайта.
                            @if ($page->updated_at)
                                <span class="text-muted">Последнее изменение: {{ $page->updated_at->format('d.m.Y H:i') }}</span>
                            @endif
                        </div>
                    @else
                        <div class="callout callout-warning">
                            Текст ещё не заполнен — страница <code>/privacy</code> не открывается, ссылок на неё на сайте нет.
                        </div>
                    @endif

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Текст политики обработки персональных данных</h3>
                        </div>
                        <form action="{{ route('admin.privacy.update') }}" method="post">
                            @csrf
                            @method('PATCH')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Заголовок страницы</label>
                                    <input type="text" class="form-control" id="title" name="title" required maxlength="255"
                                        value="{{ old('title', $page->title) }}">
                                </div>
                                <div class="form-group">
                                    <label for="content">Текст</label>
                                    <textarea class="form-control" id="content" name="content" rows="25"
                                        maxlength="{{ \App\Http\Requests\Page\UpdateRequest::CONTENT_MAX }}"
                                        placeholder="Вставьте сюда текст политики">{{ old('content', $page->content) }}</textarea>
                                    <small class="form-text text-muted">
                                        Вставляйте обычный текст (например, из Word) — оформление подставится само:
                                        каждая строка станет абзацем, а короткая строка без точки в конце
                                        (например «1. Общие положения») — заголовком раздела.
                                        HTML-теги выводятся как обычный текст. Если очистить поле, страница и ссылки на неё скроются.
                                    </small>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                @if ($page->hasContent())
                                    <a href="{{ route('privacy') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary ml-2">Открыть на сайте</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
