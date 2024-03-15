<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!--Кодировка сайта-->
    <meta charset="utf-8">

    <!-- Для медиа запросов-->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!--Название проекта можно задать в файле .env-->
    <title>{{ config('app.name', 'Laim Аренда') }}</title>
    <meta name="description" content="Аренда недвижимости">

    <!-- FAVICON PNG -->
    <link rel="icon" href="/img/siteImg/allImg/logo.svg" type="image/svg">

    <!-- Добавляем Файл Манифеста конфиг. будущего приложения - Для Создания PWA приложения -->
    <!-- Это основной цвет приложения который был задан в манифесте -->
    <meta name="theme-color" content="#ffffff" />
    <!-- Это прописываем для apple -->
    <link rel="apple-touch-icon" href="/img/siteImg/allImg/logo.svg">
    <link rel="manifest" href="/manifest.json">

    <!-- Главный js файл и Scss файл -->
    @vite(['resources/js/app.js'])

    <!-- Передача значения config('app.url') в JavaScript -->
    <script>window.appUrl = "{{ config('app.url') }}";</script>

    <!-- Подключаю библиотеку JS SDK - Freedom Pay - для приема платежей-->
    <script>
        (function (f, p, s, d, k) {
            d = f.createElement(p);
            k = f.getElementsByTagName(p)[0];
            d.src = 'https://cdn.freedompay.money/sdk/js-sdk-1.0.0.js';
            k.parentNode.insertBefore(d, k);
        })(document, 'script');
    </script>
</head>
<body>
    <div id="app"></div>
</body>

</html>
