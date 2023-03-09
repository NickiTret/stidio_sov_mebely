@extends('layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Настройки</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Главная</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('seting.create') }}" class="btn btn-primary">Добавить</a>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Страница</th>
                                        <th>Заголовок</th>
                                        <th>Описание</th>
                                        <th>Ключевые слова</th>
                                        <th>Иконка</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($setings as $seting)
                                    <tr>
                                        <td>{{ $seting->id }}</td>
                                        <td><a href="{{ route('seting.edit', $seting->id) }}">{{ $seting->page }}</a></td>
                                        <td>{{ $seting->title }}</td>
                                        <td>{{ $seting->descriptions }}</td>
                                        <td>{{ $seting->keywords }}</td>
                                        <td>
                                            <img src="{{ $seting->getImage() }}" width="50" alt="{{ $seting->title }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
