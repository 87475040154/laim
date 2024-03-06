import { createWebHistory, createRouter } from 'vue-router'
import axios from "axios";

const routes = [

    //Главная страница
    {
        path: '/',
        component: ()=> import('../components/MainComponent.vue'),
        name: 'home',
        meta: {
            title: 'Главная страница'
        },

        children: [

            //Вывод всех объявлений
            {
                path: '/allAds/:table_name/:page',
                component: () => import('../components/IndexComponent.vue'),
                name: 'allAds',
                meta: {
                    title: 'Аренда недвижимости'
                },

                children: [

                    //Вывод Одного объявление
                    {
                        path: '/oneAds/:table_name/:ads_id',
                        component: ()=> import('../components/allComponents/oneAds/OneAdsComponent.vue'),
                        name: 'oneAds',
                        meta: {
                            title: 'Недвижимость'
                        },

                        children: [

                            //Фото
                            {
                                path: '/imageOneAds',
                                component: ()=> import('../components/allComponents/ImageComponent.vue'),
                                name: 'imageOneAds',
                                meta: {
                                    title: 'Фото'
                                },
                            },

                            //Карта
                            {
                                path: '/mapOneAds',
                                component: ()=> import('../components/allComponents/map/YandexMap.vue'),
                                name: 'yandexMapOneAds',
                                meta: {
                                    title: 'Объект на карте'
                                },
                            },

                            //Нижний OffCanvas
                            {
                                path: '/oneAds/:table_name/:ads_id/:type',
                                component: ()=> import('../components/allComponents/oneAds/OneAdsBottomOffcanvas.vue'),
                                name: 'oneAdsBottomOffCanvas',
                                meta: {
                                },
                            },

                            //Lang
                            {
                                path: '/oneAds/:table_name/:ads_id/lang',
                                component: ()=> import('../components/allComponents/LangComponent.vue'),
                                name: 'langOneAds'
                            },
                        ]
                    },

                    //Фильтр
                    {
                        path: '/filter/:table_name',
                        component: ()=> import('../components/allComponents/AdsFilterComponent.vue'),
                        name: 'filter',
                        meta: {
                            title: 'Фильтр'
                        },

                        children: [
                            //Локации
                            {
                                path: '/filter/:table_name/location/:locationId/:stepLocation',
                                component: ()=> import('../components/allComponents/KZLocationComponent.vue'),
                                name: 'location',
                                meta: {
                                    title: 'Местоположение',
                                },
                            },
                        ]
                    },

                    //Авторизация на сайте
                    {
                        path: '/auth',
                        component: ()=> import('../components/allComponents/AuthComponent.vue'),
                        name: 'auth',
                        meta: {
                            title: 'Вход на сайт',
                        },
                    },

                    //Мой кабинет
                    {
                        path: '/myAccount',
                        component: ()=> import('../components/allComponents/user/MyAccountComponent.vue'),
                        name: 'myAccount',
                        meta: {
                            title: 'Мой кабинет',
                            auth: true
                        },
                        children: [
                            //Настройки
                            {
                                path: '/settings',
                                component: ()=> import('../components/allComponents/user/Settings.vue'),
                                name: "settings",
                                meta: {
                                    auth: true,
                                    title: 'Настройка аккаунта'
                                }
                            },
                        ]
                    },

                    //Чаты
                    {
                        path: '/chat/:id',
                        component: ()=> import('../components/allComponents/ChatComponent.vue'),
                        name: 'chat',
                        meta: {
                            title: 'Чаты',
                            auth: true,
                        },

                        children: [
                            //Фото
                            {
                                path: '/imageChat',
                                component: ()=> import('../components/allComponents/ImageComponent.vue'),
                                name: 'imageChat',
                                meta: {
                                    title: 'Фото'
                                },
                            },
                        ]
                    },

                    //Фото
                    {
                        path: '/allAds/:table_name/:page/image',
                        component: ()=> import('../components/allComponents/ImageComponent.vue'),
                        name: 'image',
                        meta: {
                            title: 'Фото'
                        },
                    },

                    //Яндекс Кластер - Объявления на карте
                    {
                        path: '/mapCluster/:table_name',
                        component: ()=> import('../components/allComponents/map/YandexMapCluster.vue'),
                        name: 'mapCluster',
                        meta: {
                            title: 'Объекты на карте'
                        },

                        children:  [
                            {
                                path: '/mapClusterShowAds/:table_name',
                                component: ()=> import('../components/allComponents/map/YandexMapClusterShowAds.vue'),
                                name: 'mapClusterShowAds',
                                meta: {
                                    title: 'Объекты на карте'
                                }
                            }
                        ]
                    },

                    // Меню Подача объявления
                    {
                        path: '/addAdsMenu',
                        component: ()=> import('../components/allComponents/addAds/AddAdsMenu.vue'),
                        name: 'addAdsMenu',
                        meta: {
                            title: 'Что сдать',
                            // auth: true,
                        }
                    },

                    // Подача объявления
                    {
                        path: '/addAds/:table_name/:id/:step',
                        component: ()=> import('../components/allComponents/addAds/AddAdsForm.vue'),
                        name: 'addAds',
                        meta: {
                            title: 'Подать объявление',
                            auth: true,

                        },

                        children: [
                            //Локации
                            {
                                path: '/addAds/:table_name/:id/:step/addAdsFormLocation/:locationId/:stepLocation',
                                component: ()=> import('../components/allComponents/KZLocationComponent.vue'),
                                name: 'addAdsFormLocation',
                                meta: {
                                    title: 'Местоположение'
                                },
                            },

                            //Фото
                            {
                                path: '/imageAddAds',
                                component: ()=> import('../components/allComponents/ImageComponent.vue'),
                                name: 'imageAddAds',
                                meta: {
                                    title: 'Фото'
                                },
                            },
                        ]
                    },

                    //Объявления автора
                    {
                        path: '/userAds/:author_id/:table_name/:page',
                        component: ()=> import('../components/allComponents/user/UserAds.vue'),
                        name: 'userAds',
                        meta: {
                            title: 'Объявления автора',
                        },
                        children: [
                            //Покупка рекламы
                            {
                                path: '/bueAds/:author_id/:table_name/:page/:ads_id',
                                component: ()=> import('../components/allComponents/user/bueAds/Index.vue'),
                                name: 'BueAds',
                                meta: {
                                    title: 'Продвинуть объявление',
                                },

                                children : [
                                    {
                                        path: '/bueAdsPayMethod/:author_id/:table_name/:page/:ads_id/:bueAdsType',
                                        component: ()=> import('../components/allComponents/user/bueAds/BueAdsPayMethod.vue'),
                                        name: 'BueAdsPayMethod',
                                        meta: {
                                            title: 'Способ оплаты',
                                        },
                                    }
                                ]
                            },
                        ]
                    },

                    //Lang
                    {
                        path: '/allAds/:table_name/:page/lang',
                        component: ()=> import('../components/allComponents/LangComponent.vue'),
                        name: 'lang'
                    },

                ]
            },


            //Результат покупки рекламы
            {
                path: '/bueAdsResult/:result',
                component: ()=> import('../components/allComponents/user/bueAds/BueResult.vue'),
                name: 'BueAdsResult',
                meta: {
                    title: 'Проверка платежа'
                }
            },


            //Правила использования - Политика конфиденциальности

            {
                path: '/rules',
                component: ()=> import('../components/allComponents/projectData/PravilaSaita.vue'),
                name: 'rules',
                meta: {title: 'Правила размещения объявлений'}
            },

            {
                path: '/polzovatelskoeSoglashenie',
                component: ()=> import('../components/allComponents/projectData/PolzovatelskoeSoglashenie.vue'),
                name: 'polzovatelskoeSoglashenie',
                meta: {
                    title: 'Пользовательское соглашение'
                }
            },

            {
                path: '/contacts',
                component: ()=> import('../components/allComponents/projectData/Contacts.vue'),
                name: 'contacts',
                meta: {title: 'Контакты'}
            },

            {
                path: '/uslugi',
                component: ()=> import('../components/allComponents/projectData/PlatnieUslugi.vue'),
                name: 'uslugi',
                meta: {title: 'Платные услуги'}
            },
        ]
    },


]

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    }
})

