@include('ssm.environment.head', [
    'setting' => $seting,
    'seoData' => $seoData ?? null,
    'mainset' => $mainset ?? null,
    'extraSchemas' => [$schemaService ?? null],
])

<body class="page__body">
    <div class="site-container">
        @include('ssm.environment.header', ['mainset' => $mainset])

        <main class="main service-page">
            <section class="service-hero">
                <div class="container service-hero__container">
                    <div class="service-hero__content">
                        <span class="service-hero__eyebrow">{{ $landing['eyebrow'] }}</span>
                        <h1>{{ $landing['title'] }}</h1>
                        <p>{{ $landing['lead'] }}</p>
                        <div class="service-hero__actions">
                            <button type="button" class="btn btn-main" data-custom-open="modal-contacts">
                                Получить расчет
                            </button>
                            @if ($selectedGroup)
                                <a class="btn btn-main service-hero__secondary" href="{{ route('gallery.category', $selectedGroup->slug) }}">
                                    Смотреть проекты
                                </a>
                            @endif
                        </div>
                    </div>

                    @if ($selectedGroup && $selectedGroup->slides->isNotEmpty())
                        <div class="service-hero__image">
                            <img src="{{ $selectedGroup->slides->first()->getImage() }}" alt="{{ $landing['title'] }}">
                        </div>
                    @endif
                </div>
            </section>

            <section class="service-section">
                <div class="container service-grid">
                    <div class="service-section__intro">
                        <span>Что делаем</span>
                        <h2>Мебель под задачу, размеры и интерьер</h2>
                        <p>Страница собрана под конкретный поисковый спрос, но остается полезной для клиента: здесь есть виды работ, порядок проекта, примеры и понятный вход в заявку.</p>
                    </div>

                    <div class="service-list">
                        @foreach ($landing['tasks'] as $task)
                            <article class="service-list__item">
                                <h3>{{ $task }}</h3>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="service-section service-section--muted">
                <div class="container service-columns">
                    <div>
                        <span class="service-section__label">Преимущества</span>
                        <h2>Почему мебель на заказ работает лучше типового решения</h2>
                    </div>

                    <div class="service-card-list">
                        @foreach ($landing['benefits'] as $benefit)
                            <article class="service-card-list__item">
                                <p>{{ $benefit }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="service-section">
                <div class="container service-columns">
                    <div>
                        <span class="service-section__label">Этапы</span>
                        <h2>Как проходит работа</h2>
                        <p class="service-section__text">От первой консультации до монтажа проект ведется последовательно: так проще контролировать сроки, материалы и итоговый результат.</p>
                    </div>

                    <ol class="service-steps">
                        @foreach ($landing['process'] as $step)
                            <li>{{ $step }}</li>
                        @endforeach
                    </ol>
                </div>
            </section>

            @if ($selectedGroup && $selectedGroup->slides->isNotEmpty())
                <section class="service-section service-section--muted">
                    <div class="container">
                        <div class="service-section__intro service-section__intro--wide">
                            <span>Примеры</span>
                            <h2>Проекты из этой категории</h2>
                            <p>Фотографии помогают быстрее понять стиль, материалы и уровень исполнения. Больше работ доступно в галерее.</p>
                        </div>

                        <div class="service-projects">
                            @foreach ($selectedGroup->slides->take(6) as $slide)
                                <a class="service-projects__item" href="{{ $slide->getImage() }}" data-fancybox="{{ $selectedGroup->display_title }}" data-caption="{{ $slide->title }}">
                                    <img src="{{ $slide->getImage() }}" alt="{{ $slide->title }}">
                                    <span>
                                        <strong>{{ $slide->title }}</strong>
                                        <em>{{ $slide->description }}</em>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            <section class="service-section">
                <div class="container service-columns">
                    <div>
                        <span class="service-section__label">FAQ</span>
                        <h2>Частые вопросы</h2>
                    </div>

                    <div class="preview-faq__list">
                        @foreach ($landing['faq'] as $item)
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

            @if (!$relatedGroups->isEmpty())
                <section class="service-section service-section--compact">
                    <div class="container">
                        <div class="service-section__intro service-section__intro--wide">
                            <span>Еще направления</span>
                            <h2>Другие виды мебели на заказ</h2>
                        </div>

                        <div class="service-related">
                            @foreach ($relatedGroups as $group)
                                @php
                                    $definition = $group->getDefinition();
                                @endphp
                                <a href="{{ route('gallery.category', $group->slug) }}">
                                    <strong>{{ $group->display_title }}</strong>
                                    <span>{{ $definition['page_title'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            @include('ssm.components.site-cta-new', [
                'previewContent' => $previewContent,
                'ctaPlaceholder' => 'Например: ' . mb_strtolower($landing['title']),
            ])

            @include('ssm.components.site-footer-new', [
                'previewContent' => $previewContent,
                'groups' => $groups,
                'mainset' => $mainset,
                'seting' => $seting,
                'footerLinkHref' => route('gallery'),
                'footerLinkLabel' => 'Смотреть все проекты',
            ])
        </main>

        @include('ssm.components.form')
    </div>
</body>

</html>
