
<footer class="footer" id="footer">
    <div data-aos="slide-up" data-aos-dalay="50" data-aos-duration="1000" class="container footer-container">
        <h2>Звоните или оставляйте заявку. Будем рады <span class="mark">ответить</span></h2>
        <div class="footer__wrapper">
            <div class="footer-contact">
                <ul class="list-reset footer-social__list">
                    <li class="footer-social__item">
                        <a href="tel:{{ $mainset->tel }}" class="footer__phone"><svg class="">
                                <use xlink:href="/img/sprite.svg#phone__number"></use>
                            </svg>
                            {{ $mainset->tel }}</a>
                    </li>
                    <li class="footer-social__item">
                        <a href="{{ $mainset->watsap }}" rel="noopener" target="_blank">
                            <svg class="">
                                <use xlink:href="/img/sprite.svg#whatsapp"></use>
                            </svg>
                            WhatsApp
                        </a>
                    </li>
                    <li class="footer-social__item">
                        <a href="mailto:{{ $mainset->email }}" rel="noopener" target="_blank">
                            <svg class="">
                                <use xlink:href="/img/sprite.svg#email"></use>
                            </svg>
                            {{ $mainset->email }}
                        </a>
                    </li>
                    {{-- <li class="footer-social__item">
              <a href="{{ $mainset->telegram }}" rel="noopener" target="_blank">
                    <svg class="">
                        <use xlink:href="/img/sprite.svg#telegramm__foo"></use>
                    </svg>
                    Telegram
                    </a>
                    </li> --}}
                </ul>
            </div>
            <div class="footer-map">
                <div data-map="{{ $mainset->map }}" data-icon="{{ $seting->getImage() }}" id="map-test" class="map">
                </div>
                <script src="https://api-maps.yandex.ru/2.1/?apikey=f7db7337-3ada-4e38-9eb9-336c076d79ba&lang=ru_RU"></script>
            </div>
        </div>
        <p class="footer__copyright">
            © 2000 - 2023 | {{ $mainset->footer }} | Разработка сайта <a target="_blank" href="https://infonickweb.ru/">NickWeb</a>
        </p>
    </div>
</footer>
