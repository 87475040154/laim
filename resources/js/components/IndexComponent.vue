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
            <span>
                <v-icon v-if="$route.params.table_name != 'goryachie'"
                        @click="authStore.check ? getMyLike(): $router.push('/auth')"
                        :icon="getMyLikeAds ? 'mdi-heart' : 'mdi-heart-outline'"
                        :class="{'text-red':getMyLikeAds}"
                        class="icon__heart"
                ></v-icon>
                <v-tooltip activator="parent" location="bottom">{{ $t('indexMyFavorites') }}</v-tooltip>
            </span>


            <!-- Выбор языка -->
            <span  class="flex-grow-1">
                <span role="button">
                    <span @click="$router.push({name: 'lang', params: {table_name: $route.params.table_name, page: $route.params.page}})">{{ updateDateLocaleStore.lang == 'kz' ? 'Қаз': '' }}</span>
                    <span @click="$router.push({name: 'lang', params: {table_name: $route.params.table_name, page: $route.params.page}})">{{ updateDateLocaleStore.lang == 'ru' ? 'Рус': '' }}</span>
                    <span @click="$router.push({name: 'lang', params: {table_name: $route.params.table_name, page: $route.params.page}})">{{ updateDateLocaleStore.lang == 'en' ? 'Eng': '' }}</span>

                    <v-tooltip activator="parent" location="bottom">{{ $t('indexChangeLanguage') }}</v-tooltip>
                </span>

            </span>
        </div>

        <!-- Текст горячие предложеня -->
        <div v-if="$route.params.table_name === 'goryachie'" class="pt-3 mx-2 mx-md-auto text-body-1 text-grey" style="max-width: 800px">
            {{ goryachiePredlojeniya }}
        </div>

        <!-- Gif Load  - Если объявления еще не загрузились -->
        <div v-if="query" class="d-flex justify-content-center py-1">
            <div class="spinner-border spinner-border-sm" style="color: var(--app-text-color)" role="status"></div>
        </div>

        <!-- Компонент для отображения превью всех полученных объявлений  - И компонент правая панель-->
        <div class="row g-0 gap-2 mx-auto" style="max-width: 1100px">
            <div class="col">
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


                <!-- Блок с пагинацией  - покажется если найденно более 30 объявлений -->
                <div class="mt-3" >

                    <!-- Экран более - 768 px-->
                    <Bootstrap5Pagination :align="'center'" :limit="3" :data="ads_arr"
                                          @pagination-change-page="(page)=> $router.push({name: 'allAds', params: {table_name: $route.params.table_name, page: page}})"
                                          class="d-none d-md-flex"
                    ></Bootstrap5Pagination>

                    <!-- Экран менее - 768 px-->
                    <Bootstrap5Pagination :align="'center'" :limit="1" :data="ads_arr"
                                          @pagination-change-page="(page)=> $router.push({name: 'allAds', params: {table_name: $route.params.table_name, page: page}})"
                                          class="d-md-none"
                    ></Bootstrap5Pagination>

                </div>

            </div>

            <right-panel-component v-if="$route.params.table_name != 'goryachie'" class="col-auto" :update="updateGoryachieRightPanel"></right-panel-component>
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

    </div>

    <!-- Вывод внутренних компонентов -->
    <div style="position: relative; z-index: 2">
        <router-view></router-view>
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
import rightPanelComponent from "./allComponents/RightPanelComponent.vue";

//Пакет для пагинации Laravel + Vue
import { Bootstrap5Pagination } from 'laravel-vue-pagination';

