<template>

    <!-- Компонент вывод превью объявлений -->

    <!-- Это компонент для виртуального скрола- чтоб убрать лишнее элементы в DOM -->
    <DynamicScroller
        v-if="ads_arr && ads_arr.length > 0"
        :page-mode="true"
        class="scroller"
        :items="ads_arr"
        :min-item-size="100"
        key-field="id"
        @scroll-end="$emit('get-ads-cursor-paginate')"

    >

        <template v-slot="{ item:ads, index, active }">
            <DynamicScrollerItem
                :item="ads"
                :active="active"
                :size-dependencies="[
                  ]"
                :data-index="index"
                style="padding: 1px;"
            >
                <!-- Сам блок с превью -->
                <v-card max-width="650" style="background: #ffffff; box-shadow: none; border-radius: 6px"
                        class="mx-3 my-2 mx-sm-auto ads__preview"
                >

                    <!--  Описание объявления -->
                    <div class="d-flex p-md-2">

                        <!-- Фото -->
                        <div class="image__block">

                            <!-- Срочно торг -->
                            <div v-if="ads.srochno_torg" style="position: absolute; top: 5px; left: 5px;" class="bg-yellow-darken-2 rounded-sm text-caption px-1">
                                {{ $t('adsPreviewComponentUrgentBargaining') }}
                            </div>

                            <img v-if="ads.images.length > 0" @click="showImage(ads)" class="ads__preview-img rounded-2" :src=" '/img/adsImg/' + ads.images[0] " alt="Фото недвижимости">
                            <img v-else src="/public/img/siteImg/allImg/no-image-buildings.png" alt="Нет фото" class="ads__preview-img">

                            <!-- Кто автор - Хозяин - Специалист - В архиве - Не активно - -->
                            <div class="d-flex gap-1 p-1" style="position: absolute; bottom: 0; left: 0; width: 100%; height: auto">

                                <div v-if="ads.control == 'В архиве'" class="bg-red p-1 px-2 rounded-lg">
                                    {{ $t('AdsPreviewAddArhive') }}
                                </div>
                                <div v-if="ads.control == 'Поступили жалобы' && ads.author_id == authStore.user.id" class="bg-red p-1 px-2 rounded-lg">
                                    {{ $t('AdsPreviewAddComplain') }}
                                </div>
                                <div v-if="ads.control == 'Активно' && ads.author_id == authStore.user.id" class="bg-green p-1 px-2 rounded-lg">
                                    {{ $t('AdsPreviewAddActive') }}
                                </div>
                                <div v-if="ads.control == 'Не активно'" class="bg-blue p-1 px-2 rounded-lg">
                                    {{ $t('AdsPreviewAddNoActive') }}
                                </div>

                                <div v-if="ads.ownerOrRealtor == 'Хозяин' && ads.author_id != authStore.user.id && ads.control != 'В архиве'" class="bg-green p-1 px-2 rounded-lg">
                                    <span v-if="updateDateLocale.lang == 'ru'"> {{ads.ownerOrRealtor}} </span>
                                    <span v-if="updateDateLocale.lang == 'kz'"> Иесі </span>
                                    <span v-if="updateDateLocale.lang == 'en'"> Owner </span>
                                </div>
                                <div v-if="ads.ownerOrRealtor == 'Специалист' && ads.author_id != authStore.user.id && ads.control != 'В архиве'" class="bg-blue p-1 px-2 rounded-lg">
                                    <span v-if="updateDateLocale.lang == 'ru'"> {{ads.ownerOrRealtor}} </span>
                                    <span v-if="updateDateLocale.lang == 'kz'"> Маман </span>
                                    <span v-if="updateDateLocale.lang == 'en'"> Specialist </span>
                                </div>
                                <div v-if="ads.ownerOrRealtor == 'Через риелтора' && ads.author_id != authStore.user.id && ads.table_name == 'Snimu' && ads.control != 'В архиве'" class="bg-blue p-1 px-2 rounded-lg">
                                    <span v-if="updateDateLocale.lang == 'ru'"> Можно от специалиста </span>
                                    <span v-if="updateDateLocale.lang == 'kz'"> Мүмкін маманнан </span>
                                    <span v-if="updateDateLocale.lang == 'en'"> It is possible from a specialist </span>
                                </div>

                            </div>

                        </div>

                        <!--Блок - Описание объявления -->
                        <div class="col pl-2">

                            <!--Блок - Описание объявления -->
                            <div @click="showOneAds(ads, index)" role="button" class="d-flex align-start flex-column" style="min-height: 115px">

                                <!-- Заголовок -->
                                <div style="font-size: 17px; color: #4b4b4b">
                                    {{ads.zagolovok}}
                                </div>

                                <!-- Цена аренды -->
                                <div class="my-auto fw-bold" style="font-size: 1.2em">
                                    {{ $filters.format_number(ads.cena) }} &#8376;
                                </div>

                                <!-- Адрес объекта -->
                                <div class="mt-auto" style="font-size: 0.9em; color: #5d6f6a">
                                    <span>{{ KZLocationStore.translateLocation({gorod: ads.gorod}).gorod }}</span>

                                    <span v-if="ads.raion != undefined && ads.raion != ''">
                                        , {{ KZLocationStore.translateLocation({raion: ads.raion}).raion }}
                                    </span>
                                </div>

                            </div>

                            <!-- Дата публикации - Лайк -->
                            <div class="d-flex align-center gap-2 position-relative">

                                <!-- Дата публикации -->
                                <div style="font-size: 0.9em; color: #5d6f6a">
                                    {{ $filters.transformDateRu(ads.created_at) }}
                                </div>

                                <v-spacer></v-spacer>

                                <!-- Если Отправленно в ТОП или ТОП х7, ТОП х30-->
                                <div class="d-flex gap-1 p-1" style="position: absolute; bottom: 0; right: 30px">

                                    <!-- ТОП 30 -->
                                    <v-menu open-on-hover>
                                        <template v-slot:activator="{ props }">
                                            <div v-if="ads.top_x30 != null" class="icon__crown" v-bind="props">
                                                <v-icon icon="mdi-crown" size="x-small" color="white"></v-icon>
                                            </div>
                                        </template>
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
                                            <div class="d-flex">
                                                <v-icon icon="mdi-check mx-2"></v-icon>
                                                <div class="text-grey">{{ $t('bueAdsIndex28DaysOfActivePromotion') }}</div>
                                            </div>
                                            <div class="d-flex py-2">
                                                <v-icon icon="mdi-check mx-2"></v-icon>
                                                <div class="text-grey">{{ $t('bueAdsIndexEveryDayInTheTOP') }}</div>
                                            </div>

                                        </div>
                                    </v-menu>

                                    <!-- ТОП 7 -->
                                    <v-menu open-on-hover>
                                        <template v-slot:activator="{ props }">
                                            <div v-if="ads.top_x7 != null" class="icon__diamond" v-bind="props">
                                                <v-icon icon="mdi-diamond" size="x-small" color="white"></v-icon>
                                            </div>
                                        </template>
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
                                            <div class="d-flex ">
                                                <v-icon icon="mdi-check mx-2"></v-icon>
                                                <div class="text-grey">{{ $t('bueAdsIndex7DaysOfActivePromotion') }}</div>
                                            </div>
                                            <div class="d-flex py-2">
                                                <v-icon icon="mdi-check mx-2"></v-icon>
                                                <div class="text-grey">{{ $t('bueAdsIndexEveryDayInTheTOP') }}</div>
                                            </div>
                                        </div>
                                    </v-menu>

                                    <!-- ТОП 24 час -->
                                    <v-menu open-on-hover>
                                        <template v-slot:activator="{ props }">
                                            <div v-if="ads.top != null" class="icon__triangle" v-bind="props">
                                                <v-icon icon="mdi-triangle" size="x-small" color="white"></v-icon>
                                            </div>
                                        </template>
                                        <div class="bueAds__card">

                                            <!-- ТОП 24 часа -->
                                            <div class="d-flex align-center">

                                                <!-- Иконка -->
                                                <div class="mx-3 d-flex justify-center align-center rounded-circle"
                                                     style="width: 21px; height: 21px; background: orange"
                                                >
                                                    <v-icon icon="mdi-triangle" size="x-small" color="white"></v-icon>
                                                </div>

                                                <!-- Описание -->
                                                <div class="flex-grow-1 align-center" >
                                                    <div>{{ $t('bueAdsIndexSendToTheTOP24Hours') }}</div>
                                                </div>

                                            </div>
                                        </div>
                                    </v-menu>

                                    <!-- 8 Раз в ТОП за 24 часа -->
                                    <v-menu open-on-hover>
                                        <template v-slot:activator="{ props }">
                                            <div v-if="ads.top_8 != null" class="icon__triangle icon__triangleTOP8" v-bind="props">
                                                <v-icon icon="mdi-triangle" size="x-small" color="white"></v-icon>
                                            </div>
                                        </template>
                                        <div class="bueAds__card">

                                            <!-- Поднятие в ТОП каждые 3 часа -->
                                            <div class="d-flex align-center">

                                                <!-- Иконка -->
                                                <div class="mx-3 d-flex justify-center align-center rounded-circle"
                                                     style="width: 21px; height: 21px; background: #710250"
                                                >
                                                    <v-icon icon="mdi-triangle" size="x-small" color="white"></v-icon>
                                                </div>

                                                <!-- Описание -->
                                                <div class="flex-grow-1 align-center" >
                                                    <div>{{ $t('bueAdsIndexSendToTheTOP8') }}</div>
                                                </div>

                                            </div>
                                        </div>
                                    </v-menu>

                                </div>

                                <!-- Кнопка лайк -->
                                <span>
                                    <v-icon :color="ads.userLike ? 'red' : 'grey-lighten-1'"
                                            class="icon__heart mx-1"
                                            size="large"
                                            @click="authStore.check ? addLikeToggle(index, ads): $router.push({name: $route.name + 'Auth'})"
                                    >
                                    mdi-heart
                                    </v-icon>
                                    <v-tooltip activator="parent" location="bottom">{{ $t('AdsPreviewAddFavorites') }}</v-tooltip>
                                </span>


                            </div>

                        </div>

                    </div>

                    <!--  - Управление объявлением - Продвигать рекламу - Сдать быстрее -->
                    <div class="px-md-2 py-1 m-lg-2 bg-grey-lighten-2"
                         v-if="authStore.check && authStore.user.id == ads.author_id
                    && $route.name == 'userAds' && ads.control != 'В архиве'
                    || authStore.check && authStore.user.role == 'admin' && ads.control != 'В архиве'"
                    >

                        <div class="d-flex justify-content-between align-center">

                            <!-- Кнопка сдать быстрее -->
                            <v-btn dark color="blue-darken-2"
                                   size="x-large"
                                   @click="$router.push({ name: $route.name + 'BueAds', params: {ads_id: ads.id} } )"
                                   class="text-body-1"
                                   style="min-width: 170px"
                            >
                                {{ $t('adsPreviewComponentPassFaster') }}
                            </v-btn>

                            <!-- Просмотров - Взяли номера -->
                            <div class="dropup-center dropup">

                                <!-- Кнопка - Окрыть скрытое меню -->
                                <v-btn icon size="x-large" data-bs-toggle="dropdown" aria-expanded="false">
                                    <v-icon>mdi-finance</v-icon>
                                </v-btn>

                                <!-- Тело - Скрытого меню-->
                                <div class="dropdown-menu p-3">
                                    <h4 class="text-center fw-bold mb-3">{{ $t('AdsPreviewAddStatistics') }}</h4>
                                    <div>{{ $t('AdsPreviewAddViewed') }} : <span class="fw-bold">{{ads.view}}</span></div>
                                    <div class="mt-2">{{ $t('AdsPreviewAddGotTheNumber') }} : <span class="fw-bold">{{ads.viewTel}}</span></div>
                                </div>
                            </div>

                            <!-- Блок - Управление объявлением - для автора и админа -->
                            <div class="dropstart">

                                <!-- Кнопка - Окрыть скрытое меню -->
                                <v-btn icon size="x-large" data-bs-toggle="dropdown" aria-expanded="false">
                                    <v-icon>mdi-dots-vertical</v-icon>
                                </v-btn>

                                <!-- Тело - Скрытого меню-->
                                <div class="dropdown-menu p-0 border-none">
                                    <div class="d-flex align-center justify-end gap-3 px-3 bg-grey-lighten-2"
                                         style="width: 90vw; max-width: 600px"
                                    >

                                        <!-- Кнопка - Рекламировать или Отановить объявление -->
                                        <v-btn dark
                                               color="blue-darken-2"
                                               class="text-body-1"
                                               size="x-large"
                                               @click="adsActiveToggle(index, ads.id, ads.table_name, ads.control)"
                                               :disabled="query"
                                        >
                                            {{ads.control == 'Активно' ? $t('adsPreviewComponentStop') : $t('adsPreviewComponentToAdvertise')}}
                                        </v-btn>

                                        <!-- Кнопка - Редактировать обьявление -->
                                        <v-btn dark icon
                                               size="x-large"
                                               color="blue-darken-2"
                                               @click="$router.push({name: $route.name + 'AddAds', params: {table_name: ads.table_name, id: ads.id, step:1}})"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </v-btn>

                                        <!-- Кнопка - Удалить объявление -->
                                        <v-btn dark icon
                                               size="x-large"
                                               color="blue-darken-2"
                                               @click="deleteAds(index, ads.id, ads.table_name, ads.control)"
                                               :disabled="query"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </v-btn>

                                    </div>
                                </div>

                            </div>

                        </div>

                        <!-- На сайте до-->
                        <div class="px-1 px-md-0">
                            <span v-if="updateDateLocale.lang == 'ru'">На сайте до: </span>
                            <span v-if="updateDateLocale.lang == 'en'">Before: </span>
                            <span v-if="ads.top_x30 != null">{{ addDaysToCurrentDate(ads.top_x30, 30) }}</span>
                            <span v-else-if="ads.top_x7 != null">{{ addDaysToCurrentDate(ads.top_x7, 7) }}</span>
                            <span v-else>{{ addDaysToCurrentDate(ads.updated_at, 7) }}</span>
                            <span v-if="updateDateLocale.lang == 'kz'" class="pl-1"> дейін</span>

                        </div>

                    </div>

                    <!-- Жалобы на объявления - Если поступили 5 жалоб - Они видны автору - Объявление отправиться на доработку  -->
                    <div v-if="authStore.check && authStore.user.id == ads.author_id && $route.name == 'userAds'">

                        <div v-if="ads.control == 'Поступили жалобы' " class="col-12 alert" style="background: #efa6a6; padding: 1.7px 10px!important;">
                            <i class="bi bi-exclamation-octagon"></i>
                            {{ $t('adsPreviewComponentReturnForRevision')}}
                            <div>{{ $t('adsPreviewComponentCause') }} :
                                <span v-if="ads.complain[0] == 'Объявление не актуально'">{{ $t('oneAdsBottomOffCanvasTheAdIsNotRelevant') }}</span>
                                <span v-if="ads.complain[0] == 'Ошибка в цене'">{{ $t('oneAdsBottomOffCanvasPriceError') }}</span>
                                <span v-if="ads.complain[0] == 'Некорректные фотографии'">{{ $t('oneAdsBottomOffCanvasIncorrectPhotos') }}</span>
                                <span v-if="ads.complain[0] == 'Ответил риелтор'">{{ $t('oneAdsBottomOffCanvasTheRealtorReplied') }}</span>
                                <span v-if="ads.complain[0] == 'Телефон не отвечает'">{{ $t('oneAdsBottomOffCanvasThePhoneIsNotAnswering') }}</span>
                                <span v-if="ads.complain[0] == 'Обман или ложное объявление'">{{ $t('oneAdsBottomOffCanvasDeceptionOrFalseAnnouncement') }}</span>
                            </div>
                        </div>

                    </div>

                </v-card>

            </DynamicScrollerItem>

        </template>

    </DynamicScroller>


