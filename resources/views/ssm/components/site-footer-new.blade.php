@php
    $footer = $previewContent['footer'] ?? [];
    $footerLinkHref = $footerLinkHref ?? route('gallery');
    $footerLinkLabel = $footerLinkLabel ?? 'Смотреть все проекты';
    $servicePages = collect(\App\Support\SeoLandingPages::all())->keyBy('gallery_slug');
@endphp

<footer class="preview-footer" id="footer">
    <div class="container preview-footer__container">
        <div class="preview-footer__intro">
            <span class="preview-footer__eyebrow">{{ $footer['eyebrow'] ?? '' }}</span>
            <h2>{{ $footer['title'] ?? '' }}</h2>
            <p>{{ $footer['description'] ?? '' }}</p>
        </div>

        <div class="preview-footer__grid">
            <div class="preview-footer__card">
                <h3>Направления</h3>
                <div class="preview-footer__links">
                    @foreach ($groups as $group)
                        @php
                            $servicePage = $servicePages->get($group->slug);
                        @endphp
                        <a href="{{ $servicePage ? route('service.page', $servicePage['slug']) : route('gallery.category', $group->slug) }}">{{ $group->display_title }}</a>
                    @endforeach
                    <a href="{{ route('contacts') }}">Контакты</a>
                </div>
            </div>

            <div class="preview-footer__card">
                <h3>Контакты</h3>
                <div class="preview-footer__contacts">
                    <a href="tel:{{ $mainset->tel }}">{{ $mainset->tel }}</a>
                    <a href="{{ $mainset->watsap }}" target="_blank" rel="noopener">WhatsApp</a>
                    <a href="mailto:{{ $mainset->email }}">{{ $mainset->email }}</a>
                </div>
                <p class="preview-footer__city">{{ $footer['city'] ?? '' }}</p>
                <p class="preview-footer__city">Нальчик, Кабардино-Балкарская Республика</p>
            </div>
        </div>

        <div class="preview-footer__map-wrap">
            <div data-map="{{ $mainset->map }}" data-icon="{{ $seting->getImage() }}" id="map-test" class="map preview-footer__map"></div>
            <script src="https://api-maps.yandex.ru/2.1/?apikey=f7db7337-3ada-4e38-9eb9-336c076d79ba&lang=ru_RU"></script>
        </div>

        <div class="preview-footer__bottom">
            <p>© 2000 - {{ \Carbon\Carbon::now()->year }} | {{ $mainset->footer }}</p>
            <a href="{{ $footerLinkHref }}">{{ $footerLinkLabel }}</a>
        </div>
    </div>
</footer>
