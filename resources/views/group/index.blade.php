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
                    <h1 class="m-0">Группы</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Админка</a></li>
                        <li class="breadcrumb-item active">Группы</li>
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
                            <p>Всего групп</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['with_slides'] }}</h3>
                            <p>Групп со слайдами</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-images"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-12">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['slides_total'] }}</h3>
                            <p>Всего слайдов в группах</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-photo-video"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form action="{{ route('group.index') }}" method="get">
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 mb-3 mb-lg-0">
                                        <label for="search" class="mb-1">Поиск по группам</label>
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                id="search"
                                                name="search"
                                                class="form-control"
                                                value="{{ $search }}"
                                                placeholder="Название группы или слайда"
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
                                                <option value="{{ $option }}" @selected($perPage === $option)>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="sort" value="{{ $sort }}">
                                    <input type="hidden" name="direction" value="{{ $direction }}">
                                    <div class="col-lg-6 col-md-8 col-sm-6 d-flex align-items-end justify-content-lg-end">
                                        <a href="{{ route('group.index') }}" class="btn btn-default mr-2">
                                            <i class="fas fa-sync-alt mr-1"></i> Сбросить
                                        </a>
                                        <a href="{{ route('group.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus mr-1"></i> Добавить группу
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
                                                <a href="{{ route('group.index', array_merge($baseQuery, ['sort' => 'id', 'direction' => $nextDirection('id')])) }}" class="text-dark">
                                                    # <i class="fas {{ $sortIcon('id') }} ml-1"></i>
                                                </a>
                                            </th>
                                            <th>
                                                <a href="{{ route('group.index', array_merge($baseQuery, ['sort' => 'title', 'direction' => $nextDirection('title')])) }}" class="text-dark">
                                                    Название <i class="fas {{ $sortIcon('title') }} ml-1"></i>
                                                </a>
                                            </th>
                                            <th style="width: 170px;">
                                                <a href="{{ route('group.index', array_merge($baseQuery, ['sort' => 'slides_count', 'direction' => $nextDirection('slides_count')])) }}" class="text-dark">
                                                    Слайды <i class="fas {{ $sortIcon('slides_count') }} ml-1"></i>
                                                </a>
                                            </th>
                                            <th>Примеры слайдов</th>
                                            <th class="text-right" style="width: 180px;">Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($groups as $item)
                                            <tr>
                                                <td><span class="badge badge-light">#{{ $item->id }}</span></td>
                                                <td>
                                                    <div class="font-weight-bold">{{ $item->title }}</div>
                                                    <small class="text-muted">slug: {{ $item->slug }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">{{ $item->slides_count }}</span>
                                                </td>
                                                <td>
                                                    @forelse ($item->slides->take(3) as $slide)
                                                        <span class="badge badge-light mr-1 mb-1">{{ $slide->title }}</span>
                                                    @empty
                                                        <span class="text-muted">Слайдов пока нет</span>
                                                    @endforelse
                                                    @if ($item->slides_count > 3)
                                                        <span class="text-muted">+ еще {{ $item->slides_count - 3 }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <a href="{{ route('group.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit mr-1"></i> Редактировать
                                                        </a>
                                                        <form action="{{ route('group.delete', $item->id) }}" method="post" onsubmit="return confirm('Удалить группу «{{ $item->title }}»?');">
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
                                                <td colspan="5" class="text-center py-5">
                                                    <div class="text-muted mb-2"><i class="fas fa-search fa-2x"></i></div>
                                                    <div class="font-weight-bold">Группы не найдены</div>
                                                    <div class="text-muted">Попробуй изменить поиск или добавить новую группу.</div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($groups->hasPages())
                            <div class="card-footer clearfix">
                                <div class="float-right">
                                    {{ $groups->onEachSide(1)->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
