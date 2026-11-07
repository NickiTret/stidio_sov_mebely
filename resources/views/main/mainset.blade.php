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
                                            <input type="tel" class="form-control" id="tel" value="{{ old('tel', $mainset->tel ?? '') }}" required maxlength="30" placeholder="Телефон" name="tel">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Электронная почта</label>
                                            <input type="email" class="form-control" id="email" value="{{ old('email', $mainset->email ?? '') }}" required maxlength="255" placeholder="Электронная почта" name="email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="telegram">Telegram</label>
                                            <input type="text" class="form-control" id="telegram" value="{{ old('telegram', $mainset->telegram ?? '') }}" required maxlength="255" placeholder="https://t.me/username или @username" name="telegram">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="watsap">WhatsApp</label>
                                            <input type="text" class="form-control" id="watsap" value="{{ old('watsap', $mainset->watsap ?? '') }}" required maxlength="255" placeholder="https://wa.me/79... или номер телефона" name="watsap">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="map">Метка на карте</label>
                                            <input type="text" class="form-control" id="map" value="{{ old('map', $mainset->map ?? '') }}" required maxlength="255" placeholder="Подпись метки" name="map">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label for="footer">Подпись в футере</label>
                                            <input type="text" class="form-control" id="footer" value="{{ old('footer', $mainset->footer ?? '') }}" required maxlength="255" placeholder="Подпись в подвале" name="footer">
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
