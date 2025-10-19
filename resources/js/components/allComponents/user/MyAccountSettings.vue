
<template>
    <!-- КОМПОНЕНТ - НАСТРОЙКА МОЕГО АККАУНТА -->

    <!-- Backdrop -->
    <transition name="settings__animation-backdrop">

        <div v-if="settingsAnimation" class="settings__backdrop" @click="$router.back()"></div>

    </transition>

    <!-- Обвертка - Компонента -->
    <transition name="settings__animation-wrapper">

        <!-- Обвертка - Компонента -->
        <div v-if="settingsAnimation" class="settings__wrapper">

            <!-- Сам блок -->
            <div class="settings__block">

                <!-- Header -->
                <div class="settings__header">

                   <div>{{authStore.user.name}}</div>

                    <div class="mx-2">ID: {{authStore.user.id}}</div>

                    <v-chip size="small" class="bg-red-darken-2" v-if="authStore.user.role == 'admin'">Админ</v-chip>

                    <v-spacer></v-spacer>

                    <!-- Кнопка назад -->
                    <v-btn icon dark variant="text"
                           @click="$router.back()"
                           style="position: absolute; top: 0; right: 10px;"
                    >
                        <v-icon>mdi-arrow-right</v-icon>
                    </v-btn>

                </div>

                <!-- Body -->
                <div class="settings__body">

                    <!-- Заголовок -->
                    <h3 class="alert mt-4 fw-bold text-center">{{ $t('settingsToChangeTheData') }}</h3>

                    <!-- Блок с фрмой обновления -->
                    <validation-observer as="div" ref="form" v-slot="{ handleSubmit }">

                        <form @submit="handleSubmit($event, updateUserData)" class="form">

                            <!--Поле - Имя на сайте-->
                            <validation-provider v-model="form.name"
                                                 rules="required|alpha|max:15" name="name"
                                                 v-slot="{ errors, field}">
                                <!-- Input -->
                                <v-text-field
                                    v-model="form.name" v-bind="field"
                                    name="name" :label="$t('settingsLableName')"
                                    type="text" maxlength="15"
                                    variant="outlined" color="blue-darken-2"
                                    :error-messages="form.errors.has('name') ? form.errors.get('name'):'' || errors[0]"
                                    @input="form.errors.clear('name')"
                                ></v-text-field>

                            </validation-provider>

                            <!--Поле - Номер телефона-->
                            <div class="mb-4">
                                <vue-tel-input v-model="form.tel" @validate="telValid = $event.valid" style="height: 56px"
                                               :inputOptions="{ placeholder: $t('settingsLableTel'), showDialCode: true}"
                                               :class="{'border border-danger': showTelErrorMessage != ''}"
                                               :validCharactersOnly="true"
                                ></vue-tel-input>
                                <!-- Вывод ошибки -->
                                <div v-if="showTelErrorMessage" class="text-caption mt-1 text-error">
                                    {{$t('settingsLableTel')}}
                                </div>
                            </div>

                            <!--Поле - Ваш пароль-->
                            <validation-provider v-model="form.password"  rules="min:6|max:30" vid="пароль" name="password" v-slot="{ errors, field}">

                                <!-- Input -->
                                <v-text-field
                                    v-model="form.password" v-bind="field"
                                    name="password" :label="$t('settingsLablePassword')"
                                    :type="show_password ? 'text': 'password'"
                                    maxlength="30" autocomplete="off"
                                    variant="outlined" color="blue-darken-2"

                                    :append-inner-icon="show_password ? 'mdi-eye-outline' : 'mdi-eye-off-outline'"
                                    @click:append-inner="show_password = !show_password"

                                    :error-messages="form.errors.has('password') ? form.errors.get('password'):'' || errors[0]"
                                    @input="form.errors.clear('password')"
                                ></v-text-field>

                            </validation-provider>

                            <!-- Кнопка отправка формы -->
                            <v-btn type="submit" dark block size="x-large" color="blue-darken-2 text-body-1" class="my-2" :disabled="query">
                                <span v-if="query" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span>{{ $t('settingsToChangeTheData') }}</span>
                            </v-btn>

                        </form>

                    </validation-observer>

                </div>

            </div>

        </div>

    </transition>