</template>

<script>

//Импортирую Store - Общее состояние
import { useAuthStore } from "../../stores/auth";
import { useCheckInternetStore } from "../../stores/checkInternet";
import { useImagesStore } from "../../stores/images";
import { useAdsStore } from "../../stores/ads";
import { useUpdateDateLocaleStore } from "../../stores/updateDateLocale";
import { useKZLocationStore } from "../../stores/KZLocation";

// Компонент динамический скроллер - Скрывает лишние элементы из DOM
import { DynamicScroller, DynamicScrollerItem } from 'vue-virtual-scroller'
import 'vue-virtual-scroller/dist/vue-virtual-scroller.css'

export default {
    name: "AdsPreviewComponent",

    components: {
        DynamicScroller,
        DynamicScrollerItem
    },

    props: {
        ads_arr: Array,
        getMyLikeAds: Boolean, //Это нужно чтоб знать сейчас запрос избранного или нет если да то мы можем при отмене избранного удалить его из массива
    },

    data(){
        return {

            //Подключаю Store - Наше общее состояние
            authStore: useAuthStore(),
            checkInternetStore: useCheckInternetStore(),
            imageStore: useImagesStore(),
            adsStore: useAdsStore(),
            updateDateLocale: useUpdateDateLocaleStore(),
            KZLocationStore: useKZLocationStore(),

            ads_array: '',
            query: false,

        }
    },

    watch: {
        ads_arr(){
            this.ads_array = this.ads_arr;
        },

        //Отслеживаем изменение маршрута - И обновляем данные 1-го
        '$route' (to, from) {
            if(to.name == 'allAds' || to.name == 'mapClusterShowAds'){

                if(localStorage.getItem('oneAds') != undefined){
                    this.ads_array[localStorage.getItem('oneAdsIndex')] = JSON.parse(localStorage.getItem('oneAds'));
                    //Удалим
                    localStorage.removeItem('oneAdsIndex')
                    localStorage.removeItem('oneAds')
                }

            }
        },

        //Следим за сменой языка
        async 'updateDateLocale.lang' (){
            this.ads_array = {};
            this.ads_array = this.ads_arr;
        }
    },

    methods: {

        //Открыть 1-но объявление
        async showOneAds(ads,index){
            this.query = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            this.checkInternetStore.checkInternet();

            localStorage.setItem('oneAdsIndex', index)
            localStorage.setItem('oneAds', JSON.stringify(ads))

            this.$router.push({name: this.$route.name + "OneAds" ,params: {ads_id: ads.id, table_name: ads.table_name }})
        },

        // Показать фото
        showImage(ads){
            this.imageStore.showImages({images: ads.images,index: 0, allImg: true})
            this.$router.push({name: this.$route.name + "Image"});
        },

        //Метод - Рекламировать или Остановить - рекламу объявления
        async adsActiveToggle(index, ads_id, table_name, ads_control){
            this.query = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
           this.checkInternetStore.checkInternet()


            axios.post('/adsActiveToggle', {
                ads_id, table_name
            })
                .then((response)=>{
                    this.query = false;

                    //Укажем активно или нет
                    ads_control == 'Активно' ? this.ads_arr[index].control = 'Не активно' : this.ads_arr[index].control = 'Активно';

                })
                .catch(errors=>{
                    this.query = false;

                    //Если объявление было удалено
                    if(errors.response.data.error == 'Объявление было удалено')this.ads_arr.splice(index, 1);

                    //Если объявление было отправлено в архив
                    if(errors.response.data.error == 'Объявление находиться в архиве')this.ads_arr[index].control = 'В архиве'

                    Toast.fire({
                        text: errors.response.data.error
                    })

                })
        },

        //Метод добавить в избранное
       async addLikeToggle(index, ads){

            this.query = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
           this.checkInternetStore.checkInternet()

            //Для быстрого реагирования добавим без проверки и далее в проверке уже подтвердим
            this.ads_arr[index].userLike = !this.ads_arr[index].userLike;

            axios.post('/like',{
                author_id: this.authStore.user.id,
                table_name: ads.table_name,
                ads_id: ads.id
            })
                .then(response=>{
                    this.query = false;
                    //Если метод вызван когда запрос на мои избранные то удалим данное избранно из моих
                    this.getMyLikeAds ? this.ads_arr.splice(index, 1) : '';

                })
                .catch(errors=>{
                    this.query = false;

                    //Toggle своиства избранного, меняем данные у данного объявления без перезагрузки
                    this.ads_arr[index].userLike = !this.ads_arr[index].userLike;

                    //Если объявление уже стало не активно уведомим что не активно
                    Toast.fire({
                        icon: 'error',
                        title: errors.response.data.error
                    })

                })
        },

        //Метод - Отправить в архив -  на 7 дней . Далее автоматом будет удалено
        async deleteAds(index, ads_id, table_name){
            Swal.fire({
                title: this.$t('adsPreviewComponentSendToArchive'),
                showCancelButton: true,
                confirmButtonText: this.$t('adsPreviewComponentYes'),
                cancelButtonText: this.$t('adsPreviewComponentNo')
            })
                .then(async (result) => {
                    if (result.isConfirmed) {
                        this.query = true;

                        //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
                        this.checkInternetStore.checkInternet()

                        axios.delete('/deleteAds', {params:{
                                ads_id, table_name,
                            }})
                            .then((response)=>{
                                this.query = false;

                                Toast.fire({
                                    title: this.$t('adsPreviewComponentRemoved')
                                })

                                //Уберем из массива данное объявление
                                this.ads_arr.splice(index, 1);

                            })
                            .catch((errors)=>{
                                this.query = false;
                                Toast.fire({
                                    title: this.$t('adsPreviewComponentError')
                                })
                            })
                    }
                })
        },

        // Высчитать до кокого числа зармещено объявление
        addDaysToCurrentDate(date, days) {
            if (date) {
                const newDate = new Date(date);
                newDate.setDate(newDate.getDate() + days);
                return this.$filters.transformDateRuNotWatch(newDate); // Здесь используйте ваш фильтр для форматирования даты
            }
        },

    }

}
</script>

