@extends('layouts.main')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Настройки сайта</h1>
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
                    <form @if (!empty($mainset)) action="{{ route('mainset.update') }}" @else
                        action="{{ route('mainset.store') }}" @endif method="post">
                        @csrf
                        @if (!empty($mainset))
                            @method('PATCH')
                        @endif
                        {{-- {{ dd($mainset) }} --}}
                        <div class="form-group">
                            <input type="text" class="form-control"
                                @if (!empty($mainset)) value="{{ $mainset->tel }}" @endif placeholder="Телефон"
                                name="tel">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control"
                                @if (!empty($mainset)) value="{{ $mainset->email }}" @endif
                                placeholder="Электронная почта" name="email">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control"
                                @if (!empty($mainset)) value="{{ $mainset->telegram }}" @endif
                                placeholder="Телеграмм" name="telegram">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control"
                                @if (!empty($mainset)) value="{{ $mainset->watsap }}" @endif placeholder="Watsap"
                                name="watsap">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control"
                                @if (!empty($mainset)) value="{{ $mainset->map }}" @endif
                                placeholder="Метка на карте" name="map">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control"
                                @if (!empty($mainset)) value="{{ $mainset->footer }}" @endif
                                placeholder="Подпись в подвале" name="footer">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="Добавить / Сохранить">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
