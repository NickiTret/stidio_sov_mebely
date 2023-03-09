<!DOCTYPE html>
<html lang="ru" class="page">
  <head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <meta name="theme-color" content="#111111" />
  <meta name="format-detection" content="telephone=no">
  <title>Студия Современной Мебели | {{ $seting->title }}</title>
  <meta property="og:description" content="{{ $seting->descriptions }}">
  <meta name="keywords" content="{{ $seting->keywords }}" />
  <link
    rel="preload"
    href="fonts/MullerRegular.woff2"
    as="font"
    type="font/woff2"
    crossorigin
  />
  <link rel="stylesheet" href="{{ asset('css/vendor.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"
  />
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
  <script defer src="{{ asset('js/main.js') }}"></script>
</head>
