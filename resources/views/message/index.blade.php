@extends('layouts.main')
@section('content')
    @php
        $baseQuery = request()->except(['sort', 'direction', 'page']);
        $nextDirection = function ($column) use ($sort, $direction) {
            if ($sort === $column && $direction === 'asc') {
                return 'desc';
            }

            return 'asc';
        };
        $sortIcon = function ($column) use ($sort, $direction) {
            if ($sort !== $column) {
                return 'fa-sort text-muted';
            }

            return $direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down';
        };
    @endphp

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Заявки</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Админка</a></li>
                        <li class="breadcrumb-item active">Заявки</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['total'] }}</h3>
                            <p>Всего заявок</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['today'] }}</h3>
                            <p>За сегодня</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-12">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['with_content'] }}</h3>
                            <p>С заполненным сообщением</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form action="{{ route('message.index') }}" method="get">
                                <div class="row">
                                    <div class="col-lg-5 col-md-12 mb-3 mb-lg-0">
                                        <label for="search" class="mb-1">Поиск по заявкам</label>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                id="search"
                                                name="search"
                                                class="form-control"
                                                value="{{ $search }}"
                                                placeholder="Имя, телефон, почта или текст заявки"
                                            >
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fas fa-search mr-1"></i> Найти
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3 mb-lg-0">
                                        <label for="per_page" class="mb-1">Показывать</label>
                                        <select id="per_page" name="per_page" class="form-control" onchange="this.form.submit()">
                                            @foreach ([10, 15, 25, 50] as $option)
                                                <option value="{{ $option }}" @selected($perPage === $option)>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="sort" value="{{ $sort }}">
                                    <input type="hidden" name="direction" value="{{ $direction }}">
                                    <div class="col-lg-5 col-md-8 col-sm-6 d-flex align-items-end justify-content-lg-end">
                                        <div class="btn-group mr-2">
                                            <a href="{{ route('message.index') }}" class="btn btn-default">
                                                <i class="fas fa-sync-alt mr-1"></i> Сбросить
                                            </a>
                                        </div>
                                        @if ($messages->total() > 0)
                                            <button
                                                class="btn btn-danger"
                                                type="submit"
                                                form="delete-all-messages-form"
                                            >
                                                <i class="fas fa-trash mr-1"></i> Удалить все заявки
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            @if ($messages->total() > 0)
                                <form
                                    id="delete-all-messages-form"
                                    action="{{ route('message.delete-all') }}"
                                    method="post"
                                    class="d-none"
                                    onsubmit="return confirm('Удалить все заявки? Это действие нельзя отменить.');"
                                >
                                    @csrf
                                    @method('delete')
                                </form>
                            @endif
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 90px;">
                                            <a href="{{ route('message.index', array_merge($baseQuery, ['sort' => 'id', 'direction' => $nextDirection('id')])) }}" class="text-dark">
                                                # <i class="fas {{ $sortIcon('id') }} ml-1"></i>
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('message.index', array_merge($baseQuery, ['sort' => 'name', 'direction' => $nextDirection('name')])) }}" class="text-dark">
                                                От кого <i class="fas {{ $sortIcon('name') }} ml-1"></i>
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('message.index', array_merge($baseQuery, ['sort' => 'tel', 'direction' => $nextDirection('tel')])) }}" class="text-dark">
                                                Телефон <i class="fas {{ $sortIcon('tel') }} ml-1"></i>
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('message.index', array_merge($baseQuery, ['sort' => 'mail', 'direction' => $nextDirection('mail')])) }}" class="text-dark">
                                                Почта <i class="fas {{ $sortIcon('mail') }} ml-1"></i>
                                            </a>
                                        </th>
                                        <th>Сообщение</th>
                                        <th style="width: 180px;">
                                            <a href="{{ route('message.index', array_merge($baseQuery, ['sort' => 'created_at', 'direction' => $nextDirection('created_at')])) }}" class="text-dark">
                                                Дата <i class="fas {{ $sortIcon('created_at') }} ml-1"></i>
                                            </a>
                                        </th>
                                        <th class="text-right" style="width: 130px;">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($messages as $message)
                                        <tr>
                                            <td>
                                                <span class="badge badge-light">#{{ $message->id }}</span>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold">{{ $message->name }}</div>
                                            </td>
                                            <td>
                                                <a href="tel:{{ $message->tel }}">{{ $message->tel }}</a>
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $message->mail }}">{{ $message->mail }}</a>
                                            </td>
                                            <td>
                                                @if (filled($message->content))
                                                    <span title="{{ $message->content }}">
                                                        {{ \Illuminate\Support\Str::limit($message->content, 120) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Без сообщения</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ optional($message->created_at)->format('d.m.Y') }}</div>
                                                <small class="text-muted">{{ optional($message->created_at)->format('H:i') }}</small>
                                            </td>
                                            <td class="text-right">
                                                <form
                                                    action="{{ route('message.delete', ['message' => $message->id] + request()->query()) }}"
                                                    method="post"
                                                    onsubmit="return confirm('Удалить заявку от {{ $message->name }}?');"
                                                >
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                                        <i class="fas fa-trash-alt mr-1"></i> Удалить
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="text-muted mb-2">
                                                    <i class="fas fa-search fa-2x"></i>
                                                </div>
                                                <div class="font-weight-bold">Заявки не найдены</div>
                                                <div class="text-muted">Попробуй изменить поиск или сбросить фильтры.</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                        @if ($messages->hasPages())
                            <div class="card-footer clearfix">
                                <div class="float-right">
                                    {{ $messages->onEachSide(1)->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
