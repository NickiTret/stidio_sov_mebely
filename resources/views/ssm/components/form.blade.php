<div class="modal overlay" id="modal-contacts">
    <div class="modal__wrapper">
        <h2>Напишите нам</h2>
        <button class="btn btn-close" aria-label="Close modal" data-custom-close>
        </button>
        <form action="{{ route('message.store') }}" id="form" method="post" class="form">
            @csrf
            <label class="form__label">
                <input type="text" name="name" id="name" class="input-reset form__input" placeholder="Имя" disabled>
            </label>
            <label class="form__label">
                <input type="tel" name="tel" id="tel" class="input-reset form__input" disabled
                    placeholder="Телефон">
            </label>
            <label class="form__label">
                <input type="email" name="mail" id="email" class="input-reset form__input" placeholder="Почта" disabled>
                <input type="hidden" name="secret" id="secret" value="" disabled>
            </label>
            <label class="form__label">
                <textarea type="text" name="content" class="input-reset form__input" placeholder="Сообщение" disabled></textarea>
            </label>
            <button class="btn-reset btn btn-main btn-send" type="submit">Отправить</button>
        </form>
    </div>
</div>
