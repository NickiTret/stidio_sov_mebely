@php
    $cta = $previewContent['cta'] ?? [];
    $ctaPlaceholder = $ctaPlaceholder ?? 'Например: шпонированная кухня, шкаф, гардеробная или кабинет';
@endphp

<section class="preview-cta" id="contact-form">
    <div class="container">
        @include('ssm.components.form-status')

        <div class="preview-cta__content">
            <span class="preview-cta__eyebrow">{{ $cta['eyebrow'] ?? '' }}</span>
            <h2>{{ $cta['title'] ?? '' }}</h2>
            <p>{{ $cta['description'] ?? '' }}</p>

            <ul class="list-reset preview-cta__benefits">
                @foreach (($cta['benefits'] ?? []) as $benefit)
                    <li>{{ $benefit }}</li>
                @endforeach
            </ul>
        </div>

        <form action="{{ route('message.store') }}" method="post" class="preview-cta__form js-contact-form" novalidate>
            @csrf
            <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}#contact-form">
            <input type="hidden" name="form_started_at" value="{{ now()->timestamp }}">
            <label class="form-honeypot" aria-hidden="true">
                <span>Оставьте это поле пустым</span>
                <input type="text" name="website" tabindex="-1" autocomplete="off">
            </label>
            <label class="preview-cta__field">
                <span>Ваше имя</span>
                <input type="text" name="name" class="input-reset" placeholder="Как к вам обращаться">
            </label>
            <label class="preview-cta__field">
                <span>Телефон</span>
                <input type="tel" name="tel" class="input-reset" placeholder="+7 (___) ___-__-__">
            </label>
            <label class="preview-cta__field">
                <span>E-mail</span>
                <input type="email" name="mail" class="input-reset" placeholder="mail@example.com">
            </label>
            <label class="preview-cta__field preview-cta__field--full">
                <span>Что хотите заказать <em>(необязательно)</em></span>
                <textarea name="content" class="input-reset" placeholder="{{ $ctaPlaceholder }}"></textarea>
            </label>
            <button class="btn btn-main preview-cta__submit" type="submit">{{ $cta['submit_text'] ?? 'Получить расчет' }}</button>
            <p class="preview-cta__note">{{ $cta['note'] ?? '' }}</p>
        </form>
    </div>
</section>