//Добавим Title Страницам
import { getActiveLanguage } from 'laravel-vue-i18n';

router.beforeEach( (to, from, next) => {
    //Добавим Title Страницам
    let lang = getActiveLanguage();
    if(lang == 'ru')document.title = to.meta.title;
    if(lang == 'kz'){
        if(to.meta.title == 'Аренда недвижимости')document.title = 'Жалға берілетін мүлік';
        if(to.meta.title == 'Недвижимость')document.title = 'Жылжымайтын мүлік';
        if(to.meta.title == 'Фото')document.title = 'Фото';
        if(to.meta.title == 'Объект на карте')document.title = 'Картадағы нысан';
        if(to.meta.title == 'Вход на сайт')document.title = 'Сайтқа кіру';
        if(to.meta.title == 'Мой кабинет')document.title = 'Менің кабинетім';
        if(to.meta.title == 'Настройка аккаунта')document.title = 'Есептік жазбаны орнату';
        if(to.meta.title == 'Чаты')document.title = 'Чаттар';
        if(to.meta.title == 'Объекты на карте')document.title = 'Картадағы Нысандар';
        if(to.meta.title == 'Что сдать')document.title = 'Нені тапсыру керек';
        if(to.meta.title == 'Подать объявление')document.title = 'Хабарландыру жіберу';
        if(to.meta.title == 'Местоположение')document.title = 'Орналасқан жері';
        if(to.meta.title == 'Объявления автора')document.title = 'Автордың хабарландырулары';
        if(to.meta.title == 'Продвинуть объявление')document.title = 'Хабарландыруды жылжыту';
        if(to.meta.title == 'Способ оплаты')document.title = 'Төлем әдісі';
        if(to.meta.title == 'Фильтр')document.title = 'Сүзгі';
        if(to.meta.title == 'Правила размещения объявлений')document.title = 'Жарнамаларды орналастыру ережелері';
        if(to.meta.title == 'Пользовательское соглашение')document.title = 'Қолдану ережелері';
        if(to.meta.title == 'Контакты')document.title = 'Контактілер';
        if(to.meta.title == 'Платные услуги')document.title = 'Ақылы қызметтер';
        if(to.meta.title == 'Проверка платежа')document.title = 'Төлемді тексеру';
    }
    if(lang == 'en'){
        if(to.meta.title == 'Аренда недвижимости')document.title = 'Real estate rental';
        if(to.meta.title == 'Недвижимость')document.title = 'Real estate';
        if(to.meta.title == 'Фото')document.title = 'Photo';
        if(to.meta.title == 'Объект на карте')document.title = 'Object on the map';
        if(to.meta.title == 'Вход на сайт')document.title = 'Login to the site';
        if(to.meta.title == 'Мой кабинет')document.title = 'My office';
        if(to.meta.title == 'Настройка аккаунта')document.title = 'Account Setup';
        if(to.meta.title == 'Чаты')document.title = 'Chats';
        if(to.meta.title == 'Объекты на карте')document.title = 'Objects on the map';
        if(to.meta.title == 'Что сдать')document.title = 'What to hand over';
        if(to.meta.title == 'Подать объявление')document.title = 'Submit an ad';
        if(to.meta.title == 'Местоположение')document.title = 'Location';
        if(to.meta.title == 'Объявления автора')document.title = "Author's Ads";
        if(to.meta.title == 'Продвинуть объявление')document.title = 'Ad Promotion';
        if(to.meta.title == 'Способ оплаты')document.title = 'Payment method';
        if(to.meta.title == 'Фильтр')document.title = 'Filter';
        if(to.meta.title == 'Правила размещения объявлений')document.title = 'Rules for posting ads';
        if(to.meta.title == 'Пользовательское соглашение')document.title = 'Terms of use';
        if(to.meta.title == 'Контакты')document.title = 'Contacts';
        if(to.meta.title == 'Платные услуги')document.title = 'Paid services';
        if(to.meta.title == 'Проверка платежа')document.title = 'Payment verification';

    }


    //Если пропустить не авторизованного пользователя
    if(to.meta.auth != undefined && !to.meta.auth){

        axios.get('/auth/user')
            .then(response=>{
                next({name: 'home'})
            })
            .catch(errors=>{
                next()
            })
        return;
    }

    // Пропускаем авторизованного пользователя
    if (to.meta.auth != undefined && to.meta.auth) {

        axios.get('/auth/user')
            .then(resp=>{
                next()
            })
            .catch(errors=>{
                next({name: 'home'})
            })

        return;

    }

    //Пропустить если нет auth
    next();

})

export default router
