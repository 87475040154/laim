<template>
    <!-- КОМПОНЕНТ - АВТОРИЗАЦИЯ - РЕГИСТРАЦИЯ -->

    <!-- Обвертка - Компонента-->
    <div class="auth__wrapper">

        <!-- Сам блок -->
        <div class="auth__block">

            <!-- Header -->
            <div class="auth__header">
                <v-btn icon variant="text" class="mx-1" dark @click="$router.back()">
                    <v-icon size="large">mdi-arrow-left</v-icon>
                </v-btn>
            </div>

            <!-- Body -->
            <div class="auth__body">

                <!-- После успешной регистрации или отправки письм на почту покажем этот блок -->
                <div v-if="success != ''" class="mt-5 mx-auto text-center rounded border" style="max-width:450px; font-size: 1.1em">

                    <!-- Если - Регистрация завершена! -->
                    <div v-if="success == 'Регистрация завершена!' || success == 'Письмо для активации аккаунта отправлено!' || success == 'Письмо для смены пароля отправленно!'">
                        <div class="text-button fw-bold" style="font-size: 1.3em!important">{{  $t('authLetterSent1') }}</div>
                        <div>{{  $t('authLetterSent2') }}</div>
                        <small class="text-center text-grey" v-html="$t('authLetterSent3')"></small>
                    </div>

                </div>

                <!-- Body Компонента - с прокруткой  -->
                <div v-if="success == ''" class="py-2">
                    <div class="mx-auto rounded-lg auth_card p-3" style="max-width:450px">


                        <!-- Приветствие - Кнопки Войти - Зарегестирироваться -->
                        <div class="my-2 text-center fw-bold mb-4">

                            <!-- Приветствие  -->
                            <h1 v-if="form_name=='login'">{{ $t('authWelcome') }}</h1>
                            <h1 v-if="form_name=='register'">{{ $t('authCreateAnAccount') }}</h1>
                            <h1 v-if="form_name=='sendLinkForgotPassword'">{{ $t('authRecoverPassword') }}</h1>
                            <h1 v-if="form_name=='resetPassword'">{{ $t('authChangePassword') }}</h1>
                            <h1 v-if="form_name=='sendLinkEmailVerification' || form_name == 'accountActivation' ">{{ $t('authAccountActivation')}}</h1>

                        </div>

                        <!-- Вывод всех ошибок в alert Bootstrap -->
                        <div v-if="error != ''" class="alert bg-red text-center col-12 my-2" role="alert">

                            <!-- Если пользователь не подтвердил email при попытке входа будет показан данный блок-->
                            <div v-if="error == 'Email не подтвержден'" >
                                <div>{{ $t('authYouHavenVerifiedYourEmailYet') }}</div>
                                <v-btn color="blue-darken-1" @click="showForm('sendLinkEmailVerification')">{{ $t('authConfirm')}}</v-btn>
                            </div>

                            <div v-else-if="error == 'Вы ввели неправильный пароль'">
                                <span v-if="updateDateLocaleStore.lang == 'ru'">Вы ввели неправильный пароль</span>
                                <span v-if="updateDateLocaleStore.lang == 'kz'">Сіз қате құпия сөзді енгіздіңіз</span>
                                <span v-if="updateDateLocaleStore.lang == 'en'">You entered the wrong password</span>
                            </div>

                            <div v-else-if="error == 'Пользователь с таким email еще не зарегистрирован'">
                                <span v-if="updateDateLocaleStore.lang == 'ru'">Пользователь с таким email еще не зарегистрирован</span>
                                <span v-if="updateDateLocaleStore.lang == 'kz'">Осы электрондық поштасы бар пайдаланушы әлі тіркелмеген</span>
                                <span v-if="updateDateLocaleStore.lang == 'en'">User with this email is not registered yet</span>
                            </div>

                            <div v-else-if="error == 'Неверная капча - перезагрузите страницу'">
                                <span v-if="updateDateLocaleStore.lang == 'ru'">Неверная капча - перезагрузите страницу</span>
                                <span v-if="updateDateLocaleStore.lang == 'kz'">Жарамсыз captcha - бетті қайта жүктеңіз</span>
                                <span v-if="updateDateLocaleStore.lang == 'en'">Invalid captcha - reload the page</span>
                            </div>

                            <div v-else-if="error == 'Вы уже активировали аккаунт. Попробуйте войти на сайт'">
                                <span v-if="updateDateLocaleStore.lang == 'ru'">Вы уже активировали аккаунт. Попробуйте войти на сайт</span>
                                <span v-if="updateDateLocaleStore.lang == 'kz'">Сіз өзіңіздің есептік жазбаңызды әлдеқашан белсендірдіңіз. Жүйеге кіріп көріңіз</span>
                                <span v-if="updateDateLocaleStore.lang == 'en'">You have already activated your account. Try to login</span>
                            </div>

                            <div v-else-if="error == 'Ссылка устарела, запросите новую ссылку для сменя пароля'" >
                                <span v-if="updateDateLocaleStore.lang == 'ru'">Ссылка устарела, запросите новую ссылку для сменя пароля</span>
                                <span v-if="updateDateLocaleStore.lang == 'kz'">Сілтеменің мерзімі өтті, құпия сөзді қалпына келтіру үшін жаңа сілтемені сұраңыз</span>
                                <span v-if="updateDateLocaleStore.lang == 'en'">Link expired, please request a new link to reset your password</span>
                            </div>

                            <!-- Вывод всех остальных ошибок -->
                            <div v-else> {{ error }}</div>
                        </div>

                        <!-- Для активации - Блок с текстом и gif загрузка-  идет активация -->
                        <div v-if="form_name == 'accountActivation' && error == ''" class="text-center col-12">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <div class="h4 section-title ">{{ $t('authAccountActivationProcess') }}</div>
                        </div>


                        <!-- ФОРМА - ВХОДА / РЕГИСТРАЦИИ / ВОССТАНОВЛЕНИЕ ПАРОЛЯ / АКТИВАЦИЯ EMAIL -->
                        <validation-observer v-else tag="div" ref="form" v-slot="{ handleSubmit }">
                            <form @submit="handleSubmit($event, submit)" class="form py-2 text-center">

                                <!-- Поле - Имя -->
                                <validation-provider v-if="form_name == 'register'"
                                                     rules="required|alpha|max:15"
                                                     name="name" v-slot="{ errors, field}">

                                    <!-- Input -->
                                    <v-text-field
                                        v-model="form.name" v-bind="field"
                                        name="name" :label="$t('authLableName')"
                                        type="text" maxlength="15"
                                        variant="outlined" color="blue-darken-2"
                                        :error-messages="form.errors.has('name') ? form.errors.get('name'):'' || errors[0]"
                                        @input="form.errors.clear('name')"
                                    ></v-text-field>

                                </validation-provider>

                                <!-- Поле - Email -->
                                <validation-provider v-if="form_name == 'login' || form_name == 'register' || form_name == 'sendLinkForgotPassword' || form_name == 'sendLinkEmailVerification'"
                                                     rules="required|email|max:255"
                                                     name="email" v-slot="{ errors, field}">

                                    <!-- Input -->
                                    <v-text-field
                                        v-model="form.email" v-bind="field"
                                        name="email" :label="$t('authLableEmail')"
                                        :hint="form_name == 'sendLinkForgotPassword' || form_name == 'sendLinkEmailVerification' ? 'Введите email который вы указывали при регистрации':''"
                                        type="email" inputmode="email"
                                        variant="outlined" color="blue-darken-2"
                                        :error-messages="form.errors.has('email') ? form.errors.get('email'):'' || errors[0]"
                                        @input="form.errors.clear('email')"
                                    ></v-text-field>

                                </validation-provider>

                                <!--Поле - Телефон-->
                                <div v-if="form_name == 'register'" class="mb-4">
                                    <vue-tel-input v-model="form.tel" @validate="telValid = $event.valid" style="height: 56px"
                                                   :inputOptions="{ placeholder: $t('authLableTel'), showDialCode: true}"
                                                   :class="{'border border-danger': showTelErrorMessage != ''}"
                                                   :validCharactersOnly="true"
                                    ></vue-tel-input>
                                    <!-- Вывод ошибки -->
                                    <div v-if="showTelErrorMessage" class="text-caption mt-1 text-error">
                                        {{$t('authLableTel')}}
                                    </div>
                                </div>



                                <!-- Поле - Пароль -->
                                <validation-provider v-if="form_name == 'login' || form_name == 'register' || form_name == 'resetPassword'"
                                                     rules="required|min:6|max:30"
                                                     name="password" v-slot="{ errors, field}">

                                    <!-- Input -->
                                    <v-text-field
                                        v-model="form.password" v-bind="field"
                                        name="password" :label="$t('authLablePassword')"
                                        :type="show_password ? 'text': 'password'"
                                        maxlength="30" autocomplete="off"
                                        variant="outlined" color="blue-darken-2"

                                        :append-inner-icon="show_password ? 'mdi-eye-outline' : 'mdi-eye-off-outline'"
                                        @click:append-inner="show_password = !show_password"

                                        :error-messages="form.errors.has('password') ? form.errors.get('password'):'' || errors[0]"
                                        @input="form.errors.clear('password')"
                                    ></v-text-field>

                                </validation-provider>

                                <!-- Ссылка Я не помню пароль -->
                                <div v-if="form_name == 'login'" class="mb-2 text-right">
                                    <v-btn variant="text" color="blue-darken-2" class="text-body-2"
                                           @click="showForm('sendLinkForgotPassword')">
                                        {{ $t('authIDoNotRememberThePassword') }}
                                    </v-btn>
                                </div>

                                <!-- Кнопка отправка формы -->
                                <v-btn type="submit" elevation="1" dark block size="x-large" style="background: rgb(16, 163, 127);" class="my-2 text-white text-body-1" :disabled="query">
                                    <span v-if="query" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    <span>{{ formName }}</span>
                                </v-btn>

                                <!-- Пользовательское соглашение -->
                                <div v-if="form_name == 'register'" class="text-caption text-grey py-2">
                                    {{ $t('authRulesText')}}
                                    <router-link tag="a" to="/polzovatelskoeSoglashenie" target="_blank">
                                        {{ $t('authUserAgreement')}}
                                    </router-link>
                                </div>

                            </form>
                        </validation-observer>


                        <!-- Войти -->
                        <div v-if="form_name != 'login'" class="text-center my-2">
                            <v-btn @click="showForm('login')" block
                                   color="blue"
                                   class="text-body-1"
                                   size="x-large"
                                   variant="outlined">
                                {{ $t('authLogin') }}
                            </v-btn>
                        </div>

                        <!-- Зарегистрироваться -->
                        <div v-if="form_name != 'register'" class="text-center my-2">
                            <v-btn @click="showForm('register')" block
                                   color="blue"
                                   class="text-body-1"
                                   size="x-large"
                                   variant="outlined"
                            >
                                {{ $t('authSignUp') }}
                            </v-btn>
                        </div>


                        <!-- Вход через Google -->
                        <div class="text-center my-2">
                            <GoogleLogin :callback="googleAuth" popup-type="TOKEN" prompt style="width: 100%">
                                <v-btn variant="outlined"
                                       block
                                       class="google-login-button"
                                       size="x-large"
                                >
                                    <v-icon class="google-icon">mdi-google</v-icon>
                                    {{ $t('authGoogleLogin') }}
                                </v-btn>
                            </GoogleLogin>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

