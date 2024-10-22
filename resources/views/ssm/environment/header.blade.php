<header class="header">
    <div class="container header-container">
        <div class="header__logo">
            Студия Современной Мебели
        </div>
        <div class="header-right__nav menu">
            <a class="header__link" href="{{ route('home') }}">
                Главная
            </a>
            <a class="header__link" href="{{ route('gallery') }}">
                Галерея
            </a>
            <a class="header__link fs" href="#footer">
                Контакты
            </a>
            @if (auth()->check())
                <a href="@if (auth()->user()->is_admin) {{ route('admin') }}
            @else
                {{ route('logout') }} @endif"
                    class="header__number">Настроить сайт</a>
            @else
                {{-- <a href="{{ route('login') }}" class="header__number">Войти</a> --}}

            @endif
            <a href="tel:+79280782894" class="header__number">+7 (928) 078 28 94</a>
            <button class="btn btn-main" data-custom-open="modal-contacts">Заказать звонок</button>
        </div>
        <div class="menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</header>