export default {
    name: "IndexComponent",

    components: {
        adsPreviewComponent,
        Bootstrap5Pagination,
        rightPanelComponent,
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

            updateGoryachieRightPanel: false
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

    computed: {

        //Вывод текста горячие в зависимости от локали
        goryachiePredlojeniya(){
            //Получим данны фильтра с LocaleStorage если он применен
            let filter = localStorage.getItem ("filter=" + this.$route.params.table_name ) == undefined ? '' : JSON.parse(localStorage.getItem ("filter=" + this.$route.params.table_name ));

            if(filter != ''){
                if(filter['oblast'] != null && filter['gorod'] == null){
                    if(this.updateDateLocaleStore.lang == 'ru'){return filter['oblast'] + ' горячие предложения'}
                    if(this.updateDateLocaleStore.lang == 'kz'){
                        this.KZLocationStore.translateLocation({ oblast: filter['oblast'] });
                        return this.KZLocationStore.oblast + ' ыстық ұсыныстар';
                    }
                    if(this.updateDateLocaleStore.lang == 'en'){
                        this.KZLocationStore.translateLocation({ oblast: filter['oblast'] });
                        return this.KZLocationStore.oblast + ' hot offers';
                    }
                }
                else if(filter['gorod'] != null && filter['raion'] == null){
                    if(this.updateDateLocaleStore.lang == 'ru'){
                        return filter['gorod'] + ' горячие предложения'
                    }
                    if(this.updateDateLocaleStore.lang == 'kz'){
                        this.KZLocationStore.translateLocation({ gorod: filter['gorod'] });
                        return this.KZLocationStore.gorod + ' ыстық ұсыныстар';
                    }
                    if(this.updateDateLocaleStore.lang == 'en'){
                        this.KZLocationStore.translateLocation({ gorod: filter['gorod'] });
                        return this.KZLocationStore.gorod + ' hot offers';
                    }
                }
                else if(filter['raion'] != null){
                    if(this.updateDateLocaleStore.lang == 'ru'){
                        return filter['raion'] + ' горячие предложения'
                    }
                    if(this.updateDateLocaleStore.lang == 'kz'){
                        this.KZLocationStore.translateLocation({ raion: filter['raion'] });
                        return this.KZLocationStore.raion + ' ыстық ұсыныстар';
                    }
                    if(this.updateDateLocaleStore.lang == 'en'){
                        this.KZLocationStore.translateLocation({ raion: filter['raion'] });
                        return this.KZLocationStore.raion + ' hot offers';
                    }
                }
                else{
                    if(this.updateDateLocaleStore.lang == 'ru')return  'Горячие предложения в Казахстане';
                    if(this.updateDateLocaleStore.lang == 'kz')return  'Қазақстандағы ыстық ұсыныстар';
                    if(this.updateDateLocaleStore.lang == 'en')return 'Hot deals in Kazakhstan';
                }
            }
            else{
                if(this.updateDateLocaleStore.lang == 'ru')return  'Горячие предложения в Казахстане';
                if(this.updateDateLocaleStore.lang == 'kz')return  'Қазақстандағы ыстық ұсыныстар';
                if(this.updateDateLocaleStore.lang == 'en')return 'Hot deals in Kazakhstan';
            }
        }
    },

    methods: {

        //Метод - Получить объявления выбранной категории
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
            let url = this.$route.params.table_name == 'goryachie' ? '/getGoryachie' : 'getAllAds';
            axios.get(url, {

                params: {
                    page: this.$route.params.page,
                    user_id: useAuthStore().check ? useAuthStore().user.id : 0,
                    table_name: this.$route.params.table_name,
                    filter: filter == '' ? 'Фильтр не применен' : filter,
                    getMyLikeAds: this.getMyLikeAds ? 'Получить мои лайки' : 'Не получать мои лайки',
                }

            })
                .then(response => {
                    this.query = false;
                    this.ads_arr = response.data.ads;
                    this.count_ads = response.data.ads.total;

                    this.updateGoryachieRightPanel = !this.updateGoryachieRightPanel;

                    if(response.data.ads.total > 0)this.showBtnAppInstall = true

                    //Метод проверить колличество - Фильтра
                    this.filterLength(filter);

                })
                .catch((errors)=>{
                    this.query = false;
                })


        },

        //Получить мой лайки
        getMyLike(){
            this.getMyLikeAds = this.getMyLikeAds ? false : true;
            this.getMyLikeAds ? localStorage.setItem('getMyLikeAds', true) : localStorage.removeItem('getMyLikeAds')
            this.getAds()
        },

        //Метод - Узнать Длину фильтра
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

                    this.$route.params.table_name != 'goryachie' ? this.showMapButton = true : this.showMapButton = false;
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
        localStorage.getItem('getMyLikeAds') != undefined ? this.getMyLikeAds = true: '';
        this.getAds();
    },

}
</script>

<style scoped>
.index__page{
    user-select: none;
}

.index-page__top-panel{
    display: flex;
    gap: 4em;
    background: #ffffff;
    align-items: center;
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
