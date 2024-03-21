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
                        path: 'allAdsOneAds/:ads_id',
                        component: ()=> import('../components/allComponents/oneAds/OneAdsComponent.vue'),
                        name: 'allAdsOneAds',
                        meta: {
                            title: 'Недвижимость'
                        },

                        children: [

                            //Фото
                            {
                                path: 'allAdsOneAdsImage',
                                component: ()=> import('../components/allComponents/ImageComponent.vue'),
                                name: 'allAdsOneAdsImage',
                                meta: {
                                    title: 'Фото'
                                },
                            },

                            //Lang
                            {
                                path: 'allAdsOneAdsLang',
                                component: ()=> import('../components/allComponents/LangComponent.vue'),
                                name: 'allAdsOneAdsLang',
                            },

                            //Карта
                            {
                                path: 'allAdsOneAdsMap',
                                component: ()=> import('../components/allComponents/map/OneAdsMap.vue'),
                                name: 'allAdsOneAdsMap',
                                meta: {
                                    title: 'Объект на карте'
                                },
                            },

                            //Нижний OffCanvas
                            {
                                path: 'allAdsOneAdsBottomOffCanvas/:type',
                                component: ()=> import('../components/allComponents/oneAds/OneAdsBottomOffCanvas.vue'),
                                name: 'allAdsOneAdsBottomOffCanvas',
                                children: [
                                    //Авторизация на сайте
                                    {
                                        path: 'allAdsOneAdsBottomOffCanvasAuth',
                                        component: ()=> import('../components/allComponents/AuthComponent.vue'),
                                        name: 'allAdsOneAdsBottomOffCanvasAuth',
                                        meta: {
                                            title: 'Вход на сайт',

                                        },
                                    },
                                ]
                            },

                            //Авторизация на сайте
                            {
                                path: 'allAdsOneAdsAuth',
                                component: ()=> import('../components/allComponents/AuthComponent.vue'),
                                name: 'allAdsOneAdsAuth',
                                meta: {
                                    title: 'Вход на сайт',

                                },
                            },

                        ]
                    },


                    //Фильтр
                    {
                        path: 'filter',
                        component: ()=> import('../components/allComponents/AdsFilterComponent.vue'),
                        name: 'filter',
                        meta: {
                            title: 'Фильтр'
                        },

                        children: [
                            //Локации
                            {
                                path: 'filterLocation/:locationId/:stepLocation',
                                component: ()=> import('../components/allComponents/KZLocationComponent.vue'),
                                name: 'filterLocation',
                                meta: {
                                    title: 'Местоположение',
                                },
                            },
                        ]
                    },

                    //Авторизация на сайте
                    {
                        path: 'allAdsAuth',
                        component: ()=> import('../components/allComponents/AuthComponent.vue'),
                        name: 'allAdsAuth',
                        meta: {
                            title: 'Вход на сайт',

                        },
                    },

                    //Мой кабинет
                    {
                        path: 'myAccount',
                        component: ()=> import('../components/allComponents/user/MyAccountComponent.vue'),
                        name: 'myAccount',
                        meta: {
                            title: 'Мой кабинет',
                            auth: true
                        },
                        children: [
                            //Настройки
                            {
                                path: 'myAccountSettings',
                                component: ()=> import('../components/allComponents/user/MyAccountSettings.vue'),
                                name: "myAccountSettings",
                                meta: {
                                    auth: true,
                                    title: 'Настройка аккаунта'
                                }
                            },
                        ]
                    },

                    //Чаты
                    {
                        path: 'chat/:id/',
                        component: ()=> import('../components/allComponents/ChatComponent.vue'),
                        name: 'chat',
                        meta: {
                            title: 'Чаты',
                            auth: true,
                        },

                        children: [

                            //Вывод Одного объявление
                            {
                                path: 'chatOneAds/:ads_id',
                                component: ()=> import('../components/allComponents/oneAds/OneAdsComponent.vue'),
                                name: 'chatOneAds',
                                meta: {
                                    title: 'Недвижимость'
                                },

                                children: [

                                    //Фото
                                    {
                                        path: 'chatOneAdsImage',
                                        component: ()=> import('../components/allComponents/ImageComponent.vue'),
                                        name: 'chatOneAdsImage',
                                        meta: {
                                            title: 'Фото'
                                        },
                                    },

                                    //Lang
                                    {
                                        path: 'chatOneAdsLang',
                                        component: ()=> import('../components/allComponents/LangComponent.vue'),
                                        name: 'chatOneAdsLang',
                                    },

                                    //Карта
                                    {
                                        path: 'chatOneAdsMap',
                                        component: ()=> import('../components/allComponents/map/OneAdsMap.vue'),
                                        name: 'chatOneAdsMap',
                                        meta: {
                                            title: 'Объект на карте'
                                        },
                                    },

                                    //Нижний OffCanvas
                                    {
                                        path: 'chatOneAdsBottomOffCanvas/:type',
                                        component: ()=> import('../components/allComponents/oneAds/OneAdsBottomOffCanvas.vue'),
                                        name: 'chatOneAdsBottomOffCanvas',
                                        children: [
                                            //Авторизация на сайте
                                            {
                                                path: 'chatOneAdsBottomOffCanvasAuth',
                                                component: ()=> import('../components/allComponents/AuthComponent.vue'),
                                                name: 'chatOneAdsBottomOffCanvasAuth',
                                                meta: {
                                                    title: 'Вход на сайт',

                                                },
                                            },
                                        ]
                                    },

                                    //Авторизация на сайте
                                    {
                                        path: 'chatOneAdsAuth',
                                        component: ()=> import('../components/allComponents/AuthComponent.vue'),
                                        name: 'chatOneAdsAuth',
                                        meta: {
                                            title: 'Вход на сайт',

                                        },
                                    },

                                ]
                            },

                            //Фото
                            {
                                path: 'chatImage/',
                                component: ()=> import('../components/allComponents/ImageComponent.vue'),
                                name: 'chatImage',
                                meta: {
                                    title: 'Фото',
                                    auth: true,
                                },
                            },
                        ]
                    },

                    //Фото
                    {
                        path: 'allAdsImage',
                        component: ()=> import('../components/allComponents/ImageComponent.vue'),
                        name: 'allAdsImage',
                        meta: {
                            title: 'Фото'
                        },
                    },

                    // Объявления на карте
                    {
                        path: 'allAdsMap',
                        component: ()=> import('../components/allComponents/map/AllAdsMap.vue'),
                        name: 'allAdsMap',
                        meta: {
                            title: 'Объекты на карте'
                        },

                        children:  [
                            {
                                path: 'allAdsMapPreviewAds/:pages',
                                component: ()=> import('../components/allComponents/map/AllAdsMapPreviewAds.vue'),
                                name: 'allAdsMapPreviewAds',
                                meta: {
                                    title: 'Объекты на карте'
                                },

                                children: [
                                    //Вывод Одного объявление
                                    {
                                        path: 'allAdsMapPreviewAdsOneAds/:ads_id',
                                        component: ()=> import('../components/allComponents/oneAds/OneAdsComponent.vue'),
                                        name: 'allAdsMapPreviewAdsOneAds',
                                        meta: {
                                            title: 'Недвижимость'
                                        },

                                        children: [

                                            //Фото
                                            {
                                                path: 'allAdsMapPreviewAdsOneAdsImage',
                                                component: ()=> import('../components/allComponents/ImageComponent.vue'),
                                                name: 'allAdsMapPreviewAdsOneAdsImage',
                                                meta: {
                                                    title: 'Фото'
                                                },
                                            },

                                            //Lang
                                            {
                                                path: 'allAdsMapPreviewAdsOneAdsLang',
                                                component: ()=> import('../components/allComponents/LangComponent.vue'),
                                                name: 'allAdsMapPreviewAdsOneAdsLang',
                                            },

                                            //Карта
                                            {
                                                path: 'allAdsMapPreviewAdsOneAdsMap',
                                                component: ()=> import('../components/allComponents/map/OneAdsMap.vue'),
                                                name: 'allAdsMapPreviewAdsOneAdsMap',
                                                meta: {
                                                    title: 'Объект на карте'
                                                },
                                            },

                                            //Нижний OffCanvas
                                            {
                                                path: 'allAdsMapPreviewAdsOneAdsBottomOffCanvas/:type',
                                                component: ()=> import('../components/allComponents/oneAds/OneAdsBottomOffCanvas.vue'),
                                                name: 'allAdsMapPreviewAdsOneAdsBottomOffCanvas',
                                                children: [
                                                    //Авторизация на сайте
                                                    {
                                                        path: 'allAdsMapPreviewAdsOneAdsBottomOffCanvasAuth',
                                                        component: ()=> import('../components/allComponents/AuthComponent.vue'),
                                                        name: 'allAdsMapPreviewAdsOneAdsBottomOffCanvasAuth',
                                                        meta: {
                                                            title: 'Вход на сайт',
                                                        },
                                                    },
                                                ]
                                            },

                                            //Авторизация на сайте
                                            {
                                                path: 'allAdsMapPreviewAdsOneAdsAuth',
                                                component: ()=> import('../components/allComponents/AuthComponent.vue'),
                                                name: 'allAdsMapPreviewAdsOneAdsAuth',
                                                meta: {
                                                    title: 'Вход на сайт',

                                                },
                                            },

                                        ]
                                    },

                                    //Фото с превью
                                    {
                                        path: 'allAdsMapPreviewAdsImage',
                                        component: ()=> import('../components/allComponents/ImageComponent.vue'),
                                        name: 'allAdsMapPreviewAdsImage',
                                        meta: {
                                            title: 'Фото'
                                        },
                                    },
                                ]
                            },
                        ]
                    },

                    // Меню Подача объявления
                    {
                        path: 'addAdsMenu',
                        component: ()=> import('../components/allComponents/addAds/AddAdsMenu.vue'),
                        name: 'addAdsMenu',
                        meta: {
                            title: 'Что сдать',
                            auth: true,
                        }
                    },

                    // Подача объявления
                    {
                        path: 'allAdsAddAds/:id/:step',
                        component: ()=> import('../components/allComponents/addAds/AddAdsForm.vue'),
                        name: 'allAdsAddAds',
                        meta: {
                            title: 'Подать объявление',
                            auth: true,

                        },

                        children: [
                            //Локации
                            {
                                path: 'allAdsAddAdsLocation/:locationId/:stepLocation',
                                component: ()=> import('../components/allComponents/KZLocationComponent.vue'),
                                name: 'allAdsAddAdsLocation',
                                meta: {
                                    title: 'Местоположение'
                                },
                            },

                            //Фото
                            {
                                path: 'allAdsAddAdsImage/',
                                component: ()=> import('../components/allComponents/ImageComponent.vue'),
                                name: 'allAdsAddAdsImage',
                                meta: {
                                    title: 'Фото'
                                },
                            },
                        ]
                    },

                    //Объявления автора
                    {
                        path: 'userAds/:author_id/',
                        component: ()=> import('../components/allComponents/user/UserAds.vue'),
                        name: 'userAds',
                        meta: {
                            title: 'Объявления автора',
                        },
                        children: [

                            //Вывод Одного объявление
                            {
                                path: 'userAdsOneAds/:ads_id',
                                component: ()=> import('../components/allComponents/oneAds/OneAdsComponent.vue'),
                                name: 'userAdsOneAds',
                                meta: {
                                    title: 'Недвижимость'
                                },

                                children: [

                                    //Фото
                                    {
                                        path: 'userAdsOneAdsImage',
                                        component: ()=> import('../components/allComponents/ImageComponent.vue'),
                                        name: 'userAdsOneAdsImage',
                                        meta: {
                                            title: 'Фото'
                                        },
                                    },

                                    //Lang
                                    {
                                        path: 'userAdsOneAdsLang',
                                        component: ()=> import('../components/allComponents/LangComponent.vue'),
                                        name: 'userAdsOneAdsLang',
                                    },

                                    //Карта
                                    {
                                        path: 'userAdsOneAdsMap',
                                        component: ()=> import('../components/allComponents/map/OneAdsMap.vue'),
                                        name: 'userAdsOneAdsMap',
                                        meta: {
                                            title: 'Объект на карте'
                                        },
                                    },

                                    //Нижний OffCanvas
                                    {
                                        path: 'userAdsOneAdsBottomOffCanvas/:type',
                                        component: ()=> import('../components/allComponents/oneAds/OneAdsBottomOffCanvas.vue'),
                                        name: 'userAdsOneAdsBottomOffCanvas',
                                        meta: {
                                        },
                                    },

                                ]
                            },

                            //Фото с превью
                            {
                                path: 'userAdsImage',
                                component: ()=> import('../components/allComponents/ImageComponent.vue'),
                                name: 'userAdsImage',
                                meta: {
                                    title: 'Фото'
                                },
                            },

                            // Подача объявления
                            {
                                path: 'userAdsAddAds/:id/:step',
                                component: ()=> import('../components/allComponents/addAds/AddAdsForm.vue'),
                                name: 'userAdsAddAds',
                                meta: {
                                    title: 'Подать объявление',
                                    auth: true,

                                },

                                children: [
                                    //Локации
                                    {
                                        path: 'userAdsAddAdsLocation/:locationId/:stepLocation',
                                        component: ()=> import('../components/allComponents/KZLocationComponent.vue'),
                                        name: 'userAdsAddAdsLocation',
                                        meta: {
                                            title: 'Местоположение'
                                        },
                                    },

                                    //Фото
                                    {
                                        path: 'userAdsAddAdsImage/',
                                        component: ()=> import('../components/allComponents/ImageComponent.vue'),
                                        name: 'userAdsAddAdsImage',
                                        meta: {
                                            title: 'Фото'
                                        },
                                    },
                                ]
                            },

                            //Покупка рекламы
                            {
                                path: 'userAdsBueAds/:ads_id',
                                component: ()=> import('../components/allComponents/user/bueAds/Index.vue'),
                                name: 'userAdsBueAds',
                                meta: {
                                    title: 'Продвинуть объявление',
                                },

                                children : [
                                    {
                                        path: 'userAdsBueAdsPayMethod/:bueAdsType',
                                        component: ()=> import('../components/allComponents/user/bueAds/BueAdsPayMethod.vue'),
                                        name: 'userAdsBueAdsPayMethod',
                                        meta: {
                                            title: 'Способ оплаты',
                                        },
                                    },

                                    //Авторизация на сайте
                                    {
                                        path: 'userAdsBueAdsAuth',
                                        component: ()=> import('../components/allComponents/AuthComponent.vue'),
                                        name: 'userAdsBueAdsAuth',
                                        meta: {
                                            title: 'Вход на сайт',

                                        },
                                    },
                                ]
                            },

                            //Авторизация на сайте
                            {
                                path: 'userAdsAuth',
                                component: ()=> import('../components/allComponents/AuthComponent.vue'),
                                name: 'userAdsAuth',
                                meta: {
                                    title: 'Вход на сайт',

                                },
                            },

                        ]
                    },

                    //Lang
                    {
                        path: 'allAdsLang',
                        component: ()=> import('../components/allComponents/LangComponent.vue'),
                        name: 'allAdsLang'
                    },

                ]
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

// Определяем язык приложения
import { getActiveLanguage } from 'laravel-vue-i18n';

//Импортирую Store - Наше общее состояние
import {useAuthStore} from "../stores/auth";


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

    // Проверяем, требуется ли аутентификация для маршрута
    if (to.meta.auth !== undefined) {
        const authRequired = to.meta.auth;
        const isAuthenticated = useAuthStore().check;

        // Если требуется аутентификация и пользователь не аутентифицирован, или
        // Если не требуется аутентификация и пользователь аутентифицирован,
        // перенаправляем на главную страницу
        if ((authRequired && !isAuthenticated) || (!authRequired && isAuthenticated)) {
            return next({ name: 'home' });
        }
    }

    //Пропустить если нет meta auth
    next();

})

export default router
