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
            const cities = {
                "Астана": {
                    "Астана": { zoom: 11, coordinates: [51.13235434017486,71.43261838305655] }
                },
                "Алматы": {
                    "Алматы": { zoom: 12, coordinates: [43.25016886648792,76.91359722562798] },
                },
                "Шымкент": {
                    "Шымкент": { zoom: 12, coordinates: [42.344958873660424,69.61155906079101] },
                },
                "Абай обл.": {
                    zoom: 6,
                    coordinates: [48.715458503026674,80.0905164999999],
                    "Семей": { zoom: 13, coordinates: [50.40591695904458,80.25028054736326] },
                    "Акжар": { zoom: 15, coordinates: [47.07884123110777,81.59049744448843] },
                    "Аягоз": { zoom: 13, coordinates: [47.96596608478283,80.43515343652332] },
                    "Букенчи": { zoom: 15, coordinates: [50.48501898428706,79.7230214180297] },
                    "Жангизтобе": { zoom: 15, coordinates: [49.21096740867809,81.21445699999998] },
                    "Зенковка": { zoom: 14, coordinates: [50.78904719924131,80.62361699999995] },
                    "Кабанбай": { zoom: 14, coordinates: [46.0765374350171,82.08677049999997] },
                    "Камышенка": { zoom: 14, coordinates: [50.70421954896948,80.74600399999999] },
                    "Караул": { zoom: 13, coordinates: [48.947998065455295,79.25251400000002] },
                    "Курчатов": { zoom: 13, coordinates: [50.754573709566124,78.54750044958483] },
                    "Маканчи": { zoom: 13, coordinates: [46.78426361999531,82.01821549999997] },
                    "Озерки": { zoom: 15, coordinates: [50.38031996051446,80.49127699999997] },
                    "Урджар": { zoom: 13, coordinates: [[47.08665022799585,81.62238199999996]] }
                },
                "Акмолинская обл.": {
                    zoom: 7,
                    coordinates: [51.90291228287236,69.80166699999994],
                    "Кокшетау": { zoom: 12, coordinates: [53.30124013562887,69.381502] },
                    "Косшы": { zoom: 12, coordinates: [50.982429842910015,71.3604325] },
                    "Степногорск": { zoom: 13, coordinates: [52.35107295473911,71.89634949999999] },
                    "Щучинск": { zoom: 12, coordinates: [52.93044616357264,70.22051499999998] },
                    "Атбасар": { zoom: 12, coordinates: [51.80506128369664,68.34441049999994] },
                    "Макинск": { zoom: 13, coordinates: [52.6311923051985,70.42530850000001] },
                    "Акколь": { zoom: 13, coordinates: [51.996052102647496,70.9529795] },
                },
                "Актюбинская обл.": {
                    zoom: 6,
                    coordinates: [48.33870956865984,58.9488775],
                    "Актобе": { zoom: 10, coordinates: [50.354849238247,57.32289500000002] },
                    "Алга": { zoom: 12, coordinates: [49.906304119434644,57.322922] },
                    "Жем": { zoom: 14, coordinates: [48.76971402988045,58.06591399999998] },
                    "Кандыагаш": { zoom: 11, coordinates: [49.46915886886464,57.38513499999991] },
                    "Темир": { zoom: 14, coordinates: [49.14746712322879,57.122710499999975] },
                    "Хромтау": { zoom: 11, coordinates: [50.28493054139381,58.448493499999934] },
                    "Шалкар": { zoom: 12, coordinates: [47.812997798423,59.613338999999954] },
                    "Эмба": { zoom: 13, coordinates: [48.823474419760004,58.13835449999999] },
                },
                "Алматинская обл.": {
                    zoom: 7,
                    coordinates: [44.51106560210735,77.25710000000001],
                    "Каскелен": { zoom: 13, coordinates: [43.193921474897884,76.63173749999999] },
                    "Конаев (Капчагай)": { zoom: 12, coordinates: [43.875321629468985,77.05876949999993] },
                    "Талгар": { zoom: 12, coordinates: [43.309182809412945,77.22925599999999] },
                    "Есик": { zoom: 13, coordinates: [43.34346871882701,77.46064849999998] },
                },
                "Атырауская обл.": {
                    zoom: 7,
                    coordinates: [47.519310463864116,51.76133549999999],
                    "Атырау": { zoom: 11, coordinates: [47.12861991349445,51.932679999999934] },
                    "Индер": { zoom: 13, coordinates: [48.55539854755236,51.74493249999993] },
                    "Жилгородок": { zoom: 15, coordinates: [47.09622420972974,51.902545999999965] },
                    "Кульсары": { zoom: 12, coordinates: [46.950272887240686,54.0111345] },
                    "Махамбет": { zoom: 12, coordinates: [47.68075627263942,51.58867949999988] },
                    "Ганюшкино": { zoom: 13, coordinates: [46.604301080617404,49.273019999999974] },
                },
                "Восточно-Казахстанская обл.": {
                    zoom: 7,
                    coordinates: [48.964772602430266,84.21574600000002],
                    "Усть-Каменогорск": { zoom: 11, coordinates: [49.96266068473033,82.6337865] },
                    "Риддер": { zoom: 12, coordinates: [50.337357023137415,83.50171150000001] },
                    "Шар": { zoom: 13, coordinates: [49.579377777673166,81.05417499999989] },
                    "Бородулиха": { zoom: 12, coordinates: [50.718202539771426,80.92635399999999] },
                    "Шемонаиха": { zoom: 13, coordinates: [50.62622367103176,81.90160099999994] },
                },
                "Жамбылская обл.": {
                    zoom: 7,
                    coordinates: [44.189656827134606,72.44707049999992],
                    "Тараз": { zoom: 11, coordinates: [42.869236635233605,71.36455149999999] },
                    "Жанатас": { zoom: 13, coordinates: [43.56996838609875,69.71751250000001] },
                    "Мерке": { zoom: 13, coordinates: [42.86461781431134,73.1998094999999] },
                    "Каратау": { zoom: 13, coordinates: [43.1831580654925,70.45306199999995] },
                    "шу": { zoom: 12, coordinates: [43.6122264819767,73.75876349999994] },
                    "Байзак": { zoom: 13, coordinates: [42.97796175668706,71.52874099999997] },
                },
                "Жетысу обл.": {
                    zoom: 7,
                    coordinates: [45.54963801844548,79.38673999999999],
                    "Талдыкорган": { zoom: 12, coordinates: [45.01040733949712,78.36725100000001] },
                    "Жаркент": { zoom: 13, coordinates: [44.17024697693239,80.00178549999991] },
                    "Текели": { zoom: 13, coordinates: [44.86395659814841,78.76412916650382] },
                    "Сарканд": { zoom: 13, coordinates: [45.408758924281635,79.90876899999996] },
                },
                "Западно-Казахстанская обл.": {
                    zoom: 7,
                    coordinates: [49.90016270223785,50.53347299999998],
                    "Уральск": { zoom: 11, coordinates: [51.23569208951529,51.40941549999995] },
                    "Белес": { zoom: 15, coordinates: [51.26765640405268,51.04929899999998] },
                    "Шынгырлау": { zoom: 13, coordinates: [51.09619654919049,54.085151499999974] },
                    "Таскала": { zoom: 13, coordinates: [51.110267448779275,50.2939555] },
                    "Бурлин": { zoom: 14, coordinates: [51.4254206081471,52.721634499999865] },
                    "Жалпактал": { zoom: 13, coordinates: [49.66537892307789,49.471237999999886] },
                },
                "Карагандинская обл.": {
                    zoom: 6,
                    coordinates: [48.72385425997547,72.55379499999998],
                },
                "Костанайская обл.": {
                    zoom: 6,
                    coordinates: [51.57285036813062,64.0508095],
                },
                "Кызылординская обл.": {
                    zoom: 6,
                    coordinates: [45.15500490533879,63.54006799999993],
                },
                "Мангистауская обл.": {
                    zoom: 6,
                    coordinates: [43.93908336603239,53.28500799999995],
                },
                "Павлодарская обл.": {
                    zoom: 6,
                    coordinates: [52.28903691328998,76.37294149999997],
                },
                "Северо-Казахстанская обл.": {
                    zoom: 7,
                    coordinates: [53.85693066564357,70.00124099999996],
                    "Петропавловск": { zoom: 12, coordinates: [54.88035250869472,69.15999253710937] },
                    "Мамлютка": { zoom: 13, coordinates: [54.93772720524094,68.53993800000002] },
                    "Тайынша": { zoom: 13, coordinates: [53.84830569112205,69.75746099999995] },
                    "Сергеевка": { zoom: 13, coordinates: [53.88804069664155,67.410944] },
                },
                "Туркестанская обл.": {
                    zoom: 6,
                    coordinates: [43.35015256552017,68.46576849999987],
                },
                "Улытау обл.": {
                    zoom: 7,
                    coordinates: [48.003791653841645,67.403084],
                    "Жезказган": { zoom: 13, coordinates: [47.76589892737715,67.70107299999991] },
                    "Сатпаев": { zoom: 13, coordinates: [47.89345511560729,67.54272749999998] },
                    "Каражал": { zoom: 12, coordinates: [48.03820371028707,70.90873700000003] },
                },
            };

            const selectedCity = filter.gorod;
            const selectedOblast = filter.oblast;

            // Проверяем, есть ли выбранная область и город
            if (cities[selectedOblast]) {
                if (cities[selectedOblast][selectedCity]) {
                    // Если город найден в области, используем его координаты и zoom
                    this.zoom = cities[selectedOblast][selectedCity].zoom;
                    this.coordinates = cities[selectedOblast][selectedCity].coordinates;

                } else {
                    // Если город не найден, используем координаты и zoom области
                    this.zoom = cities[selectedOblast].zoom;
                    this.coordinates = cities[selectedOblast].coordinates;
                }
            }
            else {
                // Если область не найдена, можно установить значения по умолчанию или обработать ошибку
                this.zoom = 5; // Значение по умолчанию
                this.coordinates = [48.0, 68.0]; // Центр Казахстана
            }

            //Получим все квартиры с БД для отображения на карте
            this.getAds();
        },

        //Метод получить все объявления для отображения их на карте
       getAds() {

            this.query = true;

            //Получаю объявления по выбранной локации
            axios.get('/getAllAdsYandexCluster', {

                params: {
                    user_id: useAuthStore().user.id ?? 0,
                    table_name: this.$route.params.table_name,
                    filter: JSON.parse(localStorage.getItem("filter=" + this.$route.params.table_name)) ??  'Фильтр не применен',
                    getMyLikeAds: localStorage.getItem('getMyLikeAds') != undefined ?'Получить мои лайки': 'Не получать мои лайки',//Не получать мои лайки
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
.ymaps-2-1-79-map-copyrights-promo{
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
<!--398-->
