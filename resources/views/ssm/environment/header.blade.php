<header class="header">
    @php
        $routeName = request()->route()?->getName();
        $isGalleryRoute = in_array($routeName, ['gallery', 'gallery.category'], true);
        $isServiceRoute = $routeName === 'service.page';
        $galleryGroups = \App\Support\SiteCache::galleryGroups();
        $servicePages = collect(\App\Support\SeoLandingPages::all());
        $currentCategorySlug = request()->route('slug');
        $currentLandingSlug = request()->route('landingSlug');
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
            <div class="header__dropdown {{ $isServiceRoute ? 'is-active' : '' }}">
                <a class="header__link header__dropdown-toggle" href="{{ route('service.page', $servicePages->first()['slug']) }}">
                    Услуги
                </a>
                <div class="header__dropdown-menu">
                    @foreach ($servicePages as $servicePage)
                        <a class="header__dropdown-item {{ $currentLandingSlug === $servicePage['slug'] ? 'is-active' : '' }}" href="{{ route('service.page', $servicePage['slug']) }}">
                            {{ $servicePage['eyebrow'] }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="header__dropdown {{ $isGalleryRoute ? 'is-active' : '' }}">
                <a class="header__link header__dropdown-toggle" href="{{ route('gallery') }}">
                    Галерея
                </a>
                <div class="header__dropdown-menu">
                    <a class="header__dropdown-item {{ $routeName === 'gallery' ? 'is-active' : '' }}" href="{{ route('gallery') }}">
                        Все категории
                    </a>
                    @foreach ($galleryGroups as $galleryGroup)
                        <a class="header__dropdown-item {{ $currentCategorySlug === $galleryGroup->slug ? 'is-active' : '' }}" href="{{ route('gallery.category', $galleryGroup->slug) }}">
                            {{ $galleryGroup->display_title }}
                        </a>
                    @endforeach
                </div>
            </div>
            <a class="header__link fs {{ $routeName === 'contacts' ? 'is-active' : '' }}" href="{{ route('contacts') }}">
                Контакты
            </a>
            @if (auth()->check() && auth()->user()->is_admin)
                <a href="{{ route('admin') }}" class="header__number">Настроить сайт</a>
            @endif
            @if (!empty($mainset?->tel))
                <a href="{{ $mainset->tel_href }}" class="header__number header__number--desktop">{{ $mainset->tel }}</a>
            @endif
            <div class="header__mobile-contacts">
                <span class="header__mobile-label">Контакты</span>
                @if (!empty($mainset?->tel))
                    <a href="{{ $mainset->tel_href }}" class="header__number header__number--mobile">Позвонить: {{ $mainset->tel }}</a>
                @endif
                <a href="{{ route('contacts') }}" class="header__mobile-link">Открыть контакты и карту</a>
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
