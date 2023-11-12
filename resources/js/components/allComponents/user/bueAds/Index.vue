<template>
    <!-- КОМПОНЕНТ - ПОКУПКА РЕКЛАМЫ -->

    <!-- Обвертка - Компонента -->
    <div class="bueAds__wrapper">

        <!-- Сам блок -->
        <div class="bueAds__block">

            <!-- Header -->
            <div class="bueAds__header">

                <!-- Кнопка Назад   -->
                <v-btn icon dark variant="text" class="ml-2"  @click="$router.back()">
                    <v-icon size="large">mdi-arrow-left</v-icon>
                </v-btn>

                <div class="text-body-1"> {{ $t('bueAdsIndexPromoteAnAd') }}</div>

            </div>

            <!-- Body -->
            <div class="bueAds__body">

                <!-- Обвертка для карточек -->
                <div class="bueAdsCard__wrapper">

                    <!-- x30 просмотров на месяц -->
                    <div class="bueAds__card">

                        <!-- Заголовок - x30 просмотров на месяц -->
                        <div class="d-flex py-2">

                            <!-- Иконка -->
                            <div class="mx-2 d-flex justify-center align-center rounded-circle"
                                 style="width: 21px; height: 21px; background: red"
                            >
                                <v-icon icon="mdi-crown" size="x-small" color="white"></v-icon>
                            </div>

                            <!-- Текст заголовок -->
                            <h5 class="fw-bold px-1">{{ $t('bueAdsIndexx30ViewsPerMonth') }}</h5>

                        </div>

                        <!-- Текст описания -->
                        <div class="d-flex py-2">
                            <v-icon icon="mdi-check mx-2"></v-icon>
                            <div class="text-grey">{{ $t('bueAdsIndex28DaysOfActivePromotion') }}</div>
                        </div>
                        <div class="d-flex ">
                            <v-icon icon="mdi-check mx-2"></v-icon>
                            <div class="text-grey">{{ $t('bueAdsIndexEveryDayInTheTOP') }}</div>
                        </div>
                        <div class="d-flex py-2">
                            <v-icon icon="mdi-check mx-2"></v-icon>
                            <div class="text-grey">{{ $t('bueAdsIndexEveryDayInTheFire') }}</div>
                        </div>
                        <div class="d-flex">
                            <v-icon icon="mdi-check mx-2"></v-icon>
                            <div class="text-grey">{{ $t('bueAdsIndexRedPainting') }}</div>
                        </div>
                        <div class="d-flex text-center align-center py-2">
                            <div class="text-grey flex-grow-1" style="text-decoration: line-through">15 120 &#8376;</div>
                            <div class="flex-grow-1" style="font-size: 1.3em">3 500 &#8376;</div>
                            <div class="flex-grow-1 text-green">{{ $t('bueAdsIndexEconomy') }} 11 620 &#8376;</div>
                        </div>

                        <!-- Кнопка продвинуть -->
                        <div class="px-3 py-1">
                            <v-btn @click="showBottomOffcanvas('Top x30')"  size="large" color="blue-darken-2" block class="text-body-1">
                                {{ $t('bueAdsIndexPromote') }}
                            </v-btn>
                        </div>

                    </div>

                    <!-- x7 просмотров на неделю -->
                    <div class="bueAds__card">

                        <!-- Заголовок - x7 просмотров на неделю -->
                        <div class="d-flex py-2">

                            <!-- Иконка -->
                            <div class="mx-2 d-flex justify-center align-center rounded-circle"
                                 style="width: 21px; height: 21px; background: #10a37f"
                            >
                                <v-icon icon="mdi-diamond" size="x-small" color="white"></v-icon>
                            </div>

                            <!-- Текст заголовок -->
                            <h5 class="fw-bold px-1">{{ $t('bueAdsIndexx7ViewsPerWeek') }}</h5>

                        </div>

                        <!-- Текст описания -->
                        <div class="d-flex py-2">
                            <v-icon icon="mdi-check mx-2"></v-icon>
                            <div class="text-grey">{{ $t('bueAdsIndex7DaysOfActivePromotion') }}</div>
                        </div>
                        <div class="d-flex ">
                            <v-icon icon="mdi-check mx-2"></v-icon>
                            <div class="text-grey">{{ $t('bueAdsIndexEveryDayInTheTOP') }}</div>
                        </div>
                        <div class="d-flex py-2">
                            <v-icon icon="mdi-check mx-2"></v-icon>
                            <div class="text-grey">{{ $t('bueAdsIndexEveryDayInTheFire') }}</div>
                        </div>
                        <div class="d-flex">
                            <v-icon icon="mdi-check mx-2"></v-icon>
                            <div class="text-grey">{{ $t('bueAdsIndexGreenPainting') }}</div>
                        </div>
                        <div class="d-flex text-center align-center py-2">
                            <div class="text-grey flex-grow-1" style="text-decoration: line-through">3 780 &#8376;</div>
                            <div class="flex-grow-1" style="font-size: 1.3em">2 100 &#8376;</div>
                            <div class="flex-grow-1 text-green">{{ $t('bueAdsIndexEconomy') }} 1 680 &#8376;</div>
                        </div>

                        <!-- Кнопка продвинуть -->
                        <div class="px-3 py-1">
                            <v-btn @click="showBottomOffcanvas('Top x7')" size="large" color="blue-darken-2" block class="text-body-1">
                                {{ $t('bueAdsIndexPromote') }}
                            </v-btn>
                        </div>

                    </div>

                    <!-- Продвинуть объявление - по 1 услуге -->
                    <div class="bueAds__card">

                        <!-- Заголовок-->
                        <div class="p-2">
                            <h5 class="fw-bold">{{ $t('bueAdsIndexPromoteAnAd') }}</h5>

                            <!-- Подзаголовок -->
                            <div class="text-grey">
                                {{ $t('bueAdsIndexPaidServicesWillHelpYouPassFaster') }}
                            </div>

                        </div>

                        <!-- Текст описания -->
                        <div class="p-0 pt-1">

                            <!-- Топ на сутки 8 раз -->
                            <div class="d-flex align-center text-white" style="background: rgb(0,212,255);