</template>

<script>

//Валидация laravel VFORM
import Form from 'vform'
import { Button, HasError, AlertError } from 'vform/src/components/bootstrap5'

//Компонент для формата номера телефона
import { VueTelInput } from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';

//Импортирую Store - общее состояние
import { useAuthStore } from "../../stores/auth";
import { useGetProjectDataStore} from "../../stores/getProjectData";
import {useUpdateDateLocaleStore} from "../../stores/updateDateLocale";


//Вход через Google
import { decodeCredential } from 'vue3-google-login'

export default {
    name: "AuthComponent",

    components: {
        Button, HasError, AlertError,
        VueTelInput
    },

    data(){
        return {
            //Подключаю Store - Общее состояние
            authStore: useAuthStore(),
            getProjectDataStore: useGetProjectDataStore(),
            updateDateLocaleStore: useUpdateDateLocaleStore(),

            form_name: 'login', //Какую форму показать по умолчанию форму входа
            show_password: false, //Для поля пароль показать или скрытшь

            query: false,
            form: new Form({
                name: '',
                email: '',
                tel: '',
                password: '',
                agree: true,
                recaptcha_token: '',
                path: this.$route.path
            }),
            success: '', //После успешной регистрации
            error: '', //Если есть ошибки

            //Для поля телефон валидация и ошибка
            telValid: false,
            showTelErrorMessage : false,
        }
    },

    computed: {

        // Перевожу название формы с латиницы на кирилицу
        formName(){
            if(this.form_name == 'login')return this.$t('authLogin')
            if(this.form_name == 'register')return this.$t('authSignUp')
            if(this.form_name == 'sendLinkForgotPassword')return this.$t('authRecoverPassword')
            if(this.form_name == 'resetPassword')return this.$t('authChangePassword')
            if(this.form_name == 'sendLinkEmailVerification'|| this.form_name == 'accountActivation')return this.$t('authAccountActivation')
        },

    },

    methods: {

        //ОСНОВНОЙ МЕТОД - Выбирает один из методв ниже - регистрация / Вход и тд.
        async submit(){

            this.query = true;

            //Проверка валидности номера телефона
            if(this.form_name == 'register'){
                if(this.telValid != true){
                    this.showTelErrorMessage = true;
                    this.query = false;
                    return;
                }else{
                    this.showTelErrorMessage = false;
                }
            }

            //Проверяем и получаем рекаптчу и отпровляем данные на сервер
            this.$recaptchaLoaded().then(()=>{
                this.$recaptcha('login').then((token)=>{

                    this.form.recaptcha_token = token;

                    if(this.form_name == 'login')this.login();
                    if(this.form_name == 'register')this.register();
                    if(this.form_name == 'sendLinkForgotPassword')this.sendLinkForgotPassword();
                    if(this.form_name == 'resetPassword')this.resetPassword();
                    if(this.form_name == 'sendLinkEmailVerification')this.sendLinkEmailVerification();

                }).catch(()=>{
                    this.query = false;
                    Swal.fire({
                        text: this.$t('authError')
                    })
                })
            })
                .catch(()=>{
                this.query = false;
                Swal.fire({
                    text: this.$t('authError')
                })
            })
        },

        //Метод авторизации
        login() {
            //Авторизации Sanctum перед входом нужно получить доступ
            axios.get('/sanctum/csrf-cookie').then(response => {

                this.form.post('/auth/login')
                    .then((response)=>{
                        this.query = false;
                        this.authStore.getUser();//Получим Данные авторизованного пользователя
                        this.$router.back();
                    })
                    .catch((errors)=>{
                        this.query = false;
                        this.error = errors.response.data.error
                    })
            });

        },

        //Метод регистрации
        register(){
            this.form.post('/auth/register')
                .then((response)=>{
                    this.query = false;
                    this.success = 'Регистрация завершена!';
                })
                .catch((error)=>{
                    this.query = false;
                    this.error = this.$t('authError')
                })
        },

        //Метод отправки письма для восстановления пароля
        sendLinkForgotPassword(){

            axios.post('/auth/sendLinkForgotPassword', {
                'email': this.form.email,
                'path': this.$route.path,
                'recaptcha_token': this.form.recaptcha_token,
            })
                .then((response)=>{
                    this.query = false;
                    this.success = 'Письмо для смены пароля отправленно!'
                })
                .catch((error)=>{
                    this.query = false;
                    this.error = error.response.data.error;
                })
        },

        //Метод непосредственно смены пароля
        resetPassword(){
            this.form.token = this.$route.query.resetPasswordToken;

            axios.get('/sanctum/csrf-cookie').then(response => {
                //Отправка данных на сервер
                this.form.post('/auth/resetPassword').then((response)=>{
                    this.query = false;
                    this.authStore.getUser();//Получим Данные авторизованного пользователя
                    this.$router.replace('/allAds/Kvartira');
                }).catch((error)=>{
                    this.query = false;
                    Toast.fire({
                        text: error.response.data.error
                    })
                });
            })

        },

        //Метод отправить письмо для активации аккаунта
        sendLinkEmailVerification(){
            this.form.post('/auth/sendLinkEmailVerification').then((response)=>{
                this.query = false;
                this.success = 'Письмо для активации аккаунта отправлено!';
            }).catch((error)=>{
                this.query = false;
                this.error = error.response.data.error;
            })
        },

        //Метод активации аккаунта - И авторизации
       async accountActivation(accountActivationToken){

            axios.get('/sanctum/csrf-cookie').then(response => {

                this.$recaptchaLoaded().then(()=>{
                    this.$recaptcha('login').then((recaptchaToken)=>{

                        this.query = true;

                        axios.put('/auth/emailVerification', {
                            "recaptcha_token": recaptchaToken,
                            'account_activation_token': accountActivationToken
                        })
                            .then((response)=>{
                                this.query = false;
                                this.authStore.getUser();//Получим Данные авторизованного пользователя
                                this.$router.replace('/allAds/Kvartira');
                            })
                            .catch((error)=>{
                                this.query = false;
                                Toast.fire({
                                    text: error.response.data.error
                                })
                            })
                    }).catch(()=>{
                        this.query = false;

                        Swal.fire({
                            text: this.$t('authError')
                        })
                    })
                }).catch(()=>{
                    this.query = false;

                    Swal.fire({
                        text: this.$t('authError')
                    })
                })

            });
        },

        //Метод какую форму показать, и также отчистить форму от предидущих данных
        showForm(form_name = 'login'){
            this.form_name = form_name;

            this.error = '';
            this.success = '';
            this.query = false;

            //Отчистим Форму vform
            this.form.reset();
            this.form.clear();

            //Отчистить  форму и ошибки vee validate
            this.$refs.form.resetForm();
        },


        //Авторизация через Google
        async googleAuth(response){

            let email = '';
            let name = '';
            let accessToken = '';

            //Если вызов через одно касание
            if(response.credential) {
                 email = decodeCredential(response.credential).email
                 name = decodeCredential(response.credential).given_name
            }else{

                // Если вызов через клик по кнопке
                accessToken = response.access_token;
            }

            this.query = true;

            //Авторизации Sanctum перед входом нужно получить доступ
            axios.get('/sanctum/csrf-cookie').then(response => {

                axios.post('/auth/googleLogin', {
                    token: accessToken,
                    email: email,
                    name: name
                })
                    .then((response)=>{
                        this.query = false;
                        this.authStore.getUser();//Получим Данные авторизованного пользователя
                        this.$router.back();
                    })
                    .catch((errors)=>{
                        this.query = false;
                        this.error = errors.response.data.error
                    })
            });

        },
    },

    mounted(){
        let app = this;

        document.querySelector(':root').classList.add('PATCH_modal');

        //Если запрос на изменение пароля
        if(app.$route.query.resetPasswordToken != undefined){
            app.showForm('resetPassword')
        }

        //Если запрос на активацию аккаунта
        else if(app.$route.query.accountActivationToken != undefined){
            this.form_name = 'accountActivation';
            app.accountActivation(app.$route.query.accountActivationToken);
        }
        else{
            app.showForm()
        }

    },

    beforeRouteLeave(to, from, next) {
        if(to.name == 'allAds')document.querySelector(':root').classList.remove('PATCH_modal'); //Отменим прокрутку под модальным окном
        next();
    }



}
</script>

