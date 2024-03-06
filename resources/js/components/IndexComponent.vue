<template>

    <!-- Страница вывод - Превью всех объявлений -->
    <div class="index__page">

        <!-- Блок - Сколько найденно объявлений - кнопка открыть фильтр - кнопка полуить мои like -->
        <div class="index-page__top-panel">

            <!-- Всего - Сколько найденно объявлений по данному запросу -->
            <div>{{ $t('indexFound') }} {{count_ads}}</div>

            <!-- Кнопка фильтр -->
            <div @click="$router.push('/filter/' + $route.params.table_name)" role="button">
                {{ $t('indexFilter') }}
                <v-badge floating v-if="countFilter > 0" :content="countFilter" color="error">
                    <i class="bi bi-hdd-stack-fill"></i>
                </v-badge>
                <i v-else class="bi bi-hdd-stack-fill"></i>

                <v-tooltip activator="parent" location="bottom">{{ $t('indexMakeFilter') }}</v-tooltip>
            </div>

            <!-- Кнопка показать мои лайки  -->
            <div>
                <v-icon @click="authStore.check ? getMyLike(): $router.push('/auth')"
                        :icon="getMyLikeAds ? 'mdi-heart' : 'mdi-heart-outline'"
                        :class="{'text-red':getMyLikeAds}"
                        class="icon__heart"
                ></v-icon>
                <v-tooltip activator="parent" location="bottom">{{ $t('indexMyFavorites') }}</v-tooltip>
            </div>

            <!-- Выбор языка -->
            <div role="button">
                <span @click="$router.push({name: 'lang', params: {table_name: $route.params.table_name, page: $route.params.page}})">{{ updateDateLocaleStore.lang == 'kz' ? 'Қаз': '' }}</span>
                <span @click="$router.push({name: 'lang', params: {table_name: $route.params.table_name, page: $route.params.page}})">{{ updateDateLocaleStore.lang == 'ru' ? 'Рус': '' }}</span>
                <span @click="$router.push({name: 'lang', params: {table_name: $route.params.table_name, page: $route.params.page}})">{{ updateDateLocaleStore.lang == 'en' ? 'Eng': '' }}</span>

                <v-tooltip activator="parent" location="bottom">{{ $t('indexChangeLanguage') }}</v-tooltip>
            </div>

        </div>

        <!-- Gif Load  - Если объявления еще не загрузились -->
        <div v-if="query" class="d-flex justify-content-center py-1">
            <div class="spinner-border spinner-border-sm" style="color: var(--app-text-color)" role="status"></div>
        </div>

        <ads-preview-component  :ads_arr="ads_arr.data" :getMyLikeAds="getMyLikeAds"></ads-preview-component>

        <!-- Если объявления не найденны покажется текст -->
        <div v-if="count_ads == 0 && !query" class="text-center text-muted my-3">
            <h4>{{ $t('indexAdsNotFound') }}</h4>
        </div>

        <!-- Кнопка -  установить приложение laim.kz  -->
        <div v-if="appInstallStore.app != '' && showBtnAppInstall && $route.name != 'userAds'" class="m-3 mx-sm-auto" style="max-width: 600px">
            <v-btn @click="appInstallStore.install()" block  color="white" class="text-body-2">
                <img src="/public/img/siteImg/allImg/logo.svg" width="25" height="25" alt="logo" class="rounded-3">
                {{  $t('indexInstallLime') }}
            </v-btn>
        </div>

        <!-- Кнопка показать объекты на карте -->
        <v-btn rounded="xl" height="40" class="text-caption mb-5 mb-lg-0 text-white"
               style="position:fixed; bottom: 20px; right: 20px; background: #10a37f"
               v-if="showMapButton"
               @click="$router.push('/mapCluster/' + $route.params.table_name)"
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

    <!-- Вывод внутренних компонентов -->
    <div style="position: relative; z-index: 2">
        <router-view></router-view>
    </div>


    <!-- Для автоподгрузки на мобильных устройствах -->
    <div v-if="authStore.desktopOrMobile != 'Desktop'">
        <div ref="scrollObserver"></div>
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


            showBtnAppInstall: false,
            query: false,

            count_ads: 0,
            ads_arr: {}, //Полученные объявления с БД

            //Для получения моих понравившихся
            getMyLikeAds: false,

            //Для фильтра
            countFilter: 0,

            //Кнопка объекты на карте - Показать если есть выбранная локация области
            showMapButton: false,

            //Для курсорной навигации на Мобильных устройствах
            nextCursor: '',
            prevCursor: '',

        }
    },

    watch: {

        //Отслеживаем изменение маршрута
        '$route' (to, from) {

            //Если переходим по меню
            if(to.name == 'allAds' && from.name == 'allAds'){
                //Получим объявления в зависимости от категории меню
                this.getAds()
            }

        },

        //Отслеживаем примение фильтра
        'filterStore.make_filter'() {
            this.getAds();
        }
    },

    methods: {

        //Метод - Получить объявления обычной постраничной навигацией для Desktop
        async getAds() {
            this.query = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            await this.checkInternetStore.checkInternet();

            //Обнулим данные
            this.ads_arr = {};
            !this.authStore.check ? this.getMyLikeAds = false : '';
            this.showBtnAppInstall = false;

            //Получим данны фильтра с LocaleStorage если он применен
            let filter = localStorage.getItem ("filter=" + this.$route.params.table_name ) == undefined ? '' : JSON.parse(localStorage.getItem ("filter=" + this.$route.params.table_name ));

            //Получаю объявления
            axios.get('getAllAds', {

                params: {
                    page: this.$route.params.page,
                    user_id: useAuthStore().check ? useAuthStore().user.id : 0,
                    table_name: this.$route.params.table_name,
                    filter: filter == '' ? 'Фильтр не применен' : filter,
                    getMyLikeAds: this.getMyLikeAds ? 'Получить мои лайки' : 'Не получать мои лайки',
                    cursorPaginate: this.authStore.desktopOrMobile == 'Desktop' ? false: true
                }

            })
                .then(response => {
                    this.query = false;
                    this.ads_arr = response.data.ads;
                    this.count_ads = response.data.ads.total;
                    if(response.data.ads.total > 0)this.showBtnAppInstall = true

                    this.nextCursor = response.data.ads.next_cursor;


                    //Метод проверить колличество - Фильтра
                    this.filterLength(filter);

                })
                .catch((errors)=>{
                    this.query = false;
                })


        },

        // Метод получения объявлений на мобильных устройствах - курсорной погинацией
        getAdsMobileCursorPaginate() {
            if(this.query)return;

            const scrollObserver = this.$refs.scrollObserver;
            const rect = scrollObserver.getBoundingClientRect();

            if (rect.bottom <= window.innerHeight) {
                let filter = localStorage.getItem("filter=" + this.$route.params.table_name) == undefined ? '' : JSON.parse(localStorage.getItem("filter=" + this.$route.params.table_name));
                this.query = true;

                axios.get('getAllAds', {
                    params: {
                        cursor: this.nextCursor,
                        user_id: useAuthStore().check ? useAuthStore().user.id : 0,
                        table_name: this.$route.params.table_name,
                        filter: filter == '' ? 'Фильтр не применен' : filter,
                        getMyLikeAds: this.getMyLikeAds ? 'Получить мои лайки' : 'Не получать мои лайки',
                        cursorPaginate: this.authStore.desktopOrMobile == 'Desktop' ? false: true
                    }
                })
                    .then(response => {

                        //Канкатенируем старые обьявления с новыми полученными
                        this.ads_arr.data = [...this.ads_arr.data, ...response.data.ads.data];

                        // Добавим данные для пагинации
                        this.nextCursor = response.data.ads.next_cursor;
                        this.prevCursor = response.data.ads.prev_cursor;

                        // Метод проверить количество - Фильтра
                        this.filterLength(filter);

                        this.query = false;
                    }).catch(()=>{
                    this.query = false;
                })

            }
        },

        // Метод - Получить мои избранные объявления
        getMyLike(){
            this.getMyLikeAds = this.getMyLikeAds ? false : true;
            this.getMyLikeAds ? localStorage.setItem('getMyLikeAds', true) : localStorage.removeItem('getMyLikeAds')
            this.getAds()
        },

        //Метод - Узнаем длину фильтра, тоесть сколько указанно критерий в фильтре
        filterLength(filter){
            if(filter == ''){
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

        // На мобильных устройствах при курсорной навигации запрашиваем объявления
        this.authStore.desktopOrMobile != 'Desktop' ? window.addEventListener('scroll', this.getAdsMobileCursorPaginate) :'';

    },

    beforeDestroy() {
        window.removeEventListener('scroll', this.getAdsMobileCursorPaginate);
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
    padding: 12px;
    border-radius: 10px;
    position: relative;
}

/* Стили для иконок  */
.icon__heart:hover {
    transform: scale(1.20);
    transition: transform 0.1s ease;
}
</style>
