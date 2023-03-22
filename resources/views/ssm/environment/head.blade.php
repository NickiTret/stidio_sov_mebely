<!DOCTYPE html>
<html lang="ru" class="page">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="theme-color" content="#111111" />
    <meta name="format-detection" content="telephone=no">
    <meta name="yandex-verification" content="40880704fc80f5a4" />
    <link rel="icon" href="{{ $seting->getImage() }}" type="image/svg+xml">
    <title>Студия Современной Мебели | {{ $seting->title }}</title>
    <meta property="og:description" content="{{ $seting->descriptions }}">
    <meta name="description" content="{{ $seting->descriptions }}">
    <meta name="keywords" content="{{ $seting->keywords }}" />
    <link rel="preload" href="fonts/MullerRegular.woff2" as="font" type="font/woff2" crossorigin />

    <link rel="preload" href="fonts/Montserrat-Light.woff" as="font" type="font/woff" crossorigin />
    <link rel="preload" href="fonts/Montserrat-Medium.woff" as="font" type="font/woff" crossorigin />
    <link rel="preload" href="fonts/Montserrat-Regular.woff" as="font" type="font/woff" crossorigin />
    <link rel="preload" href="fonts/Montserrat-SemiBold.woff" as="font" type="font/woff" crossorigin />

    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}?v=1" />
    <link rel="stylesheet" href="{{ asset('css/main.css') }}?v=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script defer src="{{ asset('js/main.js') }}?v=1"></script>
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
</head>
