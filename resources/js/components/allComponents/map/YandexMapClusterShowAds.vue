<template>
    <!-- КОМПОНЕНТ - ПОКАЗАТЬ ПРЕВЬЮ ОБЪЯВЛЕНИЙ ПРИ КЛИКЕ НА КЛАСТЕР -->

    <!-- Backdrop -->
    <transition name="yandexMapClusterShowAds__animation-backdrop">

        <div v-if="yandexMapClusterShowAdsAnimation" class="yandexMapClusterShowAds__backdrop" @click="$router.back()"></div>

    </transition>

    <!-- Обвертка - Компонента -->
    <transition name="yandexMapClusterShowAds__animation-wrapper">

        <!-- Обвертка - Компонента -->
        <div v-if="yandexMapClusterShowAdsAnimation" class="yandexMapClusterShowAds__wrapper">

            <!-- Сам блок -->
            <div class="yandexMapClusterShowAds__block">

                <!-- Header -->
                <div class="yandexMapClusterShowAds__header position-relative">

                    <!-- Кнопка закрыть-->
                    <v-btn role="button" icon size="small" variant="text"
                           class="mx-1"
                           style="position: absolute; top: 5px; right: 5px"
                           dark @click="$router.back()">
                        <v-icon size="large">mdi-close</v-icon>
                    </v-btn>

                </div>

                <!-- Body -->
                <div class="yandexMapClusterShowAds__body">

                    <!-- Компонент для отображения превью всех полученных объявлений  -->
                    <ads-preview-component :ads_arr="ads"></ads-preview-component>

                </div>

            </div>

        </div>

    </transition>

</template>

<script>

//Импортируем - Store - Наше хранилище доступное со всех компонентов
import {useAuthStore} from "../../../stores/auth";
import { useCheckInternetStore } from "../../../stores/checkInternet";


//Импортируем - Компонент Слайдер фото - Swiper
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination, Mousewheel } from 'swiper';
import 'swiper/scss';
import 'swiper/scss/pagination';
import 'swiper/scss/mousewheel';

//Импортируем Компоненты
import adsPreviewComponent from "../../allComponents/AdsPreviewComponent.vue";

export default {
    name: "YandexMapClusterShowAds",

    components: {
        Swiper, SwiperSlide,//Компоненты - Слайдера для фото
        adsPreviewComponent,
    },

    data(){
        return {

            //Подключаем - Store - Наше хранилище
            authStore: useAuthStore(),
            checkInternetStore: useCheckInternetStore(),

            yandexMapClusterShowAdsAnimation: false,

            ads: [],

            modules: [Pagination, Mousewheel], //Подключаем дополнительные модули к слайдеру SWIPER,

        }
    },

    methods: {

        //Получить массив объявлений при клике на кластер
       async getAllAds() {

            this.query = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
           await this.checkInternetStore.checkInternet()
            if(!this.checkInternetStore.online){
                this.query = false;
                return
            }

            this.ads = [];


            //Получаю объявления
            axios.get('/getAllAds', {

                params: {
                    page: 1,
                    user_id: useAuthStore().check ? useAuthStore().user.id : 0,
                    table_name: this.$route.params.table_name,
                    filter: 'Фильтр не применен',
                    getMyLikeAds: 'Не получать мои лайки',
                    arr_ads_id: JSON.parse(localStorage.getItem("mapClusterAdsId")),
                    getAdsYandexClusterer: true
                }
            })
                .then(response => {
                    this.query = false;
                    this.ads = response.data.ads;
                })
                .catch((errors)=>{
                    this.query = false;
                })
        },

    },

    mounted(){
        let app = this;
        this.yandexMapClusterShowAdsAnimation = true;
        document.querySelector(':root').classList.add('PATCH_modal');

        //Id объявлений для выбора по кластеру
        if(localStorage.getItem('mapClusterAdsId') != undefined){
            this.getAllAds();
        }
        else{
            this.$router.back();
        }

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
    height: auto;
}

.yandexMapClusterShowAds__body{
    flex-grow: 1;
    width: 100%;
}



</style>
