<template>

    <!-- Шапка сайта для компьютерных устройств -->
    <header class="header">

        <!-- Верхний Блок -->
        <div class="d-none d-lg-block py-0" style="background: rgb(63 63 69)">
            <div class="container-lg">

                <!-- Центральный блок шапки сайта менее 760px display:none  -->
                <div class="d-flex g-0 align-items-center p-md-1">

                    <!-- Логотип -->
                    <v-btn variant="text" class="text-body-1 d-flex align-center"
                           @click="appInstallStore.app != ''? appInstallStore.install(): $router.push('/allAds/Kvartira/1')"
                    >
                        <img src="/public/img/siteImg/allImg/logo.svg" width="30" height="30" alt="Логотип">
                    </v-btn>

                    <!-- Ссылки - Мои объявления - Сообщения - Мой кабинет - -->
                    <div class="flex-grow-1">
                        <div v-if="authStore.check" class="d-flex gap-3 align-center justify-end">

                            <!-- Мои обьявлениями -->
                            <v-btn icon size="x-small" color="blue-grey" variant="tonal"
                                   @click="$router.push({name:'userAds', params: {author_id: authStore.user.id, table_name: $route.params.table_name, page: 1}})"
                            >
                                <v-badge v-if="getProjectDataStore.countReturnAds > 0" floating :content="getProjectDataStore.countReturnAds" color="error">
                                    <v-icon class="mt-n2" size="x-large">mdi-bullhorn-outline</v-icon>
                                </v-badge>
                                <v-icon v-else  size="x-large">mdi-bullhorn-outline</v-icon>

                                <v-tooltip activator="parent" location="bottom">{{ $t('headerMy') }}</v-tooltip>
                            </v-btn>

                            <!-- Чаты -->
                            <v-btn icon size="x-small" color="blue-grey" variant="tonal"
                                   @click="$router.push('/chat/null/'+ $route.params.table_name)"
                            >
                                <v-badge floating v-if="getProjectDataStore.countNewMessage > 0" :content="getProjectDataStore.countNewMessage" color="error" style="margin-botom: -5px">
                                    <v-icon class="mt-n2" size="large">mdi-email-outline</v-icon>
                                </v-badge>
                                <v-icon v-else size="large">mdi-email-outline</v-icon>
                                <v-tooltip activator="parent" location="bottom">{{ $t('headerChats') }}</v-tooltip>
                            </v-btn>

                            <!-- Мой аккаунт- Имя пользователя - Показать если авторизован -->
                            <v-btn icon size="x-small" color="blue-grey" variant="tonal"
                                   @click="$router.push('/myAccount/'+ $route.params.table_name)"
                            >
                                <v-icon size="x-large">mdi-account-outline</v-icon>
                                <v-tooltip activator="parent" location="bottom">{{ $t('headerMyAccount') }}</v-tooltip>
                            </v-btn>

                        </div>
                    </div>


                    <!-- Кнопка Подать Объявление -->
                    <div class="mx-3">
                        <v-btn rounded="lg" class="text-body-1 text-white" style="background: #fc3441"
                               @click="authStore.check ? $router.push('/addAdsMenu/' + $route.params.table_name) : $router.push('/auth')"
                        >
                            {{ $t('headerSubmitAnAd')}}
                        </v-btn>
                    </div>

                </div>

            </div>
        </div>

        <!-- МЕНЮ - КАТЕГОРИИ - ЧТО ПОКАЗАТЬ -->
        <v-item-group class="d-flex gap-2 justify-content-md-center text-center p-1 pt-3 pb-3 p-md-2 py-md-3" style="overflow: auto">

            <!-- Квартиры -->
            <div @click="changeMenu('/allAds/Kvartira/1')">
                <div class="icon__wrapper icon__wrapper-apartments elevation-3" :class="{'icon__wrapper-active':$route.params.table_name=='Kvartira'}">
                    <img src="/public/img/siteImg/menuImg/1.svg" alt="Квартиры" width="35" height="35">
                    <small>{{ $t("headerMenuApartments")}}</small>
                </div>
            </div>

            <!-- Общежития -->
            <div @click="changeMenu('/allAds/Obshejitie/1')">
                <div class="icon__wrapper icon__wrapper-hostels elevation-3" :class="{'icon__wrapper-active':$route.params.table_name=='Obshejitie'}">
                    <img src="/public/img/siteImg/menuImg/2.svg" alt="Общежития" width="35" height="35">
                    <small>{{ $t("headerMenuHostels")}}</small>
                </div>
            </div>

            <!-- Дома-->
            <div @click="changeMenu('/allAds/Dom/1')">
                <div class="icon__wrapper icon__wrapper-houses elevation-3" :class="{'icon__wrapper-active':$route.params.table_name=='Dom'}">
                    <img src="/public/img/siteImg/menuImg/3.svg" alt="Дома" width="35" height="35">
                    <small>{{ $t("headerMenuHouses")}}</small>
                </div>
            </div>

            <!-- Офиса -->
            <div @click="changeMenu('/allAds/Ofis/1')">
                <div class="icon__wrapper icon__wrapper-offices elevation-3" :class="{'icon__wrapper-active':$route.params.table_name=='Ofis'}">
                    <img src="/public/img/siteImg/menuImg/4.svg" alt="Офиса" width="35" height="35">
                    <small>{{ $t("headerMenuOffice")}}</small>
                </div>
            </div>

            <!-- Здания -->
            <div @click="changeMenu('/allAds/Zdanie/1')">
                <div class="icon__wrapper icon__wrapper-buildings elevation-3" :class="{'icon__wrapper-active':$route.params.table_name=='Zdanie'}">
                    <img src="/public/img/siteImg/menuImg/5.svg" alt="Здания" width="35" height="35">
                    <small>{{ $t("headerMenuBuilding")}}</small>
                </div>
            </div>

            <!-- Магазины -->
            <div @click="changeMenu('/allAds/Magazin/1')">
                <div class="icon__wrapper icon__wrapper-shops elevation-3" :class="{'icon__wrapper-active':$route.params.table_name=='Magazin'}">
                    <img src="/public/img/siteImg/menuImg/6.svg" alt="Магазины" width="35" height="35">
                    <small>{{ $t("headerMenuShops")}}</small>
                </div>
            </div>

            <!-- Промбазы -->
            <div @click="changeMenu('/allAds/Prombaza/1')">
                <div class="icon__wrapper icon__wrapper-bases elevation-3" :class="{'icon__wrapper-active':$route.params.table_name=='Prombaza'}">
                    <img src="/public/img/siteImg/menuImg/7.svg" alt="Промбазы" width="35" height="35">
                    <small>{{ $t("headerMenuBases")}}</small>
                </div>
            </div>

            <!-- Прочая -->
            <div @click="changeMenu('/allAds/Prochaya/1')">
                <div class="icon__wrapper icon__wrapper-other elevation-3" :class="{'icon__wrapper-active':$route.params.table_name=='Prochaya'}">
                    <img src="/public/img/siteImg/menuImg/8.svg" alt="Прочая недвижимость" width="35" height="35">
                    <small>{{ $t("headerMenuOther")}}</small>
                </div>
            </div>

            <!-- Бизнес -->
            <div @click="changeMenu('/allAds/Business/1')">
                <div class="icon__wrapper icon__wrapper-business elevation-3" :class="{'icon__wrapper-active':$route.params.table_name=='Business'}">
                    <i class="bi bi-briefcase-fill" style="color: #988a98; font-size: 1.6em; line-height: 35px"></i>
                    <small>{{ $t("headerMenuBusiness")}}</small>
                </div>
            </div>

            <!-- Сниму -->
            <div @click="changeMenu('/allAds/Snimu/1')">
                <div class="icon__wrapper icon__wrapper-rent elevation-3" :class="{'icon__wrapper-active':$route.params.table_name=='Snimu'}">
                    <i class="bi bi-building-check" style="color: #988a98; font-size: 1.6em; line-height: 35px"></i>
                    <small>{{ $t("headerMenuRent")}}</small>
                </div>
            </div>

        </v-item-group>

    </header>

</template>

<script>

//Импортируем Store - Общее состояние
import { useAuthStore } from "../stores/auth";
import { useGetProjectDataStore } from "../stores/getProjectData";
import { useAppInstallStore } from "../stores/AppInstall";

export default {
    name: "HeaderComponent",

    components:{
    },

    data(){
        return {
            //Подключаем Store - Общее состояние
            authStore: useAuthStore(),
            appInstallStore: useAppInstallStore(),
            getProjectDataStore: useGetProjectDataStore(),
        }
    },

    methods: {

        // Переход по навигации
        changeMenu(link) {
            if(this.$route.fullPath == '/allAds/goryachie/1'){
                this.$router.push(link);
            }else{
                this.$router.replace(link);
            }
        },
    }

}
</script>

<style scoped>

.header{
    user-select: none; /* Отмена выделения текста */
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
    background-color: #fff;
    border-radius: 12px;
    color: #232222;
    font-size: 1.1em;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
    cursor: pointer;
}

.icon__wrapper:hover {
    transform: translateY(-3px);
}

.icon__wrapper-active{
    background: rgb(16, 163, 127);
    color: #ffffff;
}
</style>
