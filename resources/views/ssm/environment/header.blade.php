<header class="header">
    @php
        $routeName = request()->route()?->getName();
        $isGalleryRoute = in_array($routeName, ['gallery', 'gallery.category'], true);
        $galleryCategories = collect(\App\Models\Group::categoryDefinitions())->values();
        $currentCategorySlug = request()->route('slug');
    @endphp
    <div class="container header-container">
        <div class="header__logo">
            Студия Современной Мебели
        </div>
        <div class="header-right__nav menu">
            <p class="header_subtitle">Студия Современной Мебели</p>
            <a class="header__link {{ $routeName === 'home' ? 'is-active' : '' }}" href="{{ route('home') }}">
                Главная
            </a>
            <div class="header__dropdown {{ $isGalleryRoute ? 'is-active' : '' }}">
                <a class="header__link header__dropdown-toggle" href="{{ route('gallery') }}">
                    Галерея
                </a>
                <div class="header__dropdown-menu">
                    <a class="header__dropdown-item {{ $routeName === 'gallery' ? 'is-active' : '' }}" href="{{ route('gallery') }}">
                        Все категории
                    </a>
                    @foreach ($galleryCategories as $category)
                        <a class="header__dropdown-item {{ $currentCategorySlug === $category['slug'] ? 'is-active' : '' }}" href="{{ route('gallery.category', $category['slug']) }}">
                            {{ $category['name'] }}
                        </a>
                    @endforeach
                </div>
            </div>
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
            <a href="tel:+79280782894" class="header__number header__number--desktop">+7 (928) 078 28 94</a>
            <div class="header__mobile-contacts">
                <span class="header__mobile-label">Контакты</span>
                <a href="tel:+79280782894" class="header__number header__number--mobile">Позвонить: +7 (928) 078 28 94</a>
                <a href="#footer" class="header__mobile-link">Открыть контакты и карту</a>
            </div>
            {{-- <button class="btn btn-main" data-custom-open="modal-contacts">Заказать звонок</button> --}}
        </div>
        <div class="menu-btn">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</header>
