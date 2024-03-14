<template>
    <!-- КОМПОНЕНТ - ВЫВОД ОБЪЕКТОВ НА КАРТЕ - ЯНДЕКС КЛАСТЕР -->

    <!-- Обвертка - Компонента -->
    <div class="yandexMapCluster__wrapper">

        <!-- Сам блок -->
        <div class="yandexMapCluster__block">

            <!-- Кнопка - Назад -->
            <v-btn size="x-small" class="border" icon dark @click="$router.back()" style="position: absolute; top: 10px; left: 10px; z-index: 5">
                <v-icon size="x-large">mdi-arrow-left</v-icon>
            </v-btn>

            <!-- Body -->
            <div class="yandexMapCluster__body">

                <!-- Компонент яндекс карты -->
                <YandexMap :settings="settings" :coordinates="coordinates" :zoom="zoom" :options="{maxZoom: 15}" >

                    <!-- Компонент кластера -->
                    <YandexClusterer @vue:mounted="onClustererMounted" :options="{ preset: 'islands#darkBlueClusterIcons', openBalloonOnClick: false}">
                        <YandexMarker v-for="(ads, index) in ads_arr"
                                      :key="`1-marker-${index}`"
                                      :coordinates="[ads.lat, ads.lon]"
                                      :marker-id="`1-marker-${index}`"
                                      :options="{ preset: 'islands#darkBlueStretchyIcon'}"
                                      :properties="{iconContent: ads.cena + 'тг', id: ads.id}"
                                      @click="showAds(ads.id)"
                        >
                        </YandexMarker >
                    </YandexClusterer>

                </YandexMap>

            </div>

        </div>

    </div>

    <!-- Вывожу - внутренние страницы - Это превью одного объявления или массив превью обьявлений -->
    <router-view></router-view>

</template>

<script>

//Импортируем - Store - Наше хранилище доступное со всех компонентов
import {useAuthStore} from "../../../stores/auth";
import { useCheckInternetStore } from "../../../stores/checkInternet";

//Импортируем - Компонент - Yandex map - Карта Яндекс
import { YandexMap, YandexMarker, YandexClusterer } from 'vue-yandex-maps'

export default {
    name: "Index",

    components: {
        YandexMap, YandexMarker, YandexClusterer,//Компоненты - Яндекс карты
    },

    data(){
        return {

            //Подключаем - Store - Наше хранилище
            authStore: useAuthStore(),
            checkInternetStore: useCheckInternetStore(),

            //Настройки для карты
            settings :{
                apiKey: '8740b571-75d9-47f0-a5c4-582b1feaf201',
                lang: 'ru_RU',
                coordorder: 'latlong',
                enterprise: false,
                version: '2.1'
            },
            zoom: 0, //Динамически увеличиваем zoom на карте в зависимости что выбранно - Весь Казахстан, Область, город, или район
            coordinates : [],//После определения координат занесем их сюда - эти координаты мы берем от выбранной локации

            ads_arr: [], //Массив объявлений которые нужно нанести на карту
        }
    },

    methods: {

        // Метод открыть карту на весь экран
        showCluster(){
            // Получим фильтр
            let filter = JSON.parse(localStorage.getItem("filter=" + this.$route.params.table_name));

            // Укажем zoom и координаты

            //Если в локации выбранна вся область
            if(filter.gorod == null && filter.raion == null){
                this.zoom = 7;
            }

            //Если выбран город
            if(filter.gorod != null && filter.raion == null){
                this.zoom = 11;
            }

            //Если выбран район
            if(filter.raion != null){
                this.zoom = 12;
            }

            this.coordinates = [filter.lat, filter.lon]

            //Получим все квартиры с БД для отображения на карте
            this.getAds();
        },

        //Метод получить все объявления для отображения их на карте
       async getAds() {

            this.query = true;

           //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
           await this.checkInternetStore.checkInternet();

            //Получаю объявления по выбранной локации
            axios.get('/getAllAds', {

                params: {
                    user_id: useAuthStore().user.id ?? 0,
                    table_name: this.$route.params.table_name,
                    filter: JSON.parse(localStorage.getItem("filter=" + this.$route.params.table_name)) ??  'Фильтр не применен',
                    getMyLikeAds: localStorage.getItem('getMyLikeAds') != undefined ?'Получить мои лайки': 'Не получать мои лайки',//Не получать мои лайки
                    getCoordinates: true //Передадим информацию что получить объявления для cluster
                }
            })
                .then(response => {
                    this.query = false;
                    this.ads_arr = response.data.ads;
                })
                .catch((errors)=>{
                    this.query = false;
                })
        },

        // Клик по множественному кластеру, открыть превью всех объявлений из данного кластера
        onClustererMounted(e) {

            // При монтировании навесим событие прослушивания
            e.component.exposed.events.add('click',  (e)=>{

                let data = e.get("target");
                let arr_id = []; //Массив id - объявлений

                //Получим метки и объявления по ним
                if(data.geometry._lastZoom == 15){
                    data.properties._data.geoObjects.forEach(elem=>{
                        arr_id.push(elem.properties._data.id);
                    })

                    //Показать объявлений
                    this.showAds(arr_id)

                }
            });
        },

        // Показать 1 объявление при клике на цену или массив при клике на кластер
        showAds(ads_id){

            //Если клик по 1 объявлению а не по кластеру
            if(typeof ads_id == 'number') ads_id = [ads_id];

            localStorage.setItem('mapClusterAdsId', JSON.stringify(ads_id))
            this.$router.push({name: 'allAdsMapPreviewAds', params: { pages: 1 } })
        }

    },

    async mounted(){
        await this.showCluster();
        document.querySelector(':root').classList.add('PATCH_modal');
    },

    beforeRouteLeave(to, from, next) {
        document.querySelector(':root').classList.remove('PATCH_modal');
        next();
    }

}
</script>


<style>

/*В этот контейнер монтируется - Яндекс карта */
.yandex-container {
    width: 100%;
    height: 100%;
}

/* Уберем значки на карте - Например: как добраться, создать новую карту, кнопка увеличения уменьшения карты */
.ymaps-2-1-79-controls__control_toolbar, .ymaps-2-1-79-gototech, .ymaps-2-1-79-controls__control, .ymaps-2-1-79-map-copyrights-promo{
    display: none!important;
}

/* Стили для макрера - наше фото квартиры */
.ymaps-2-1-79-image{
    border-radius: 100%;
    box-shadow: 0 0 0 2px white, 0 0 0 4px #2196F3;
    background-size: cover;
}

/* Анимация -- Для Wrapper */
.yandexMapCluster__animation-wrapper-enter-active,
.yandexMapCluster__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}


.yandexMapCluster__animation-wrapper-enter-from{
    transform: translateY(10%);
}

.yandexMapCluster__animation-wrapper-leave-to{
    transform: translateY(100%);
}

.yandexMapCluster__animation-wrapper-enter-to,
.yandexMapCluster__animation-wrapper-leave-from{
    transform: translateY(0);
}
</style>

<style scoped>
.yandexMapCluster__wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #ffffff;
    user-select: none;
}

.yandexMapCluster__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
}

.yandexMapCluster__body {
    flex-grow: 1; /* Растянем этот блок на всю оставшуюся высоту экрана */
}
</style>
