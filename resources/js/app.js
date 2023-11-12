// Подключение Service Worker для сайта PWA Если он доступен в Браузере
window.addEventListener('load', async () => {
    // Проверка есть ли данное свойство в данном браузере в navigator
    if ('serviceWorker' in navigator) {
        // Получение значения appUrl из .env или другого источника
        const appUrl = window.appUrl

        // Регистрация Service Worker и отправка сообщения с данными
        navigator.serviceWorker.register('/service-worker.js')
            .then(registration => {
                // Отправляем сообщение с данными в Service Worker
                registration.active.postMessage({ appUrl: appUrl });
            })
            .catch(error => {
                console.error('Ошибка при регистрации Service Worker:', error);
            });
    }
});

import './bootstrap';

//Создаем приложение VUE
import {createApp} from 'vue/dist/vue.esm-bundler'
//Импортируем главный компонент
import App from './components/AppComponent.vue'
const app = createApp(App);



window.SiteDomain = window.location.origin;

//Пакет красивых alert sweetalert2
import Swal from 'sweetalert2';
window.Swal = Swal.mixin({
    // toast: true,
    confirmButtonColor: '#10a37f',
});
const Toast = Swal.mixin({
    toast: true,
    position: 'bottom',
    showConfirmButton: false,
    timer: 1500,
    padding: '0',
});
window.Toast = Toast;

//Подключаю рекапчу 3 версия
import { VueReCaptcha } from 'vue-recaptcha-v3'; //Пакет reCAPTCHA

//Импортируем плагин настройки валидации VeeValidate
import VeeValidatePlugin from './plugins/veeValidate'


// Пакет форматирование даты
import moment from 'moment';
import 'moment/dist/locale/kk';
import 'moment/dist/locale/ru';
import 'moment/dist/locale/en-gb';

const momentLocale = moment;

let lang = localStorage.getItem('lang') == undefined ? 'ru': localStorage.getItem('lang');
lang == 'kz' ? lang = 'kk' : '';
lang == 'en' ? lang = 'en-gb' : '';
moment.locale(lang);
app.config.globalProperties.$filters = {

    //Для трансформации даты в календарный формат с часами
    transformDateRu(last_active) {
        const today = moment();
        const yesterday = moment().subtract(1, 'days');
        const lastActiveDate = moment(last_active);

        if (lastActiveDate.isSame(today, 'day')) {
            if (moment.locale() === 'ru') return `Сегодня, ${lastActiveDate.format('HH:mm')}`;
            if (moment.locale() === 'kk') return `Бүгін, ${lastActiveDate.format('HH:mm')}`;
            if (moment.locale() === 'en-gb') return `Today, ${lastActiveDate.format('HH:mm')}`;
        } else if (lastActiveDate.isSame(yesterday, 'day')) {
            if (moment.locale() === 'ru') return `Вчера, ${lastActiveDate.format('HH:mm')}`;
            if (moment.locale() === 'kk') return `Кеше, ${lastActiveDate.format('HH:mm')}`;
            if (moment.locale() === 'en-gb') return `Yesterday, ${lastActiveDate.format('HH:mm')}`;
        } else {
            return lastActiveDate.format('D MMMM, HH:mm');
        }
    },

    //Для трансформации даты в календарный формат без часов
    transformDateRuNotWatch(last_active) {
        const today = moment();
        const yesterday = moment().subtract(1, 'days');
        const lastActiveDate = moment(last_active);

        if (lastActiveDate.isSame(today, 'day')) {
            if (moment.locale() === 'ru') return 'Сегодня';
            if (moment.locale() === 'kk') return 'Бүгін';
            if (moment.locale() === 'en-gb') return 'Today';
        } else if (lastActiveDate.isSame(yesterday, 'day')) {
            if (moment.locale() === 'ru') return 'Вчера';
            if (moment.locale() === 'kk') return 'Кеше';
            if (moment.locale() === 'en-gb') return 'Yesterday';
        } else {
            return lastActiveDate.format('D MMMM');
        }
    },

    //Для часов
    transformDateWatch(last_active) {
        return moment(last_active).format("HH:mm"); // Например, "14:30"
    },

    //Для цены было 90000000 формат будет 9 000 000
    format_number(value) {
        return parseInt(value).toLocaleString()
    },
}



// Vuetify
import '@mdi/font/css/materialdesignicons.css' // Ensure you
import colors from 'vuetify/lib/util/colors'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
    components,
    directives,
    icons: {
        defaultSet: 'mdi', // This is already the default value - only for display purposes
    },

    theme: {
        themes: {
            light: {
                colors: {
                    teal: '#008080',
                }
            },
        },
    },

})

//Импортируем стили и Bootstrap
import '../sass/app.scss'
import * as bootstrap from 'bootstrap'
// Bootstrap Icons
import "bootstrap-icons/font/bootstrap-icons.css";


//Для загрузки картинок На сервер
import { serialize } from 'object-to-formdata';
window.objectToFormData = serialize;




//Импортируем маршруты
import Router from './router/index'

//Подключаем пакет Pinia глобальный Store
import { createPinia } from 'pinia';

//Авторизация через Google, Apple
import vue3GoogleLogin from 'vue3-google-login'

//Для мультиязыка
import { i18nVue } from 'laravel-vue-i18n'

app.use(VeeValidatePlugin)
    .use(createPinia())
    .use(Router)
    .use(vuetify)
    .use(vue3GoogleLogin, {
        clientId: '360947734406-e44a0i729f9oio8qjko16itu892ajj3n.apps.googleusercontent.com'
    })
    .use(VueReCaptcha, { siteKey: '6Ld4nRkkAAAAABV1_7o0mAOBAHWa5hf1Y5Z7OEoB', loaderOptions: {useRecaptchaNet: true}})
    .use(i18nVue, {
        resolve: async lang => {
            const langs = import.meta.glob('../../lang/php_*.json');
            return await langs[`../../lang/php_${lang}.json`]();
        }
    })
    .mount("#app")
