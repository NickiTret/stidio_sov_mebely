@extends('layouts.main')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактировать пост "{{ $post->title }}"</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">{{ $post->title }}</li>
                    </ol>
                </div><!-- /.col -->
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('post.update', $post->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $post->title }}"
                                placeholder="Наименование" name="title">
                        </div>
                        <div class="form-group">
                            <textarea type="text" rows="50" class="form-control redactor" value="{{ $post->description }}" placeholder="Описание"
                                name="description"> </textarea>
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="image_src" id="image_src">
                                <label class="custom-file-label" for="image_src">Выбрать</label>
                            </div>
                            <div><img src="{{ $post->getImage() }}" alt="" class="img-thumbnail mt-2"
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
