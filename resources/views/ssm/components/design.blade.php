@if (!$groups->isEmpty())
    <section class="design-prod">
        <div class="design-prod-container container">
            @php
                $isCategoryPage = !empty($selectedGroup);
                $pageTitle = $isCategoryPage ? $selectedGroup->getDefinition()['page_title'] : 'Галерея наших проектов';
                $pageDescription = $isCategoryPage
                    ? $selectedGroup->getDefinition()['page_description']
                    : 'Выберите нужную категорию и посмотрите проекты мебели на заказ в Нальчике: кухни, шкафы-купе, детские, спальни, гостиные, гардеробные, кабинеты и торговое оборудование.';
                $renderGroups = $isCategoryPage ? collect([$selectedGroup]) : collect();
            @endphp

            <div class="design-prod__hero">
                <div class="design-prod__hero-main">
                    <span class="design-prod__eyebrow">{{ $isCategoryPage ? 'Категория проектов' : 'Портфолио KBR Mebel' }}</span>
                    @if ($pageTitle === 'Галерея наших проектов')
                        <h1>Галерея <span class="mark">наших проектов</span></h1>
                    @else
                        <h1>{{ $pageTitle }}</h1>
                    @endif
                    <p>{{ $pageDescription }}</p>
                </div>
            </div>

            <div class="tabs tabs--links">
                <ul class="tabs__nav tabs__nav--links design-prod__nav">
                    <li class="tabs__nav-item">
                        <a class="tabs__nav-btn btn-main {{ !$isCategoryPage ? 'tabs__nav-btn--active' : '' }}"
                            href="{{ route('gallery') }}">
                            Все категории
                        </a>
                    </li>
                    @foreach ($groups as $item)
                        <li class="tabs__nav-item">
                            <a class="tabs__nav-btn btn-main {{ !empty($selectedGroup) && $selectedGroup->id === $item->id ? 'tabs__nav-btn--active' : '' }}"
                                href="{{ route('gallery.category', $item->slug) }}">
                                {{ $item->display_title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <div class="tabs__content">
                    @if (!$isCategoryPage)
                        <div class="tabs__panel tabs__panel--active">
                            <div class="tabs__panel__grid">
                                @foreach ($groups as $group)
                                    @php($previewSlide = $group->slides->first())
                                    <div class="tabs__panel__item">
                                        <a class="tabs__panel__box" href="{{ route('gallery.category', $group->slug) }}">
                                            <div class="tabs__panel__image">
                                                <img src="{{ $previewSlide->getImage() }}" alt="{{ $group->display_title }}" />
                                            </div>
                                            <div class="tabs__panel__body">
                                                <span class="tabs__panel__meta">Категория</span>
                                                <h2>{{ $group->display_title }}</h2>
                                                <p>{{ $group->getDefinition()['page_description'] }}</p>
                                                <span class="tabs__panel__link">Открыть раздел</span>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @foreach ($renderGroups as $slider)
                        <div class="tabs__panel tabs__panel--active">
                            <div class="tabs__panel__grid">
                                @foreach ($slider->slides as $item_slide)
                                    <div class="tabs__panel__item">
                                        <a class="tabs__panel__box" href="{{ $item_slide->getImage() }}"
                                            data-fancybox="{{ $item_slide->group->display_title }}"
                                            data-caption="{{ $slider->display_title }}">
                                            <div class="tabs__panel__image">
                                                <img src="{{ $item_slide->getImage() }}" alt="{{ $item_slide->title }}" />
                                            </div>
                                            <div class="tabs__panel__body">
                                                <span class="tabs__panel__meta">{{ $slider->display_title }}</span>
                                                <h2>{{ $item_slide->title }}</h2>
                                                <p>{{ $item_slide->description }}</p>
                                                <span class="tabs__panel__link">Открыть фото</span>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
