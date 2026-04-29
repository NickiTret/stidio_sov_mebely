@extends('layouts.main')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактировать слайд</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Админка</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('slider.index') }}">Слайды</a></li>
                        <li class="breadcrumb-item active">{{ $slider->title }}</li>
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
                            <h3 class="card-title">Параметры слайда</h3>
                        </div>
                        <form action="{{ route('slider.update', $slider->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Заголовок</label>
                                    <input type="text" class="form-control" id="title" value="{{ old('title', $slider->title) }}" placeholder="Название слайда" name="title">
                                </div>
                                <div class="form-group">
                                    <label for="description">Описание</label>
                                    <input type="text" class="form-control" id="description" value="{{ old('description', $slider->description) }}" placeholder="Краткое описание" name="description">
                                </div>
                                <div class="form-group">
                                    <label for="group_id">Группа</label>
                                    <select name="group_id" id="group_id" class="form-control">
                                        <option value="">Без группы</option>
                                        @foreach ($groups as $group)
                                            <option @selected((string) old('group_id', $slider->group_id) === (string) $group->id) value="{{ $group->id }}">{{ $group->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label for="image_src">Изображение</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="image_src" id="image_src">
                                        <label class="custom-file-label" for="image_src">Выбрать файл</label>
                                    </div>
                                    <div class="mt-3">
                                        <img src="{{ $slider->getImage() }}" alt="{{ $slider->title }}" class="img-thumbnail" width="220">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('slider.index') }}" class="btn btn-default">Назад</a>
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
