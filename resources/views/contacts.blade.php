@include('ssm.environment.head', ['setting' => $seting, 'seoData' => $seoData ?? null, 'mainset' => $mainset ?? null])

<body class="page__body">
    <div class="site-container">
        @include('ssm.environment.header', ['mainset' => $mainset])

        <main class="main service-page">
            <section class="service-hero">
                <div class="container service-hero__container">
                    <div class="service-hero__content">
                        <span class="service-hero__eyebrow">Контакты KBR Mebel</span>
                        <h1>Мебель на заказ в Нальчике: расчет, замер и консультация</h1>
                        <p>Свяжитесь с нами, чтобы обсудить кухню, шкаф-купе, гардеробную, детскую, спальню, гостиную или коммерческую мебель по индивидуальному проекту.</p>
                        <div class="service-hero__actions">
                            <a class="btn btn-main" href="tel:{{ $mainset->tel }}">{{ $mainset->tel }}</a>
                            <a class="btn btn-main service-hero__secondary" href="{{ $mainset->watsap }}" target="_blank" rel="noopener">WhatsApp</a>
                        </div>
                    </div>

                    <div class="service-contact-card">
                        <h2>Как связаться</h2>
                        <a href="tel:{{ $mainset->tel }}">{{ $mainset->tel }}</a>
                        <a href="mailto:{{ $mainset->email }}">{{ $mainset->email }}</a>
                        <a href="{{ $mainset->watsap }}" target="_blank" rel="noopener">Написать в WhatsApp</a>
                        <p>Нальчик, Кабардино-Балкарская Республика</p>
                        <p>Замер, проектирование, изготовление, доставка и монтаж мебели под ключ.</p>
                    </div>
                </div>
            </section>

            <section class="service-section service-section--muted">
                <div class="container service-columns">
                    <div>
                        <span class="service-section__label">Карта</span>
                        <h2>Работаем по Нальчику и Кабардино-Балкарии</h2>
                        <p class="service-section__text">Если у объекта нестандартная планировка, лучше сразу прислать фото, размеры или короткое описание задачи в WhatsApp.</p>
                    </div>

                    <div class="service-map">
                        <div data-map="{{ $mainset->map }}" data-icon="{{ $seting->getImage() }}" id="map-test" class="map service-map__frame"></div>
                        <script src="https://api-maps.yandex.ru/2.1/?apikey=f7db7337-3ada-4e38-9eb9-336c076d79ba&lang=ru_RU"></script>
                    </div>
                </div>
            </section>

            @include('ssm.components.site-cta-new', [
                'previewContent' => $previewContent,
                'ctaPlaceholder' => 'Например: нужна кухня на заказ в Нальчике, есть размеры и фото помещения',
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
