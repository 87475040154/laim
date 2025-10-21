<template>

    <!-- Вывод - дочерних  компонентов например: Фильтр, язык и тд. -->
    <div style="position: relative; z-index: 2">
        <router-view></router-view>
    </div>


    <!-- Страница вывод - Превью всех объявлений -->
    <div class="index__page">

        <!-- Блок - Сколько найденно объявлений - кнопка открыть фильтр - кнопка полуить мои like -->
        <div :class="{'index-page__top-panel-fixed': topPanelFixed}">
            <div class="container-lg">
                <div class="index-page__top-panel my-4">

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

                    <!-- Кнопка показать мои Избранные  -->
                    <div>
                        <v-icon @click="authStore.check ? getMyLike(): $router.push({name: $route.name + 'Auth'})"
                                :icon="getMyLikeAds ? 'mdi-heart' : 'mdi-heart-outline'"
                                :class="{'text-red':getMyLikeAds}"
                                class="icon__heart"
                        ></v-icon>
                        <v-tooltip activator="parent" location="bottom">{{ $t('indexMyFavorites') }}</v-tooltip>
                    </div>

                </div>
            </div>
        </div>

        <!-- Кнопка -  установить приложение laim.kz  -->
        <div v-if="appInstallStore.app != '' && showBtnAppInstall && $route.name == 'allAds'" class="m-3 mx-sm-auto" style="max-width: 600px">
            <v-btn @click="appInstallStore.install()" block  color="white" class="text-body-2">
                <img src="/img/siteImg/allImg/logo.svg" width="25" height="25" alt="logo" class="rounded-3">
                {{  $t('indexInstallLime') }}
            </v-btn>
        </div>


        <!-- Компонент превью объявлений -->
        <ads-preview-component  :ads_arr="ads_arr" :getMyLikeAds="getMyLikeAds" :parent-query="query"  :is-last-load="isLastLoad" @get-ads="getAds"></ads-preview-component>

        <!-- Gif Load  - Если объявления еще не загрузились -->
        <div v-if="query" class="d-flex justify-content-center py-1">
            <div class="spinner-border spinner-border-sm" style="color: var(--app-text-color)" role="status"></div>
        </div>

        <!-- Если объявления не найденны покажется текст -->
        <div v-if="count_ads == 0 && !query" class="text-center text-muted my-3">
            <h4>{{ $t('indexAdsNotFound') }}</h4>
        </div>

        <!-- Кнопка показать объекты на карте -->
        <v-btn rounded="xl" height="45" class="text-caption mb-5 mb-lg-0 text-dark"
               style="position:fixed; bottom: 22px; right: 20px; background: #ffdb4d"
               v-if="showMapButton"
               @click="$router.push({name: 'allAdsMap'})"
        >
            <i class="bi bi-geo-alt px-1"></i>
            {{  $t('indexOnTheMap') }}
        </v-btn>


    </div>

</template>

<script>

//Импортирую Store - Общее состояние
import { useAuthStore} from "../stores/auth";
import { useAppInstallStore } from "../stores/AppInstall";
import { useFilterStore } from "../stores/Filter";
import { useKZLocationStore } from "../stores/KZLocation";
import {useUpdateDateLocaleStore} from "../stores/updateDateLocale";

//Импортируем Компоненты
import adsPreviewComponent from "./allComponents/AdsPreviewComponent.vue";

