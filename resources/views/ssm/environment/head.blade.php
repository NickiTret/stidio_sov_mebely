<!DOCTYPE html>
<html lang="ru" class="page">

<head>
    @php
        $routeName = request()->route()?->getName();
        $defaultImage = $seting->getImage();
        $defaultKeywords = $seting->keywords;
        $customSeoData = $seoData ?? null;

        $seo = $customSeoData ?? match ($routeName) {
            'home' => [
                'title' => 'Мебель на заказ в Нальчике | Кухни, шкафы, гардеробные | KBR Mebel',
                'description' => 'Изготавливаем мебель на заказ в Нальчике: кухни, шкафы-купе, гардеробные, спальни и детские. Шпон, МДФ, эмаль, индивидуальные размеры, установка под ключ.',
                'keywords' => 'мебель на заказ Нальчик, кухни на заказ Нальчик, шкафы купе Нальчик, гардеробные Нальчик, мебель из шпона Нальчик',
            ],
            'gallery' => [
                'title' => 'Галерея мебели на заказ в Нальчике | Наши проекты | KBR Mebel',
                'description' => 'Фотографии выполненных проектов мебели на заказ в Нальчике: кухни, шкафы-купе, гардеробные, спальни, детские и кабинеты.',
                'keywords' => 'галерея мебели Нальчик, проекты мебели на заказ, кухни на заказ Нальчик фото, шкафы купе Нальчик фото',
            ],
            default => [
                'title' => 'Мебель на заказ в Нальчике | ' . $seting->title,
                'description' => $seting->descriptions,
                'keywords' => $defaultKeywords,
            ],
        };

        $seoImage = $seo['image'] ?? $defaultImage;
        $schemaBusiness = null;

        if (!empty($mainset)) {
            $schemaBusiness = [
                '@context' => 'https://schema.org',
                '@type' => 'FurnitureStore',
                'name' => 'Студия Современной Мебели',
                'url' => url('/'),
                'image' => $seoImage,
                'telephone' => $mainset->tel,
                'email' => $mainset->email,
                'areaServed' => [
                    '@type' => 'City',
                    'name' => 'Нальчик',
                ],
                'contactPoint' => [[
                    '@type' => 'ContactPoint',
                    'telephone' => $mainset->tel,
                    'email' => $mainset->email,
                    'contactType' => 'customer service',
                    'areaServed' => 'RU-KB',
                    'availableLanguage' => ['ru'],
                ]],
            ];

            if (!empty($mainset->watsap)) {
                $schemaBusiness['sameAs'] = [$mainset->watsap];
            }
        }
    @endphp
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="theme-color" content="#111111" />
    <meta name="format-detection" content="telephone=no">
    <meta name="yandex-verification" content="40880704fc80f5a4" />
    <link rel="icon" href="{{ $defaultImage }}" type="image/svg+xml">
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    <meta name="keywords" content="{{ $seo['keywords'] }}" />
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['description'] }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:locale" content="ru_RU">
    <link rel="preload" href="fonts/MullerRegular.woff2" as="font" type="font/woff2" crossorigin />

    <link rel="preload" href="fonts/Montserrat-Light.woff" as="font" type="font/woff" crossorigin />
    <link rel="preload" href="fonts/Montserrat-Medium.woff" as="font" type="font/woff" crossorigin />
    <link rel="preload" href="fonts/Montserrat-Regular.woff" as="font" type="font/woff" crossorigin />
    <link rel="preload" href="fonts/Montserrat-SemiBold.woff" as="font" type="font/woff" crossorigin />

    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}?v=1222222" />
    <link rel="stylesheet" href="{{ asset('css/main.css') }}?v=1222222" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script defer src="{{ asset('js/main.js') }}?01222222"></script>
    <script type="text/javascript">
        (function(m, e, t, r, i, k, a) {
            m[i] = m[i] || function() {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            for (var j = 0; j < document.scripts.length; j++) {
                if (document.scripts[j].src === r) {
                    return;
                }
            }
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(
                k, a)
        })
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(92774377, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true,
            webvisor: true
        });
    </script>
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/92774377" style="position:absolute; left:-9999px;" alt="" />
        </div>
    </noscript>
    @if (!empty($schemaBusiness))
        <script type="application/ld+json">
            {!! json_encode($schemaBusiness, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endif
</head>