background: linear-gradient(90deg, rgba(0,212,255,1) 0%, rgba(26,135,175,1) 0%, rgba(177,32,161,1) 0%, rgba(115,19,146,1) 80%, rgba(106,7,144,1) 100%, rgba(145,8,82,1) 100%, rgba(138,4,142,1) 100%);">

                                <!-- Иконка -->
                                <div class="mx-3 d-flex justify-center align-center rounded-circle"
                                     style="width: 21px; height: 21px; border: 1px solid white"
                                >
                                    <v-icon icon="mdi-triangle-outline" size="x-small" color="white"></v-icon>
                                </div>

                                <!-- Описание -->
                                <div class="flex-grow-1 align-center" @click="bueAdsType.includes('Топ 8 раз') ? bueAdsType = bueAdsType.filter(function(f) { return f !== 'Топ 8 раз' }) : bueAdsType.unshift('Топ 8 раз') ">
                                    <div>{{ $t('bueAdsIndexTOPPromotionForADay') }}</div>
                                    <div>{{ $t('bueAdsIndex8TimesInTheTOPFor') }} 1900 &#8376;</div>
                                </div>

                                <!-- Переключатель - Switch -->
                                <div class="mx-2" style="height: 55px">
                                    <v-switch v-model="bueAdsType" value="Топ 8 раз" color="teal-darken-2" inset></v-switch>
                                </div>

                            </div>

                            <!-- ТОП 24 часа -->
                            <div class="d-flex align-center" :class="{'opacity-25': bueAdsType.includes('Топ 8 раз')}">

                                <!-- Иконка -->
                                <div class="mx-3 d-flex justify-center align-center rounded-circle"
                                     style="width: 21px; height: 21px; background: orange"
                                >
                                    <v-icon icon="mdi-triangle" size="x-small" color="white"></v-icon>
                                </div>

                                <!-- Описание -->
                                <div class="flex-grow-1 align-center" @click="bueAdsType.includes('Топ 24') ? bueAdsType = bueAdsType.filter(function(f) { return f !== 'Топ 24' }) : bueAdsType.unshift('Топ 24') ">
                                    <div>{{ $t('bueAdsIndexSendToTheTOP24Hours') }}</div>
                                    <div class="text-grey">300 &#8376;</div>
                                </div>

                                <!-- Переключатель - Switch-->
                                <div class="mx-2"  style="height: 55px">
                                    <v-switch v-model="bueAdsType" value="Топ 24" color="teal-darken-2" inset :disabled="bueAdsType.includes('Топ 8 раз')"></v-switch>
                                </div>

                            </div>

                            <!-- Горячие на 24 часа -->
                            <div class="d-flex align-center" style="border-top: 1px solid rgba(0,0,0,.1); border-bottom: 1px solid rgba(0,0,0,.1)">

                                <!-- Иконка -->
                                <div class="mx-3 d-flex justify-center align-center rounded-circle"
                                     style="width: 21px; height: 21px; background: #FF0000"
                                >
                                    <v-icon icon="mdi-fire" size="x-small" color="white"></v-icon>
                                </div>

                                <!-- Описание -->
                                <div class="flex-grow-1 align-center" @click="bueAdsType.includes('Горячие') ? bueAdsType = bueAdsType.filter(function(f) { return f !== 'Горячие' }) : bueAdsType.unshift('Горячие')">
                                    <div>{{ $t('bueAdsIndexSendToTheFire') }}</div>
                                    <div class="text-grey">240 &#8376;</div>
                                </div>

                                <!-- Переключатель - Switch-->
                                <div class="mx-2"  style="height: 55px">
                                    <v-switch v-model="bueAdsType" value="Горячие" color="teal-darken-2" inset></v-switch>
                                </div>

                            </div>

                            <!-- Срочно торг -->
                            <div class="d-flex align-center">

                                <!-- Иконка -->
                                <div class="mx-3 d-flex justify-center align-center rounded-circle"
                                     style="width: 21px; height: 21px; background: yellow"
                                >
                                    <v-icon icon="mdi-tag" size="x-small" color="white"></v-icon>
                                </div>


                                <!-- Описание -->
                                <div class="flex-grow-1 align-center" @click="bueAdsType.includes('Срочно торг') ? bueAdsType = bueAdsType.filter(function(f) { return f !== 'Срочно торг' }) : bueAdsType.unshift('Срочно торг')">
                                    <div>{{ $t('bueAdsIndexPutUrgentBargain') }}</div>
                                    <div class="text-grey">90 &#8376;</div>
                                </div>

                                <!-- Переключатель - Switch -->
                                <div class="mx-2" style="height: 55px">
                                    <v-switch v-model="bueAdsType" value="Срочно торг" color="teal-darken-2" inset></v-switch>
                                </div>

                            </div>

                        </div>

                        <!-- Кнопка продвинуть - c рекламой -->
                        <div class="px-3 pb-3" v-if="bueAdsType.length > 0">
                            <v-btn @click="showBottomOffcanvas('Top')" size="large" color="blue-darken-2" block :disabled="query" class="text-body-1">
                                <v-progress-circular v-if="query" size="x-small" indeterminate color="white"></v-progress-circular>
                                <span>{{ $t('bueAdsIndexPromoteFor') }} {{bueAdsSumm}} &#8376;</span>
                            </v-btn>
                        </div>

                        <!-- Кнопка опубликовать без - рекламы -->
                        <div class="px-3 pb-3" v-else>
                            <v-btn @click="$router.replace('/userAds/Kvartira/'+authStore.user.id + '/1')" size="large" color="blue-darken-2" block class="text-body-1">
                                {{ $t('bueAdsIndexSubmitWithoutPromotion') }}
                            </v-btn>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Внутренние страницы - Покупка рекламы и тд. -->
    <router-view></router-view>

