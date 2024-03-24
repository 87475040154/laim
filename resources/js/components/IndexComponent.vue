<template>

    <!-- Вывод - дочерних  компонентов например: Фильтр, язык и тд. -->
    <div style="position: relative; z-index: 2">
        <router-view></router-view>
    </div>


    <!-- Страница вывод - Превью всех объявлений -->
    <div class="index__page">

        <!-- Блок - Сколько найденно объявлений - кнопка открыть фильтр - кнопка полуить мои like -->
        <div class="index-page__top-panel my-2">

            <!-- Всего - Сколько найденно объявлений по данному запросу -->
            <div>{{ $t('indexFound') }} {{count_ads}}</div>

            <!-- Кнопка фильтр -->
            <div @click="$router.push({name: 'filter'})" role="button">
                {{ $t('indexFilter') }}
                <v-badge floating v-if="countFilter > 0" :content="countFilter" color="error">
                    <i class="bi bi-hdd-stack-fill"></i>
                </v-badge>
                <i v-else class="bi bi-hdd-stack-fill"></i>

                <v-tooltip activator="parent" location="bottom">{{ $t('indexMakeFilter') }}</v-tooltip>
            </div>

            <!-- Кнопка показать мои лайки  -->
            <div>
                <v-icon @click="authStore.check ? getMyLike(): $router.push({name: $route.name + 'Auth'})"
                        :icon="getMyLikeAds ? 'mdi-heart' : 'mdi-heart-outline'"
                        :class="{'text-red':getMyLikeAds}"
                        class="icon__heart"
                ></v-icon>
                <v-tooltip activator="parent" location="bottom">{{ $t('indexMyFavorites') }}</v-tooltip>
            </div>


        </div>


        <!-- Компонент превью объявлений -->
        <ads-preview-component  :ads_arr="ads_arr.data" :getMyLikeAds="getMyLikeAds" @get-ads-cursor-paginate="getAdsMobileCursorPaginate"></ads-preview-component>

        <!-- Gif Load  - Если объявления еще не загрузились -->
        <div v-if="query" class="d-flex justify-content-center py-1">
            <div class="spinner-border spinner-border-sm" style="color: var(--app-text-color)" role="status"></div>
        </div>

        <!-- Если объявления не найденны покажется текст -->
        <div v-if="count_ads == 0 && !query" class="text-center text-muted my-3">
            <h4>{{ $t('indexAdsNotFound') }}</h4>
        </div>

        <!-- Кнопка -  установить приложение laim.kz  -->
        <div v-if="appInstallStore.app != '' && showBtnAppInstall && $route.name == 'allAds'" class="m-3 mx-sm-auto" style="max-width: 600px">
            <v-btn @click="appInstallStore.install()" block  color="white" class="text-body-2">
                <img src="/public/img/siteImg/allImg/logo.svg" width="25" height="25" alt="logo" class="rounded-3">
                {{  $t('indexInstallLime') }}
            </v-btn>
        </div>

        <!-- Кнопка показать объекты на карте -->
        <v-btn rounded="xl" height="45" class="text-caption mb-5 mb-lg-0 text-dark"
               style="position:fixed; bottom: 20px; right: 20px; background: #ffdb4d"
               v-if="showMapButton"
               @click="$router.push({name: 'allAdsMap'})"
        >
            <i class="bi bi-geo-alt px-1"></i>
            {{  $t('indexOnTheMap') }}
        </v-btn>

        <!-- Блок с пагинацией  - покажется если найденно более 30 объявлений -->
        <div v-if="authStore.desktopOrMobile == 'Desktop'" class="mt-3" >

            <!-- Экран более - 768 px-->
            <Bootstrap5Pagination :align="'center'" :limit="3" :data="ads_arr"
                                  @pagination-change-page="(page)=> $router.push({name: 'allAds', params: {table_name: $route.params.table_name, page: page}})"
                                  class="d-none d-md-flex"
            ></Bootstrap5Pagination>

        </div>

    </div>

