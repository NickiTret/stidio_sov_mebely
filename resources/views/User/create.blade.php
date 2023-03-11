
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Регистрация</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link href="{{ asset('assets/admin/css/admin.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
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
          <input type="text" name="name" class="form-control" placeholder="Имя" value="{{ old('name') }}">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Пароль" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password_confirmation" class="form-control" placeholder="Подвердить пароль">
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

      <a href="{{ route( 'login.create' ) }}" class="text-center">Если вы уже зарегистрированы</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="{{ asset('assets/js/admin.js') }}"></script>

<script type="module" src="{{ asset('assets/js/main.js') }}"></script>

<script>
     //Initialize Select2 Elements
     $('.select2').select2()

    $('.nav-sidebar').each(function (){
        let location = window.location.protocol + '//' + window.location.host + window.location.pathname;

        let link = this.href;
        if(link === location){
            $(this).addClass('active');
            $(this).closest('.has-treeview').addClass('menu-open');
        };
    });

</script>
</body>
</html>
