<template>

    <!-- Backdrop -->
    <transition name="lang__animation-backdrop">

        <div v-if="langAnimation" class="lang__backdrop" @click="$router.back()"></div>

    </transition>

    <!-- Обвертка - Компонента -->
    <transition name="lang__animation-wrapper">

        <!-- Обвертка - Компонента -->
        <div v-if="langAnimation" class="lang__wrapper">

            <!-- Сам блок -->
            <div class="lang__block">

                <!-- Header -->
                <div class="lang__header">

                    <!-- Кнопка назад -->
                    <v-btn role="button" icon size="small" variant="text"
                           class="mx-1"
                           style="position: absolute; top: 5px; right: 5px"
                           dark @click="$router.back()">
                        <v-icon size="large">mdi-close</v-icon>
                    </v-btn>

                </div>

                <!-- Body -->
                <div class="lang__body">

                    <div class="lang__links-block">

                        <div @click="getLang('kz'), $router.back()" class="lang__link">

                            <img src="/public/img/siteImg/allImg/kz.png"
                                 alt="Kazakhstan"
                                 width="24" />
                            <span class="lang__link-text px-2" :class="{'text-blue': lang == 'kz'}">Қазақ</span>

                        </div>

                        <div @click="getLang('ru'), $router.back()" class="lang__link">

                            <img src="/public/img/siteImg/allImg/ru.png"
                                 alt="Russia"
                                 width="24" />
                            <span class="lang__link-text px-2" :class="{'text-blue': lang == 'ru'}">Русский</span>

                        </div>

                        <div @click="getLang('en'), $router.back()" class="lang__link">

                            <img src="/public/img/siteImg/allImg/gb.png"
                                 alt="United Kingdom"
                                 width="24" />
                            <span class="lang__link-text px-2" :class="{'text-blue': lang == 'en'}">English</span>

                        </div>

                    </div>

                </div>

            </div>


        </div>

    </transition>

</template>


<script>

//Мультиязык Moment.js
import { loadLanguageAsync } from 'laravel-vue-i18n';
import moment from "moment";

// Импортирую store
import {useUpdateDateLocaleStore} from "../../stores/updateDateLocale";

//Импорт мультиязык vee-validate
import { setLocale } from '@vee-validate/i18n';

export default {
    name: "LangComponent",
    data(){
        return {

            //Импортирую Store - Общее состояние
            updateDateLocale: useUpdateDateLocaleStore(),
            lang: 'ru',

            langAnimation: false,
        }
    },

    methods: {
        getLang(lang){

            //Изменим локаль - Приложения ( Мои переводы )
            loadLanguageAsync(lang)
            this.lang = lang;

            // Занесем выбранную в хранилище
            localStorage.setItem('lang', lang);

            // Обновим в store
            this.updateDateLocale.updateLang(lang)

            // Поменяем локаль ошибок vee-validate
            setLocale(lang);

            //Поменяем local даты пакета moment.jz
            this.lang == 'kz' ? lang = 'kk' : '';
            this.lang == 'en' ? lang = 'en-gb' : '';
            moment.locale(lang);
        }
    },

    mounted() {
        this.langAnimation = true;

        document.querySelector(':root').classList.add('PATCH_modal');
        localStorage.getItem('lang') != undefined ? this.getLang(localStorage.getItem('lang')) : ''
    },

    beforeRouteLeave(to, from, next) {
        this.langAnimation = false; // Установите значение в false перед покиданием маршрута
        if(to.name == 'allAds')document.querySelector(':root').classList.remove('PATCH_modal'); //Отменим прокрутку под модальным окном

        setTimeout(() => {
            next(); // Вызываем next() после завершения setTimeout - Для завершения анимации
        }, 350);
    }
}
</script>
<style>

/* Анимация -- Backdrop */
.lang__animation-backdrop-enter-active,
.lang__animation-backdrop-leave-active{
    transition: opacity 0.3s ease;
}

.lang__animation-backdrop-enter-from,
.lang__animation-backdrop-leave-to    {
    opacity: 0;
}

.lang__animation-backdrop-enter-to,
.lang__animation-backdrop-leave-from{
    opacity: 1;
}

/* Анимация для -- Wrapper */
.lang__animation-wrapper-enter-active,
.lang__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}

.lang__animation-wrapper-enter-from{
    transform: translateY(50%); /* Начальное положение при анимации скрытия */
}

.lang__animation-wrapper-leave-to {
    transform: translateY(100%); /* Начальное положение при анимации скрытия */
}

.lang__animation-wrapper-enter-to,
.lang__animation-wrapper-leave-from {
    transform: translateY(0);/* Конечное положение при анимации появления */
}

</style>

<style scoped>
/* Backdrop */
.lang__backdrop{
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: 0;
    background: rgba(0,0,0,0.8);
    z-index: 3;
}

.lang__wrapper{
    position: fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    bottom:0;
    left: 0;
    width: 100%;
    height: auto;
    max-height: 100%;
    overflow-y: auto;
    user-select: none;
    z-index: 3;
}

.lang__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 800px;
    border-radius: 10px 10px 0 0;
    background: #ffffff;
}

.lang__header{
    position: relative;
    text-align: center;
    padding: 20px;
}

.lang__body{
    flex-grow: 1;
    width: 100%;
    font-size: 1.1em;
    padding-bottom: 20px;
}

.lang__links-block{
    padding: 10px;
}

.lang__link{
    color: #2a2a2a;
    padding: 15px;
    display: flex;
    align-items: center;
}

.lang__link-text{
    font-size: 1.1em;
}

.lang__link:hover{
    background: rgba(0,0,0,0.1);
    cursor: pointer;
    border-radius: 10px;
}

/*При экранее более 992px */
@media screen and  (min-width: 992px) {
    .lang__block{
        margin-right: 10px;
    }
}
</style>
