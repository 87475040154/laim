<template>
    <!-- КОМПОНЕНТ - ПОКАЗАТЬ ПРЕВЬЮ ОБЪЯВЛЕНИЙ ПРИ КЛИКЕ НА КЛАСТЕР -->

    <!-- Backdrop -->
    <transition name="yandexMapClusterShowAds__animation-backdrop">

        <div v-if="yandexMapClusterShowAdsAnimation" class="yandexMapClusterShowAds__backdrop" @click="$router.back()"></div>

    </transition>

    <!-- Обвертка - Компонента -->
    <transition name="yandexMapClusterShowAds__animation-wrapper">

        <!-- Обвертка - Компонента -->
        <div v-if="yandexMapClusterShowAdsAnimation" class="yandexMapClusterShowAds__wrapper" >

            <!-- Сам блок -->
            <div class="yandexMapClusterShowAds__block">

                <!-- Header -->
                <div class="yandexMapClusterShowAds__header">

                    <!-- Кнопка закрыть-->
                    <v-btn role="button" icon size="small" variant="text"
                           class="mx-1"
                           dark @click="$router.back()"
                    >
                        <v-icon size="large">mdi-close</v-icon>
                    </v-btn>

                </div>

                <!-- Body -->
                <div class="yandexMapClusterShowAds__body">

                    <!-- Компонент для отображения превью всех полученных объявлений  -->
                    <ads-preview-component  :ads_arr="ads_arr.data"  @get-ads-cursor-paginate="getAdsMobileCursorPaginate"></ads-preview-component>

                    <!-- Gif Load  - Если объявления еще не загрузились -->
                    <div v-if="query" class="d-flex justify-content-center py-1">
                        <div class="spinner-border spinner-border-sm" style="color: var(--app-text-color)" role="status"></div>
                    </div>

                    <!-- Блок с пагинацией  - покажется если найденно более 30 объявлений -->
                    <div v-if="authStore.desktopOrMobile == 'Desktop'" class="mt-3" >

                        <!-- Экран более - 768 px-->
                        <Bootstrap5Pagination :align="'center'" :limit="3" :data="ads_arr"
                                              @pagination-change-page="(page)=> $router.push({name: 'allAdsMapPreviewAds', params: { pages: page } })"
                                              class="d-none d-md-flex"
                        ></Bootstrap5Pagination>

                    </div>

                    <!-- Дочерние компоненты -->
                    <router-view></router-view>

                </div>

            </div>

        </div>

    </transition>

</template>

<script>

//Импортируем - Store - Наше хранилище доступное со всех компонентов
import {useAuthStore} from "../../../stores/auth";
import { useCheckInternetStore } from "../../../stores/checkInternet";

//Импортируем Компоненты
import adsPreviewComponent from "../../allComponents/AdsPreviewComponent.vue";

//Пакет для пагинации Laravel + Vue
import { Bootstrap5Pagination } from 'laravel-vue-pagination';

