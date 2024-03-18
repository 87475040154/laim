<template>
    <!-- КОМПОНЕНТ - ОБЪЯВЛЕНИЯ АВТОРА -->

    <!-- Обвертка - Компонента -->
    <div class="userAds__wrapper">

        <!-- Сам блок-->
        <div class="userAds__block">

            <!-- Header -->
            <div class="userAds__header">

                <!-- Кнопка Назад   -->
                <v-btn icon dark variant="text" class="ml-2"  @click="$router.back()">
                    <v-icon size="large">mdi-arrow-left</v-icon>
                </v-btn>

                <!-- Данные автора объявлений - Имя - Дата регистрации - последняя активность -->
                <div class="d-flex align-items-center ml-n2">

                    <v-icon size="x-large"  style="font-size: 3em" class="mx-1">mdi-account-circle</v-icon>

                    <div>

                        <!-- Имя пользователя -->
                        <div v-if="user_data">
                            <span class="fw-bold"> {{ user_data.name }} </span>

                            <!-- В сети -->
                            <span class="small px-1" v-if="new Date(user_data.last_active).getTime() > new Date().getTime()-60000">
                            {{ $t('userAdsOnline') }}
                        </span>
                            <span class="small px-1" v-else>
                            {{ $t('userAdsWas')}} {{$filters.transformDateRu(user_data.last_active)}}
                        </span>

                        </div>

                        <!-- Дата регистрации пользователя -->
                        <div  style="font-size: 0.7em">{{ $t('userAdsOnLaimWith') }} {{ $filters.transformDateRu(user_data.created_at)}}</div>

                    </div>

                </div>

            </div>

            <!-- Body -->
            <div class="userAds__body">

                <!-- Gif Load  - Если объявления еще не загрузились -->
                <div v-if="query" class="d-flex justify-content-center">
                    <div class="spinner-border spinner-border-sm" role="status"></div>
                </div>

                <!-- Вывожу мои объявления -->
                <ads-preview-component :ads_arr="ads_arr"></ads-preview-component>

                <!-- Если объявления не найденны показать текст-->
                <div v-if="ads_arr != undefined && ads_arr.length < 1" class="text-center text-muted my-3">
                    <h5>{{ $t('userAdsAdsNotFound') }}</h5>
                </div>

            </div>

        </div>

    </div>

    <!-- Внутренние страницы - Покупка рекламы и тд. -->
    <router-view></router-view>

</template>

<script>

//Импортирую Store - Наше общее состояние
import {useAuthStore} from "../../../stores/auth";
import {useCheckInternetStore } from "../../../stores/checkInternet";

//Импортирую компоненты
import adsPreviewComponent from "../AdsPreviewComponent.vue";


//Laravel Vue Pagination
import { Bootstrap5Pagination } from 'laravel-vue-pagination';

export default {
    name: "Index",

    components: {
        adsPreviewComponent,
        Bootstrap5Pagination,
    },

    data(){
        return{
            //Подключаю Store - Наше общее состояние
            authStore: useAuthStore(),
            checkInternetStore: useCheckInternetStore(),

            query: false,

            ads_arr: {}, //Массив объявлений автора
            user_data: false, //Данные автора объявлений

        }
    },


    methods: {

        //Получение объявления пользователя или все объявления которые на проверке для админа
       async getAds() {
           this.query = true;

           //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
           this.checkInternetStore.checkInternet();

            this.ads_arr = {};
            this.user_data = '';

            //Получаю все объявления пользователя
            axios.get('/getUserAds', {
                params: {
                    author_id: this.$route.params.author_id,
                }
            })
                .then(response => {
                    this.query = false;

                    //Заносим все объявления
                    this.ads_arr = response.data.allAds;
                    this.user_data = response.data.userData;

                })
                .catch(errors=>{
                    this.query = false;
                    Toast.fire({
                        icon: 'error',
                        title: 'Ошибка'
                    })
                })
        },

    },

    mounted(){
        let app = this;
        document.querySelector(':root').classList.add('PATCH_modal'); //Отменим прокрутку под модальным окном
        this.getAds();

    },

    beforeRouteLeave(to, from, next){
        document.querySelector(':root').classList.remove('PATCH_modal');
        next();
    }



}
</script>

<style scoped>

.userAds__wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--app-bg-color);
    user-select: none;
}

.userAds__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
}

.userAds__header {
    width: 100%;
    height: auto;
    display: flex;
    align-items: center;
    background: var(--app-header-bg-color);
    color: #ffffff;
}

.userAds__body {
    flex-grow: 1; /* Растянем этот блок на всю оставшуюся высоту экрана */
    overflow-y: auto;
}

</style>
