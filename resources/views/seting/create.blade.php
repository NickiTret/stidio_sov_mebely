@extends('layouts.main')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Добавить настройки страницы</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Админка</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('seting.index') }}">Настройки страниц</a></li>
                        <li class="breadcrumb-item active">Добавить</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Новая SEO-страница</h3>
                        </div>
                        <form action="{{ route('seting.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="page">Страница</label>
                                    <input type="text" class="form-control" id="page" placeholder="/gallery/kuhni или /" name="page" value="{{ old('page') }}">
                                </div>
                                <div class="form-group">
                                    <label for="title">Заголовок</label>
                                    <input type="text" class="form-control" id="title" placeholder="Title страницы" name="title" value="{{ old('title') }}">
                                </div>
                                <div class="form-group">
                                    <label for="descriptions">Описание</label>
                                    <textarea class="form-control" id="descriptions" rows="4" placeholder="Meta description" name="descriptions">{{ old('descriptions') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="keywords">Ключевые слова</label>
                                    <textarea class="form-control" id="keywords" rows="3" placeholder="Ключевые слова через запятую" name="keywords">{{ old('keywords') }}</textarea>
                                </div>
                                <div class="form-group mb-0">
                                    <label for="favicon">Иконка / изображение</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="favicon" id="favicon">
                                        <label class="custom-file-label" for="favicon">Выбрать файл</label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('seting.index') }}" class="btn btn-default">Назад</a>
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
