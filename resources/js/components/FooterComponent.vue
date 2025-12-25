<template>

    <!-- FOOTER - Подвал сайта -->
    <footer class="footer">

        <!-- FOOTER DESKTOP -->
        <div class="footer__desktop-wrapper">

            <div class="footer__desktop-block">

                <!-- Logo | Rules | Contacts -->
                <div class="footer__desktop-top-block">

                    <!-- 🏠 Логотип -->
                    <button @click="$router.push('/allAds/Kvartira')" class="footer__desktop-logo">
                        <img src="/img/siteImg/allImg/logo.svg" width="90" height="90" alt="Логотип"/>
                    </button>

                    <!-- Правила размещения объявлений / Пользовательское соглашение  -->
                    <div class="footer__desktop-links-block">
                        <div @click="$router.push('/rules')" class="footer__desktop-link">{{ $t('footerRules') }}</div>
                        <div @click="$router.push('/polzovatelskoeSoglashenie')" class="footer__desktop-link">{{ $t('footerPolzovatelskoeSoglashenie') }}</div>
                        <div @click="$router.push('/contacts')" class="footer__desktop-link">{{ $t('footerContacts') }}</div>
                        <div @click="$router.push('/uslugi')" class="footer__desktop-link">{{ $t('footerPaidServices') }}</div>
                    </div>


                    <!-- Где доступно - Google play и тд.-->
                    <a href="https://play.google.com/store/apps/details?id=kz.laim&hl=ru&gl=US" target="_blank" class="store-btn">
                        <!-- Иконка Google Play (SVG) -->
                        <svg viewBox="0 0 15 15" width="24px" height="24px" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier">
                            <path d="M1.25097 0.0664289C1.40622 -0.0227402 1.59729 -0.0220988 1.75194 0.0681106L9.59402 4.64266L7.16668 6.82726L1 1.27725V0.5C1 0.320967 1.09572 0.155598 1.25097 0.0664289Z" fill="currentColor"></path>
                            <path d="M1 2.62262V12.3773L6.41925 7.49994L1 2.62262Z" fill="currentColor"></path>
                            <path d="M1 13.7226V14.5C1 14.679 1.09572 14.8444 1.25097 14.9336C1.40622 15.0227 1.59729 15.0221 1.75194 14.9319L9.59409 10.3573L7.16668 8.17262L1 13.7226Z" fill="currentColor"></path>
                            <path d="M10.5011 9.82822L13.7519 7.93189C13.9055 7.84228 14 7.67783 14 7.5C14 7.32217 13.9055 7.15772 13.7519 7.06811L10.501 5.17173L7.9141 7.49994L10.5011 9.82822Z" fill="currentColor"></path> </g></svg>
                        <!-- Текст -->
                        <div class="store-btn__text">
                            <span class="store-btn__subtitle">{{ $t('footerAvailableIn') }}</span>
                            <span class="store-btn__title">Google Play</span>
                        </div>
                    </a>


                    <!-- Кнопка прокрутки страницы вверх -->
                    <button v-if="pageScrolled" class="footer__desktop-scroll-top-btn" @click="scrollTop">
                        <svg viewBox="0 0 24 24" width="24px" height="24px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 5V19M12 5L6 11M12 5L18 11" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    </button>

                </div>

                <!-- Текст - Copyright -->
                <div class="footer__desktop-bottom-block">
                    &copy; 2022 - {{new Date().getFullYear()}} Лайм.kz
                </div>

            </div>

        </div>

        <!-- FOOTER MOBILE -->
        <div class="footer__mobile">

            <!-- Главная - Лайм.kz -->
            <div class="footer__mobile-link-block" @click="pageScrolled ? scrollTop : $router.push('/allAds/Kvartira')">

                <!-- лайм.kz - Главная страница -->
                <svg v-if="pageScrolled" fill="#000000" width="24px" height="28px" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <style type="text/css"> .st0{fill:#000000;} </style> <g> <polygon class="st0" points="256.003,0 18.618,316.508 153.601,316.508 153.601,512 358.4,512 358.4,316.508 493.382,316.508 "></polygon> </g> </g></svg>
                <svg v-else  xmlns="http://www.w3.org/2000/svg" fill="#000000" width="28px" height="28px" viewBox="0 0 24 24"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"></path></g></svg>

                <div class="footer__mobile-link-text">Лайм.kz</div>

            </div>

            <!-- Мои объявления -->
            <div  class="footer__mobile-link-block" @click="authStore.check ?$router.push({name:'userAds', params: {author_id: authStore.user.id}}) : $router.push({name: $route.name + 'Auth'})">
                <!-- 🔸 Кол-во объявлений на доработку -->
                <div class="icon-wrapper">
                    <!-- Бейдж -->
                    <div v-if="getProjectDataStore.countReturnAds > 0" class="badge">
                        {{ getProjectDataStore.countReturnAds }}
                    </div>

                    <!-- Иконка -->
                    <svg fill="currentColor" width="23px" height="21px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>bullhorn</title> <path d="M26.588 1.896c-0.17-0.092-0.372-0.146-0.587-0.146-0.261 0-0.503 0.080-0.704 0.217l0.004-0.003c-6.053 4.080-12.829 6.047-23.389 6.789-0.651 0.047-1.162 0.588-1.162 1.247v0 12c0 0.666 0.521 1.21 1.177 1.248l0.003 0c0.63 0.035 1.214 0.093 1.819 0.14v6.612c0 0.69 0.56 1.25 1.25 1.25h5c0.69-0 1.25-0.56 1.25-1.25v-5.606c5.325 1.063 10.049 3.031 14.236 5.756l-0.156-0.095c0.19 0.122 0.422 0.195 0.67 0.195 0.221 0 0.428-0.057 0.607-0.158l-0.006 0.003c0.389-0.216 0.649-0.625 0.649-1.094 0-0.001 0-0.001 0-0.002v0-26c-0-0.475-0.265-0.888-0.655-1.1l-0.007-0.003zM3.25 11.159c2.735-0.221 5.202-0.542 7.5-0.953v11.545c-1.776-0.325-3.65-0.577-5.64-0.76-0.038-0.003-0.071-0.022-0.11-0.022-0.015 0-0.028 0.008-0.043 0.009-0.568-0.051-1.119-0.111-1.707-0.151zM8.75 28.75h-2.5v-5.132c0.85 0.095 1.695 0.192 2.5 0.316zM24.75 26.783c-3.313-1.94-7.15-3.492-11.215-4.454l-0.285-0.057v-12.566c4.342-0.941 8.187-2.474 11.682-4.537l-0.182 0.099zM30 10.75c-0.69 0-1.25 0.56-1.25 1.25v8c0 0.69 0.56 1.25 1.25 1.25s1.25-0.56 1.25-1.25v0-8c-0-0.69-0.56-1.25-1.25-1.25h-0z"></path> </g></svg>
                </div>
                <div class="footer__mobile-link-text">{{ $t('footerMy') }}</div>
            </div>

            <!-- Сдать -->
            <div  class="footer__mobile-link-block" @click="authStore.check ? $router.push({name: 'addAdsMenu'}) : $router.push({name: $route.name + 'Auth'})">
                <svg viewBox="0 0 24 24" fill="none" width="48" height="48" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <rect width="24" height="24" fill="white"></rect> <path fill-rule="evenodd" clip-rule="evenodd" d="M13 9C13 8.44772 12.5523 8 12 8C11.4477 8 11 8.44772 11 9V11H9C8.44772 11 8 11.4477 8 12C8 12.5523 8.44772 13 9 13H11V15C11 15.5523 11.4477 16 12 16C12.5523 16 13 15.5523 13 15V13H15C15.5523 13 16 12.5523 16 12C16 11.4477 15.5523 11 15 11H13V9ZM7.25007 2.38782C8.54878 2.0992 10.1243 2 12 2C13.8757 2 15.4512 2.0992 16.7499 2.38782C18.06 2.67897 19.1488 3.176 19.9864 4.01358C20.824 4.85116 21.321 5.94002 21.6122 7.25007C21.9008 8.54878 22 10.1243 22 12C22 13.8757 21.9008 15.4512 21.6122 16.7499C21.321 18.06 20.824 19.1488 19.9864 19.9864C19.1488 20.824 18.06 21.321 16.7499 21.6122C15.4512 21.9008 13.8757 22 12 22C10.1243 22 8.54878 21.9008 7.25007 21.6122C5.94002 21.321 4.85116 20.824 4.01358 19.9864C3.176 19.1488 2.67897 18.06 2.38782 16.7499C2.0992 15.4512 2 13.8757 2 12C2 10.1243 2.0992 8.54878 2.38782 7.25007C2.67897 5.94002 3.176 4.85116 4.01358 4.01358C4.85116 3.176 5.94002 2.67897 7.25007 2.38782Z" fill="#10a37f"></path> </g></svg>
            </div>

            <!-- Мои избранные -->
            <div  class="footer__mobile-link-block" @click="authStore.check ?getMyLikeAds = !getMyLikeAds : $router.push({name: $route.name + 'Auth'})">
                <svg width="26px" height="26px" :fill="getMyLikeAds ? '#ff3b30' : 'currentColor'" viewBox="0 0 24 24">
                    <path v-if="getMyLikeAds"  d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5 c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    <path v-else d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5 c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zM12 19.55l-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5 18.5 5 20 6.5 20 8.5c0 2.89-3.14 5.74-7.9 10.95l-.1.1z"/>
                </svg>
                <div class="footer__mobile-link-text">{{ $t('footerFavorites') }}</div>
            </div>

            <!-- Мой аккаунт -->
            <div  class="footer__mobile-link-block" @click="authStore.check ? $router.push({name: 'myAccount'}) : $router.push({name: $route.name + 'Auth'})">
                <svg viewBox="0 0 16 16" fill="none" width="26px" height="26px" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M8 7C9.65685 7 11 5.65685 11 4C11 2.34315 9.65685 1 8 1C6.34315 1 5 2.34315 5 4C5 5.65685 6.34315 7 8 7Z" fill="currentColor"></path> <path d="M14 12C14 10.3431 12.6569 9 11 9H5C3.34315 9 2 10.3431 2 12V15H14V12Z" fill="currentColor"></path> </g></svg>
                <div class="footer__mobile-link-text">{{authStore.check ? authStore.user.name: $t('footerLogin')}}</div>
            </div>

        </div>

    </footer>

</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useScroll, useLocalStorage  } from '@vueuse/core'

