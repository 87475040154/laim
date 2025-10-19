//Наше название КЭША
const CACHE = 'cache-v1';

//Для начала мы кэшируем все наши статические ресурсы
const staticAssets = [

    // Основные данные
    './',
    'index.php',
    '/resources/views/app.blade.php',
    '/css/app.css',
    '/js/app.js',
    'manifest.json',

    //Занашу в кеш все статические фото
    '/img/siteImg/allImg/logo.svg',
    '/img/siteImg/allImg/apartment.jpg',
    '/img/siteImg/allImg/chat-bg.png',
    '/img/siteImg/allImg/no-image-buildings.png',
    '/img/siteImg/allImg/kz.png',
    '/img/siteImg/allImg/ru.png',
    '/img/siteImg/allImg/en.png',
    '/favicon.ico',
    '/img/siteImg/menuImg/1.svg',
    '/img/siteImg/menuImg/2.svg',
    '/img/siteImg/menuImg/3.svg',
    '/img/siteImg/menuImg/4.svg',
    '/img/siteImg/menuImg/5.svg',
    '/img/siteImg/menuImg/6.svg',
    '/img/siteImg/menuImg/7.svg',
    '/img/siteImg/menuImg/8.svg',

    //Язык
    '/lang/en.json',
    '/lang/kz.json',
    '/lang/ru.json',

    // Шрифты
    '/fonts/OpenSans/OpenSans-Regular.ttf',
    '/fonts/YandexSans/YandexSansText-Regular.ttf',

    // Кэширую проект
    '/build/assets/1-43b3fcd7.svg',
    '/build/assets/2-2d9b4c31.svg',
    '/build/assets/3-e1e821fb.svg',
    '/build/assets/4-5112ddbf.svg',
    '/build/assets/5-233feeae.svg',
    '/build/assets/6-b27d0cc0.svg',
    '/build/assets/7-0ad6c393.svg',
    '/build/assets/8-176b3198.js',
    '/build/assets/8-ecb1cab4.svg',
    '/build/assets/_plugin-vue_export-helper-6f2d444f.js',
    '/build/assets/AddAdsForm-25f21cf9.css',
    '/build/assets/AddAdsForm-952b4010.js',
    '/build/assets/AddAdsMenu-4f126855.js',
    '/build/assets/AddAdsMenu-c6001574.css',
    '/build/assets/ads-3178eab2.js',
    '/build/assets/AdsFilterComponent-a6407fca.css',
    '/build/assets/AdsFilterComponent-bf542b04.js',
    '/build/assets/AdsPreviewComponent-0a850561.css',
    '/build/assets/AdsPreviewComponent-7d12c23b.js',
    '/build/assets/AlertError-164578ce.js',
    '/build/assets/app-cb25614e.css',
    '/build/assets/app-db5fcc14.js',
    '/build/assets/AppInstall-310c9127.js',
    '/build/assets/auth-251dac5b.js',
    '/build/assets/AuthComponent-4b2a2eb6.js',
    '/build/assets/AuthComponent-a15d1316.css',
    '/build/assets/axios-a5444ce4.js',
    '/build/assets/bootstrap-icons-966620f9.woff2',
    '/build/assets/bootstrap-icons-c6569d46.woff',
    '/build/assets/BueAdsPayMethod-eaea4fd7.css',
    '/build/assets/BueAdsPayMethod-f8a7343e.js',
    '/build/assets/BueResult-2b160f3c.js',
    '/build/assets/ChatComponent-37bae1cb.css',
    '/build/assets/ChatComponent-57d226d6.js',
    '/build/assets/checkInternet-26a15e08.js',
    '/build/assets/Contacts-76f83251.js',
    '/build/assets/Filter-eac84a5d.js',
    '/build/assets/ImageComponent-9f73404f.css',
    '/build/assets/ImageComponent-cd663c94.js',
    '/build/assets/images-1a68d104.js',
    '/build/assets/index.esm-82b126ca.js',
    '/build/assets/index-2c93ffca.js',
    '/build/assets/Index-8fdfa94e.css',
    '/build/assets/Index-85025b98.js',
    '/build/assets/IndexComponent-64f019f9.js',
    '/build/assets/IndexComponent-94c968a7.css',
    '/build/assets/keyboard-daa71e83.js',
    '/build/assets/KZLocation-5fd0a21f.js',
    '/build/assets/KZLocationComponent-5b9ac470.css',
    '/build/assets/KZLocationComponent-ced2bbd0.js',
    '/build/assets/LangComponent-80c3cf1c.js',
    '/build/assets/laravel-vue-pagination.es-a4252953.js',
    '/build/assets/logo-741e9f30.js',
    '/build/assets/logo-d22c7765.svg',
    '/build/assets/logo_visa-bf4abe8b.png',
    '/build/assets/MainComponent-bced5b7d.js',
    '/build/assets/materialdesignicons-webfont-6d5e4be4.woff',
    '/build/assets/materialdesignicons-webfont-739dc70d.woff2',
    '/build/assets/materialdesignicons-webfont-c02d41ce.ttf',
    '/build/assets/materialdesignicons-webfont-f5966bae.eot',
    '/build/assets/moment-e4e61832.js',
    '/build/assets/mousewheel-02677f44.js',
    '/build/assets/mousewheel-bb68f9eb.css',
    '/build/assets/MyAccountComponent-39d4e7de.js',
    '/build/assets/MyAccountComponent-64060daa.css',
    '/build/assets/no-image-buildings-371b5421.js',
    '/build/assets/no-image-buildings-ec82983f.png',
    '/build/assets/OneAdsBottomOffcanvas-1c64faa1.js',
    '/build/assets/OneAdsBottomOffcanvas-8867b60f.css',
    '/build/assets/OneAdsComponent-2ab7701e.js',
    '/build/assets/OneAdsComponent-01410ff2.css',
    '/build/assets/OpenSans-Regular-325f509b.ttf',
    '/build/assets/php_en-641b0c66.js',
    '/build/assets/php_kz-a4d011c3.js',
    '/build/assets/php_ru-086ea159.js',
    '/build/assets/pinia-c26f6e9d.js',
    '/build/assets/PlatnieUslugi-8ff28b76.js',
    '/build/assets/PlatnieUslugi-37ec66bb.css',
    '/build/assets/PolitikaKonfidencialnosti-399b86c5.js',
    '/build/assets/PravilaSaita-ef88cf3f.js',
    '/build/assets/Settings-7f7902d9.js',
    '/build/assets/Settings-176ddff1.css',
    '/build/assets/updateDateLocale-94fca1c4.js',
    '/build/assets/UserAds-80c79032.js',
    '/build/assets/UserAds-290d577c.css',
    '/build/assets/vform.es-314fa623.js',
    '/build/assets/vue-draggable-next.esm-bundler-d6beb74e.js',
    '/build/assets/vue-tel-input-027b1098.css',
    '/build/assets/vue-tel-input-8159f9af.js',
    '/build/assets/vue-yandex-maps.esm-7be01412.js',
    '/build/assets/YandexMap-cee342ad.css',
    '/build/assets/YandexMap-e7303e5f.js',
    '/build/assets/YandexMapCluster-65142f0c.css',
    '/build/assets/YandexMapCluster-e1b8646e.js',
    '/build/assets/YandexMapClusterShowAds-8fa4813d.js',
    '/build/assets/YandexMapClusterShowAds-1872b62e.css',
    '/build/assets/YandexSansText-Regular-2875d597.ttf',
]

