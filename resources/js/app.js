// Подключение Service Worker для PWA
window.addEventListener('load', async () => {
    if ('serviceWorker' in navigator) {
        const appUrl = window.appUrl;
        navigator.serviceWorker.register('/service-worker.js')
            .then(registration => {
                if (registration.active) {
                    registration.active.postMessage({ appUrl });
                }
            })
            .catch(error => console.error('Ошибка при регистрации Service Worker:', error));
    }
});

import './bootstrap';

// Создаем приложение Vue
import { createApp } from 'vue';
import App from './components/AppComponent.vue';
const app = createApp(App);

// Важная переменная проекта
window.SiteDomain = window.location.origin;

// SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal.mixin({ confirmButtonColor: '#10a37f' });
window.Toast = Swal.mixin({
    toast: true,
    position: 'bottom',
    showConfirmButton: false,
    timer: 1500,
    padding: '0'
});

// ReCaptcha v3
import { VueReCaptcha } from 'vue-recaptcha-v3';

// VeeValidate
import VeeValidatePlugin from './plugins/veeValidate';

// Moment.js
import moment from 'moment';
import 'moment/dist/locale/kk';
import 'moment/dist/locale/ru';
const momentMap = { ru: 'ru', kz: 'kk' };

// Pinia
import { createPinia } from 'pinia';
import piniaPersist from 'pinia-plugin-persist';
const pinia = createPinia();
pinia.use(piniaPersist);
app.use(pinia);

// Lang Store
import { useLangStore } from './stores/lang';
const langStore = useLangStore();

// Инициализация moment с текущим языком
moment.locale(momentMap[langStore.LANG] ?? 'ru');

// Vuetify
import '@mdi/font/css/materialdesignicons.css';
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
const vuetify = createVuetify({
    components,
    directives,
    icons: { defaultSet: 'mdi' },
    theme: { themes: { light: { colors: { teal: '#008080' } } } }
});

// Bootstrap & стили
import '../sass/app.scss';
import * as bootstrap from 'bootstrap';
import "bootstrap-icons/font/bootstrap-icons.css";

// Для загрузки изображений
import { serialize } from 'object-to-formdata';
window.objectToFormData = serialize;

// Router
import Router from './router/index';

// Google Login
import vue3GoogleLogin from 'vue3-google-login';

// i18n
import { i18nVue } from 'laravel-vue-i18n';
app.use(i18nVue, {
    resolve: async lang => {
        const langs = import.meta.glob('./lang/*.json');
        return await langs[`./lang/${lang}.json`]();
    }
});

// Фильтры для дат и чисел
app.config.globalProperties.$filters = {
    transformDateRu(last_active) {
        const today = moment();
        const yesterday = moment().subtract(1, 'days');
        const d = moment(last_active);
        const locale = momentMap[langStore.LANG] ?? 'ru';
        moment.locale(locale);

        if (d.isSame(today, 'day')) {
            if (locale === 'ru') return `Сегодня, ${d.format('HH:mm')}`;
            if (locale === 'kk') return `Бүгін, ${d.format('HH:mm')}`;
        } else if (d.isSame(yesterday, 'day')) {
            if (locale === 'ru') return `Вчера, ${d.format('HH:mm')}`;
            if (locale === 'kk') return `Кеше, ${d.format('HH:mm')}`;
        } else {
            return d.format('D MMMM, HH:mm');
        }
    },
    transformDateRuNotWatch(last_active) {
        const today = moment();
        const yesterday = moment().subtract(1, 'days');
        const d = moment(last_active);
        const locale = momentMap[langStore.LANG] ?? 'ru';
        moment.locale(locale);

        if (d.isSame(today, 'day')) {
            if (locale === 'ru') return 'Сегодня';
            if (locale === 'kk') return 'Бүгін';
        } else if (d.isSame(yesterday, 'day')) {
            if (locale === 'ru') return 'Вчера';
            if (locale === 'kk') return 'Кеше';
        } else {
            return d.format('D MMMM');
        }
    },
    transformDateWatch(last_active) {
        const locale = momentMap[langStore.LANG] ?? 'ru';
        moment.locale(locale);
        return moment(last_active).format("HH:mm");
    },
    format_number(value) {
        return parseInt(value).toLocaleString();
    }
};

// Инициализация приложения
app.use(VeeValidatePlugin)
    .use(Router)
    .use(vuetify)
    .use(vue3GoogleLogin, { clientId: '360947734406-hsjnvasg1pokf20r4sa112vf4mv16jf6.apps.googleusercontent.com' })
    .use(VueReCaptcha, { siteKey: '6Ld4nRkkAAAAABV1_7o0mAOBAHWa5hf1Y5Z7OEoB', loaderOptions: { useRecaptchaNet: true } })
    .mount("#app");
