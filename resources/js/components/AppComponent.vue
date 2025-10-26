<template>

    <!-- Внешний wrapper для фона -->
    <div :class="['app-wrapper', appInstallStore.theme]">

        <div class="app-container">

            <!-- Alert если нет Интернета -->
            <transition name="fade">
                <div v-if="checkInternetStore.showAlert && !checkInternetStore.online" class="internet-alert" role="alert">
                    <strong>{{ checkInternetStore.message }}</strong>
                    <button type="button" class="internet-alert__close" @click="checkInternetStore.showAlertBlock(false)" aria-label="Закрыть">&times;</button>
                </div>
            </transition>

            <!-- HEADER -->
            <header-component></header-component>

            <!-- MAIN  -->
            <main-component></main-component>

            <!-- FOOTER -->
            <footer-component></footer-component>

        </div>

    </div>

</template>

<script setup>
import { onMounted } from 'vue';

// Импорт компонентов
import HeaderComponent from './HeaderComponent.vue';
import MainComponent from './MainComponent.vue';
import FooterComponent from './FooterComponent.vue';

// Импорт Store
import { useAuthStore } from "../stores/auth";
import { useAppInstallStore } from "../stores/AppInstall";
import { useCheckInternetStore } from "../stores/checkInternet";
import { useGetProjectDataStore } from '../stores/getProjectData';

// Мультиязык
import { loadLanguageAsync } from 'laravel-vue-i18n';

// Инициализация Stores
const authStore = useAuthStore();
const appInstallStore = useAppInstallStore();
const checkInternetStore = useCheckInternetStore();
const getProjectDataStore = useGetProjectDataStore();

onMounted(() => {

    // Определяем устройство - Desktop или Mobile
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB|PlayBook|IEMobile|Windows Phone|Kindle|Silk|Opera Mini/i.test(navigator.userAgent)) {
        authStore.desktopOrMob('Mobile');
        document.documentElement.classList.add('mobile__device');
    } else {
        authStore.desktopOrMob('Desktop');
        document.documentElement.classList.add('desktop__device');
    }

    // Мультиязык - беру значение с локального хронения и указываю язык приложения // мои переводы
    const lang = localStorage.getItem('lang');
    if (lang) loadLanguageAsync(lang);

    // 🔌 Автопроверка интернет соединения каждые 15 секунд
    checkInternetStore.startAutoCheck();

    // Установка PWA
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault(); // Отменяем всплывающее окно
        appInstallStore.appInstallerAdd(e); // Сохраняем событие для последующего вызова
    });

    // Событие после установки приложения
    window.addEventListener('appinstalled', () => {
        appInstallStore.appInstallerAdd('');
        Toast.fire({
            title: '<strong class="text-success">Приложение установлено</strong>'
        });
    });
});
</script>

<style scoped>
.app-wrapper {
    background: var(--app-bg-color);
    min-height: 100vh;
}

.app-container {
    width: 100%;
    max-width: 1320px;
    margin: 0 auto;
    background: var(--app-inner-bg-color);
}

.main__pages{
    min-height: 500px;
}


/* Темы */
.light-theme {
    --app-bg-color: #f8f9fa;
    --text-color: #333;
    --app-inner-bg-color: #fff;
}

.dark-theme {
    --app-bg-color: #1e1e1e;
    --text-color: #f5f5f5;
    --app-inner-bg-color: #2a2a2a;
}


/* Alert для отсутствия интернета */
.internet-alert {
    background-color: #ff4d4f;
    color: #fff;
    padding: 12px 20px;
    position: relative;
    text-align: center;
    font-weight: bold;
    border-radius: 4px;
    margin: 10px 0;
}

/* Кнопка закрытия алерта */
.internet-alert__close {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    font-size: 18px;
    color: #fff;
    cursor: pointer;
}

/* Плавное появление/исчезновение алерта */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
