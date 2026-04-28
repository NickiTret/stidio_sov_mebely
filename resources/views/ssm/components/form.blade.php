<div class="modal overlay" id="modal-contacts">
    <div class="modal__wrapper">
        <span class="modal__eyebrow">Обсудить проект</span>
        <h2>Расскажите, какую мебель вы хотите заказать</h2>
        <p class="modal__lead">Подскажем по материалам, срокам и формату проекта для кухни, шкафа, гардеробной, кабинета или другой мебели на заказ.</p>
        <button class="btn btn-close" aria-label="Close modal" data-custom-close>
        </button>
        <form action="{{ route('message.store') }}" id="modal-form" method="post" class="form modal__form js-contact-form" novalidate>
            @csrf
            <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}#contact-form">
            <input type="hidden" name="form_started_at" value="{{ now()->timestamp }}">
            <label class="form-honeypot" aria-hidden="true">
                <span>Оставьте это поле пустым</span>
                <input type="text" name="website" tabindex="-1" autocomplete="off">
            </label>
            <label class="form__label">
                <input type="text" name="name" id="modal-name" class="input-reset form__input" placeholder="Имя">
            </label>
            <label class="form__label">
                <input type="tel" name="tel" id="modal-tel" class="input-reset form__input"
                    placeholder="Телефон">
            </label>
            <label class="form__label">
                <input type="email" name="mail" id="modal-email" class="input-reset form__input" placeholder="Почта">
                <input type="hidden" name="secret" id="modal-secret" value="">
            </label>
            <label class="form__label">
                <textarea type="text" name="content" class="input-reset form__input" placeholder="Например: шпонированная кухня, шкаф-купе, гардеробная или кабинет"></textarea>
            </label>
            <button class="btn-reset btn btn-main btn-send" type="submit">Отправить</button>
            <p class="modal__note">Нажимая на кнопку, вы соглашаетесь на обработку персональных данных.</p>
        </form>
    </div>
</div>