<style>

/* Анимация для -- Wrapper */
.auth__animation-wrapper-enter-active,
.auth__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}


.auth__animation-wrapper-enter-from{
    transform: translateY(10%);
}

.auth__animation-wrapper-leave-to{
    transform: translateY(100%);
}


.auth__animation-wrapper-enter-to,
.auth__animation-wrapper-leave-from{
    transform: translateY(0);
}

</style>

<style scoped>
.auth__wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #ffffff;
}

.auth__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
}

.auth__header {
    width: 100%;
    height: auto;
}

.auth__body {
    flex-grow: 1; /* Растянем этот блок на всю оставшуюся высоту экрана */
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.google-login-button {
    background-color: #ffffff; /* Белый фон */
    border: 1px solid #d9d9d9; /* Лёгкая серая рамка */
    color: #757575; /* Цвет текста */
    font-weight: bold;
    text-transform: none; /* Обычный текст, без верхнего регистра */
    transition: background-color 0.3s ease, color 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Иконка Google */
.google-icon {
    color: #4285f4; /* Синий цвет иконки Google */
    margin-right: 8px; /* Отступ справа для разделения иконки и текста */
    font-size: 1.25em;
}

/* Hover эффекты */
.google-login-button:hover {
    background-color: #f5f5f5; /* Светлый фон при наведении */
    border-color: #c6c6c6;
}

/* Активное состояние */
.google-login-button:active {
    background-color: #e0e0e0; /* Более тёмный фон при нажатии */
    border-color: #b3b3b3;
}
</style>
