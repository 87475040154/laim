<template>
    <!-- 🔹 Основной хедер -->
    <header class="header" :class="{ 'header__sticky': isSticky }">

        <!-- 🔸 Верхняя панель -->
        <div class="top">
            <div class="top__inner">

                <!-- 🏠 Логотип -->
                <button class="logo" @click="onLogoClick">
                    <img
                        src="/img/siteImg/allImg/logo.svg"
                        width="30"
                        height="30"
                        alt="Логотип"
                    />
                </button>

                <div class="spacer"></div>

                <!-- 🔸 Правая часть -->
                <div class="right-group">

                    <!-- ❤️ Кнопка "Избранные" -->
                    <div class="actions">
                        <button
                            v-if="authStore.check"
                            class="icon-btn"
                            @click=" getMyLikeAds = !getMyLikeAds "
                        >
                            <!-- Если есть избранные -->
                            <svg
                                v-if="getMyLikeAds"
                                width="24"
                                height="24"
                                fill="#ff3b30"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                                    2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5
                                    2.09C13.09 3.81 14.76 3 16.5 3
                                    19.58 3 22 5.42 22 8.5
                                    c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                                />
                            </svg>

                            <!-- Если нет избранных -->
                            <svg
                                v-else
                                width="24"
                                height="24"
                                fill="#fff"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M16.5 3c-1.74 0-3.41.81-4.5
                                    2.09C10.91 3.81 9.24 3 7.5
                                    3 4.42 3 2 5.42 2 8.5
                                    c0 3.78 3.4 6.86 8.55
                                    11.54L12 21.35l1.45-1.32C18.6
                                    15.36 22 12.28 22 8.5
                                    22 5.42 19.58 3 16.5 3zM12
                                    19.55l-.1-.1C7.14 14.24 4
                                    11.39 4 8.5 4 6.5 5.5 5 7.5
                                    5c1.54 0 3.04.99 3.57
                                    2.36h1.87C13.46 5.99 14.96 5
                                    16.5 5 18.5 5 20 6.5 20
                                    8.5c0 2.89-3.14 5.74-7.9
                                    10.95l-.1.1z"
                                />
                            </svg>
                        </button>

                        <!-- 📋 Кнопка "Мои объявления" -->
                        <button
                            v-if="authStore.check"
                            class="icon-btn"
                            @click="$router.push({ name: 'userAds', params: { author_id: authStore.user.id } })"
                        >
                            <!-- 🔸 Кол-во активных объявлений -->
                            <div
                                v-if="getProjectDataStore.countReturnAds > 0"
                                class="badge"
                            >
                                {{ getProjectDataStore.countReturnAds }}
                            </div>

                            <svg width="24" height="24" fill="#fff" viewBox="0 0 24 24">
                                <path d="M4 4h16v2H4zm0 7h16v2H4zm0 7h16v2H4z" />
                            </svg>
                        </button>

                        <!-- 👤 Кнопка "Мой аккаунт" -->
                        <button
                            v-if="authStore.check"
                            class="icon-btn"
                            @click="$router.push({ name: 'myAccount' })"
                        >
                            <svg width="24" height="24" fill="#fff" viewBox="0 0 24 24">
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4
                                    1.79-4 4 1.79 4 4 4zm0
                                    2c-2.67 0-8 1.34-8 4v2h16v-2
                                    c0-2.66-5.33-4-8-4z"
                                />
                            </svg>
                        </button>
                    </div>

                    <!-- ➕ Кнопка "Добавить объявление" -->
                    <button class="submit-btn" @click="onSubmitClick">
                        {{ $t('headerSubmitAnAd') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- 🔸 Меню категорий -->
        <nav class="menu">
            <div
                v-for="(item, i) in categories"
                :key="i"
                class="menu__item"
                @click="getNewAds(item.link)"
            >
                <div
                    class="menu__icon"
                    :class="{ active: $route.params.table_name === item.name }"
                >
                    <img
                        :src="item.icon"
                        :alt="item.label"
                        width="45"
                        height="45"
                    />
                    <small>
                        {{ $t(item.label) !== item.label ? $t(item.label) : item.fallback }}
                    </small>
                </div>
            </div>
        </nav>
    </header>

</template>


<script setup>
import { ref, watch, nextTick } from 'vue'
import { useScroll, useLocalStorage  } from '@vueuse/core'
import { useRouter, useRoute } from 'vue-router'

// 🧭 Подключаем маршрутизатор
const router = useRouter()
const route = useRoute()

// 🏪 Подключаем Pinia-хранилища
import { useAuthStore } from '../stores/auth'
import { useGetProjectDataStore } from '../stores/getProjectData'
import { useAppInstallStore } from '../stores/AppInstall'

const authStore = useAuthStore()
const getProjectDataStore = useGetProjectDataStore()
const appInstallStore = useAppInstallStore()

// создаём реактивный ref, который синхронизирован с localStorage
const getMyLikeAds = useLocalStorage('getMyLikeAds', false)

// 🗂️ Меню категорий
const categories = [
    { name: 'Kvartira', label: 'headerMenuApartments', fallback: 'Квартиры', icon: '/img/siteImg/menuImg/1.svg', link: '/allAds/Kvartira' },
    { name: 'Obshejitie', label: 'headerMenuHostels', fallback: 'Общежития', icon: '/img/siteImg/menuImg/2.svg', link: '/allAds/Obshejitie' },
    { name: 'Dom', label: 'headerMenuHouses', fallback: 'Дома', icon: '/img/siteImg/menuImg/3.svg', link: '/allAds/Dom' },
    { name: 'Ofis', label: 'headerMenuOffice', fallback: 'Офисы', icon: '/img/siteImg/menuImg/4.svg', link: '/allAds/Ofis' },
    { name: 'Zdanie', label: 'headerMenuBuilding', fallback: 'Здания', icon: '/img/siteImg/menuImg/5.svg', link: '/allAds/Zdanie' },
    { name: 'Magazin', label: 'headerMenuShops', fallback: 'Магазины', icon: '/img/siteImg/menuImg/6.svg', link: '/allAds/Magazin' },
    { name: 'Prombaza', label: 'headerMenuBases', fallback: 'Промбазы', icon: '/img/siteImg/menuImg/7.svg', link: '/allAds/Prombaza' },
    { name: 'Prochaya', label: 'headerMenuOther', fallback: 'Прочая', icon: '/img/siteImg/menuImg/8.svg', link: '/allAds/Prochaya' },
    { name: 'Business', label: 'headerMenuBusiness', fallback: 'Бизнес', icon: '/img/siteImg/menuImg/9.svg', link: '/allAds/Business' }
]

// 📜 Отслеживаем прокрутку страницы
const { y } = useScroll(window)

// 📌 Состояние "прилипания" хедера
const isSticky = ref(false)
let lastY = 0
const DELTA = 10 // порог чувствительности — мелкие движения игнорируем

// 🎯 Логика прилипания хедера
watch(y, (newY) => {
    const diff = newY - lastY

    // Если вернулись к самому верху — хедер обычный (не липкий)
    if (newY <= 0) {
        isSticky.value = false
        lastY = newY
        return
    }

    // Игнорируем мелкие движения (чтобы не "дрожал")
    if (Math.abs(diff) <= DELTA) return

    if (diff < 0) {
        // Скролл вверх — хедер становится "sticky"
        isSticky.value = true
    } else if (diff > 0) {
        // Скролл вниз — хедер отпускается
        isSticky.value = false
    }

    lastY = newY
})


function getNewAds(itemLink){
    router.replace(itemLink);
}


// 🏠 Клик по логотипу
function onLogoClick() {
    // Если приложение установлено — вызвать установку
    if (appInstallStore.app !== '') appInstallStore.install()
    // Иначе перейти на страницу "Квартиры"
    else router.push('/allAds/Kvartira')
}

// ➕ Клик по кнопке "Добавить объявление"
function onSubmitClick() {
    // Если пользователь авторизован — перейти в меню добавления объявления
    if (authStore.check) router.push({ name: 'addAdsMenu' })
    // Если нет — переадресовать на страницу авторизации
    else router.push({ name: route.name + 'Auth' })
}

</script>


<style scoped>
.header {
    position: relative; /* <-- важно */
    top: 0;
    left: 0;
    right: 0;
    background: #eeeeee;
    will-change: transform;
}
.header__sticky {
    position: sticky;
    top: 0;
    z-index: 1;
    backdrop-filter: blur(6px);
    transition: background 0.2s ease, box-shadow 0.2s ease;
}

.top {
    display: none;
    background: rgb(63 63 69);
    color: #fff;
    padding: 8px 0;
    margin-bottom: 16px;
}
.top__inner {
    display: flex;
    align-items: center;

    padding: 0 12px;
}
.logo {
    display: flex;
    align-items: center;
    border: none;
    background: none;
    cursor: pointer;
}
.spacer {
    flex: 1;
}

.right-group {
    display: flex;
    align-items: center;
    gap: 20px; /* 🔹 немного увеличил отступ между блоками */
}
.actions {
    display: flex;
    align-items: center;
    gap: 16px; /* 🔹 чуть больше воздуха между иконками */
}
.icon-btn {
    position: relative;
    background: #19b07b; /* 🔹 лёгкий фон для современного ощущения */
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 10px; /* 🔹 скругления как у iOS-style кнопок */
    transition: all 0.2s ease;
}
.icon-btn:hover {
    background: #4ae6b0; /* 🔹 лёгкий hover эффект */
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(0,0,0,0.08);
}
.icon-btn svg {
    width: 22px;
    height: 22px;
}
.badge {
    position: absolute;
    top: 2px;
    right: 2px;
    background: #ff3b30;
    color: #fff;
    font-size: 11px;
    padding: 1px 4px;
    border-radius: 10px;
    font-weight: 700;
    line-height: 1;
    min-width: 16px;
    text-align: center;
}
.submit-btn {
    background: #fc3441;
    border: none;
    color: #fff;
    padding: 8px 14px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}
.submit-btn:hover {
    opacity: 0.9;
}

.menu {
    display: flex;
    gap: 14px;
    padding: 12px 10px 16px;
    overflow-x: auto;
    scrollbar-width: none; /* Firefox */
}

.menu::-webkit-scrollbar {
    display: none; /* Chrome/Safari */
}

.menu__item {
    flex: 0 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    user-select: none;
    transition: transform 0.25s ease;
}

.menu__item:hover {
    transform: translateY(-3px) scale(1.03);
}

.menu__icon {
    width: 84px;
    height: 84px;
    border-radius: 18px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f8f8f8;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    transition: all 0.25s ease;
    padding: 6px 0;
}

.menu__icon:hover {
    box-shadow: 0 10px 22px rgba(0, 0, 0, 0.1);
}

.menu__icon img {
    margin-bottom: 6px;
    width: 48px;
    height: 48px;
    object-fit: contain;
}

.menu__icon small {
    font-size: 13px;
    color: #333;
    text-align: center;
    line-height: 1.2;
    transition: color 0.25s ease;
}

.menu__icon.active {
    background: linear-gradient(135deg, #19b07b, #1fc79b);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.menu__icon.active small {
    color: #fff;
}





/* 📞 Маленькие телефоны (≥480px) */
@media (min-width: 480px) {

}

/* 📱 Средние телефоны (≥576px) */
@media (min-width: 576px) {

}

/* 💼 Планшеты (≥768px) */
@media (min-width: 768px) {

}

/* при ширине экрана ≥823px — по центру */
@media (min-width: 823px) {
    .menu {
        justify-content: center;
        margin-top: 20px;
    }
}

/* 💻 Ноутбуки (≥992px) */
@media (min-width: 992px) {
    .top{
        display: block;
    }

    .menu__icon img {
        width: 40px;
        height: 40px;
    }
}

/* 🖥 Десктопы (≥1200px) */
@media (min-width: 1200px) {

}

/* 🖥💎 Большие мониторы (≥1400px) */
@media (min-width: 1400px) {

}

/* 🖥 Ultra-wide 2K (≥1600px) */
@media (min-width: 1600px) {

}

/* 🖥 4K дисплеи (≥1920px) */
@media (min-width: 1920px) {

}
</style>
