//Наше название КЭША
const CACHE = 'cache-v1';

//Для начала мы кэшируем все наши статические ресурсы
const staticAssets = [
    // Основная страница и файлы приложения
    '/',
    '/manifest.json',

    // Статические изображения
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

    // Языковые файлы
    '/lang/en.json',
    '/lang/kz.json',
    '/lang/ru.json',

    // Шрифты
    '/fonts/OpenSans/OpenSans-Regular.ttf',
    '/fonts/YandexSans/YandexSansText-Regular.ttf',
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
        if(
            pathname == '/api/getAllAds'
            || pathname == '/api/getAllAdsYandexCluster'
            || pathname == '/api/getAllAdsInYandexCluster'
            || pathname == '/api/getOneAds'
            || pathname == '/api/countAds'
            || pathname == '/api/getUserAds'
            || pathname == '/api/getProjectData'
        ){
            skipGetRequest = true;
        }

        // "Эти GET запросы пропустим сразу в сеть
        if (
            path == '/api/sanctum/csrf-cookie' ||
            path == '/api/auth/user' ||
            path == '/api/user/getPayLink' ||
            path.startsWith('/ping.txt') // 👈 добавил сюда
        ) {
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
                const index_page = await caches.match('/');
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
                const index_page = await caches.match('/');
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
            const index_page = await caches.match('/');
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

