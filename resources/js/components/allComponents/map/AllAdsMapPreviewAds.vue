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
                    <ads-preview-component  :ads_arr="ads_arr"  @get-ads="getAds"></ads-preview-component>

                    <!-- Gif Load  - Если объявления еще не загрузились -->
                    <div v-if="query" class="d-flex justify-content-center py-1">
                        <div class="spinner-border spinner-border-sm" style="color: var(--app-text-color)" role="status"></div>
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

//Импортируем Компоненты
import adsPreviewComponent from "../../allComponents/AdsPreviewComponent.vue";

export default {
    name: "YandexMapClusterShowAds",

    components: {
        adsPreviewComponent,
    },

    data(){
        return {

            //Подключаем - Store - Наше хранилище
            authStore: useAuthStore(),

            yandexMapClusterShowAdsAnimation: false,

            query: false,
            ads_arr: [],

            //Для курсорной навигации
            nextCursor: null,
            isFirstLoad: true,
            isLastLoad: false
        }
    },


    methods: {

        //Получить массив объявлений при клике на кластер
        getAds() {
            if (this.query) return;
            this.query = true;

            // Запрос объявлений
            axios.get('getAllAdsInYandexCluster', {
                params: {
                    cursor: this.nextCursor,
                    user_id: useAuthStore().check ? useAuthStore().user.id : 0,
                    table_name: this.$route.params.table_name,
                    arr_ads_id: JSON.parse(localStorage.getItem("mapClusterAdsId")),
                }
            })
                .then (response => {
                    this.query = false;

                    if (this.isFirstLoad) {
                        // Это первая загрузка данных
                        this.ads_arr = response.data.ads.data;
                        this.isFirstLoad = false; // Сбрасываем флаг после первой загрузки
                    }
                    else if (this.nextCursor !== null) {
                        // Если это не первая загрузка и еще есть данные
                        this.ads_arr = [...this.ads_arr, ...response.data.ads.data];
                    }

                    if(response.data.ads.next_cursor == null){
                        this.isLastLoad = true;
                    }else{
                        this.isLastLoad = false;
                    }
                    this.nextCursor = response.data.ads.next_cursor;
                })
                .catch(errors => {
                    this.query = false; // Сбрасываем флаг при ошибке
                });
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
    text-align: end;
}

.yandexMapClusterShowAds__body{
    flex-grow: 1;
    width: 100%;
}

</style>
