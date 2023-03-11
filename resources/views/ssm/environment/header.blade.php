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
        {{-- <a href="{{ route('logout') }}" class="header__number">Выйти</a> --}}
        {{-- <a href="{{ route('logout') }}" class="header__number">{{ dd(auth()) }}</a> --}}
        <button  class="btn btn-main" data-custom-open="modal-contacts">Заказать звонок</button>
      </div>
    </div>
  </header>
