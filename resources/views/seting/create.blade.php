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
                        <li class="breadcrumb-item active">Новая страница</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('seting.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Страница" name="page">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Наименование" name="title">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Описание" name="descriptions">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Ключевые слова, через запятую"
                                name="keywords">
                        </div>
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="favicon" id="favicon">
                                <label class="custom-file-label" for="favicon">Выбрать</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="Добавить">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
