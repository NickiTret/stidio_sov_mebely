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
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Админка</a></li>
                        <li class="breadcrumb-item active">Настройки сайта</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ filled(optional($mainset)->tel) ? '1' : '0' }}</h3>
                            <p>Телефон заполнен</p>
                        </div>
                        <div class="icon"><i class="fas fa-phone"></i></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ filled(optional($mainset)->email) ? '1' : '0' }}</h3>
                            <p>Email заполнен</p>
                        </div>
                        <div class="icon"><i class="fas fa-envelope"></i></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-12">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ filled(optional($mainset)->map) ? '1' : '0' }}</h3>
                            <p>Карта подключена</p>
                        </div>
                        <div class="icon"><i class="fas fa-map-marked-alt"></i></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Контакты и глобальные настройки сайта</h3>
                        </div>
                        <form @if (!empty($mainset)) action="{{ route('mainset.update') }}" @else action="{{ route('mainset.store') }}" @endif method="post">
                            @csrf
                            @if (!empty($mainset))
                                @method('PATCH')
                            @endif
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tel">Телефон</label>
                                            <input type="text" class="form-control" id="tel" @if (!empty($mainset)) value="{{ $mainset->tel }}" @endif placeholder="Телефон" name="tel">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Электронная почта</label>
                                            <input type="text" class="form-control" id="email" @if (!empty($mainset)) value="{{ $mainset->email }}" @endif placeholder="Электронная почта" name="email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telegram">Telegram</label>
                                            <input type="text" class="form-control" id="telegram" @if (!empty($mainset)) value="{{ $mainset->telegram }}" @endif placeholder="Ссылка или username Telegram" name="telegram">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="watsap">WhatsApp</label>
                                            <input type="text" class="form-control" id="watsap" @if (!empty($mainset)) value="{{ $mainset->watsap }}" @endif placeholder="Ссылка WhatsApp" name="watsap">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="map">Код карты / iframe</label>
                                            <input type="text" class="form-control" id="map" @if (!empty($mainset)) value="{{ $mainset->map }}" @endif placeholder="Метка на карте или iframe" name="map">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label for="footer">Подпись в футере</label>
                                            <input type="text" class="form-control" id="footer" @if (!empty($mainset)) value="{{ $mainset->footer }}" @endif placeholder="Подпись в подвале" name="footer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save mr-1"></i> Сохранить настройки сайта
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
