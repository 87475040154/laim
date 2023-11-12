<template>
    <!-- КОМПОНЕНТ - ПОКАЗАТЬ 1 ОБЪЕКТ НА КАРТЕ - ДЛЯ ОДНОГО ОБЪЯВЛЕНИЯ -->

    <!-- Обвертка - Компонента -->
    <div class="yandexMap__wrapper">

        <!-- Сам блок -->
        <div class="yandexMap__block">

            <!-- Body -->
            <div class="yandexMap__body">

                <!-- Кнопка Закрыть окно -->
                <v-btn size="x-small" class="border" icon dark @click="$router.back()" style="position: absolute; top: 10px; left: 10px; z-index: 5">
                    <v-icon size="large">mdi-arrow-left</v-icon>
                </v-btn>

                <!-- Карта для показа 1-го объявления -->
                <YandexMap :settings="settings"
                           :coordinates="coordinates"
                           :controls="[]"
                           :detailed-controls="detailedControls"
                           :zoom="16"
                >
                    <YandexMarker :coordinates="coordinates"
                                  :marker-id="123"
                                  :options="options"
                    ></YandexMarker>
                </YandexMap>

            </div>

        </div>

    </div>

</template>

<script>

//Подключаем Yandex Карту
import { YandexMap, YandexMarker } from 'vue-yandex-maps'


export default {
    name: "Index",

    components: {
        YandexMap, YandexMarker
    },

    data(){
        return {

            settings :{
                apiKey: '8740b571-75d9-47f0-a5c4-582b1feaf201',
                lang: 'ru_RU',
                coordorder: 'latlong',
                enterprise: false,
                version: '2.1'
            },
            //Заносим координаты объекта
            coordinates : [],
            //Поведение при увеличении и уменьшении карты
            detailedControls :{ zoomControl: { position: { right: 0, top: 0 } } },
            //Стили для маркера, будет фото квартиры
            //Конфиг для маркера
            options : {
                iconLayout: 'default#image',
                iconImageHref: '',
                iconImageSize: [25, 26],
                iconImageOffset: [-20, -10],
            },
        }
    },

    mounted(){
        let app = this;

        document.querySelector(':root').classList.add('PATCH_modal');

        if(this.$route.query.image == 'no-image'){
            this.options.iconImageHref = '/img/siteImg/allImg/apartmens.jpg';
        }
        else{
            this.options.iconImageHref = '/img/adsImg/' + this.$route.query.image;
        }
        this.coordinates = [this.$route.query.lat, this.$route.query.lon];

    },

    beforeRouteLeave(to, from, next) {
        next();
    }


}
</script>


<!--Глобальные Стили для Yandex Карты на всем сайте -->
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

</style>

<style scoped>
.yandexMap__wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #ffffff;
    user-select: none;
}

.yandexMap__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
}

.yandexMap__body {
    flex-grow: 1; /* Растянем этот блок на всю оставшуюся высоту экрана */
}

</style>