</template>

<script>

//Валидация laravel VFORM
import Form from 'vform'
import { Button, HasError, AlertError } from 'vform/src/components/bootstrap5'

//Компонент для формата номера телефона
import { VueTelInput } from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';

//Импортирую Store - Общее состояние
import { useAuthStore } from "../../../stores/auth";


export default {
    name: "Index",

    components: {
        Button, HasError, AlertError,
        VueTelInput
    },

    data(){
        return {

            //Подключаю Store - Общее состояние
            authStore: useAuthStore(),

            settingsAnimation: false,

            show_password: false,
            query: false,

            form: new Form({
                user_id: '',
                name: '',
                tel: '',
                password: '',
                recaptcha_token: ''
            }),

            //Для поля телефон валидация и ошибка
            telValid: false,
            showTelErrorMessage : false,
        }
    },

    methods: {

        //Метод Обновить пароль
       async updateUserData(){

            //Проверка валидности номера телефона
            if(this.telValid != true){
                this.showTelErrorMessage = true;
                return;
            }else{
                this.showTelErrorMessage = false;
            }


            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            this.query = true;

            this.form.user_id = this.authStore.user.id;

            //Проверяем и получаем капчу и отпровляем данные на сервер для регистрации
            this.$recaptchaLoaded().then(()=>{
                this.$recaptcha('login').then((token)=>{

                    this.form.recaptcha_token = token;
                    this.form.post('/user/updateUserData').then((response)=>{
                        this.query = false;
                        this.authStore.getUser();
                        Toast.fire({
                            title: this.$t('settingsDataUpdatedSuccessfully')
                        });
                    }).catch((error)=>{
                        this.query = false;
                        Toast.fire({
                            icon: 'error',
                            title: this.$t('settingsError')
                        });
                    })
                })
            })
        },
    },

    mounted(){
        let app = this;

        this.settingsAnimation = true;
        document.querySelector(':root').classList.add('PATCH_modal'); //Отменим прокрутку за OffCanvas

        this.form.fill(this.authStore.user);

    },

    beforeRouteLeave(to, from, next){

        this.settingsAnimation = false;

        setTimeout(() => {
            next(); // Вызываем next() после завершения setTimeout - Для завершения анимации
        }, 350);
    }

}
</script>

<style>
/* Анимация для плавного появления - Backdrop */
.settings__animation-backdrop-enter-active,
.settings__animation-backdrop-leave-active{
    transition: opacity 0.3s ease;
}

.settings__animation-backdrop-enter-from,
.settings__animation-backdrop-leave-to {
    opacity: 0;
}

.settings__animation-backdrop-enter-to,
.settings__animation-backdrop-leave-from {
    opacity: 1;
}

/* Анимация для плавного появления - Wrapper */
.settings__animation-wrapper-enter-active,
.settings__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}


.settings__animation-wrapper-enter-from{
    transform: translateX(50%); /* Начальное положение при анимации скрытия */
}

.settings__animation-wrapper-leave-to {
    transform: translateX(100%); /* Начальное положение при анимации скрытия */
}

.settings__animation-wrapper-enter-to,
.settings__animation-wrapper-leave-from {
    transform: translateY(0);/* Конечное положение при анимации появления */
}
</style>

<style scoped>

/* Backdrop  */
.settings__backdrop{
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: 0;
}

.settings__wrapper{
    width: 100%;
    max-width: 450px;
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: auto;
    background: #ffffff;
    user-select: none;
}

.settings__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
}

.settings__header{
    position: relative;
    width: 100%;
    height: 44px;
    display: flex;
    align-items: center;
    font-size: 1.2em;
    font-weight: bold;
    background: var(--app-header-bg-color);
    color: #ffffff;
    padding: 0 10px;
}

.settings__body{
    flex-grow: 1;
    overflow-y: auto;
    padding: 10px;
}

</style>