</template>

<script>

//Импортирую Store - Общее состояние
import { useAuthStore} from "../stores/auth";
import { useCheckInternetStore } from "../stores/checkInternet";
import { useAppInstallStore } from "../stores/AppInstall";
import { useFilterStore } from "../stores/Filter";
import { useKZLocationStore } from "../stores/KZLocation";
import {useUpdateDateLocaleStore} from "../stores/updateDateLocale";

//Импортируем Компоненты
import adsPreviewComponent from "./allComponents/AdsPreviewComponent.vue";

//Пакет для пагинации Laravel + Vue
import { Bootstrap5Pagination } from 'laravel-vue-pagination';

export default {
    name: "IndexComponent",

    components: {
        adsPreviewComponent,
        Bootstrap5Pagination,
    },

    data(){
        return {

            //Подключаю Store - Наше общее состояние
            authStore: useAuthStore(),
            checkInternetStore: useCheckInternetStore(),
            appInstallStore: useAppInstallStore(),
            filterStore: useFilterStore(),
            updateDateLocaleStore: useUpdateDateLocaleStore(),
            KZLocationStore: useKZLocationStore(),

            query: false,


            showBtnAppInstall: false,

            count_ads: 0,

            ads_arr: {}, //Полученные объявления с БД

            //Для получения моих избранных
            getMyLikeAds: false,

            //Сколько позиций выбранно в фильтре
            countFilter: 0,

            //Кнопка объекты на карте - Показать если есть выбранная локация области
            showMapButton: false,

            //Для курсорной навигации на Мобильных устройствах - Иногда применяю обычную
            nextCursor: '',
            page: 2
        }
    },

     watch: {

        //Отслеживаем изменение маршрута
        '$route' (to, from) {

            //Если переходим по меню или пагинации
            if(to.name == 'allAds' && from.name == 'allAds'){
                //Получим объявления в зависимости от категории меню
                this.getAds()
            }

        },

        //Отслеживаем примение фильтра
        'filterStore.make_filter'() {
            this.count_ads = 0;
            if(this.$route.params.page == 1){
                this.getAds();
            }else{
                setTimeout(()=>{
                    this.$router.replace({name: 'allAds', params: {table_name: this.$route.params.table_name, page: 1}})
                },500)
            }

        }
    },

    methods: {

        //Метод - Получить объявления обычной постраничной навигацией для Desktop
        getAds() {

            this.query = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            this.checkInternetStore.checkInternet();

            //Обнулим данные
            this.ads_arr = {};
            !this.authStore.check ? this.getMyLikeAds = false : '';
            this.showBtnAppInstall = false;

            //Получаю объявления
            axios.get('getAllAds', {

                params: {
                    page: this.$route.params.page,
                    user_id: useAuthStore().check ? useAuthStore().user.id : 0,
                    table_name: this.$route.params.table_name,
                    filter: JSON.parse(localStorage.getItem ("filter=" + this.$route.params.table_name )) ?? 'Фильтр не применен',
                    getMyLikeAds: this.getMyLikeAds ? 'Получить мои лайки' : 'Не получать мои лайки',
                    cursorPaginate: this.authStore.desktopOrMobile == 'Desktop' ? false: true,
                    countAds: true
                }

            })
                .then(response => {
                    this.query = false;

                    this.ads_arr = response.data.ads;
                    this.count_ads = response.data.ads.total ?? response.data.countAds;
                    if(response.data.ads.total > 0)this.showBtnAppInstall = true
                    this.nextCursor = response.data.ads.next_cursor ?? this.getMyLikeAds ?'fds':null ;

                    //Метод добавит длину фильтра
                    this.filterLength();
                })
                .catch((errors)=>{
                    this.query = false;
                })


        },

        // Метод получения объявлений на мобильных устройствах - курсорной погинацией - и иногда обычной
        getAdsMobileCursorPaginate() {
            if( this.query || this.nextCursor == null )return;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            this.checkInternetStore.checkInternet();

            this.query = true;

            axios.get('getAllAds', {
                params: {
                    cursor: this.nextCursor,
                    page: this.page,
                    user_id: useAuthStore().check ? useAuthStore().user.id : 0,
                    table_name: this.$route.params.table_name,
                    filter: JSON.parse(localStorage.getItem("filter=" + this.$route.params.table_name)) ?? 'Фильтр не применен',
                    getMyLikeAds: this.getMyLikeAds ? 'Получить мои лайки' : 'Не получать мои лайки',
                    cursorPaginate: this.authStore.desktopOrMobile == 'Desktop' ? false: true
                }
            })
                .then(response => {
                    this.query = false;

                    //Канкатенируем старые обьявления с новыми полученными
                    this.ads_arr.data = [...this.ads_arr.data, ...response.data.ads.data];

                    // Добавим данные для пагинации
                    if(response.data.ads.next_cursor != undefined ){
                        this.nextCursor = response.data.ads.next_cursor;
                    }else{
                        // В случаях где не подходит курсорная мы будем применять обычную
                        if(response.data.ads.current_page < response.data.ads.last_page){
                            this.nextCursor = 'next';
                            this.page++;
                        }else{
                            //Если окончание пагинации
                            this.nextCursor = null;
                            this.page = 2;
                        }
                    }

                    // Метод узнать количество - Фильтра
                    this.filterLength();

                }).catch(()=>{
                this.query = false;
            })
        },

        // Метод - Получить мои избранные объявления
        getMyLike(){
            this.getMyLikeAds = this.getMyLikeAds ? false : true;
            this.getMyLikeAds ? localStorage.setItem('getMyLikeAds', true) : localStorage.removeItem('getMyLikeAds')
            if(this.$route.params.page == 1){
                this.getAds();
            }else{
                this.$router.push({name: 'allAds', params: {table_name: this.$route.params.table_name, page: 1}})
            }
        },

        //Метод - Узнаем длину фильтра, тоесть сколько указанно критерий в фильтре
        filterLength(){
            let filter = JSON.parse(localStorage.getItem ("filter=" + this.$route.params.table_name ));
            if(filter == undefined){
                this.countFilter = 0;
                this.showMapButton = false;
                return;
            }


            let countFilter = 0;
            for(let key in filter){
                if(key != 'originalData' && key != 'busy' && key != 'successful' && key != 'recentlySuccessful' && key != 'recentlySuccessfulTimeoutId' && key != 'errors' && key != 'progress'){
                    if(filter[key] != '' && filter[key] != null){
                        countFilter += 1;
                    }
                }
            }

            //Если применен фильтр
            if(countFilter > 0) {

                //Если выбранны локации уберем Колличество длины фильтра
                if (filter.oblast != null) {
                    filter.gorod != null ? countFilter -= 1 : '';
                    filter.raion != null ? countFilter -= 1 : '';

                    //И отнимем lat, lon
                    countFilter -= 2;
                    this.showMapButton = true
                }else{
                    this.showMapButton = false;
                }

                this.countFilter = countFilter;

            } else{
                this.countFilter = countFilter;
                this.showMapButton = false;
            }
        },
    },

    mounted(){

        // При загрузке компонента - узнаем мы запрашиваем все объявления или только мои избранные
        localStorage.getItem('getMyLikeAds') != undefined ? this.getMyLikeAds = true: '';
        this.getAds();
    }

}
</script>

<style scoped>
.index__page{
    user-select: none;
}

.index-page__top-panel{
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
    padding: 15px;
    border-radius: 10px;
    position: relative;
}

/* Стили для иконок  */
.icon__heart:hover {
    transform: scale(1.20);
    transition: transform 0.1s ease;
}
</style>
