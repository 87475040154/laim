<template>
    <!-- МОЙ КАБИНЕТ -->

    <!-- Backdrop -->
    <transition name="myAccount__animation-backdrop">

        <div v-if="myAccountAnimation" class="myAccount__backdrop" @click="$router.back()"></div>

    </transition>

    <!-- Обвертка - Компонента -->
    <transition name="myAccount__animation-wrapper">

        <div v-if="myAccountAnimation" class="myAccount__wrapper">

            <!-- Сам блок -->
            <div class="myAccount__block">

                <!-- Header -->
                <div class="myAccount__header">

                    <div>{{authStore.user.name}}</div>

                    <div class="mx-2">ID: {{authStore.user.id}}</div>

                    <v-chip size="small" class="bg-red-darken-2" v-if="authStore.user.role == 'admin'">Админ</v-chip>

                    <v-spacer></v-spacer>

                    <!-- Кнопка назад -->
                    <v-btn icon dark variant="text" class="mx-2"  @click="$router.back()">
                        <v-icon>mdi-arrow-right</v-icon>
                    </v-btn>

                </div>

                <!-- Body -->
                <div class="myAccount__body">

                    <!-- Ссылка скачать договор аренды / Поделиться договором аренды  -->
                    <div class="alert mt-4">

                        <!-- Текст -->
                        <div class="text-center">
                            <div class="h3 fw-bold">{{ $t('myAccountLeaseContract1') }}</div>
                            <div class="small">{{ $t('myAccountLeaseContract2') }}</div>
                        </div>

                        <!-- Кнопки -->
                        <div class="row g-0 gap-2 pt-3">
                            <v-btn dark color="blue-darken-2" variant="outlined" @click="downloadDogovorArendy" class="col">
                                <i class="bi bi-cloud-arrow-down pr-1"></i>
                                {{ $t('myAccountDownload') }}
                            </v-btn>
                            <v-btn dark color="blue-darken-2" variant="outlined" @click="shareDogovorArendy" class="col">
                                <i class="bi bi-share pr-1"></i>
                                {{ $t('myAccountShare') }}
                            </v-btn>
                        </div>

                    </div>

                    <!-- Правила размещения объявлений / Пользовательское соглашение / Выход -->
                    <div class="myAccount__lings-block">
                        <div @click="$router.push({name: 'myAccountSettings'})" class="myAccount__ling">
                            <v-icon icon="mdi-cog-outline px-4"></v-icon>
                            <span class="myAccount__ling-text">{{ $t('myAccountSettings') }}</span>
                        </div>
                        <div @click="$router.push('/rules')" class="myAccount__ling">
                            <v-icon icon="mdi-book-open-outline px-4"></v-icon>
                            <span class="myAccount__ling-text">{{ $t('myAccountRules') }}</span>
                        </div>
                        <div @click="$router.push('/polzovatelskoeSoglashenie')" class="myAccount__ling">
                            <v-icon icon="mdi-book-open-outline px-4"></v-icon>
                            <span class="myAccount__ling-text">{{ $t('myAccountPolzovatelskoeSoglashenie') }}</span>
                        </div>
                        <div @click="$router.push('/contacts')" class="myAccount__ling">
                            <v-icon icon="mdi-card-account-phone-outline px-4"></v-icon>
                            <span class="myAccount__ling-text">{{ $t('myAccountContacts') }}</span>
                        </div>
                        <div @click="$router.push('/uslugi')" class="myAccount__ling">
                            <v-icon icon="mdi-credit-card-outline px-4"></v-icon>
                            <span class="myAccount__ling-text">{{ $t('myAccountPaidServices') }}</span>
                        </div>
                        <div @click="logout" class="myAccount__ling">
                            <v-icon icon="mdi-account-arrow-left-outline px-4"></v-icon>
                            <span class="myAccount__ling-text">{{ $t('myAccountLogout') }}</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </transition>

    <!-- Внутренние компоненты - Настройки -->
    <router-view></router-view>

