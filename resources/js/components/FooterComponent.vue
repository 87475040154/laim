<template>

    <!-- Компонент FOOTER - Низ сайта -->
    <footer class="footer">

        <!-- FOOTER DESKTOP -->
        <div class="footer__desktop-wrapper">

            <div class="footer__desktop">

                <!-- Logo | Rules | Contacts -->
                <div class="footer__desktop-top-block">

                    <!-- Logo -->
                    <div @click="appInstallStore.app != ''? appInstallStore.install(): $router.push('/allAds/Kvartira')">
                        <img src="/img/siteImg/allImg/logo.svg" width="90" height="90" alt="logo">
                    </div>

                    <!-- Правила размещения объявлений / Пользовательское соглашение / Выход -->
                    <div class="footer__desktop-lings-block">
                        <div @click="$router.push('/rules')" class="footer__desktop-ling">
                            <v-icon icon="mdi-book-open-outline px-4"></v-icon>
                            <span class="footer__desktop-ling-text">{{ $t('footerRules') }}</span>
                        </div>
                        <div @click="$router.push('/polzovatelskoeSoglashenie')" class="footer__desktop-ling">
                            <v-icon icon="mdi-book-open-outline px-4"></v-icon>
                            <span class="footer__desktop-ling-text">{{ $t('footerPolzovatelskoeSoglashenie') }}</span>
                        </div>
                        <div @click="$router.push('/contacts')" class="footer__desktop-ling">
                            <v-icon icon="mdi-card-account-phone-outline px-4"></v-icon>
                            <span class="footer__desktop-ling-text">{{ $t('footerContacts') }}</span>
                        </div>
                        <div @click="$router.push('/uslugi')" class="footer__desktop-ling">
                            <v-icon icon="mdi-credit-card-outline px-4"></v-icon>
                            <span class="footer__desktop-ling-text">{{ $t('footerPaidServices') }}</span>
                        </div>
                    </div>


                    <!-- Где доступно - Google play и тд.-->
                    <a href="https://play.google.com/store/apps/details?id=kz.laim&hl=ru&gl=US" target="_blank" class="d-flex align-center text-decoration-none px-5">
                        <v-btn variant="outlined" color="blue-grey" class="text-body-2">
                            <i class="bi bi-google-play"></i>
                            <div class="d-flex flex-column text-start px-1">
                                <span>{{ $t('footerAvailableIn') }}</span>
                                <span class="fw-bold">Google Play</span>
                            </div>
                        </v-btn>
                    </a>


                    <!-- Кнопка прокрутки страницы вверх -->
                    <v-btn v-if="pageScrolled"
                           style="position: fixed; bottom: 20px; right: 140px"
                           icon dark @click="scrollTop"
                    >
                        <v-icon>mdi-arrow-up</v-icon>
                    </v-btn>

                </div>

                <!-- Copyright -->
                <div class="footer__desktop-bottom-block">
                    &copy; 2022 - {{new Date().getFullYear()}} Laim.kz
                </div>

            </div>

        </div>

        <!-- FOOTER MOBILE -->
        <div class="footer__mobile">

            <!-- Главная - Лайм.kz -->
            <div class="footer__mobile-link">

                <!-- лайм.kz - Главная страница -->
                <div v-if="!pageScrolled" @click="$router.push('/allAds/Kvartira')">
                    <v-icon>{{$route.name == 'allAds' || $route.path == '/' ? 'mdi-home text-dark' : 'mdi-home-outline'}}</v-icon>
                    <div style="font-size: 0.5em; line-height: 8px">Лайм.kz</div>
                </div>

                <!-- Кнопка прокрутка страницы вверх -->
                <div v-else @click="scrollTop">
                    <v-icon class="text-dark" style="font-size: 1.5em">mdi-arrow-up-bold</v-icon>
                    <div style="font-size: 0.5em; line-height: 8px">Лайм.kz</div>
                </div>

            </div>

            <!-- Мои объявления -->
            <div  class="footer__mobile-link" @click="authStore.check ?$router.push({name:'userAds', params: {author_id: authStore.user.id}}) : $router.push({name: $route.name + 'Auth'})">


                <i class="bi bi-megaphone position-relative">
                    <div class="rounded-lg text-white fw-bold"
                         style="position: absolute; top: -10px; right: -15px; background: #c00; padding: 0px 6px; font-size: 12px"
                         v-if="getProjectDataStore.countReturnAds > 0"
                    >
                        {{getProjectDataStore.countReturnAds}}
                    </div>
                </i>
                <div style="font-size: 0.5em; line-height: 8px">{{ $t('footerMy') }}</div>
            </div>

            <!-- Сдать -->
            <div  class="footer__mobile-link" @click="authStore.check ? $router.push({name: 'addAdsMenu'}) : $router.push({name: $route.name + 'Auth'})">
                <v-icon style="font-size: 2em; color: #10a37f">mdi-plus-box</v-icon>
            </div>

            <!-- Мои избранные -->
            <div  class="footer__mobile-link" @click="authStore.check ?getMyLikeAds = !getMyLikeAds : $router.push({name: $route.name + 'Auth'})">
                <v-icon :icon="getMyLikeAds ? 'mdi-heart' : 'mdi-heart-outline'"
                        :class="{'text-red':getMyLikeAds}"
                        class="icon__heart"
                ></v-icon>
                <div style="font-size: 0.5em; line-height: 8px">{{ 'Избранные' }}</div>
            </div>

            <!-- Мой аккаунт -->
            <div  class="footer__mobile-link" @click="authStore.check ? $router.push({name: 'myAccount'}) : $router.push({name: $route.name + 'Auth'})">
                <v-icon>{{authStore.check ? 'mdi-account': 'mdi-account-arrow-right'}}</v-icon>
                <div style="font-size: 0.5em; line-height: 8px">
                    {{authStore.check ? authStore.user.name: $t('footerLogin')}}
                </div>
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
.footer__desktop{
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
.footer__desktop-lings-block{
    padding: 10px;
}
.footer__desktop-ling{
    padding: 10px 0;
}
.footer__desktop-ling-text{
    font-size: 1em;
}
.footer__desktop-ling:hover{
    background: rgba(37, 37, 37, 0.1);
    cursor: pointer;
    border-radius: 10px;
}

.footer__desktop-bottom-block{
    text-align: center;
}

.footer__mobile{
    display: flex;
    align-items: center;
    text-align: center;
    width: 100%;
    height: auto;
    position: fixed;
    bottom: 0;
    left: 0;
    background: #ffffff;
    color: #888b94;
    border-top: 1px solid rgba(0,0,0,.1);
    font-size: 1.4em;
    padding-bottom: 5px;
}

.footer__mobile-link{
    flex: 1; /* Растянуть на всю длину*/
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
