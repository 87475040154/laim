<template>
    <header
        ref="headerRef"
        class="header"
        :class="{ 'header--fixed': isFixed, 'header--hidden': isHidden }"
        :style="headerStyle"
    >
        <!-- Верхняя панель -->
        <div class="top">
            <div class="top__inner">
                <!-- Логотип -->
                <button class="logo" @click="onLogoClick">
                    <img src="/img/siteImg/allImg/logo.svg" width="30" height="30" alt="Логотип" />
                </button>

                <div class="spacer"></div>

                <!-- Правая часть -->
                <div class="right-group">
                    <div class="actions">
                        <!-- Избранные -->
                        <button v-if="authStore.check" class="icon-btn" @click="getMyLikeAdsFn">
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

                        <!-- Мои объявления -->
                        <button
                            v-if="authStore.check"
                            class="icon-btn"
                            @click="$router.push({ name: 'userAds', params: { author_id: authStore.user.id } })"
                        >
                            <div v-if="getProjectDataStore.countReturnAds > 0" class="badge">
                                {{ getProjectDataStore.countReturnAds }}
                            </div>
                            <svg width="24" height="24" fill="#fff" viewBox="0 0 24 24">
                                <path
                                    d="M4 4h16v2H4zm0 7h16v2H4zm0 7h16v2H4z"
                                />
                            </svg>
                        </button>

                        <!-- Мой аккаунт -->
                        <button
                            v-if="authStore.check"
                            class="icon-btn"
                            @click="$router.push({ name: 'myAccount' })"
                        >
                            <svg width="24" height="24" fill="#fff" viewBox="0 0 24 24">
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4
                    1.79-4 4 1.79 4 4 4zm0
                    2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"
                                />
                            </svg>
                        </button>
                    </div>

                    <button class="submit-btn" @click="onSubmitClick">
                        {{ $t('headerSubmitAnAd') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Категории -->
        <nav class="menu">
            <div
                v-for="(item, i) in categories"
                :key="i"
                class="menu__item"
                @click="$router.replace(item.link)"
            >
                <div class="menu__icon" :class="{ active: $route.params.table_name === item.name }">
                    <img :src="item.icon" :alt="item.label" width="35" height="35" />
                </div>
                <small>{{ $t(item.label) !== item.label ? $t(item.label) : item.fallback }}</small>
            </div>
        </nav>
    </header>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useScroll, useElementSize } from '@vueuse/core'
import { useAuthStore } from '../stores/auth'
import { useGetProjectDataStore } from '../stores/getProjectData'
import { useAppInstallStore } from '../stores/AppInstall'

const authStore = useAuthStore()
const getProjectDataStore = useGetProjectDataStore()
const appInstallStore = useAppInstallStore()

const headerRef = ref(null)
const { height: headerHeight } = useElementSize(headerRef)
const { y, directions } = useScroll(window)

const isFixed = ref(false)
const isHidden = ref(false)
let lastY = 0
const DELTA = 10

watch(y, (newY) => {
    const diff = newY - lastY
    if (Math.abs(diff) < DELTA) return

    if (newY <= 0) {
        isFixed.value = false
        isHidden.value = false
    } else if (directions.bottom) {
        isHidden.value = true
    } else if (directions.top) {
        isFixed.value = true
        isHidden.value = false
    }
    lastY = newY
})

const headerStyle = computed(() => ({
    transform: isHidden.value ? `translateY(-${headerHeight.value}px)` : 'translateY(0)',
    transition: 'transform 220ms cubic-bezier(.2,.9,.2,1)',
}))

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

function onLogoClick() {
    if (appInstallStore.app !== '') appInstallStore.install()
    else $router.push('/allAds/Kvartira')
}

function onSubmitClick() {
    if (authStore.check) $router.push({ name: 'addAdsMenu' })
    else $router.push({ name: $route.name + 'Auth' })
}
</script>

<style scoped>
.header {
    position: relative;
    width: 100%;
    background: #eeeeee;
    will-change: transform;
    z-index: 1000;
}
.header--fixed {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
}
.top {
    background: rgb(63 63 69);
    color: #fff;
    padding: 8px 0;
}
.top__inner {
    display: flex;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
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
    gap: 16px;
}
.actions {
    display: flex;
    gap: 12px;
    align-items: center;
}
.icon-btn {
    position: relative;
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
}
.badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #ff3b30;
    color: #fff;
    font-size: 11px;
    padding: 2px 5px;
    border-radius: 8px;
    font-weight: 700;
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
    justify-content: center;
    gap: 12px;
    flex-wrap: nowrap;
    overflow-x: auto;
    padding: 10px 8px 14px;
}
.menu__item {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    min-width: 70px;
    user-select: none;
}
.menu__icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f9f9f9;
    margin-bottom: 5px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    transition: background 0.2s;
}
.menu__icon.active {
    background: #19b07b;
    color: #fff;
}
.menu small {
    font-size: 13px;
    color: #333;
}
</style>