export default {
    name: "YandexMapClusterShowAds",

    components: {
        adsPreviewComponent,
        Bootstrap5Pagination
    },

    data(){
        return {

            //Подключаем - Store - Наше хранилище
            authStore: useAuthStore(),
            checkInternetStore: useCheckInternetStore(),

            yandexMapClusterShowAdsAnimation: false,

            query: false,

            ads_arr: {},

            //Для курсорной навигации на Мобильных устройствах
            nextCursor: '',
        }
    },

    watch: {

        //Отслеживаем изменение маршрута
        '$route' (to, from) {

            //Если переходим по пагинации
            if(to.name == 'allAdsMapPreviewAds' && from.name == 'allAdsMapPreviewAds'){
                this.getAds()
            }

        },

    },

    methods: {

        //Получить массив объявлений при клике на кластер
       async getAds() {

           this.query = true;

           //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
           await this.checkInternetStore.checkInternet();

           //Обнулим данные
           this.ads_arr = {};

            //Получаю объявления
            axios.get('/getAllAds', {

                params: {
                    page: this.$route.params.pages,
                    user_id: useAuthStore().check ? useAuthStore().user.id : 0,
                    table_name: this.$route.params.table_name,
                    filter: 'Фильтр не применен',
                    getMyLikeAds: 'Не получать мои лайки',
                    cursorPaginate: this.authStore.desktopOrMobile == 'Desktop' ? false: true,
                    arr_ads_id: JSON.parse(localStorage.getItem("mapClusterAdsId")),
                    getAdsYandexClusterer: true
                }
            })
                .then(response => {
                    this.query = false;
                    this.ads_arr = response.data.ads;

                    this.nextCursor = response.data.ads.next_cursor;
                })
                .catch((errors)=>{
                    this.query = false;
                })
        },

        // Метод получения объявлений на мобильных устройствах - курсорной погинацией
        getAdsMobileCursorPaginate() {

            if( this.query || this.nextCursor == null )return;

            this.query = true;

            axios.get('getAllAds', {
                params: {
                    cursor: this.nextCursor,
                    user_id: useAuthStore().check ? useAuthStore().user.id : 0,
                    table_name: this.$route.params.table_name,
                    filter: 'Фильтр не применен',
                    getMyLikeAds: 'Не получать мои лайки',
                    cursorPaginate: this.authStore.desktopOrMobile == 'Desktop' ? false: true,
                    arr_ads_id: JSON.parse(localStorage.getItem("mapClusterAdsId")),
                    getAdsYandexClusterer: true
                }
            })
                .then(response => {

                    //Канкатенируем старые обьявления с новыми полученными
                    this.ads_arr.data = [...this.ads_arr.data, ...response.data.ads.data];

                    // Добавим данные для пагинации
                    this.nextCursor = response.data.ads.next_cursor;

                    this.query = false;

                }).catch(()=>{
                this.query = false;
            })
        },

    },

    mounted(){
        let app = this;
        this.yandexMapClusterShowAdsAnimation = true;
        document.querySelector(':root').classList.add('PATCH_modal');

        //Id объявлений для выбора по кластеру
        localStorage.getItem('mapClusterAdsId') != undefined ?
            this.getAds() :
            this.$router.back();

    },

    beforeRouteLeave(to, from, next) {
        this.yandexMapClusterShowAdsAnimation = false; // Установите значение в false перед покиданием маршрута

        setTimeout(() => {
            next(); // Вызываем next() после завершения setTimeout - Для завершения анимации
        }, 350);
    }

}
</script>

<style>
/* Анимация для -- Backdrop */
.yandexMapClusterShowAds__animation-backdrop-enter-active,
.yandexMapClusterShowAds__animation-backdrop-leave-active{
    transition: opacity 0.3s ease;
}

.yandexMapClusterShowAds__animation-backdrop-enter-from,
.yandexMapClusterShowAds__animation-backdrop-leave-to {
    opacity: 0;
}

.yandexMapClusterShowAds__animation-backdrop-enter-to,
.yandexMapClusterShowAds__animation-backdrop-leave-from {
    opacity: 1;
}

/* Анимация для -- Wrapper */
.yandexMapClusterShowAds__animation-wrapper-enter-active,
.yandexMapClusterShowAds__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}


.yandexMapClusterShowAds__animation-wrapper-enter-from {
    transform: translateY(50%); /* Начальное положение при анимации скрытия */
}

.yandexMapClusterShowAds__animation-wrapper-leave-to {
    transform: translateY(100%); /* Начальное положение при анимации скрытия */
}

.yandexMapClusterShowAds__animation-wrapper-enter-to,
.yandexMapClusterShowAds__animation-wrapper-leave-from {
    transform: translateY(0);/* Конечное положение при анимации появления */
}

</style>

<style scoped>

/* Backdrop */
.yandexMapClusterShowAds__backdrop{
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: 0;
    background: rgba(0,0,0,0.5);
}

.yandexMapClusterShowAds__wrapper{
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    max-height: 100%;
    height: auto;
    overflow-y: auto;
}

.yandexMapClusterShowAds__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 850px;
    margin: 0 auto;
    border-radius: 10px 10px 0 0;
    background: var(--app-bg-color);
}


.yandexMapClusterShowAds__header{
    display: flex;
    justify-content: end;
    align-items: center;
    height: auto;
}

.yandexMapClusterShowAds__body{
    flex-grow: 1;
    width: 100%;
}

</style>
