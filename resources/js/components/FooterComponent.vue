<template>

    <!-- Компонент FOOTER - Низ сайта -->
    <footer class="footer">

        <!-- FOOTER DESKTOP -->
        <div class="footer__desktop-wrapper">

            <div class="footer__desktop">

                <!-- Logo | Rules | Contacts -->
                <div class="footer__desktop-top-block">

                    <!-- Logo -->
                    <div @click="appInstallStore.app != ''? appInstallStore.install(): $router.push('/allAds/Kvartira/1')">
                        <img src="/public/img/siteImg/allImg/logo.svg" width="90" height="90" alt="logo">
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

                </div>

                <!-- Copyright -->
                <div class="footer__desktop-bottom-block">
                    &copy; 2022 - {{new Date().getFullYear()}} Laim.kz
                </div>

            </div>

        </div>

        <!-- FOOTER MOBILE -->
        <div class="footer__mobile pb-1">

            <!-- Главная - Laim.kz -->
            <div class="footer__mobile-link">

                <!-- lime.kz - Главная страница -->
                <div v-if="!pageScrolled" @click="$router.push('/allAds/Kvartira/1')">
                    <v-icon>{{$route.name == 'allAds' || $route.path == '/' ? 'mdi-home text-dark' : 'mdi-home-outline'}}</v-icon>
                    <div style="font-size: 0.5em; line-height: 8px">laim.kz</div>
                </div>

                <!-- Кнопка прокрутка страницы вверх -->
                <div v-else @click="scrollTop">
                    <v-icon class="text-dark" style="font-size: 1.5em">mdi-arrow-up-bold</v-icon>
                    <div style="font-size: 0.5em; line-height: 8px">laim.kz</div>
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

            <!-- Чаты -->
            <div  class="footer__mobile-link" @click="authStore.check ? $router.push({ name: 'chat', params: {id: 'null'} }) : $router.push({name: $route.name + 'Auth'})">
                <i class="bi bi-envelope position-relative">
                    <div class="rounded-lg text-white fw-bold"
                         style="position: absolute; top: -10px; right: -15px; background: #c00; padding: 0px 6px; font-size: 12px"
                         v-if="getProjectDataStore.countNewMessage > 0"
                    >
                        {{getProjectDataStore.countNewMessage}}
                    </div>
                </i>
                <div style="font-size: 0.5em; line-height: 8px">{{ $t('footerChats') }}</div>

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

<script>

//Импортирую Store - Общее состояние
import { useAuthStore } from "../stores/auth";
import { useAppInstallStore } from "../stores/AppInstall";
import { useGetProjectDataStore } from "../stores/getProjectData";


export default {
    name: "FooterComponent",

    data(){
        return {
            //Подключаю Store - Общее состояние
            authStore: useAuthStore(),
            appInstallStore: useAppInstallStore(),
            getProjectDataStore: useGetProjectDataStore(),

            //Проверка прокрученна ли страница
            pageScrolled: false,
        }
    },


    methods: {

        //Метод прокрутить страницу Вверх
        scrollTop(){
            window.scroll(0,0)
        }
    },

    mounted(){

        let app = this;

        //Отслеживаем событие прокрутка страницы вниз
        window.onscroll = function() {
            let posTop = (window.pageYOffset !== undefined) ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
            posTop > 0 ? app.pageScrolled = true : app.pageScrolled = false;
        }
    }

}
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
