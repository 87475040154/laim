<template>
    <!-- КОМПОНЕНТ - МЕНЮ - ЧТО СДАТЬ -->

    <!-- Backdrop -->
    <transition name="addAdsMenu__animation-backdrop">
        <div v-if="addAdsMenuAnimation" class="addAdsMenu__backdrop" @click="$router.back()"></div>
    </transition>

    <!-- Обвертка - Компонента -->
    <transition name="addAdsMenu__animation-wrapper">

        <!-- Обвертка - Компонента -->
        <div v-if="addAdsMenuAnimation" class="addAdsMenu__wrapper">

            <!-- Сам блок -->
            <div class="addAdsMenu__block">

                <!-- Header -->
                <div class="addAdsMenu__header">

                    <!-- Заголовок -->
                    <div>{{ $t('addAdsMenuWhatToHandOver') }}</div>

                    <!-- Кнопка назад -->
                    <v-btn icon dark variant="text"
                           @click="$router.back()"
                           style="position: absolute; top: 0; right: 10px;"
                    >
                        <v-icon class="d-none d-lg-block">mdi-arrow-right</v-icon>
                        <v-icon class="d-block d-lg-none">mdi-close</v-icon>
                    </v-btn>

                </div>

                <!-- Body -->
                <div class="addAdsMenu__body">

                    <!-- Если У пользователя уже 10 объявлений-->
                    <div v-if="error" class="alert alert-danger" role="alert">
                        <strong>{{ $t('addAdsMenuLimit10Ads') }}</strong>
                        <div>
                            {{ $t('addAdsMenuLimit10Ads2') }}
                        </div>
                    </div>

                    <!-- Меню - что сдать?-->
                    <div class="menu__block">

                        <!-- Квартира -->
                        <div @click="showForm('Kvartira')">
                            <div class="icon__wrapper icon__wrapper-apartments">
                                <img  src="/public/img/siteImg/menuImg/1.svg" alt="Квартиры" width="35" height="35">
                                <small>{{ $t("addAdsMenuApartment")}}</small>
                            </div>
                        </div>

                        <!-- Общежития -->
                        <div @click="showForm('Obshejitie')">
                            <div class="icon__wrapper icon__wrapper-hostels">
                                <img src="/public/img/siteImg/menuImg/2.svg" alt="Общежития" width="35" height="35">
                                <small>{{ $t("addAdsMenuHostel")}}</small>
                            </div>
                        </div>

                        <!-- Дома-->
                        <div @click="showForm('Dom')">
                            <div class="icon__wrapper icon__wrapper-houses">
                                <img src="/public/img/siteImg/menuImg/3.svg" alt="Дома" width="35" height="35">
                                <small>{{ $t("addAdsMenuHouse")}}</small>
                            </div>
                        </div>

                        <!-- Офиса -->
                        <div @click="showForm('Ofis')">
                            <div class="icon__wrapper icon__wrapper-offices">
                                <img src="/public/img/siteImg/menuImg/4.svg" alt="Офиса" width="35" height="35">
                                <small>{{ $t("addAdsMenuOffice")}}</small>
                            </div>
                        </div>

                        <!-- Здания -->
                        <div @click="showForm('Zdanie')">
                            <div class="icon__wrapper icon__wrapper-buildings">
                                <img src="/public/img/siteImg/menuImg/5.svg" alt="Здания" width="35" height="35">
                                <small>{{ $t("addAdsMenuBuilding")}}</small>
                            </div>
                        </div>

                        <!-- Магазины -->
                        <div @click="showForm('Magazin')">
                            <div class="icon__wrapper icon__wrapper-shops">
                                <img src="/public/img/siteImg/menuImg/6.svg" alt="Магазины" width="35" height="35">
                                <small>{{ $t("addAdsMenuShop")}}</small>
                            </div>
                        </div>

                        <!-- Промбаза -->
                        <div @click="showForm('Prombaza')">
                            <div class="icon__wrapper icon__wrapper-bases">
                                <img src="/public/img/siteImg/menuImg/7.svg" alt="Промбазы" width="35" height="35">
                                <small>{{ $t("addAdsMenuBase")}}</small>
                            </div>
                        </div>

                        <!-- Прочая -->
                        <div @click="showForm('Prochaya')">
                            <div class="icon__wrapper icon__wrapper-other">
                                <img src="/public/img/siteImg/menuImg/8.svg" alt="Прочая недвижимость" width="35" height="35">
                                <small>{{ $t("addAdsMenuOther")}}</small>
                            </div>
                        </div>

                        <!-- Бизнес -->
                        <div @click="showForm('Business')">
                            <div class="icon__wrapper icon__wrapper-business">
                                <i class="bi bi-briefcase-fill" style="color: rgb(118 118 118); font-size: 2em; line-height: 35px"></i>
                                <small>{{ $t("addAdsMenuBusiness")}}</small>
                            </div>
                        </div>

                        <!-- Сниму -->
                        <div @click="showForm('Snimu')">
                            <div class="icon__wrapper icon__wrapper-rent">
                                <i class="bi bi-building-check" style="color: rgb(118 118 118); font-size: 1.6em; line-height: 35px"></i>
                                <small>{{ $t("addAdsMenuRent")}}</small>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </transition>

    <!-- Вывод - Вложенных компонентов -->
    <router-view></router-view>

