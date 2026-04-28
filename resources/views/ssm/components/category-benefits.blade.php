@php
    $definition = $group->getDefinition();
    $previewSlide = $group->slides->first();
    $relatedGroups = collect($groups ?? [])->reject(fn ($item) => $item->id === $group->id)->take(4);
@endphp

<section class="realish">
    <div data-aos="zoom-down" data-aos-dalay="50" data-aos-duration="1000" class="container realish-container">
        <h2><span class="mark">{{ $definition['name'] }}</span> на заказ в Нальчике</h2>
        <div class="realish__wrapper">
            <div class="realish__image">
                <img src="{{ $previewSlide->getImage() }}" alt="{{ $definition['name'] }}">
            </div>
            <div class="realish__text">
                <p>Проектируем, изготавливаем, доставляем и устанавливаем мебель по индивидуальным размерам с учетом планировки, задач и стиля вашего интерьера.</p>
                <ul class="list-reset">
                    @foreach ($definition['benefits'] as $benefit)
                        <li>{{ $benefit }}</li>
                    @endforeach
                </ul>
                <p>Оставьте заявку, и мы поможем подобрать материалы, компоновку и удобное решение именно под ваш интерьер.</p>
                <button type="button" class="btn btn-main" data-custom-open="modal-contacts">Получить консультацию</button>
            </div>
        </div>
    </div>
</section>

<section class="category-seo">
    <div class="container category-seo__container">
        <div class="category-seo__content">
            <h2>Почему выбирают {{ mb_strtolower($definition['name']) }} на заказ</h2>
            @foreach ($definition['content'] as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </div>

        <div class="category-seo__aside">
            <h3>Другие категории</h3>
            <div class="category-seo__links">
                @foreach ($relatedGroups as $relatedGroup)
                    <a class="btn btn-main" href="{{ route('gallery.category', $relatedGroup->slug) }}">
                        {{ $relatedGroup->display_title }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
