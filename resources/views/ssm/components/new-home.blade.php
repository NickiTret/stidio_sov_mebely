@php
    $hero = $previewContent['hero'] ?? [];
    $faq = $previewContent['faq'] ?? [];
    $cta = $previewContent['cta'] ?? [];
    $footer = $previewContent['footer'] ?? [];
    $metrics = $previewContent['metrics'] ?? [];
    $categoriesSection = $previewContent['categories_section'] ?? [];
    $approach = $previewContent['approach'] ?? [];
    $showcase = $previewContent['showcase'] ?? [];
    $isPreview = $isPreview ?? false;
@endphp

<main class="main home-preview">
    <section class="preview-subnav">
        <div class="container preview-subnav__container">
            <div class="preview-subnav__catalog">
                <button type="button" class="preview-subnav__catalog-btn">
                    <span class="preview-subnav__catalog-icon" aria-hidden="true"></span>
                    Каталог мебели
                </button>
                <div class="preview-subnav__catalog-panel">
                    <div class="preview-subnav__catalog-grid">
                        @foreach ($groups as $group)
                            @php
                                $definition = $group->getDefinition();
                            @endphp
                            <a class="preview-subnav__catalog-card" href="{{ route('gallery.category', $group->slug) }}">
                                <strong>{{ $group->display_title }}</strong>
                                <span>{{ $definition['page_title'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="preview-subnav__links">
                @foreach ($featuredGroups as $group)
                    <a href="{{ route('gallery.category', $group->slug) }}">{{ $group->display_title }}</a>
                @endforeach
                <a href="{{ route('gallery') }}">Все категории</a>
            </div>
        </div>
    </section>

    <section class="preview-hero">
        <div class="container preview-hero__container">
            <div class="preview-hero__content">
                <span class="preview-hero__eyebrow">{{ $hero['eyebrow'] }}</span>
                <h1>{{ $hero['title'] }}</h1>
                <p class="preview-hero__lead">
                    {{ $hero['lead'] }}
                </p>

                <div class="preview-hero__actions">
                    <a href="{{ route('gallery') }}" class="btn btn-main">{{ $hero['primary_button_text'] }}</a>
                    <button type="button" class="btn btn-main preview-hero__ghost" data-custom-open="modal-contacts">
                        {{ $hero['secondary_button_text'] }}
                    </button>
                </div>

                <div class="preview-hero__quick-title">
                    <span>{{ $hero['quick_title'] }}</span>
                    <p>{{ $hero['quick_description'] }}</p>
                </div>

                <div class="preview-hero__quick-links">
                    @foreach ($primaryGroups as $group)
                        <a class="preview-hero__quick-link" href="{{ route('gallery.category', $group->slug) }}">
                            <strong>{{ $group->display_title }}</strong>
                            <span>{{ $group->getDefinition()['benefits'][0] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="preview-hero__visual">
                <img src="{{ $highlightImage }}" alt="Проект мебели на заказ">
            </div>

            <div class="preview-hero__card">
                <h2>{{ $hero['card_title'] }}</h2>
                <ul class="list-reset">
                    @foreach ($hero['card_points'] as $point)
                        <li>{{ $point }}</li>
                    @endforeach
                </ul>
                <div class="preview-hero__contacts">
                    <a href="tel:{{ $mainset->tel }}">{{ $mainset->tel }}</a>
                    <a href="{{ $mainset->watsap }}" target="_blank" rel="noopener">WhatsApp</a>
                </div>
            </div>
        </div>
    </section>

    @if ($isPreview)
        @php
            $variantOne = $groups->get(0);
            $variantTwo = $groups->get(1) ?? $groups->get(0);
            $variantThree = $groups->get(2) ?? $groups->get(0);
        @endphp

        <section class="preview-hero-lab">
            <div class="container">
                <div class="preview-section-heading">
                    <span>Hero concepts</span>
                    <h2>Три разных направления для нового первого экрана</h2>
                    <p>Ниже не “финальный макет”, а три разные логики подачи: более скандинавская, более контрактная и более коммерческая с акцентом на категории.</p>
                </div>

                <div class="preview-hero-lab__grid">
                    <article class="preview-hero-concept">
                        <div class="preview-hero-concept__media">
                            <img src="{{ optional($variantOne?->slides->first())->getImage() ?? $highlightImage }}" alt="Вариант главного экрана 1">
                        </div>
                        <div class="preview-hero-concept__body">
                            <span>Вариант 1</span>
                            <h3>Спокойный интерьерный hero</h3>
                            <p>Опора на крупное фото, чистую типографику и ощущение мастерской. Ближе по характеру к скандинавским и интерьерным студиям.</p>
                            <ul class="list-reset">
                                <li>меньше коммерческого шума</li>
                                <li>больше визуального доверия</li>
                                <li>подходит для премиального тона</li>
                            </ul>
                        </div>
                    </article>

                    <article class="preview-hero-concept">
                        <div class="preview-hero-concept__media">
                            <img src="{{ optional($variantTwo?->slides->first())->getImage() ?? $highlightImage }}" alt="Вариант главного экрана 2">
                        </div>
                        <div class="preview-hero-concept__body">
                            <span>Вариант 2</span>
                            <h3>Процесс и реализация под ключ</h3>
                            <p>Hero с более деловым акцентом: проектирование, производство, монтаж. Хорошо работает, если хочется показать надежность и системность.</p>
                            <ul class="list-reset">
                                <li>сильнее коммерческий смысл</li>
                                <li>хорошо заходит под B2B и частных клиентов</li>
                                <li>можно связать с шагами работы</li>
                            </ul>
                        </div>
                    </article>

                    <article class="preview-hero-concept">
                        <div class="preview-hero-concept__media">
                            <img src="{{ optional($variantThree?->slides->first())->getImage() ?? $highlightImage }}" alt="Вариант главного экрана 3">
                        </div>
                        <div class="preview-hero-concept__body preview-hero-concept__body--selected">
                            <span>Вариант 3</span>
                            <h3>Категории сразу в первом экране</h3>
                            <p>Подходит, если хочется, чтобы главная сразу работала как точка входа в основные направления: кухни, шкафы, гардеробные и детские.</p>
                            <ul class="list-reset">
                                <li>сильнее SEO-логика</li>
                                <li>быстрее ведет в нужный раздел</li>
                                <li>чуть более “маркетинговый” характер</li>
                            </ul>
                            <div class="preview-hero-concept__choice">Выбран как базовое направление</div>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    @endif

    <section class="preview-faq">
        <div class="container preview-faq__container">
            <div class="preview-section-heading preview-section-heading--left">
                <span>{{ $faq['eyebrow'] ?? 'FAQ-блок' }}</span>
                <h2>{{ $faq['title'] ?? '' }}</h2>
                <p>{{ $faq['description'] ?? '' }}</p>
            </div>

            <div class="preview-faq__list">
                @foreach (($faq['items'] ?? []) as $item)
                    <details class="preview-faq__item" open>
                        <summary>{{ $item['question'] }}</summary>
                        <div class="preview-faq__content">
                            <p>{{ $item['answer'] }}</p>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    <section class="preview-metrics">
        <div class="container preview-metrics__container">
            @foreach (($metrics['items'] ?? []) as $item)
                <article class="preview-metrics__item">
                    <span class="preview-metrics__value">{{ $item['value'] }}</span>
                    <p>{{ $item['text'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="preview-categories">
        <div class="container">
            <div class="preview-section-heading">
                <span>{{ $categoriesSection['eyebrow'] ?? 'Категории' }}</span>
                <h2>{{ $categoriesSection['title'] ?? '' }}</h2>
                <p>{{ $categoriesSection['description'] ?? '' }}</p>
            </div>

            <div class="preview-categories__grid">
                @foreach ($groups as $group)
                    @php
                        $slide = $group->slides->first();
                        $definition = $group->getDefinition();
                    @endphp
                    <a class="preview-category-card" href="{{ route('gallery.category', $group->slug) }}">
                        <span class="preview-category-card__image">
                            <img src="{{ $slide->getImage() }}" alt="{{ $group->display_title }}">
                        </span>
                        <span class="preview-category-card__body">
                            <strong>{{ $group->display_title }}</strong>
                            <span>{{ $definition['page_title'] }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="preview-approach">
        <div class="container preview-approach__container">
            <div class="preview-section-heading preview-section-heading--left">
                <span>{{ $approach['eyebrow'] ?? 'Подход' }}</span>
                <h2>{{ $approach['title'] ?? '' }}</h2>
                <p>{{ $approach['description'] ?? '' }}</p>
            </div>

            <div class="preview-approach__list">
                @foreach (($approach['steps'] ?? []) as $step)
                    <article class="preview-approach__item">
                        <strong>{{ $step['title'] }}</strong>
                        <p>{{ $step['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- <section class="preview-showcase">
        <div class="container">
            <div class="preview-section-heading">
                <span>{{ $showcase['eyebrow'] ?? 'Подборка проектов' }}</span>
                <h2>{{ $showcase['title'] ?? '' }}</h2>
                <p>{{ $showcase['description'] ?? '' }}</p>
            </div>

            <div class="preview-showcase__grid">
                @foreach ($showcaseGroups as $group)
                    @php
                        $slide = $group->slides->first();
                        $definition = $group->getDefinition();
                    @endphp
                    <article class="preview-showcase__item">
                        <div class="preview-showcase__image">
                            <img src="{{ $slide->getImage() }}" alt="{{ $group->display_title }}">
                        </div>
                        <div class="preview-showcase__body">
                            <span>{{ $group->display_title }}</span>
                            <h3>{{ $definition['page_title'] }}</h3>
                            <p>{{ $definition['seo_description'] }}</p>
                            <a href="{{ route('gallery.category', $group->slug) }}">Открыть подборку</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section> --}}

    @include('ssm.components.site-cta-new', [
        'previewContent' => $previewContent,
        'ctaPlaceholder' => 'Например: шпонированная кухня, шкаф, гардеробная или кабинет',
    ])

    @include('ssm.components.site-footer-new', [
        'previewContent' => $previewContent,
        'groups' => $groups,
        'mainset' => $mainset,
        'seting' => $seting,
        'footerLinkHref' => $isPreview ? route('home') : route('gallery'),
        'footerLinkLabel' => $isPreview ? ($footer['bottom_text'] ?? 'Открыть текущую главную') : 'Смотреть все проекты',
    ])
</main>
