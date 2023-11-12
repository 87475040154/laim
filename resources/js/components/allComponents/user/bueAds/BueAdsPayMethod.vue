<template>
    <!-- КОМПОНЕНТ - МЕТОД ПОКУПКИ РЕКЛАМЫ -->

    <!-- Backdrop -->
    <transition name="bueAdsPayMethod__animation-backdrop">

        <div v-if="bueAdsPayMethodAnimation" class="bueAdsPayMethod__backdrop" @click="$router.back()"></div>

    </transition>

    <!-- Обвертка - Компонента -->
    <transition name="bueAdsPayMethod__animation-wrapper">

        <!-- Обвертка - Компонента -->
        <div v-if="bueAdsPayMethodAnimation" class="bueAdsPayMethod__wrapper">

            <!-- Сам блок -->
            <div class="bueAdsPayMethod__block">

                <!-- Header -->
                <div class="bueAdsPayMethod__header">

                    <!-- Заголовок -->
                    <div class="fw-bold" style="font-size: 1.2em">
                        {{ $t('bueAdsPayMethodPaymentMethods') }}
                    </div>

                    <!-- Кнопка назад -->
                    <v-btn role="button" icon size="small" variant="text"
                           class="mx-1"
                           style="position: absolute; top: 5px; right: 5px"
                           dark @click="$router.back()">
                        <v-icon size="large">mdi-close</v-icon>
                    </v-btn>

                </div>

                <!-- Body -->
                <div class="bueAdsPayMethod__body">
                    <!-- Оплата Kaspi.kz -->
                    <!--            <div class="border-bottom p-2 px-3 row g-0 gap-3 align-center" role="button">-->
                    <!--                <div class="col-auto">-->
                    <!--                    <img src="/public/img/siteImg/allImg/logo_kaspi.png" width="25" height="25" alt="Логотип">-->
                    <!--                </div>-->
                    <!--                <span class="col">Kaspi.kz</span>-->
                    <!--            </div>-->

                    <!-- Карта Visa -->
                    <div @click="bueVisa" class="border-bottom p-2 px-3 row g-0 gap-3 align-center" role="button">
                        <div class="col-auto">
                            <img src="/public/img/siteImg/allImg/logo_visa.png" width="25" height="25" alt="Логотип">
                        </div>
                        <span class="col">{{ $t('bueAdsPayMethodVisaMasterCardCard') }}</span>
                    </div>


                    <!-- Личный счёт -->
                    <div class="border-bottom p-2 px-3 row g-0 gap-3 align-center" role="button">
                        <div class="col-auto">
                            <img src="/public/img/siteImg/allImg/logo.svg" width="25" height="25" alt="Логотип">
                        </div>
                        <span class="col">{{ $t('bueAdsPayMethodPersonalAccount') }} ({{authStore.user.balance}} &#8376;)</span>
                    </div>

                </div>

            </div>

        </div>

    </transition>

</template>

<script>

//Импортирую Store - Общее состояние
import { useAuthStore } from "../../../../stores/auth";
import { useCheckInternetStore } from "../../../../stores/checkInternet";
import { useUpdateDateLocaleStore} from "../../../../stores/updateDateLocale";

export default {
    name: "BueAdsPayMethod",

    data(){
        return {
            //Подключаю Store
            authStore: useAuthStore(),
            checkInternetStore: useCheckInternetStore(),
            updateDateLocaleStore: useUpdateDateLocaleStore(),

            bueAdsPayMethodAnimation: false,
        }
    },

    computed:{
        lang(){
            if(this.updateDateLocaleStore.lang == 'ru')return 'rus'
            if(this.updateDateLocaleStore.lang == 'kz')return 'kaz'
            if(this.updateDateLocaleStore.lang == 'en')return 'eng'
        }
    },


    methods: {

        // Покупка через карту - используем сервис epayment.kz
       async bueVisa(){

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
           await this.checkInternetStore.checkInternet()
            if(!this.checkInternetStore.online)return;


            // Получить ссылку для перехода к оплате
            axios.get('/user/getPayLink', {
                params: {
                    author_id: this.authStore.user.id,
                    author_email: this.authStore.user.email,
                    ads_id: this.$route.params.ads_id,
                    table_name: this.$route.params.table_name,
                    bue_ads_type: this.$route.params.bueAdsType
                }
            })
                .then((response)=>{
                    //Вызовим метод перехода на страницу оплаты
                    this.handlePayment(response.data.body, response.data.invoiceID, response.data.amount);
                })
                .catch((errors)=>{
                    // Если возникла ошибка при получении ссылки на оплату, покажем уведомление
                    Swal.fire({
                        title: this.$t('bueAdsPayMethodError'),
                        text: errors.response.data.message
                    })
                })

        },

        //Метод непосредственно перехода на страницу оплаты
        handlePayment(token, invoiceID, amount) {
            if (typeof halyk !== 'undefined' && typeof halyk.pay === 'function') {

                // Создаем объект с параметрами платежа
                var paymentObject = {
                    invoiceId: invoiceID,
                    backLink: SiteDomain + "/bueAdsResult/success",
                    failureBackLink: SiteDomain + "/bueAdsResult/error",
                    postLink: SiteDomain + '/api/user/checkBueAds',
                    language: this.lang,
                    description: "Покупка рекламы для продвижения",
                    accountId: "testuser1",
                    terminal: "67e34d63-102f-4bd1-898e-370781d0074d",
                    amount: amount,
                    data: "{\"statement\":{\"name\":\"Arman Ali\",\"invoiceID\":\"" + invoiceID + "\"}}",
                    currency: "KZT",
                    phone: this.authStore.user.tel,
                    email: this.authStore.user.email,
                    cardSave: true //Параметр должен передаваться как Boolean
                };

                // Передаем токен в объект платежа
                paymentObject.auth = token;

                // Вызываем метод halyk.pay() с объектом платежа
                halyk.pay(paymentObject);

            } else {
                console.error('Failed to load halyk.pay() method or halyk.js library.');
            }
        }

    },

    mounted(){
        let app = this;

        this.bueAdsPayMethodAnimation = true;
        document.querySelector(':root').classList.add('PATCH_modal'); //Отменим прокрутку под этим компонентом
    },


    beforeRouteLeave(to, from, next) {
        this.bueAdsPayMethodAnimation = false;

        setTimeout(() => {
            next(); // Вызываем next() после завершения setTimeout для завершения анимации
        }, 350);
    }

}
</script>

<style>

/* Анимация -- Backdrop  */
.bueAdsPayMethod__animation-backdrop-enter-active,
.bueAdsPayMethod__animation-backdrop-leave-active{
    transition: opacity 0.3s ease;
}


.bueAdsPayMethod__animation-backdrop-enter-from,
.bueAdsPayMethod__animation-backdrop-leave-to    {
    opacity: 0;
}

.bueAdsPayMethod__animation-backdrop-enter-to,
.bueAdsPayMethod__animation-backdrop-leave-from{
    opacity: 1;
}

/* Анимация для -- Wrapper */
.bueAdsPayMethod__animation-wrapper-enter-active,
.bueAdsPayMethod__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}


