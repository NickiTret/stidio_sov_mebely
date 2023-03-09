<header class="header">
    <div class="container header-container">
      <div class="header__logo">
        Студия Современной Мебели
      </div>
      <div class="header-right__nav">
        <a href="{{ $mainset->watsap }}" class="header__phone">
          <svg class="svg-site">
            <use xlink:href="/img/sprite.svg#phone"></use>
          </svg>
        </a>
        <a href="{{ $mainset->telegram }}" class="header__telegramm">
          <svg class="svg-site">
            <use xlink:href="/img/sprite.svg#telegramm"></use>
          </svg>
        </a>
        <a href="tel:{{ $mainset->tel }}" class="header__number">{{ $mainset->tel }}</a>
        <button  class="btn btn-main" data-custom-open="modal-contacts">Заказать звонок</button>
      </div>
    </div>
  </header>
