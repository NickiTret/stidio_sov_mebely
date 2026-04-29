@extends('layouts.main')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактировать статью</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Админка</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('post.index') }}">Статьи</a></li>
                        <li class="breadcrumb-item active">{{ $post->title }}</li>
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
                            <h3 class="card-title">Параметры статьи</h3>
                        </div>
                        <form action="{{ route('post.update', $post->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Заголовок</label>
                                    <input type="text" class="form-control" id="title" value="{{ old('title', $post->title) }}" placeholder="Название статьи" name="title">
                                </div>
                                <div class="form-group">
                                    <label for="description">Описание</label>
                                    <textarea rows="18" class="form-control redactor" id="description" placeholder="Описание статьи" name="description">{{ old('description', $post->description) }}</textarea>
                                </div>
                                <div class="form-group mb-0">
                                    <label for="image_src">Изображение</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="image_src" id="image_src">
                                        <label class="custom-file-label" for="image_src">Выбрать файл</label>
                                    </div>
                                    <div class="mt-3">
                                        <img src="{{ $post->getImage() }}" alt="{{ $post->title }}" class="img-thumbnail" width="220">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('post.index') }}" class="btn btn-default">Назад</a>
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