.bueAdsPayMethod__animation-wrapper-enter-from {
    transform: translateY(50%); /* Начальное положение при анимации скрытия */
}

.bueAdsPayMethod__animation-wrapper-leave-to {
    transform: translateY(100%); /* Начальное положение при анимации скрытия */
}

.bueAdsPayMethod__animation-wrapper-enter-to,
.bueAdsPayMethod__animation-wrapper-leave-from {
    transform: translateY(0);/* Конечное положение при анимации появления */
}

</style>


<style scoped>

/* Backdrop */
.bueAdsPayMethod__backdrop{
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: 0;
    background: rgba(0,0,0,0.5);
}

.bueAdsPayMethod__wrapper{
    position: fixed;
    bottom:0;
    left: 0;
    width: 100%;
    max-height: 100%;
    height: auto;
    overflow-y: auto;
}

.bueAdsPayMethod__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 850px;
    margin: 0 auto;
    border-radius: 10px 10px 0 0;
    background: #ffffff;
}

.bueAdsPayMethod__header{
    position: relative;
    text-align: center;
    padding: 20px;
}

.bueAdsPayMethod__body{
    flex-grow: 1;
    width: 100%;
    font-size: 1.1em;
    padding-bottom: 20px;
}

/*При экранее более 992px */
@media screen and  (min-width: 992px) {

}
</style>
