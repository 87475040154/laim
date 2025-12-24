<template>

    <div class="app-container">

        <!-- Блок - если нет Интернета -->
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

</template>

<script setup>

import { onMounted } from 'vue';

// Импорт компонентов
import HeaderComponent from './HeaderComponent.vue';
import MainComponent from './MainComponent.vue';
import FooterComponent from './FooterComponent.vue';

// Импорт Stores
import { useAuthStore } from "../stores/auth";
import { useAppInstallStore } from "../stores/AppInstall";
import { useCheckInternetStore } from "../stores/checkInternet";

// Мультиязык -> resources -> lang -> ru.json, kz.json, en.json Делаю перевод сайта
import { loadLanguageAsync } from 'laravel-vue-i18n';

// Инициализация Stores - Общее состояние сайта
const authStore = useAuthStore();
const appInstallStore = useAppInstallStore();
const checkInternetStore = useCheckInternetStore();


// Проверка через что зашел пользователь Телефон - Компьютер
function detectDevice() {
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|BB|PlayBook|IEMobile|Windows Phone|Kindle|Silk|Opera Mini/i.test(navigator.userAgent);
    authStore.desktopOrMob(isMobile ? 'Mobile' : 'Desktop');
    document.documentElement.classList.add(isMobile ? 'mobile__device' : 'desktop__device');
}

onMounted(() => {

    // Определяем устройство - Desktop или Mobile
    detectDevice();

    // Мультиязык - беру значение с локального хронения и указываю язык приложения если перезагрузка
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

.app-container {
    min-height: 100vh;          /* контейнер минимум на весь экран */
    max-width: 1320px;
    margin: 0 auto;

    display: flex;
    flex-direction: column;

    background: #eeeeee;
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
<!--130-->
