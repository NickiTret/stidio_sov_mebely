@extends('layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Редактировать настройки для "{{ $seting->title }}"</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">{{ $seting->title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('seting.update', $seting->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $seting->page }}" placeholder="Страница"
                                name="page">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $seting->title }}"
                                placeholder="Наименование" name="title">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $seting->descriptions }}"
                                placeholder="Описание" name="descriptions">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" value="{{ $seting->keywords }}"
                                placeholder="Ключевые слова, через запятую" name="keywords">
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="favicon" id="favicon">
                                <label class="custom-file-label" for="favicon">Выбрать</label>
                            </div>
                            <div><img src="{{ $seting->getImage() }}" alt="" class="img-thumbnail mt-2"
                                    width="200"></div>
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