//Первое. Установка Service Worker и кэширование всех статических данных
self.addEventListener('install', (event)=>{
    event.waitUntil(
        caches.open( CACHE ).then( cache => { //При установки или открытии сайта открываем наш созданный КЭШ и заносим все наши статические файлы
                cache.addAll( staticAssets )
            }).then(()=>{
                self.skipWaiting();
            })
    )
});

//Второе. Активация Service Worker // Активируется каждый раз при закрытии всех вкладок, удаляем название старого кэша
self.addEventListener('activate', async event=>{

    //Получим все ключи - это название наших кэшей
    //Если я буду изменять SW то чтоб он обновился у всех пользователей нужно поменять название кэша и все
    const cachesKeys = await caches.keys()

    //Переберем и удалим не действующие
    const checkKeys = cachesKeys.map(async key=>{
        if(CACHE !== key){
            await caches.delete(key)
        }
    })

    //Дождемся удаления
    await Promise.all(checkKeys);

    //Это указывает чтоб FETCH применялся сразу как только пользователь попадет на сайт будут кэшироваться динамические запросы
    return self.clients.claim();

});


//-------------------- СТРАТЕГИЯ КЭШИРОВАНИЯ ------------------------

// Получение значения appUrl из глобальной области видимости
let appUrl;
self.addEventListener('message', event => {
    if (event.data && event.data.appUrl) {
        appUrl = event.data.appUrl;
    }
});