</template>

<script>

//Импортируем Store - Общее состояние
import { useAuthStore } from "../../../stores/auth";
import { useAppInstallStore } from "../../../stores/AppInstall";

export default {
    name: "MyAccount",

    data(){
        return {
            //Store - Общее состояние
            authStore: useAuthStore(),
            appInstallStore: useAppInstallStore(),

            myAccountAnimation: false,
        }
    },

    methods: {

        //Метод  -  скачать Договор Аренды
        async downloadDogovorArendy(){

            let link = document.createElement("a");
            link.setAttribute("href",'/docs_pdf/dogovor_arendy.pdf');
            link.setAttribute("download", 'dogovor_arendy.pdf');
            link.click();
        },

        //Метод  -  Поделиться Договором Аренды - Например в Whatsapp
        async shareDogovorArendy(){

            const response = await fetch("/docs_pdf/dogovor_arendy.pdf");
            const buffer = await response.arrayBuffer();

            const pdf = new File([buffer], "hello.pdf", { type: "application/pdf" });
            const files = [pdf];

            // Поделиться файлом
            if (navigator.canShare({ files })) await navigator.share({ files });
        },

        //Метод выхода с аккаунта
        async logout(){

            // Убедитесь, что 'ls' инициализирована перед использованием метода getItem()
            if (localStorage.getItem('getMyLikeAds') !== undefined) {
                localStorage.removeItem('getMyLikeAds');
            }

            this.authStore.logout();
        }
    },

    mounted(){
        let app = this;

        this.myAccountAnimation = true;

        document.querySelector(':root').classList.add('PATCH_modal'); //Отменим прокрутку за OffCanvas

    },

    beforeRouteLeave(to, from, next){
        this.myAccountAnimation = false;
        document.querySelector(':root').classList.remove('PATCH_modal');

        setTimeout(() => {
            next(); // Вызываем next() после завершения setTimeout - Для завершения анимации
        }, 350);
    }
}
</script>

<style>

/* Анимация для плавного появления -- Backdrop */
.myAccount__animation-backdrop-enter-active,
.myAccount__animation-backdrop-leave-active{
    transition: opacity 0.3s ease;
}


.myAccount__animation-backdrop-enter-from,
.myAccount__animation-backdrop-leave-to {
    opacity: 0;
}

.myAccount__animation-backdrop-enter-to,
.myAccount__animation-backdrop-leave-from {
    opacity: 1;
}

/* Анимация для плавного появления - Wrapper */
.myAccount__animation-wrapper-enter-active,
.myAccount__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}

.myAccount__animation-wrapper-enter-from{
    transform: translateX(50%); /* Начальное положение при анимации скрытия */
}

.myAccount__animation-wrapper-leave-to {
    transform: translateX(100%); /* Начальное положение при анимации скрытия */
}

.myAccount__animation-wrapper-enter-to,
.myAccount__animation-wrapper-leave-from {
    transform: translateY(0);/* Конечное положение при анимации появления */
}

</style>

<style scoped>

/* Backdrop */
.myAccount__backdrop{
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: 0;
    background: rgba(0,0,0,0.8);
}

.myAccount__wrapper{
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: auto;
    width: 100%;
    max-width: 450px;
    background: #ffffff;
    user-select: none;
}

.myAccount__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
}

.myAccount__header{
    display: flex;
    align-items: center;
    background: #19b07b;
    color: #ffffff;
    padding: 0 5px;
}

.myAccount__body{
    flex-grow: 1;
    overflow-y: auto;
}

.myAccount__lings-block{
    padding: 10px;
}

.myAccount__ling{
    color: #2a2a2a;
    padding: 15px 0;
}

.myAccount__ling-text{
    font-size: 1.1em;
}

.myAccount__ling:hover{
    background: rgba(0,0,0,0.1);
    cursor: pointer;
    border-radius: 10px;
}
</style>
<!--282-->
