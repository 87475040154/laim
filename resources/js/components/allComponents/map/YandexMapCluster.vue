<template>
    <!-- КОМПОНЕНТ - ЯНДЕКС КЛАСТЕР - ВЫВОД ОБЪЕКТОВ НА КАРТЕ -->

    <!-- Обвертка - Компонента -->
    <div class="yandexMapCluster__wrapper">

        <!-- Сам блок -->
        <div class="yandexMapCluster__block">

            <!-- Кнопка - Назад -->
            <v-btn size="x-small" class="border" icon dark @click="$router.back()" style="position: absolute; top: 10px; left: 10px; z-index: 5">
                <v-icon size="large">mdi-arrow-left</v-icon>
            </v-btn>

            <!-- Body -->
            <div class="yandexMapCluster__body">

                <!-- Показать объявления на карте -->
                <YandexMap :settings="settings" :coordinates="coordinates" :zoom="zoom" :options="{maxZoom: 15}" >

                    <YandexClusterer @vue:mounted="onClustererMounted" :options="{ preset: 'islands#darkBlueClusterIcons', openBalloonOnClick: false}">
                        <YandexMarker v-for="(ads, index) in ads_arr"
                                      :key="`1-marker-${index}`"
                                      :coordinates="[ads.lat, ads.lon]"
                                      :marker-id="`1-marker-${index}`"
                                      :options="{ preset: 'islands#darkBlueStretchyIcon'}"
                                      :properties="{iconContent: ads.cena + 'тг', id: ads.ads_id == undefined ? ads.id: ads.ads_id, table_name: ads.table_name}"
                                      @click="showAds(ads.ads_id == undefined ? ads.id: ads.ads_id)"
                        >

                        </YandexMarker >
                    </YandexClusterer>

                </YandexMap>

            </div>

        </div>

    </div>

    <!-- Вывожу - внутренние страницы -->
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
            coordinates : [],//После определения координват занесем их сюда
            filter: '', //Получаем данные с фильтра если он применен

            ads_arr: [], //Массив квартир которые нужно нанести на карту
        }
    },

    methods: {

        //Открыть окно с картой
        showCluster(){
            //Если применен фильтр и есть выбранная локация укажем zoom и координаты
            if(localStorage.getItem ('filter=' + this.$route.params.table_name) != undefined) {
                this.filter = JSON.parse(localStorage.getItem('filter=' + this.$route.params.table_name));
                if(this.filter.oblast != null){

                    //Если в локации выбранна вся область
                    if(this.filter.gorod == null && this.filter.raion == null){
                        this.zoom = 7;
                    }

                    //Если выбран город
                    if(this.filter.gorod != null && this.filter.raion == null){
                        this.zoom = 11;
                    }

                    //Если выбран район
                    if(this.filter.raion != null){
                        this.zoom = 12;
                    }

                    this.coordinates = [this.filter.lat, this.filter.lon]

                }

                //Если локация не выбранна занесем координаты казахстана
                else{
                    this.zoom = 4;
                    this.coordinates = [48.136207, 67.153559];
                }
            }
            //Если фильтр не применен занесем просто координаты Казахстана
            else{
                this.zoom = 4;
                this.coordinates = [48.136207, 67.153559];
            }

            //Получим все квартиры с БД для отображения на карте
            this.getAds();
        },

        //Метод получить все объявления для отображения их на карте
       async getAds() {

            this.query = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
           await this.checkInternetStore.checkInternet()
            if(!this.checkInternetStore.online){
                this.query = false;
                return
            }

            //Получаю объявления
            axios.get('/getAllAds', {

                params: {
                    user_id: useAuthStore().check ? useAuthStore().user.id : 0,
                    table_name: this.$route.params.table_name,
                    filter: this.filter != '' ? this.filter : 'Фильтр не применен',
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

        //Клик по множественному кластеру
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

                    //Узнаем тип объекта
                    let table_name = data.properties._data.geoObjects[0].properties._data.table_name;

                    //Получим объявления
                    this.showAds(arr_id)

                }


            });
        },

        //Показать 1 объявление или массив
        showAds(ads_id){
            //Заносим id одного объявления если клик по маркеру или массив если по кластеру

            let id_arr = [];
            if(typeof ads_id != Array)id_arr.push(ads_id);

            localStorage.setItem('mapClusterAdsId', JSON.stringify(id_arr.length >0? id_arr: ads_id))
            this.$router.push('/mapClusterShowAds/'+ this.$route.params.table_name)
        }

    },

    async mounted(){
        let app = this;
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
