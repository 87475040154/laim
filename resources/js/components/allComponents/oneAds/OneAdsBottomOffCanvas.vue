<template>
    <!-- КОМПОНЕНТ - ВЫВОД ИНФОРМАЦИЯ ДЛЯ ОНДНОГО ОБЪЯВЛЕНИЯ ВНИЗУ СТРАНИЦЫ  -->

    <!-- Backdrop -->
    <transition name="oneAdsBottom__animation-backdrop">

        <div v-if="oneAdsBottomAnimation" class="oneAdsBottom__backdrop" @click="$router.back()"></div>

    </transition>

    <!-- Обвертка - Компонента -->
    <transition name="oneAdsBottom__animation-wrapper">

        <!-- Обвертка - Компонента -->
        <div v-if="oneAdsBottomAnimation" class="oneAdsBottom__wrapper">

            <!-- Сам блок -->
            <div class="oneAdsBottom__block">

                <!-- Header - Заголовок | Имя автора | причина жалобы и тд. -->
                <div class="oneAdsBottom__header">

                    <!-- Заголовок - Имя автора | Причина жалобы и тд. -->
                    <h4 v-if="$route.params.type == 'Позвонить' ">
                        <span>{{ads.name}}</span>
                    </h4>
                    <h4 v-if="$route.params.type == 'Пожаловаться'">{{ $t('oneAdsBottomOffCanvasCallReasonForComplaint') }}</h4>
                    <h4 v-if="$route.params.type == 'Скачать или поделиться фото'">{{ $t('oneAdsBottomOffCanvasDownloadPhotos') }}</h4>
                    <h4 v-if="$route.params.type == 'Поделиться объявлением'">{{ $t('oneAdsBottomOffCanvasShareAnAd') }}</h4>

                    <!-- Кнопка закрыть блок -->
                    <v-btn role="button" icon size="small" variant="text"
                           class="mx-1"
                           style="position: absolute; top: 5px; right: 5px"
                           dark @click="$router.back()">
                        <v-icon size="large">mdi-close</v-icon>
                    </v-btn>

                </div>

                <!-- Body -->
                <div class="oneAdsBottom__body">

                    <!-- Кнопки номер телефона и Whatsapp-->
                    <div v-if="$route.params.type=='Позвонить'" class="text-center">

                        <!-- Кнопки - Tel, whatsapp - 1 - Если отправленно с контактами автора-->
                        <div>
                            <div>
                                <a v-if="$route.query.tel == undefined"  :href="'https://api.whatsapp.com/send?phone='+ads.tel+'&text=' + SiteDomain +'/allAds/'+ $route.params.table_name + '/allAdsOneAds/' + $route.params.ads_id" class="btn text-white text-body-1 mx-1 py-3" style="width: 100%; max-width: 170px; background: #10a37f"> {{ $t('oneAdsBottomOffCanvasGoToWA') }} <i class="bi bi-whatsapp"></i> </a>
                                <a :href="'tel: '+ String($route.query.tel || ads.tel)" class="btn bg-blue text-white text-body-1 mx-1 py-3" style="width: 100%; max-width: 170px">
                                    <i class="bi bi-telephone-fill pr-2"></i>
                                    <small> {{ $route.query.tel || ads.tel }}</small>
                                </a>
                            </div>

                            <!-- Кнопки - Tel, whatsapp - 2 -->
                            <div v-if="$route.query.tel == undefined" class="mt-2">
                                <a v-if="ads.tel2 != null" :href="'https://api.whatsapp.com/send?phone='+ads.tel2+'&text=' + SiteDomain +'/allAds/'+ $route.params.table_name + '/allAdsOneAds/' + $route.params.ads_id" class="btn text-white text-body-1 mx-1 py-3" style="width: 100%; max-width: 170px; background: #10a37f"> {{ $t('oneAdsBottomOffCanvasGoToWA') }} <i class="bi bi-whatsapp"></i> </a>
                                <a v-if="ads.tel2 != null" :href="'tel:'+ads.tel2" class="btn bg-blue text-white text-body-1 mx-1 py-3" style="width: 100%; max-width: 170px">
                                    <i class="bi bi-telephone-fill pr-2"></i>
                                    <small>{{ String(ads.tel2) }}</small>
                                </a>
                            </div>
                        </div>

                    </div>

                    <!-- Отправить жалобы -->
                    <div v-if="$route.params.type=='Пожаловаться'" role="button">

                        <div @click="addComplain('Ответил риелтор')" class="border-bottom p-2 py-3">{{ $t('oneAdsBottomOffCanvasTheRealtorReplied') }}</div>
                        <div @click="addComplain('Объявление не актуально')" class="border-bottom p-2 pb-3">{{ $t('oneAdsBottomOffCanvasTheAdIsNotRelevant') }}</div>
                        <div @click="addComplain('Ошибка в цене')" class="border-bottom p-2 py-3">{{ $t('oneAdsBottomOffCanvasPriceError') }}</div>
                        <div @click="addComplain('Некорректные фотографии')" class="border-bottom p-2 py-3">{{ $t('oneAdsBottomOffCanvasIncorrectPhotos') }}</div>
                        <div @click="addComplain('Телефон не отвечает')" class="border-bottom p-2 py-3">{{ $t('oneAdsBottomOffCanvasThePhoneIsNotAnswering') }}</div>
                        <div @click="addComplain('Обман или ложное объявление')" class="p-2 py-3">{{ $t('oneAdsBottomOffCanvasDeceptionOrFalseAnnouncement') }}</div>

                    </div>

                    <!-- Скачать или поделиться фото -->
                    <div v-if="$route.params.type=='Скачать или поделиться фото'">

                        <!-- Ссылка счакать фото -->
                        <div @click="downloadImage" class="border-bottom p-2 pb-3" role="button">
                            <i class="bi bi-cloud-arrow-down mx-1"></i>
                            {{ $t('oneAdsBottomOffCanvasSaveToDevice') }}
                        </div>

                        <!-- Ссылка поделиться фото -->
                        <div @click="shareImage" class="p-2 py-3" role="button">
                            <i class="bi bi-share mx-1"></i>
                            {{ $t('oneAdsBottomOffCanvasToShare') }}
                        </div>

                    </div>

                    <!-- Поделиться объявлением -->
                    <div v-if="$route.params.type=='Поделиться объявлением'">

                        <!-- Поделиться с номером телефона автора -->
                        <div v-if="$route.query.tel == undefined" @click="linkShare('С номером автора')" class="border-bottom p-2 pb-3" role="button">
                            <i class="bi bi-share mx-1"></i>
                            {{ $t('oneAdsBottomOffCanvasToShare') }}
                        </div>

                        <!-- Поделиться с моим номером телефона -->
                        <div v-if="$route.query.tel == undefined" @click="authStore.check ? linkShare('С моим номером') : $router.push({name: $route.name + 'Auth'})" class="p-2 py-3" role="button">
                            <i class="bi bi-share mx-1"></i>
                            {{ $t('oneAdsBottomOffCanvasShareWithMyPhoneNumber') }}
                        </div>

                        <!-- Поделиться с моим номером телефона -->
                        <div v-if="$route.query.tel != undefined" @click="linkShare('С моим номером')" class="p-2 py-3" role="button">
                            <i class="bi bi-share mx-1"></i>
                            {{ $t('oneAdsBottomOffCanvasToShare') }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </transition>

    <!-- Дочерние компоненты -->
    <router-view></router-view>

</template>

<script>

//Валидация laravel VFORM
import Form from 'vform'
import { Button, HasError, AlertError } from 'vform/src/components/bootstrap5'


//Импортирую Store - Общее состояние
import { useAuthStore } from "../../../stores/auth";
import {useUpdateDateLocaleStore} from "../../../stores/updateDateLocale";


//Импортируем NavigatorShare - пакет дает возможность поделиться ссылкой например через whatsapp
import NavigatorShare from 'vue-navigator-share'

export default {
    name: "OneAdsBottomOffCanvas",


    components: {
        NavigatorShare, //Поделиться, ссылкой, фото
        Button, HasError, AlertError, //Валидация формы VForm
    },

    data(){
        return {

            //Подключаю Store - Наше общее состояние
            authStore: useAuthStore(),
            updateDateLocale: useUpdateDateLocaleStore(),

            ads: '', //Сюда занесем данные 1-го объявления

            oneAdsBottomAnimation: false,

            query: false,
            SiteDomain: SiteDomain
        }
    },

    computed: {
        tipObekta(){
            if(this.ads.zagolovok != undefined){
                return this.ads.zagolovok;
            }else{
                return this.ads.tip_obekta;
            }

        }
    },

    methods:{

        //Метод - Отправить жалобу на объявление
       async addComplain(complain){
           this.query = true;

            axios.post('/addComplain', {
                ads_id: this.ads.id,
                table_name: this.ads.table_name,
                user_id: this.authStore.user.id,
                complain: complain
            })
                .then(response=>{
                    this.$router.back();
                    Toast.fire({
                        title: this.$t('oneAdsBottomOffCanvasThanks'),
                        text: this.$t('oneAdsBottomOffCanvasComplainText')
                    })
                    this.query = false;
                })
                .catch(errors=>{
                    this.$router.back();
                    Toast.fire({
                        title: this.$t('oneAdsBottomOffCanvasErrorText'),
                        text: errors.response.data.error
                    })
                    this.query = false;
                })
        },

        //Метод поделиться ссылкой
        async linkShare(type) {

            //Поделиться с номером телефона автора
            if(type == 'С номером автора'){
                navigator.share({
                    url: SiteDomain +'/allAds/' + this.ads.table_name + '/1/allAdsOneAds/' + this.ads.id
                })
            }

            //Поделиться с моим номером телефона
            if(type == 'С моим номером'){
                let tel = new URL(window.location).searchParams.has('tel') ? new URL(window.location).searchParams.get('tel') : this.authStore.user.tel
                navigator.share({
                    url:  SiteDomain + '/allAds/' + this.ads.table_name + '/1/allAdsOneAds/' + this.ads.id + '?tel=' + tel
                })
            }

        },

        //Метод скачать фото
        async downloadImage(){

            this.ads.images.forEach(elem=>{
                let link = document.createElement("a");
                link.setAttribute("href",'/img/adsImg/'+elem);
                link.setAttribute("download", elem);
                link.click();
            })
        },
        //Поделиться фото - напримерв Whatsapp
        async shareImage(){

            let files = [];
            for (const item of this.ads.images) {
                const response = await fetch("/img/adsImg/" + item);
                const blob = await response.blob();

                const file = new File([blob], item, { type: blob.type });

                files.push(file);
            }

            // Поделиться файлом
            if (navigator.canShare({ files })) await navigator.share({ files });

        },
    },

    mounted(){
        let app = this;

        this.oneAdsBottomAnimation = true;

        this.ads = JSON.parse(localStorage.getItem('oneAds'));
        document.querySelector(':root').classList.add('PATCH_modal');

    },

    beforeRouteLeave(to, from, next) {
        this.oneAdsBottomAnimation = false; // Установите значение в false перед покиданием маршрута

        setTimeout(() => {
            next(); // Вызываем next() после завершения setTimeout - Для завершения анимации
        }, 350);
    }

}
</script>

<style>

/* Анимация -- Backdrop */
.oneAdsBottom__animation-backdrop-enter-active,
.oneAdsBottom__animation-backdrop-leave-active{
    transition: opacity 0.3s ease;
}

.oneAdsBottom__animation-backdrop-enter-from,
.oneAdsBottom__animation-backdrop-leave-to    {
    opacity: 0;
}

.oneAdsBottom__animation-backdrop-enter-to,
.oneAdsBottom__animation-backdrop-leave-from{
    opacity: 1;
}

/* Анимация для -- Wrapper */
.oneAdsBottom__animation-wrapper-enter-active,
.oneAdsBottom__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}

.oneAdsBottom__animation-wrapper-enter-from{
    transform: translateY(50%); /* Начальное положение при анимации скрытия */
}

.oneAdsBottom__animation-wrapper-leave-to {
    transform: translateY(100%); /* Начальное положение при анимации скрытия */
}

.oneAdsBottom__animation-wrapper-enter-to,
.oneAdsBottom__animation-wrapper-leave-from {
    transform: translateY(0);/* Конечное положение при анимации появления */
}

</style>

<style scoped>

/* Backdrop */
.oneAdsBottom__backdrop{
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: 0;
    background: rgba(0,0,0,0.5);
}

.oneAdsBottom__wrapper{
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
}

.oneAdsBottom__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 800px;
    border-radius: 10px 10px 0 0;
    background: #ffffff;
}

.oneAdsBottom__header{
    position: relative;
    text-align: center;
    padding: 20px;
}

.oneAdsBottom__body{
    flex-grow: 1;
    width: 100%;
    font-size: 1.1em;
    padding-bottom: 20px;
}

/*При экранее более 992px */
@media screen and  (min-width: 992px) {
    .oneAdsBottom__block{
        margin-right: 10px;
    }
}

</style>
