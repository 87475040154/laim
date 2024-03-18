<template>
    <!-- КОМПОНЕНТ - МЕТОД ПОКУПКИ РЕКЛАМЫ -->

    <!-- Это блок - Backdrop -->
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

                    <!-- Кнопка - Оплата через карту - Freedom Pay -->
                    <div v-if="!showFormVisaCard"
                        @click="showFormVisaCard = !showFormVisaCard"
                         class="border-bottom p-2 py-3 px-3 row g-0 gap-3 align-center"
                         role="button"
                    >
                        <!-- Иконка справа-->
                        <div class="col-auto">
                            <img src="/public/img/siteImg/allImg/logo_visa.png" width="25" height="25" alt="Логотип">
                        </div>

                        <!-- Текст -->
                        <span class="col">{{ $t('bueAdsPayMethodVisaMasterCardCard') }}</span>
                    </div>

                    <!-- Кнопка - Оплата с Личного счёта -->
                    <div v-if="!showFormVisaCard"
                        class="border-bottom p-2 py-3 px-3 row g-0 gap-3 align-center"
                        role="button"
                    >
                        <div class="col-auto">
                            <img src="/public/img/siteImg/allImg/logo.svg" width="25" height="25" alt="Логотип">
                        </div>
                        <span class="col">{{ $t('bueAdsPayMethodPersonalAccount') }} ({{authStore.user.balance}} &#8376;)</span>
                    </div>


                    <!-- Форма для ввода Номера карты - Visa -->
                    <div v-if="showFormVisaCard">

                        <!-- Форма Карты Visa -->
                        <validation-observer tag="div" ref="form" v-slot="{ handleSubmit }">
                            <form @submit="handleSubmit($event, addOrderDB)" class="form p-2 text-center">

                                <!-- Поле - Номер карты -->
                                <validation-provider rules="required|min:1|length:19"
                                                     name="card_number" v-slot="{ errors, field }"
                                >
                                    <!-- Input -->
                                    <v-text-field
                                        v-model="card_number" v-bind="field"
                                        name="card_number" :label="$t('bueAdsPayMethodCardNumber')"
                                        type="text" maxlength="19" inputmode="numeric"
                                        variant="outlined" color="blue-darken-2"
                                        :error-messages="errors"
                                        v-mask="'#### #### #### ####'"
                                        prepend-inner-icon="mdi-credit-card"
                                    ></v-text-field>
                                </validation-provider>


                                <!-- ММ / ГГ и CVV -->
                                <div class="d-flex flex-row gap-2">

                                    <!-- ММ / ГГ -->
                                    <validation-provider rules="required|min:5"
                                                         name="card_expiry" v-slot="{ errors, field }"
                                    >
                                        <!-- Input -->
                                        <v-text-field
                                            v-model="card_expiry" v-bind="field"
                                            name="card_expiry" :label="$t('bueAdsPayMethodMonthYear')"
                                            type="text" inputmode="numeric"
                                            variant="outlined" color="blue-darken-2"
                                            :error-messages="errors[0]"
                                            maxlength="5"
                                            v-mask="'##/##'"
                                        ></v-text-field>
                                    </validation-provider>

                                    <!-- Поле - CVV код -->
                                    <validation-provider rules="required|numeric|length:3"
                                                         name="cvv" v-slot="{ errors, field}"
                                    >
                                        <!-- Input -->
                                        <v-text-field
                                            v-model="card_cvv" v-bind="field"
                                            name="card_cvv" label="CVV"
                                            type="text" maxlength="3"
                                            inputmode="numeric"

                                            variant="outlined" color="blue-darken-2"
                                            :error-messages="errors[0]"
                                            v-mask="'###'"
                                        ></v-text-field>
                                    </validation-provider>


                                </div>


                                <!-- Поле - Имя владельца карты -->
                                <validation-provider rules="required|max:255"
                                                     name="name" v-slot="{ errors, field}"
                                >
                                    <!-- Input -->
                                    <v-text-field
                                        v-model="card_holder_name" v-bind="field"
                                        name="card_holder_name" :label="$t('bueAdsPayMethodCardHolderName')"
                                        type="text"
                                        variant="outlined" color="blue-darken-2"
                                        :error-messages="errors[0]"
                                    ></v-text-field>

                                </validation-provider>

                                <!-- Кнопка отправки формы -->
                                <v-btn type="submit" elevation="1" dark block size="x-large" style="background: rgb(16, 163, 127);" class="my-2 text-white text-body-1" :disabled="query">
                                    <span v-if="query" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    <span>{{ $t('bueAdsPayMethodToPay') }}</span>
                                </v-btn>

                            </form>
                        </validation-observer>


                    </div>

                    <!-- Тег с id - Если нужна будет проверка карты - от самой SDK-->
                    <div id="3dsForm"></div>

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


import {mask} from 'vue-the-mask' //Пакет для изменения данных при вводе в поле, маска

export default {
    name: "BueAdsPayMethod",

    directives: {mask}, // Директива для изменения формата с 000000 на 0000 0000 v-mask

    data(){
        return {
            //Подключаю Store
            authStore: useAuthStore(),
            checkInternetStore: useCheckInternetStore(),
            updateDateLocaleStore: useUpdateDateLocaleStore(),

            bueAdsPayMethodAnimation: false,

            query: false,

            //Показать форму для ввода данных карты visa для оплаты
            showFormVisaCard: false,

            // Данные карты
            card_number: '',
            card_expiry: '', // ММ/ГГ
            card_cvv: '',
            card_holder_name: ''

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

        //Метод - Добавить заказ в БД и сделать попытку оплаты
        async addOrderDB(){

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            await this.checkInternetStore.checkInternet()
            if(!this.checkInternetStore.online)return;

            this.query = true;

            // Занесем заказ в БД и вернем id - заказа
            axios.post('/user/addOrderDB',{
                author_id: this.authStore.user.id,
                author_email: this.authStore.user.email,
                ads_id: this.$route.params.ads_id,
                table_name: this.$route.params.table_name,
                bue_ads_type: this.$route.params.bueAdsType
            })
                .then((response)=>{

                    //Метод оплаты чере Карту - Freedom Pay
                    this.doPaymentFreedomPay(response.data.order);

                })
                .catch((errors)=>{
                    // Если возникла ошибка при добавлении заказа в БД
                    Swal.fire({
                        title: this.$t('bueAdsPayMethodError'),
                        text: errors.response.data.message
                    })

                    this.query = false;
                })
        },

        // Метод - Оплата через карту - Freedom Pay
        async doPaymentFreedomPay(order){

            // Данные платежа - Номер, сумма и тд.
            const JSPaymentOptions = {
                order_id: order.id, // должен быть уникальным на каждый запрос
                auto_clearing: 0,
                amount: order.summ, //Сумма
                currency: "KZT",
                description: "Покупка продвижение объявления",
                test: 1,
                options: {
                    custom_params: {},
                    user: {
                        email: this.authStore.user.email,
                        phone: this.authStore.user.tel,
                    }
                },
            };

            // Данные банковской карты
            const JSTransactionOptionsBankCard = {
                type: 'bank_card',
                options: {
                    card_number: this.card_number.replace(/\s/g, ''),
                    card_holder_name: this.card_holder_name,
                    card_exp_month: this.card_expiry.split('/')[0],
                    card_exp_year: this.card_expiry.split('/')[1],
                    card_cvv: this.card_cvv
                }
            };

            try {

                let JSPayResult = await FreedomPaySDK.charge(
                    JSPaymentOptions, JSTransactionOptionsBankCard
                );

                if (JSPayResult.payment_status === "need_confirm") {
                    console.log('need_confirm')

                    JSPayResult = await FreedomPaySDK.confirmInIframe(JSPayResult, "3dsForm");
                }

                // открыть страницу результата платежа и т д
                // ...
                console.log(JSPayResult);
                console.log('dffdsdfsdfs');

                this.query = false;

            } catch(JSErrorObject) {

                this.query = false;
                console.log(JSErrorObject)

                // Если возникла ошибка при попытке оплаты
                Swal.fire({
                    title: this.$t('bueAdsPayMethodError'),
                    text: 'Попробуйте еще раз!'
                })

                // Удалим заказ с БД, чтоб не было переполнения
                this.deleteOrderDB(order)

            }
        },

        deleteOrderDB(order){
            // Определяем URL с параметрами
            let url = `/user/deleteOrderDB?order_id=${order.id}`;

            // Выполняем запрос DELETE
            axios.delete(url)
                .then((response) => {
                    console.log('Заказ удален!');
                })
                .catch((error) => {
                    console.log('Заказ не удален!');
                });
        },

      async test(){
          const JSPaymentOptions = {
              order_id: "1", // должен быть уникальным на каждый запрос
              auto_clearing: 0,
              amount: 20,
              currency: "KZT",
              description: "Описание заказа",
              test: 1,
              options: {
                  user: {
                      email: "client@email.com",
                      phone: "+77777777777"
                  }
              },
          };

          const JSTransactionOptionsBankCard = {
              type: 'bank_card',
              options: {
                  card_number: "4916307416334310",
                  card_holder_name: "test",
                  card_exp_month: "12",
                  card_exp_year: "24",
                  card_cvv: 123
              }
          };

          try {

              let JSPayResult = await FreedomPaySDK.charge(
                  JSPaymentOptions, JSTransactionOptionsBankCard
              );

              if (JSPayResult.payment_status === "need_confirm") {

                  console.log('need_confirm');
                  JSPayResult = await FreedomPaySDK.confirmInIframe(JSPayResult, "3dsForm");
              }

              // открыть страницу результата платежа и т д
              // ...
              console.log(JSPayResult);


          } catch(JSErrorObject) {
              // Обработать JSErrorObject.response
              console.log(JSErrorObject);
          }
        }
    },

   async  mounted(){

       this.bueAdsPayMethodAnimation = true;

       document.querySelector(':root').classList.add('PATCH_modal'); //Отменим прокрутку под этим компонентом


       // FREEDOM PAY - Добавляем публичный ключ - и токен для проведения оплаты через Freedom
        try {
            await FreedomPaySDK.setup("-----BEGIN PUBLIC KEY-----\n" +
                "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtebJwl/B5+aHfAzxHyd7\n" +
                "h/+pjeHUS9TC70VW5slcDsn5yGnE+xqrEB2HHLTHBehoYmGxEEGshjA7HQpZe4Be\n" +
                "FZY+d7D6f6PE0nsxo0fbq6YI4kY7MQW/BTwAKCb5W328j0mZB3L10WBd7gQCKUCA\n" +
                "TE9qFBF1g6k1lMcgLB+zMB1NmxrVolD3pZYUqogwXBrTDVJsV2PrIZYqlu2HSD7+\n" +
                "HLRhX70ZxpGtWO6BxxpzZ+SUPk99YlYPfN95QsRDQV2M/4e5uZp82R3yFf64ZgzR\n" +
                "F1YhKv6aIn30KGFu5ZnnqvGuCI+Qn1xr9Ig2QNPdvhjx9Vh83ItQgGsLFNUZ73RK\n" +
                "7wIDAQAB\n" +
                "-----END PUBLIC KEY-----",'2hbyMxtqNqpMjwIfzG1A7QLMjDsxLntW');

            this.test();
            console.log('SDK FreedomPay инициализирован');
        } catch (error) {
            console.error('Ошибка при инициализации SDK FreedomPay:', error);
        }

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

.form{
    display: block;
    width: 100%;
    max-width: 400px;
    margin: auto;
}

/*При экранее более 992px */
@media screen and  (min-width: 992px) {

}
</style>