</template>

<script>

//Дерективы для формата номера телефона и цены
import { mask } from 'vue-the-mask'

//Импортирую Store - Общее состояние
import { useAuthStore } from "../../../../stores/auth";
import { useCheckInternetStore} from "../../../../stores/checkInternet";

export default {
    name: "Index",

    //Для телефона трансформировать ввод
    directives: {mask},


    data(){
        return {
            //Подключаю Store
            authStore: useAuthStore(),
            checkInternetStore: useCheckInternetStore(),

            //Покупка рекламных услуг
            bueAdsType: ['Топ 24'],

            summ: '',

            query: false
        }
    },


    computed:{

        //Сколько итого за рекламу
        bueAdsSumm(){
            let sum = 0;
            if(this.bueAdsType.includes('Топ 8 раз')){
                let index = this.bueAdsType.indexOf('Топ 24');
                if(index >= 0) {
                    this.bueAdsType.splice(index, 1);
                }
                sum += 1900
            }
            if(this.bueAdsType.includes('Топ 24'))sum += 300
            if(this.bueAdsType.includes('Горячие'))sum += 240
            if(this.bueAdsType.includes('Срочно торг'))sum += 90

            return sum;
        }

    },

    methods: {

        //Открыть нижний OffCanvas для покупки рекламы
        showBottomOffcanvas(type){

            if(type == 'Top x30'){this.summ = 3500; this.bueAdsType = ['Top x30']}
            if(type == 'Top x7') {this.summ = 2100; this.bueAdsType = ['Top x7']}
            if(type == 'Top') {
                this.summ = this.bueAdsSumm;
                //Уберем топ 30
                let top_x30 = this.bueAdsType.indexOf("Top x30");
                if (top_x30 !== -1) {
                    this.bueAdsType.splice(top_x30, 1);
                }
                let top_x7 = this.bueAdsType.indexOf("Top x7");
                if (top_x7 !== -1) {
                    this.bueAdsType.splice(top_x7, 1);
                }
            }

            this.$router.push('/bueAdsPayMethod/' + this.authStore.user.id + '/'+ this.$route.params.table_name + '/1/' + this.$route.params.ads_id
            + '/' + this.bueAdsType)
        },

    },

    mounted(){
        let app = this;
        document.querySelector(':root').classList.add('PATCH_modal'); //Отменим прокрутку под модальным окном
    },


    beforeRouteLeave(to, from, next) {
        next();
    }

}
</script>


<style scoped>

.bueAds__wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #ffffff;
}

.bueAds__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
}


.bueAds__header {
    width: 100%;
    height: auto;
    display: flex;
    align-items: center;
    background: var(--app-header-bg-color);
    color: #ffffff;
}

.bueAds__body {
    flex-grow: 1; /* Растянем этот блок на всю оставшуюся высоту экрана */
    padding: 5px;
    height: 100%;
    overflow-y: auto;
    background: rgb(237, 238, 240);
}

.bueAdsCard__wrapper{
    width: 100%;
    max-width: 800px;
    margin: 10px auto;
}

.bueAds__card{
    width: 100%;
    max-width: 500px;
    border: 1px solid rgba(0,0,0,.1);
    border-radius: 5px;
    padding: 10px;
    margin: 10px auto;
    background: #ffffff;
}

@media (min-width: 992px) {

    .bueAdsCard__wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .bueAds__card {
        flex-basis: calc(50% - 10px); /* 50% ширины экрана с отступами между элементами */
        padding: 10px;
    }

    .bueAds__card:last-child {
        max-width: 100%;
        margin-top: 10px;
        flex-basis: 100%; /* Последний блок растягивается на 100% ширины экрана */
    }

}


</style>
