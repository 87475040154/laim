<template>

    <!-- Alert - если нет Интернета -->
    <div v-if="checkInternetStore.showAlert && !checkInternetStore.online" class="alert alert-danger alert-dismissible fade show fixed-top text-center" role="alert" style="z-index: 9999;">
        <strong>{{ checkInternetStore.message }}</strong>
        <button type="button" class="btn-close" @click="checkInternetStore.showAlertBlock(false)" aria-label="Закрыть"></button>
    </div>

    <!-- Компонент Шаблон -->
    <div :class="{'dark-theme':appInstallStore.theme == 'dark-theme'}" style="background: var(--app-bg-color); display: flex; flex-direction: column; min-height: 100vh;">

        <!-- Компоненты - HEADER | MAIN | FOOTER -->
        <header-component></header-component>

        <div style="min-height: 100vh">
            <router-view></router-view>
        </div>

        <footer-component></footer-component>

    </div>

</template>

<script>

//Импортирую Компоненты - HEADER | FOOTER
import HeaderComponent from './HeaderComponent.vue'
import FooterComponent from './FooterComponent.vue'

//Импортирую Store - Общее состояние
import { useAuthStore } from "../stores/auth";
import { useAppInstallStore } from "../stores/AppInstall";
import { useCheckInternetStore } from "../stores/checkInternet";
import { useGetProjectDataStore } from '../stores/getProjectData';

//Мультиязык
import { loadLanguageAsync } from 'laravel-vue-i18n';


export default {
    name: "AppComponent",

    components: {
        HeaderComponent, FooterComponent
    },

    data() {
        return {

            //Подключаю Store - Общее состояние
            authStore: useAuthStore(),
            appInstallStore: useAppInstallStore(),
            checkInternetStore: useCheckInternetStore(),
            getProjectDataStore: useGetProjectDataStore(),

        }
    },

    created() {
        let app = this;
    },

    mounted() {
        let app = this;

        //Определяем Что это - Desktop or Mobile
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB|PlayBook|IEMobile|Windows Phone|Kindle|Silk|Opera Mini/i.test(navigator.userAgent)) {
            app.authStore.desktopOrMob('Mobile');
            document.getElementsByTagName('html')[0].classList.add('mobile__device');
        }
        else {
            app.authStore.desktopOrMob('Desktop');
            document.getElementsByTagName('html')[0].classList.add('desktop__device');
        }

        //Мультиязык
        localStorage.getItem('lang') != undefined ? loadLanguageAsync( localStorage.getItem('lang') ) : ''


        //Установка приложения PWA - Отловим и занесем это событие в Store что-бы в дальнейшем его использовать
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();// Отменяем всплывающее окно об установки
            app.appInstallStore.appInstallerAdd(e);// Сохраняем событие об установки чтобы его можно было запустить позже
        });
        //Уведомление когда установленно приложение
        window.addEventListener('appinstalled', () => {
            app.appInstallStore.appInstallerAdd('');

            Toast.fire({
                title: '<strong class="text-success">Приложение установленно</strong>'
            })

        });
    },

}
</script>

<style scoped>

</style>
