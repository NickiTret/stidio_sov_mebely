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
                    <h1 class="m-0">Слайды галереи</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Админка</a></li>
                        <li class="breadcrumb-item active">Слайды</li>
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
                            <p>Всего слайдов</p>
                        </div>
                        <div class="icon"><i class="fas fa-images"></i></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['with_group'] }}</h3>
                            <p>Привязано к группам</p>
                        </div>
                        <div class="icon"><i class="fas fa-link"></i></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-12">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['groups_total'] }}</h3>
                            <p>Доступных групп</p>
                        </div>
                        <div class="icon"><i class="fas fa-layer-group"></i></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form action="{{ route('slider.index') }}" method="get">
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 mb-3 mb-lg-0">
                                        <label for="search" class="mb-1">Поиск по слайдам</label>
                                        <div class="input-group">
                                            <input type="text" id="search" name="search" class="form-control" value="{{ $search }}" placeholder="Заголовок, описание или группа">
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
                                        <a href="{{ route('slider.index') }}" class="btn btn-default mr-2">
                                            <i class="fas fa-sync-alt mr-1"></i> Сбросить
                                        </a>
                                        <a href="{{ route('slider.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus mr-1"></i> Добавить слайд
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
                                                <a href="{{ route('slider.index', array_merge($baseQuery, ['sort' => 'id', 'direction' => $nextDirection('id')])) }}" class="text-dark">
                                                    # <i class="fas {{ $sortIcon('id') }} ml-1"></i>
                                                </a>
                                            </th>
                                            <th>Фото</th>
                                            <th>
                                                <a href="{{ route('slider.index', array_merge($baseQuery, ['sort' => 'title', 'direction' => $nextDirection('title')])) }}" class="text-dark">
                                                    Заголовок <i class="fas {{ $sortIcon('title') }} ml-1"></i>
                                                </a>
                                            </th>
                                            <th>Описание</th>
                                            <th>
                                                <a href="{{ route('slider.index', array_merge($baseQuery, ['sort' => 'group_id', 'direction' => $nextDirection('group_id')])) }}" class="text-dark">
                                                    Группа <i class="fas {{ $sortIcon('group_id') }} ml-1"></i>
                                                </a>
                                            </th>
                                            <th style="width: 180px;">
                                                <a href="{{ route('slider.index', array_merge($baseQuery, ['sort' => 'created_at', 'direction' => $nextDirection('created_at')])) }}" class="text-dark">
                                                    Дата <i class="fas {{ $sortIcon('created_at') }} ml-1"></i>
                                                </a>
                                            </th>
                                            <th class="text-right" style="width: 180px;">Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($sliders as $slider)
                                            <tr>
                                                <td><span class="badge badge-light">#{{ $slider->id }}</span></td>
                                                <td><img width="120" class="img-thumbnail" src="{{ $slider->getImage() }}" alt="{{ $slider->title }}"></td>
                                                <td><div class="font-weight-bold">{{ $slider->title }}</div></td>
                                                <td>{{ \Illuminate\Support\Str::limit(strip_tags((string) $slider->description), 120) }}</td>
                                                <td>
                                                    @if ($slider->group)
                                                        <span class="badge badge-info">{{ $slider->group->title }}</span>
                                                    @else
                                                        <span class="text-muted">Без группы</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>{{ optional($slider->created_at)->format('d.m.Y') }}</div>
                                                    <small class="text-muted">{{ optional($slider->created_at)->format('H:i') }}</small>
                                                </td>
                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <a href="{{ route('slider.edit', $slider->id) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit mr-1"></i> Редактировать
                                                        </a>
                                                        <form action="{{ route('slider.delete', $slider->id) }}" method="post" onsubmit="return confirm('Удалить слайд «{{ $slider->title }}»?');">
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
                                                <td colspan="7" class="text-center py-5">
                                                    <div class="text-muted mb-2"><i class="fas fa-search fa-2x"></i></div>
                                                    <div class="font-weight-bold">Слайды не найдены</div>
                                                    <div class="text-muted">Попробуй изменить поиск или добавить новый слайд.</div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($sliders->hasPages())
                            <div class="card-footer clearfix">
                                <div class="float-right">
                                    {{ $sliders->onEachSide(1)->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
