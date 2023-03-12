<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Регистрация</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.css') }} ">
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            <b>Регистрация</b>
        </div>

        <div class="card">

            @include('Component.errors')
            <div class="card-body register-card-body">

                <form action="{{ route('register.store') }}" method="post">
                    @csrf

                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Имя"
                            value="{{ old('name') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email"
                            value="{{ old('email') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Пароль">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Подвердить пароль">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Зарегистрироваться</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <a href="{{ route('login.create') }}" class="text-center">Если вы уже зарегистрированы</a>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <script src="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.js') }}"></script>

    <script>
        $(function() {
            if ($('.tags')) {
                $('.tags').select2()
            }

            if ($('.colors')) {
                $('.colors').select2()
            }

            bsCustomFileInput.init();
            if ($('.redactor')) {
                $('.redactor').summernote()
            }

            if ($('.redactor2')) {
                $('.redactor2').summernote()
            }
        })
    </script>
</body>

</html>
