<footer class="footer">
    <div class="container footer-container">
      <h2>Остались <span class="mark">вопросы?</span></h2>
      <h3>Будем рады ответить</h3>
      <div class="footer__wrapper">
        <div class="footer-contact">
          <a href="tel:{{ $mainset->tel }}" class="footer__phone"
            ><svg class="">
              <use xlink:href="/img/sprite.svg#phone__number"></use>
            </svg>
            {{ $mainset->tel }}</a
          >
          <button type="button" class="btn btn-main" data-custom-open="modal-contacts">Заказать звонок</button>
          <ul class="list-reset footer-social__list">
            <li class="footer-social__item">
              <a href="{{ $mainset->watsap }}">
                <svg class="">
                  <use xlink:href="/img/sprite.svg#whatsapp"></use>
                </svg>
                WhatsApp
              </a>
            </li>
            <li class="footer-social__item">
              <a href="{{ $mainset->telegram }}">
                <svg class="">
                  <use xlink:href="/img/sprite.svg#telegramm__foo"></use>
                </svg>
                Telegram
              </a>
            </li>
          </ul>
        </div>
        <div class="footer-map">
          <script
            type="text/javascript"
            charset="utf-8"
            async
            src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3Ac45a179216bb33cde6670ce2669e7c6ff2ff3debe5d88539ded686529313033a&amp;width=754&amp;height=400&amp;lang=ru_RU&amp;scroll=true"
          ></script>
        </div>
      </div>
      <p class="footer__copyright">
        © 2000 - 2023 | {{ $mainset->footer }}
      </p>
    </div>
  </footer>
