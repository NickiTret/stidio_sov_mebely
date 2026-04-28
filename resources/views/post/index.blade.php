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
                    <h1 class="m-0">Статьи</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Админка</a></li>
                        <li class="breadcrumb-item active">Статьи</li>
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
                            <p>Всего статей</p>
                        </div>
                        <div class="icon"><i class="fas fa-newspaper"></i></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['with_image'] }}</h3>
                            <p>С изображением</p>
                        </div>
                        <div class="icon"><i class="fas fa-image"></i></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-12">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['with_description'] }}</h3>
                            <p>С описанием</p>
                        </div>
                        <div class="icon"><i class="fas fa-align-left"></i></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form action="{{ route('post.index') }}" method="get">
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 mb-3 mb-lg-0">
                                        <label for="search" class="mb-1">Поиск по статьям</label>
                                        <div class="input-group">
                                            <input type="text" id="search" name="search" class="form-control" value="{{ $search }}" placeholder="Заголовок или текст">
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
                                                <option value="{{ $option }}" @selected($perPage === $option)>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="sort" value="{{ $sort }}">
                                    <input type="hidden" name="direction" value="{{ $direction }}">
                                    <div class="col-lg-6 col-md-8 col-sm-6 d-flex align-items-end justify-content-lg-end">
                                        <a href="{{ route('post.index') }}" class="btn btn-default mr-2">
                                            <i class="fas fa-sync-alt mr-1"></i> Сбросить
                                        </a>
                                        <a href="{{ route('post.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus mr-1"></i> Добавить статью
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 90px;">
                                                <a href="{{ route('post.index', array_merge($baseQuery, ['sort' => 'id', 'direction' => $nextDirection('id')])) }}" class="text-dark">
                                                    # <i class="fas {{ $sortIcon('id') }} ml-1"></i>
                                                </a>
                                            </th>
                                            <th>Изображение</th>
                                            <th>
                                                <a href="{{ route('post.index', array_merge($baseQuery, ['sort' => 'title', 'direction' => $nextDirection('title')])) }}" class="text-dark">
                                                    Заголовок <i class="fas {{ $sortIcon('title') }} ml-1"></i>
                                                </a>
                                            </th>
                                            <th>Описание</th>
                                            <th style="width: 180px;">
                                                <a href="{{ route('post.index', array_merge($baseQuery, ['sort' => 'created_at', 'direction' => $nextDirection('created_at')])) }}" class="text-dark">
                                                    Дата <i class="fas {{ $sortIcon('created_at') }} ml-1"></i>
                                                </a>
                                            </th>
                                            <th class="text-right" style="width: 180px;">Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($posts as $post)
                                            <tr>
                                                <td><span class="badge badge-light">#{{ $post->id }}</span></td>
                                                <td>
                                                    <img width="120" class="img-thumbnail" src="{{ $post->getImage() }}" alt="{{ $post->title }}">
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold">{{ $post->title }}</div>
                                                </td>
                                                <td>{{ \Illuminate\Support\Str::limit(strip_tags($post->description), 140) }}</td>
                                                <td>
                                                    <div>{{ optional($post->created_at)->format('d.m.Y') }}</div>
                                                    <small class="text-muted">{{ optional($post->created_at)->format('H:i') }}</small>
                                                </td>
                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <a href="{{ route('post.edit', $post->id) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit mr-1"></i> Редактировать
                                                        </a>
                                                        <form action="{{ route('post.delete', $post->id) }}" method="post" onsubmit="return confirm('Удалить статью «{{ $post->title }}»?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                                                <i class="fas fa-trash-alt mr-1"></i> Удалить
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5">
                                                    <div class="text-muted mb-2"><i class="fas fa-search fa-2x"></i></div>
                                                    <div class="font-weight-bold">Статьи не найдены</div>
                                                    <div class="text-muted">Попробуй изменить поиск или добавить новую статью.</div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($posts->hasPages())
                            <div class="card-footer clearfix">
                                <div class="float-right">
                                    {{ $posts->onEachSide(1)->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