</template>

<script>

//Импортирую Store
import { useAuthStore } from "../../../stores/auth";
import { useCheckInternetStore } from "../../../stores/checkInternet";

export default {
    name: "AddAdsMenu",

    data(){
        return {
            //Подключаю Store
            authStore: useAuthStore(),
            checkInternetStore: useCheckInternetStore(),

            addAdsMenuAnimation: false,

            error: false
        }
    },

    methods: {

        //Перейти в форму добавления объявлений
        showForm(table_name){

            if(this.authStore.user.role == 'admin'){
                this.$router.push({ name: 'allAdsAddAds', params: {table_name: table_name, id: 'null', step: 1} })
            }
            else{
                //Проверка - Если у пользователя 10 Объявлений то не открывать форму
                if(this.authStore.user.count_ads >= 10){
                    this.error = true;
                }else{
                    this.$router.push({name: 'allAdsAddAds', params: {table_name: table_name, id: 'null', step: 1} })
                }
            }
        },

    },


    async mounted(){
        let app = this;

        this.addAdsMenuAnimation = true;
        document.querySelector(':root').classList.add('PATCH_modal'); // Отменим прокрутку под компонентом

        //Получим новые данные User
        this.checkInternetStore.checkInternet()
        this.authStore.getUser();

        if(this.authStore.user.role == 'user' && this.authStore.user.count_ads >= 10){
            app.error = true;
        }

    },

    beforeRouteLeave(to, from, next) {

        this.addAdsMenuAnimation = false;

        document.querySelector(':root').classList.remove('PATCH_modal');

        setTimeout(() => {
            next(); // Вызываем next() после завершения setTimeout - Для завершения анимации
        }, 350);

    }

}
</script>

<style>

/* Анимация -- Backdrop  */
.addAdsMenu__animation-backdrop-enter-active,
.addAdsMenu__animation-backdrop-leave-active{
    transition: opacity 0.3s ease;
}


.addAdsMenu__animation-backdrop-enter-from,
.addAdsMenu__animation-backdrop-leave-to{
    opacity: 0;
}

.addAdsMenu__animation-backdrop-enter-to,
.addAdsMenu__animation-backdrop-leave-from{
    opacity: 1;
}

/* Анимация для -- Wrapper */
.addAdsMenu__animation-wrapper-enter-active,
.addAdsMenu__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}

.addAdsMenu__animation-wrapper-enter-from {
    transform: translateY(50%); /* Начальное положение при анимации скрытия */
}

.addAdsMenu__animation-wrapper-leave-to {
    transform: translateY(100%); /* Начальное положение при анимации скрытия */
}

.addAdsMenu__animation-wrapper-enter-to,
.addAdsMenu__animation-wrapper-leave-from {
    transform: translateY(0);/* Конечное положение при анимации появления */
}

/* При экране более 992px */
@media screen and (min-width: 992px) {
    .addAdsMenu__animation-wrapper-enter-from{
        transform: translateX(50%); /* Начальное положение при анимации скрытия */
    }

    .addAdsMenu__animation-wrapper-leave-to {
        transform: translateX(100%); /* Начальное положение при анимации скрытия */
    }

    .addAdsMenu__animation-wrapper-enter-to,
    .addAdsMenu__animation-wrapper-leave-from {
        transform: translateX(0);/* Конечное положение при анимации появления */
    }

}

</style>

<style scoped>

/* Backdrop */
.addAdsMenu__backdrop{
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: 0;
    background: rgba(0,0,0,0.8);
}

.addAdsMenu__wrapper{
    position: fixed;
    bottom:0;
    left: 0;
    width: 100%;
    height: auto;
    overflow-y: auto;
    border-radius: 10px 10px 0 0;
    background: #ffffff;
    user-select: none;
}

.addAdsMenu__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
}

.addAdsMenu__header{
    width: 100%;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5em;
    font-weight: bold;
    position: relative;
    background: #ffffff;
    color: #000000;
}

.addAdsMenu__body{
    flex-grow: 1;
}


/* Для ссылок меню */
.menu__block{
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    padding: 20px 0;
}

/* Стили для кнопок меню */
.icon__wrapper {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin: auto;
    width: 80px;
    height: 80px;
    border-radius: 12px;
    color: #232222;
    font-size: 1.1em;
    transition: transform 0.2s;
    cursor: pointer;
    border: 1px solid #434343;
}

.icon__wrapper:hover {
    transform: translateY(-5px);
}



/* При экране более 992px */
@media screen and (min-width: 992px){

    .addAdsMenu__wrapper{
        max-width: 450px;
        top: 0;
        bottom:0;
        right: 0;
        left: auto;
        border-radius: 0;
    }

    .addAdsMenu__header{
        background: var(--app-header-bg-color);
        color: #ffffff;

    }

}

</style>
