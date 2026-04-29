@extends('layouts.main')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактировать группу</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Админка</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('group.index') }}">Группы</a></li>
                        <li class="breadcrumb-item active">{{ $group->title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Параметры группы</h3>
                        </div>
                        <form action="{{ route('group.update', $group->id) }}" method="post">
                            @csrf
                            @method('PATCH')
                            <div class="card-body">
                                <div class="form-group mb-0">
                                    <label for="title">Название группы</label>
                                    <input type="text" class="form-control" id="title" value="{{ old('title', $group->title) }}" placeholder="Название группы" name="title">
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('group.index') }}" class="btn btn-default">Назад</a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save mr-1"></i> Сохранить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
