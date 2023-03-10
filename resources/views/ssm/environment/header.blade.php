<header class="header">
    <div class="container header-container">
      <div class="header__logo">
        Студия Современной Мебели
      </div>
      <div class="header-right__nav">
        <a href="{{ $mainset->watsap }}" rel="noopener" target="_blank" class="header__phone">
          <svg class="svg-site">
            <use xlink:href="/img/sprite.svg#whatsapp"></use>
          </svg>
        </a>
        <a href="{{ $mainset->telegram }}" rel="noopener" target="_blank" class="header__telegramm">
          <svg class="svg-site">
            <use xlink:href="/img/sprite.svg#telegramm"></use>
          </svg>
        </a>
        <a href="tel:{{ $mainset->tel }}" class="header__number">{{ $mainset->tel }}</a>
        @if (auth()->check())
            <a href="@if (auth()->user()->is_admin)
                {{ route('admin') }}
            @else
                {{ route('logout') }}
            @endif" class="header__number">{{ auth()->user()->name }}</a>
        @else
        <a href="{{ route('login') }}" class="header__number">Войти</a>
        @endif
        <button  class="btn btn-main" data-custom-open="modal-contacts">Заказать звонок</button>
      </div>
    </div>
  </header>
