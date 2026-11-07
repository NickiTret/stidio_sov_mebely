{{--
    Галочка согласия на обработку ПД — отдельное действие пользователя, а не «нажимая на кнопку…».
    Обязательна на сервере (Message\StoreRequest: consent => accepted) и в JS (validate.js).
    Ссылки на документы показываются, только если их текст заполнен в админке.
--}}
@php
    $consentPage = \App\Support\SiteCache::page(\App\Models\Page::CONSENT);
    $privacyPage = \App\Support\SiteCache::page(\App\Models\Page::PRIVACY);
@endphp
<label class="form-consent {{ $consentClass ?? '' }}">
    <input type="checkbox" name="consent" value="1" class="form-consent__input" required @checked(old('consent'))>
    <span class="form-consent__text">
        Я даю
        @if ($consentPage)
            <a class="legal-link" href="{{ route('consent') }}" target="_blank">согласие на обработку персональных данных</a>
        @else
            согласие на обработку персональных данных
        @endif
        @if ($privacyPage)
            в соответствии с <a class="legal-link" href="{{ route('privacy') }}" target="_blank">политикой конфиденциальности</a>
        @endif
    </span>
</label>