export default {
    name: "IndexComponent",

    components: {
        adsPreviewComponent,
    },

    data(){
        return {

            //Подключаю Store - Наше общее состояние
            authStore: useAuthStore(),
            appInstallStore: useAppInstallStore(),
            filterStore: useFilterStore(),
            updateDateLocaleStore: useUpdateDateLocaleStore(),
            KZLocationStore: useKZLocationStore(),

            query: false,
            controller: null,     // Для отмены предыдущего запроса

            topPanelFixed: false, // Фиксируем панель при прокрутке

            showBtnAppInstall: false,

            count_ads: 0,

            ads_arr: [], //Полученные объявления с БД

            //Для получения моих избранных
            getMyLikeAds: false,

            //Сколько позиций выбранно в фильтре
            countFilter: 0,

            //Кнопка объекты на карте - Показать если есть выбранная локация области
            showMapButton: false,

            //Для курсорной навигации
            nextCursor: null,
            isFirstLoad: true,
            isLastLoad: false
        }
    },

     watch: {

         //Отслеживаем изменение маршрута
         '$route' (to, from) {

             //Если переходим по меню или пагинации
             if(to.name == 'allAds' && from.name == 'allAds'){
                 //Получим объявления в зависимости от категории меню
                 this.clearData();
                 this.getAds()
             }

         },

        //Отслеживаем примение фильтра
        'filterStore.make_filter'() {
            this.clearData();
            this.getAds();
        }
    },

    methods: {

   // Метод - Получить объявления
        async getAds() {
            // Если достигнут конец списка объявлений, выходим
            if(this.isLastLoad) return;

            // Создаем новый контроллер для текущего запроса
            if(this.controller) this.controller.abort();// Отменяем предыдущий запрос, если он еще выполняется
            this.controller = new AbortController();
            const signal = this.controller.signal; // Получаем сигнал для axios

            const showGifDelay = setTimeout(() => {
                this.query = true;// Устанавливаем флаг, что сейчас выполняется запрос
            }, 200);


            // Сбрасываем настройки в зависимости от авторизации
            !this.authStore.check ? (this.getMyLikeAds = false) : '';
            this.showBtnAppInstall = false;

            try {
                const response = await axios.get('getAllAds', {
                    params: {
                        cursor: this.nextCursor,
                        user_id: useAuthStore().check ? useAuthStore().user.id : 0,
                        table_name: this.$route.params.table_name,
                        filter: JSON.parse(localStorage.getItem("filter=" + this.$route.params.table_name)) || {},
                        getMyLikeAds: this.getMyLikeAds ? 'Получить мои лайки' : 'Не получать мои лайки',
                    },
                    signal // передаем сигнал для отмены
                });

                this.ads_arr = this.isFirstLoad ? response.data.ads.data : [...this.ads_arr, ...response.data.ads.data];
                if(this.isFirstLoad) this.countAds();
                this.nextCursor = response.data.ads.next_cursor;
                this.isLastLoad = this.nextCursor === null;
                this.filterLength();

                this.isFirstLoad = false;

            }catch(error) {
                if(error.name !== 'CanceledError') console.error(error);
            } finally {
                clearTimeout(showGifDelay); // отменяем показ гифки, если запрос закончился раньше
                this.query = false;
            }
        },

        // Метод - Получить мои избранные объявления
        getMyLike(){
            this.getMyLikeAds = this.getMyLikeAds ? false : true;
            this.getMyLikeAds ? localStorage.setItem('getMyLikeAds', true) : localStorage.removeItem('getMyLikeAds')
            this.clearData();
            this.getAds();
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

        //Метод узнать сколько все объявлений в БД
        countAds(){
            axios.get('countAds', {

                params: {
                    user_id: useAuthStore().check ? useAuthStore().user.id : 0,
                    table_name: this.$route.params.table_name,
                    filter: JSON.parse(localStorage.getItem ("filter=" + this.$route.params.table_name )) ?? 'Фильтр не применен',
                    getMyLikeAds: this.getMyLikeAds ? 'Получить мои лайки' : 'Не получать мои лайки',
                }

            })
                .then(response => {
                    this.count_ads = response.data.countAds;
                    if(this.count_ads > 0)this.showBtnAppInstall = true
                })
        },

        //Сбросим данные для получения
        clearData(){
            this.ads_arr = [];
            this.nextCursor = null;
            this.isFirstLoad = true;
            this.isLastLoad = false;
        }
    },

    mounted(){
        //При прокрутке страницы будем фиксировать панель К-во объявлений, фильтр, лайки
        let lastScrollTop = 0; // Переменная для хранения предыдущей позиции прокрутки
        window.addEventListener("scroll", () => {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;

            if (currentScroll > 500) {

                if(currentScroll < lastScrollTop){
                    this.topPanelFixed = currentScroll < lastScrollTop; // Показать, если прокрутка вверх
                }else {
                    this.topPanelFixed = false; // Если прокрутка меньше 500 пикселей, не фиксируем header
                }

            }else{
                if (currentScroll > lastScrollTop) {
                    this.topPanelFixed = false; // Показать, если прокрутка вверх
                }
            }

            lastScrollTop = currentScroll; // Обновляем предыдущее положение прокрутки
        });

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


.index-page__top-panel-fixed{
    position: sticky;
    top: 110px;
    left: 0;
    right: 0;
    background: var(--app-bg-color);
    z-index: 1;
}

/* При экране более 992px */
@media screen and (min-width: 992px){
    .index-page__top-panel-fixed{
        position: sticky;
        top: 150px;
    }
}

/* Стили для иконок  */
.icon__heart:hover {
    transform: scale(1.20);
    transition: transform 0.1s ease;
}
</style>
