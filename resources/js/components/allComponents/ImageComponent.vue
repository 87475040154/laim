<template>
    <!-- КОМПОНЕНТ - ФОТО -->

    <!-- Обвертка - Компонента -->
    <div class="image__wrapper">

        <!-- Сам блок -->
        <div class="image__block">

            <!-- Кнопка Закрыть окно -->
            <v-btn size="x-small" class="border" icon dark @click="$router.back()" style="position: absolute; top: 10px; left: 10px; z-index: 5">
                <v-icon size="large">mdi-arrow-left</v-icon>
            </v-btn>

            <!-- Слайдер - 1 -->
            <swiper  @swiper="onSwiper" :mousewheel="true" :slides-per-view="1" :space-between="0" :grabCursor="true"
                     :keyboard="true" :zoom="true" :thumbs="{ swiper: swiper2 }" :modules="modules"
                     @touchMoveOpposite="touchMove = 'Свайп'" @touchEnd="touchMove == 'Свайп' ? $router.back():''"
                     :pagination="{ type: 'fraction'}" class="mySwiper"
            >

                <!-- Вывод самого слайда - то-есть 1-го фото -->
                <swiper-slide  v-for="(img, i) in imageStore.images" :key="i" class="position-relative">
                    <div class="swiper-zoom-container">
                        <img :src="img.previewImg" alt="Фото квартир">
                    </div>

                    <!-- Кнопки перетаскивания -->
                    <div class="swiper-button-next" @click="swiper1.slidePrev()"></div>
                    <div class="swiper-button-prev" @click="swiper1.slideNext()"></div>
                </swiper-slide>

            </swiper>

        </div>

    </div>

</template>

<script>
// Слайдер фото Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue';

import 'swiper/scss';
import 'swiper/scss/pagination';
import 'swiper/scss/mousewheel';
import 'swiper/scss/zoom';
import 'swiper/scss/keyboard';
import 'swiper/scss/free-mode';
import 'swiper/scss/thumbs';
import { Pagination, Mousewheel, Zoom, Keyboard, Thumbs, FreeMode} from 'swiper';

// Импортирую Store - общее состояние
import {useImagesStore} from "../../stores/images";

export default {
    name: "ShowImage",

    components: {
        Swiper,
        SwiperSlide,
    },

    data(){
        return {
            //Store
            imageStore: useImagesStore(),

            //Настройки слайдера swiper
            touchMove: '',
            swiper1: null, //Первый слайдер
            modules: [Pagination, Mousewheel, Zoom, Keyboard, FreeMode,Thumbs],

            isDragging: false, // Флаг для определения, идет ли перетаскивание
        }
    },

    methods:{
        //Инициализируем Слайдер 1
        onSwiper(swiper){
            this.swiper1 = swiper;
        },
    },

    mounted(){
        let app = this;
        document.querySelector(':root').classList.add('PATCH_modal'); //Отменим прокрутку под модальным окном

        this.swiper1.slideTo(this.imageStore.index, 0);
    },

    // ... остальные методы и хуки ...
    beforeRouteLeave(to, from, next) {
        if(to.name == 'allAds')document.querySelector(':root').classList.remove('PATCH_modal'); //Отменим прокрутку под модальным окном
        next();
    }


}
</script>

<style scoped>
/* Стили для слайдера */
::v-deep(.swiper-pagination-fraction){
    width: auto;
    color: #ffffff;
    background: none;
    border-radius: 3px;
    padding: 0 2px;
    top: 15px;
    right: auto;
    left: 60px;
    bottom: auto;
    font-size: 1em;
}

.swiper-button-next, .swiper-button-prev{
    position: absolute;
    top:0;
    width: 40px;
    height: 100%;
    cursor: pointer;
}

.swiper-button-next{
    left: 0;
}
.swiper-button-prev{
    right: 0;
}

.image__wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #444444;
    user-select: none;
}


.image__block{
    width: 100%;
    height: 100%;
}

.swiper {
    width: 100%;
    height: 100%;
}

.swiper-slide {
    text-align: center;
    font-size: 18px;
    background: #444;

    /* Center slide text vertically */
    display: -webkit-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    -webkit-justify-content: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -webkit-align-items: center;
    align-items: center;
}

.swiper-slide img {
    display: block;
    width: auto;
    height: auto;
    object-fit: cover;
}

.swiper {
    width: 100%;
    height: 300px;
    margin-left: auto;
    margin-right: auto;
}

.swiper-slide {
    background-size: cover;
    background-position: center;
}

.mySwiper {
    height: 100%;
    width: 100%;
    position: relative;
}

.swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.mySwiper img {
    display: block;
    width: auto;
    height: auto;
    object-fit: cover;
}

</style>
