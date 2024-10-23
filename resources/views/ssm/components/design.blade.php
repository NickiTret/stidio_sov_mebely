@if (!$sliders->isEmpty())
    <section class="design-prod">
        <div class="design-prod-container container">
            <h2>Наши <span class="mark">проекты</span></h2>
            <div class="tabs" data-tabs="tab">
                <ul class="tabs__nav">
                    @foreach ($groups as $item)
                        @if (count($item->slides))
                            <li class="tabs__nav-item"><button class="tabs__nav-btn  btn-main"
                                    type="button">{{ $item->title }}</button></li>
                            {{-- {{ dd($item->slides) }} --}}
                        @endif
                    @endforeach
                </ul>
                <div class="tabs__content">

                    @foreach ($groups as $slider)
                        @if (count($slider->slides))
                            <div class="tabs__panel">
                                <div class="tabs__panel__grid">
                                    @foreach ($slider->slides as $item_slide)

                                    <div class="tabs__panel__item">
                                        <a class="tabs__panel__box" href="{{ $item_slide->getImage() }}"
                                            data-fancybox="{{ $item_slide->group->title }}"
                                            data-caption="{{ $slider->title }}">
                                            <div class="tabs__panel__image">
                                                <img src="{{ $item_slide->getImage() }}" alt="{{ $item_slide->title }}" />
                                            </div>
                                            <h2>{{ $item_slide->title }}</h2>
                                            <p>{{ $item_slide->description }}</p>
                                        </a>

                                    </div>

                                    @endforeach
                                </div>

                            </div>
                        @endif
                    @endforeach

                    {{-- @foreach ($groups as $slider)
                        @if (count($slider->slides))
                            <div class="tabs__panel">
                                <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff;"
                                    class="swiper mySwiper-{{ $slider->id }}">
                                    <div class="swiper-wrapper">
                                        @foreach ($slider->slides as $item_slide)
                                            <div class="swiper-slide">
                                                <a href="{{ $item_slide->getImage() }}"
                                                    data-fancybox="{{ $item_slide->group->title }}"
                                                    data-caption="{{ $slider->title }}">
                                                    <img src="{{ $item_slide->getImage() }}"
                                                        alt="{{ $item_slide->title }}" />
                                                    <div class="swiper-slide__description">
                                                        <h3>{{ $item_slide->title }}</h3>
                                                        <p>{{ $item_slide->description }}</p>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                            </div>
                        @endif
                    @endforeach --}}
                </div>

            </div>
        </div>
    </section>
@endif
