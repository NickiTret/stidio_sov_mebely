@extends('layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактировать фото для "{{ $slider->title }}"</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">{{ $slider->title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('slider.update', $slider->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $slider->title }}"
                                placeholder="Наименование" name="title">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $slider->description }}"
                                placeholder="Описание" name="description">
                        </div>
                        <div class="form-group">
                            <label>Группа</label>
                            <select name="group_id" class="form-control">
                                @foreach ($groups as $group)
                                    <option @if($group->id === $slider->group_id) selected @endif value="{{ $group->id }}">{{ $group->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" value="{{ $slider->getImage() }}" name="image_src" id="image_src">
                                <label class="custom-file-label" for="image_src">Выбрать</label>
                            </div>
                            <div><img src="{{ $slider->getImage() }}" alt="" class="img-thumbnail mt-2"
                                    width="200">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="Сохранить">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
