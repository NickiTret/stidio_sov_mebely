@if (!$sliders->isEmpty())
<section class="design-prod">
    <div class="design-prod-container container">
        <h2>Наши <span class="mark">проекты</span></h2>
        <h4>Совершенство комфорта в вашем распоряжении</h4>
        <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff;"
            class="swiper mySwiper2">
            <div class="swiper-wrapper">
                @foreach ($sliders as $slider )
                <div class="swiper-slide">
                    <img src="{{ $slider->getImage() }}"  alt="{{ $slider->title }}"/>
                    <div class="swiper-slide__description">
                        <h4>Группа: {{ $slider->group->title }}</h4>
                        <h3>{{ $slider->title }}</h3>
                        <p>{{ $slider->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        <div thumbsSlider="" class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach ($sliders as $slider )
                <div class="swiper-slide">
                    <img src="{{ $slider->getImage() }}"  alt="{{ $slider->title }}"/>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

