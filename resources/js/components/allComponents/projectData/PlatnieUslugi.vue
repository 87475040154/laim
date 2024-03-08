<template>
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
                <h4 class="fw-bold px-1">{{ $t('bueAdsIndexx30ViewsPerMonth') }}</h4>

            </div>

            <!-- Текст описания -->
            <div class="d-flex">
                <v-icon icon="mdi-check mx-2"></v-icon>
                <div class="text-grey">{{ $t('bueAdsIndex28DaysOfActivePromotion') }}</div>
            </div>
            <div class="d-flex py-2">
                <v-icon icon="mdi-check mx-2"></v-icon>
                <div class="text-grey">{{ $t('bueAdsIndexEveryDayInTheTOP') }}</div>
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
                <h4 class="fw-bold px-1">{{ $t('bueAdsIndexx7ViewsPerWeek') }}</h4>

            </div>

            <!-- Текст описания -->
            <div class="d-flex">
                <v-icon icon="mdi-check mx-2"></v-icon>
                <div class="text-grey">{{ $t('bueAdsIndex7DaysOfActivePromotion') }}</div>
            </div>
            <div class="d-flex py-2 ">
                <v-icon icon="mdi-check mx-2"></v-icon>
                <div class="text-grey">{{ $t('bueAdsIndexEveryDayInTheTOP') }}</div>
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

        </div>

        <!-- Продвинуть объявление - по 1 услуге -->
        <div class="bueAds__card">

            <!-- Заголовок-->
            <div class="p-2">
                <h4 class="fw-bold">{{ $t('bueAdsIndexPromoteAnAd') }}</h4>

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
                    <div class="flex-grow-1 align-center">
                        <div>{{ $t('bueAdsIndexTOPPromotionForADay') }}</div>
                        <div>{{ $t('bueAdsIndex8TimesInTheTOPFor') }} 1900 &#8376;</div>
                    </div>

                </div>

                <!-- ТОП 24 часа -->
                <div class="d-flex align-center py-3">

                    <!-- Иконка -->
                    <div class="mx-3 d-flex justify-center align-center rounded-circle"
                         style="width: 21px; height: 21px; background: orange"
                    >
                        <v-icon icon="mdi-triangle" size="x-small" color="white"></v-icon>
                    </div>

                    <!-- Описание -->
                    <div class="flex-grow-1 align-center" >
                        <div>{{ $t('bueAdsIndexSendToTheTOP24Hours') }}</div>
                        <div class="text-grey">300 &#8376;</div>
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
                    <div class="flex-grow-1 align-center">
                        <div>{{ $t('bueAdsIndexPutUrgentBargain') }}</div>
                        <div class="text-grey">90 &#8376;</div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</template>

<script>

//Store
export default {
    name: "Index",

    data(){
        return {
        }
    }
}
</script>

<style scoped>
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