//При каждом запросе к серверу SW - Перехватывает этот запрос, и возвращает ответ либо с кэша либо с сервера либо страницу что нет интернета
self.addEventListener('fetch', async event=>{

    const requestUrl = event.request.url;

    //Перехватываю запросы только моего домена
    if (requestUrl.startsWith(appUrl)) {


        //Некоторые GET запросы я отправляю сразу на сервер а не в кэш для получения свежих данных.
        //Если в этот момент нет СЕТИ то данные будут взяты и КЭША
        let skipGetRequest = false;
        let {pathname} = new URL(event.request.url);


        //Эти GET запросы мы отпровляем сразу в сеть - Так как нам нужны сразу свежие данные
        if(    pathname == '/api/getAllAds'
            || pathname == '/api/getAllAdsYandexCluster'
            || pathname == '/api/getAllAdsInYandexCluster'
            || pathname == '/api/getOneAds'
            || pathname == '/api/getUserAds'
            || pathname == '/api/getProjectData'
        ){
            skipGetRequest = true;
        }

        // Если это запрос на проверку интернета с internetStore пропустим его
        if (pathname == '/api/sanctum/csrf-cookie' || pathname == '/api/auth/user' || pathname == '/api/checkInternet' || pathname == '/api/user/getPayLink') {
            return event.respondWith(fetch(event.request));
        }

        if (event.request.method == 'GET') {
            if (!skipGetRequest) {
               return event.respondWith(cacheFirstGetRequest(event.request));
            } else {
               return event.respondWith(networkFirstGetRequest(event.request));
            }
        }
        else {
           return event.respondWith(fetch(event.request));
        }
    }

});

// GET - Запросы для получения сначала в КЭШ CACHE FIRST
async function cacheFirstGetRequest(req) {

        const cache = await caches.open(CACHE);
        const checkCache = await cache.match(req);

        if (checkCache) {

            // Выполним сетевой запрос для обновления кэша
            fetch(req).then(async (networkResponse) => {
                if (networkResponse.ok) {
                    // Обновляем кэш с новыми данными
                    await cache.put(req, networkResponse.clone());
                }
            });

            return checkCache;
        }
        else {
            try{
                // Попытка выполнить GET-запрос к серверу
                const res = await fetch(req);

                // Если ответ получен успешно
                if(res.ok){
                    // Заносим его в кэш
                    await cache.put(req, res.clone());
                    // Возвращаем полученный ответ
                    return res;
                }
            }
                //Если сервер не доступен вообще сработате этот код - Тоесть вообще нет связи
            catch (error){
                // Если кеш не найден и нет соединения с сервером вернем эти данные
                const index_page = await caches.match('index.php');
                if(index_page != null){
                    return index_page;
                }else{
                    const errorResponse = new Response('Нет соединения с сервером', {
                        status: 503, // 503 Service Unavailable
                        statusText: 'Service Unavailable',
                    });
                    return errorResponse;
                }
            }

        }
    }

// Отправка GET-запроса сразу на СЕРВЕР NETWORK FIRST
async function networkFirstGetRequest(req) {
    const cache = await caches.open(CACHE);

    try{
        // Попытка выполнить GET-запрос к серверу
        const res = await fetch(req);

        // Если ответ получен успешно
        if(res.ok){

            // Заносим его в кэш
            await cache.put(req, res.clone());

            // Возвращаем полученный ответ
            return res;

        }
        else{

            // Попробуем получить в кеше
            const checkCache = await cache.match(req);

            // Если кэш найден вернем его
            if (checkCache) {
                return checkCache;
            } else{
                // Если кеш не найден и нет соединения с сервером вернем эти данные
                const index_page = await caches.match('index.php');
                if(index_page != null){
                    return index_page;
                }
                else{
                    const errorResponse = new Response('Нет соединения с сервером', {
                        status: 503, // 503 Service Unavailable
                        statusText: 'Service Unavailable',
                    });
                    return errorResponse;
                }
            }
        }

    }
    //Если сервер не доступен вообще сработате этот код - Тоесть вообще нет связи
    catch (error){
        // Попробуем получить в кеше
        const checkCache = await cache.match(req);

        // Если кэш найден вернем его
        if (checkCache) {
            return checkCache;
        }
        else{
            // Если кеш не найден и нет соединения с сервером вернем эти данные
            const index_page = await caches.match('index.php');
            if(index_page != null){
                return index_page;
            }
            else{
                const errorResponse = new Response('Нет соединения с сервером', {
                    status: 503, // 503 Service Unavailable
                    statusText: 'Service Unavailable',
                });
                return errorResponse;
            }
        }
    }

}