// Импортируем Pinia Store
import { useAuthStore } from "../stores/auth";
import { useAppInstallStore } from "../stores/AppInstall";
import { useGetProjectDataStore } from "../stores/getProjectData";
import { useUpdateDateLocaleStore } from "../stores/updateDateLocale";

// 🔹 Подключаем Stores
const authStore = useAuthStore()
const appInstallStore = useAppInstallStore()
const getProjectDataStore = useGetProjectDataStore()
const updateDateLocaleStore = useUpdateDateLocaleStore()

// 🔹 Проверка прокручена ли страница
const pageScrolled = ref(false)

// создаём реактивный ref, который синхронизирован с localStorage
const getMyLikeAds = useLocalStorage('getMyLikeAds', false)

// 🔹 Метод прокрутки страницы вверх
function scrollTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' })
}

// 🔹 Слежка за скроллом
function handleScroll() {
    const posTop = window.pageYOffset || document.documentElement.scrollTop
    pageScrolled.value = posTop > 0
}

// 🔹 Подписка на событие скролла при монтировании
onMounted(() => {
    window.addEventListener('scroll', handleScroll)
})

// 🔹 Убираем слушатель при размонтировании
onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>

.footer{
    user-select: none; /* Отмена выделения текста */
}

.footer__desktop-wrapper{
    display: none;
    background: rgb(63,63,69);
    color: #ffffff;
}
.footer__desktop-block{
    width: 90%;
    max-width: 1200px;
    margin: auto;
    padding: 5px;
    color: #607D8B;
}
.footer__desktop-top-block{
    display: flex;
    align-items: center;
}
.footer__desktop-logo {
    display: flex;
    align-items: center;
    border: none;
    background: none;
    cursor: pointer;
}
.footer__desktop-links-block{
    padding: 10px 50px;
}
.footer__desktop-link{
    padding: 10px 0;
}
.store-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    border-radius: 8px;
    border: 1px solid #607D8B;
    text-decoration: none;
    color: #607D8B;
    transition: background 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
}
.store-btn__text {
    display: flex;
    flex-direction: column;
    text-align: left;
    line-height: 1.1;
}
.footer__desktop-scroll-top-btn {
    position: fixed;
    bottom: 20px;
    right: 140px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    background: #ffffff;              /* белый фон */
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    transition: background 0.2s ease, transform 0.2s ease;
}
.footer__desktop-scroll-top-btn:hover {
    background: #dfdfdf;
    transform: translateY(-2px);
}
.footer__desktop-scroll-top-btn:active {
    transform: translateY(0);
}
.footer__desktop-bottom-block{
    text-align: center;
}




.footer__mobile {
    display: flex;
    align-items: center;
    justify-content: space-around;
    width: 100%;
    position: fixed;
    bottom: 0;
    left: 0;
    background: #ffffff;
    color: #888b94;
    border-top: 1px solid rgba(0,0,0,.1);
    font-size: 1.4em;
    padding: 5px 0;
}
.footer__mobile-link-block {
    flex: 1;
    text-align: center;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}
.icon-wrapper {
    position: relative; /* Бейдж привязывается сюда */
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.badge {
    position: absolute;
    top: -4px;
    right: -6px;
    background: #ff3b30;
    color: #fff;
    font-size: 11px;
    padding: 2px 5px;
    border-radius: 10px;
    font-weight: 700;
    line-height: 1;
    min-width: 16px;
    text-align: center;
    z-index: 1;
}
.footer__mobile-link-text {
    font-size: 0.5em;
    line-height: 1em;
    margin-top: 2px;
}


/* При экране более 992px для компьютера */
@media screen and (min-width: 992px){

    .footer__desktop-wrapper{
        display: block;
    }
    .footer__mobile{
        display: none;
    }
}

</style>
<!--252-->