<style scoped>

/* Стиль для - виртуального скролла */
.scroller {
    width: 100%;
    height: 100%;
}


.ads__preview{
    user-select: none;
}

/* Стили для слайдера */
::v-deep(.swiper-pagination-fraction){
    width: auto;
    color: #fff;
    background: rgb(0,0,0,.7);
    border-radius: 3px;
    padding: 0 2px;
    right: 5px;
    left: auto;
    bottom: 22px;
    font-size: 0.7em;
}

.image__block{
    position: relative;
    width: 90px;
    max-height: 90px;
    overflow: hidden;
}

.ads__preview-img{
    width: 100%;
    height: 90px;
    object-fit: cover;
    object-position: center
}

@media screen and (min-width: 321px){
    .image__block{
        width: 130px;
        max-height: 110px;
    }

    .ads__preview-img{
        height: 110px;
    }
}

@media screen and (min-width: 390px){
    .image__block{
        width: 170px;
        max-height: 150px;
    }

    .ads__preview-img{
        height: 150px;
    }
}


/* Стили для иконок */
.icon__crown, .icon__diamond, .icon__triangle{
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    width: 19px;
    height: 19px;
    background: red
}
.icon__crown{
    background: red
}
.icon__diamond{
    background: #10a37f;
}
.icon__triangle{
    background: orange
}

.icon__triangleTOP8{
    background: #710250
}


.icon__crown:hover,
.icon__diamond:hover,
.icon__triangle:hover,
.icon__heart:hover {
    transform: scale(1.20);
    transition: transform 0.1s ease;
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

</style>

