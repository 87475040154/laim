<template>
    <!-- КОМПОНЕНТ - ВЫБОР ЛОКАЦИИ -->

    <!-- Backdrop -->
    <transition name="location__animation-backdrop">

        <!-- Backdrop -->
        <div v-if="locationAnimation" class="location__backdrop" :class="locationWrapperClass" @click="$router.back()"></div>

    </transition>

    <!-- Обвертка - Компонента -->
    <transition name="location__animation-wrapper">

        <!-- Обвертка - Компонента -->
        <div v-if="locationAnimation" class="location__wrapper">

            <!-- Сам блок -->
            <div class="location__block">

                <!-- Header - Заголовок -->
                <div class="location__header">

                    <!-- Кнопка Закрыть окно -->
                    <v-btn icon variant="text" class="mx-2" dark @click="$router.back()">
                        <v-icon>mdi-arrow-left</v-icon>
                    </v-btn>

                    <!-- Иконка и текст местоположение -->
                    <span class="text-body-1">{{ $t('KZLocationLocation') }}</span>

                    <v-spacer></v-spacer>

                </div>

                <!-- Body - Блок с локациями вывод -->
                <div class="location__body">
                    <!-- Gif загрузка - Если Локации еще не загрузились покажется этот gif  -->
                    <div v-if="query" class="d-flex justify-content-center">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                    </div>

                    <!-- Выберите область -->
                    <div v-if="!query" class="p-2">
                        <div v-if="$route.params.id == 'null'" class="my-1 text-caption text-grey">{{ $t('KZLocationChooseAnArea') }}</div>
                    </div>

                    <!-- Вывожу список локаций - И кнопки управления для админа - редактировать - удалить -->
                    <div v-for="(location, index) in locations">
                        <v-divider class="m-0"></v-divider>

                        <!-- Локация -->
                        <div class="p-2 d-flex align-items-center text-body-1">

                            <div class="col py-2">
                                <!-- Выбор Облости -->
                                <div role="button" v-if="location.gorod == null" @click="$router.push({name: this.$route.name, params: {locationId: location.id, stepLocation: Number($route.params.stepLocation)+1}})">
                                    <span v-if="updateDateLocale.lang == 'ru'">{{ location.oblast }}</span>
                                    <span v-if="updateDateLocale.lang == 'kz'">{{ location.oblast_kz }}</span>
                                    <span v-if="updateDateLocale.lang == 'en'">{{ location.oblast_en }}</span>
                                </div>

                                <!-- Выбор Города -->
                                <div v-if="location.gorod != null && location.raion == null">

                                    <div v-if="location.gorod == 'Астана' || location.gorod == 'Алматы' || location.gorod == 'Шымкент'">

                                        <!-- Выбрать весь город -->
                                        <div role="button" class="pt-1 pb-3" @click="addSelectedLocation( location )">
                                            {{ $t('KZLocationSelectWholeCity') }}
                                            <span v-if="updateDateLocale.lang == 'ru'">{{ location.gorod }}</span>
                                            <span v-if="updateDateLocale.lang == 'kz'">{{ location.gorod_kz }}</span>
                                            <span v-if="updateDateLocale.lang == 'en'">{{ location.gorod_en }}</span>
                                        </div>

                                        <v-divider class="m-0"></v-divider>

                                        <!-- Показать районы -->
                                        <div role="button" class="py-3" @click="$router.push({name: this.$route.name, params: {locationId: location.id, stepLocation: Number($route.params.stepLocation) + 1}})">{{ $t('KZLocationSelectDistricts') }}</div>

                                    </div>

                                    <div role="button" v-else @click="location.isset_children? $router.push({name: this.$route.name, params: {locationId: location.id, stepLocation: Number($route.params.stepLocation) + 1}}): addSelectedLocation( location )">
                                        <span v-if="updateDateLocale.lang == 'ru'">{{ location.gorod }}</span>
                                        <span v-if="updateDateLocale.lang == 'kz'">{{ location.gorod_kz }}</span>
                                        <span v-if="updateDateLocale.lang == 'en'">{{ location.gorod_en }}</span>
                                    </div>
                                </div>

                                <!-- Выбор Района -->
                                <div role="button" v-if="location.raion != null" @click="addSelectedLocation( location )">
                                    <span v-if="updateDateLocale.lang == 'ru'">{{ location.raion }}</span>
                                    <span v-if="updateDateLocale.lang == 'kz'">{{ location.raion_kz }}</span>
                                    <span v-if="updateDateLocale.lang == 'en'">{{ location.raion_en }}</span>
                                </div>
                            </div>

                            <!-- Стрелка если есть дочерние локации - Кнопки управления локацией для админа -->
                            <div class="col-auto">

                                <!-- Стрелка если есть дочерние локации -->
                                <i v-if="location.isset_children && location.gorod != 'Астана' && location.gorod != 'Алматы' && location.gorod != 'Шымкент'" @click="$router.push({name: $route.name, params: {locationId: location.id, stepLocation: Number($route.params.stepLocation) + 1}})" class="bi bi-arrow-right-short"></i>

                                <!-- Для админа кнопки - Редактирование - Удаление локации -->
                                <span v-if="authStore.check && authStore.user.role == 'admin'">

                            <!-- Добавить -->
                            <i v-if="!location.isset_children" @click="$router.push({name: this.$route.name, params: {id: location.id}})" class="bi bi-plus-square"></i>

                                    <!-- Редактировать -->
                                <i @click="formFill(location, index)" class="bi bi-pencil-square mx-2"></i>

                                    <!-- Удалить -->
                                <i @click="deleteLocation(location.id, index)" class="bi bi-trash3"></i>

                            </span>

                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer - Форма добавления -->
                <div class="location__footer"
                     v-if=" authStore.check && authStore.user.role == 'admin' "
                >

                    <!-- Форма добавления Локации -->
                    <validation-observer as="div" ref="form" v-slot="{ handleSubmit }" class="col-12">

                        <form @submit="handleSubmit($event, addOrUpdateLocation == 'add' ? addLocation() : updateLocation() )" class="form w100">

                            <!-- Поле - Введите название области -->
                            <div class="form-group" v-if="$route.params.id == 'null'">

                                <validation-provider rules="required|min:1|max:100" name="Область" v-slot="{ errors, field}">

                                    <input v-model="form.oblast" v-bind="field" type="text" maxlength="100" class="form-control" placeholder="Введите название области">

                                    <!-- Вывожу ошибки - veevalidate, vform -->
                                    <div class="small" style="color: red">
                                        <span >{{ errors[0] }}</span>
                                        <has-error :form="form" field="oblast"></has-error>
                                    </div>
                                </validation-provider>

                            </div>

                            <!-- Поле -  Введите название города -->
                            <div class="form-group" v-if="$route.params.id != 'null'">

                                <validation-provider rules="required|min:1|max:100" name="Город" v-slot="{ errors, field}">

                                    <input v-model="form.gorod" v-bind="field" type="text" maxlength="100" class="form-control" placeholder="Введите название города">

                                    <!-- Вывожу ошибки - veevalidate, vform -->
                                    <div class="small" style="color: red">
                                        <span >{{ errors[0] }}</span>
                                        <has-error :form="form" field="gorod"></has-error>
                                    </div>
                                </validation-provider>

                            </div>

                            <!-- Поле - Введите название района -->
                            <div class="form-group" v-if="$route.params.id != 'null'">

                                <validation-provider rules="required|min:1|max:100" name="Район" v-slot="{ errors, field}">

                                    <input v-model="form.raion" v-bind="field" type="text" maxlength="100" class="form-control" placeholder="Введите название района">

                                    <!-- Вывожу ошибки - veevalidate, vform -->
                                    <div class="small" style="color: red">
                                        <span >{{ errors[0] }}</span>
                                        <has-error :form="form" field="raion"></has-error>
                                    </div>
                                </validation-provider>

                            </div>


                            <!-- Кнопка - Добавить - Сохранить изменения -  Локацию -->
                            <v-btn type="submit" color="blue-darken-1 m-2" :disabled="query">
                                {{ addOrUpdateLocation == 'add' ? 'Добавить' : 'Сохранить изменения'}}
                            </v-btn>

                        </form>

                    </validation-observer>

                </div>

            </div>

        </div>

    </transition>


</template>

<script>

//Валидация laravel VForm
import Form from 'vform'
import { Button, HasError, AlertError } from 'vform/src/components/bootstrap5'

//Импортирую Store - Наше общее состояние
import { useAuthStore } from "../../stores/auth";
import {useKZLocationStore} from "../../stores/KZLocation";
import {useUpdateDateLocaleStore} from "../../stores/updateDateLocale";

//Для получения Координат
import { loadYmap  } from 'vue-yandex-maps'
import {useCheckInternetStore} from "../../stores/checkInternet";


export default {
    name: 'KZLocation',

    //Подключаем компоненты
    components: {
        Button, HasError, AlertError,
    },

    data(){
        return {

            //Подключаю Store - Наше общее состояние
            authStore: useAuthStore(),
            KZLocationStore: useKZLocationStore(),
            updateDateLocale: useUpdateDateLocaleStore(),
            checkInternetStore: useCheckInternetStore(),


            query: false,
            locationAnimation: false,

            locations: [], //Массив локаций полученных с базы данных

            //Для Добавления - редактирования локации
            addOrUpdateLocation: 'add', //Для проверки добавляем или редактироваем локацию
            update_location_index: '', //При редактировании занесем индекс данной локации
            form: new Form({
                id: null,
                parent_id: null,
                oblast: null,
                gorod: null,
                raion: null,
                lat: null,
                lon: null,
            }),

            //Настройки для Яндекс карты по умолчанию
            settings :{
                apiKey: '8740b571-75d9-47f0-a5c4-582b1feaf201',
                lang: 'ru_RU',
                coordorder: 'latlong',
                enterprise: false,
                version: '2.1'
            },
            ymapsGeocoder: '',   //Яндекс карта геокодер
        }
    },

    watch: {

        '$route' (to) {
            this.getLocations(to.params.locationId);
        },

    },

    computed: {

        //Добавим затемнение только если компонент выводится в addAds
        locationWrapperClass() {
            const currentRoute = this.$route.name;
            // Установите классы CSS в зависимости от текущего маршрута
            return {
                'location__wrapper--addAds': currentRoute !== 'filterLocation',
            };
        },
    },

    methods: {

        //Метод получение локации с базы данных по полю parent_id
        async getLocations(location){

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            this.checkInternetStore.checkInternet()

            location == 'null'? location = null: '';

            //Запрос на получение локации с БД по полю parent_id
            axios.get('/KZLocation/getLocations', {
                params: {
                    parent_id: location,
                }
            })
                .then(response=>{
                    this.locations = response.data;

                    //Заполним форму для добавления новой локации
                    this.authStore.check && this.authStore.user.role == 'admin' ? this.formFill( location ) : '';

                })
                .catch(errors=>{
                    Toast.fire({
                        icon: 'error',
                        title: errors.response.data.error
                    })
                })
        },

        //Занесем выбранную локацию
        addSelectedLocation( location ){
            this.KZLocationStore.addLocation(location)
            this.$router.go(-this.$route.params.stepLocation)
        },

        //Добавление - редактирование - удаление локации

        //Заполнить форму данными
        formFill( location, index = null ){

            //Если передан null то сбросим форму
            if( location == null){
                this.clearForm()
            }else{
                //Заполним форму данными
                this.clearForm();
                this.form.fill( location );
                index == null ? this.form.parent_id = location.id : '';

                //Если выбрана локация для редактирования
                if(index != null){
                    this.addOrUpdateLocation = 'update';
                    this.update_location_index = index;
                }
            }

        },

        //Добавить новую локацию
        async addLocation(){
            this.query = true;


            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            this.checkInternetStore.checkInternet()


            //Узнаем и добавим координаты перед занесением
            let getCoord = 'Казахстан, ' + this.form.oblast + 'область, ';
            this.form.gorod != null ? getCoord += 'город ' + this.form.gorod + ', ':'';
            this.form.raion != null ? getCoord += this.form.raion + 'район':'';

            this.ymapsGeocoder.geocode(getCoord).then((res)=>{

                //Запишем в форму координаты
                this.form.lat = res.geoObjects.get(0).geometry._coordinates[0]
                this.form.lon = res.geoObjects.get(0).geometry._coordinates[1]

                //Добавим локацию
                this.form.post('/KZLocation/addLocation')
                    .then(response=>{
                        this.query = false;
                        this.locations.push(response.data);
                        Toast.fire({
                            icon: 'success',
                            title: 'Добавленно'
                        })
                    })
                    .catch(errors=>{
                        this.query = false;
                        Toast.fire({
                            icon: 'error',
                            title: errors.response.data.error
                        })
                    })

            })


        },

        //Редактировать локацию
        async updateLocation(){
            this.query = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            this.checkInternetStore.checkInternet()


            this.form.put('/KZLocation/updateLocation')
                .then(response=>{
                    this.query = false;

                    this.locations.splice(this.update_location_index, 1)
                    this.locations.push(response.data);

                    Toast.fire({
                        icon: 'success',
                        title: 'Изменения сохраненны'
                    })

                })
                .catch(errors=>{
                    this.query = false;

                    Toast.fire({
                        icon: 'error',
                        title: errors.response.data.error
                    })
                })
        },

        //Отчистить форму
        clearForm(){
            this.addOrUpdateLocation = 'add';

            //vform
            this.form.reset();
            this.form.clear();

            //Отчистить  форму и ошибки vee validate
            this.$refs.form.resetForm();

        },

        //Удалить локацию
       async deleteLocation(location_id, index){
            Swal.fire({
                title: 'Удалить',
                text: "Удалить данную локацию",
                showCancelButton: true,
                confirmButtonText: 'Да',
                cancelButtonText: 'Нет'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    this.query = true;

                    //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
                    this.checkInternetStore.checkInternet()

                    axios.delete('/KZLocation/delete/' + location_id)
                        .then(response=>{
                            this.query = false;

                            this.locations.splice(index, 1)
                            Toast.fire({
                                icon: 'success',
                                title: 'Удалена'
                            })
                        })
                        .catch(errors=>{
                            this.query = false;
                            Toast.fire({
                                icon: 'error',
                                title: errors.response.data.error
                            })
                        })

                }
            })


        }
    },

    async mounted(){
        let app = this;

        this.locationAnimation = true;

        document.querySelector(':root').classList.add('PATCH_modal'); //Отменим прокрутку за OffCanvas

        if(app.$route.params.locationId == 'null'){
            this.getLocations(null)
        }else{
            this.getLocations(app.$route.params.locationId)
        }

        // Инициализируем Яндекс карту - для доступа к геокодеру.
        await loadYmap(app.settings);
        app.ymapsGeocoder = ymaps;

    },

    beforeRouteLeave(to, from, next){
        this.locationAnimation = false;

        setTimeout(() => {
            next(); // Вызываем next() после завершения setTimeout - Для завершения анимации
        }, 350);
    }


}
</script>

<style>

/* Анимация для плавного появления -- Backdrop */
.location__animation-backdrop-enter-active,
.location__animation-backdrop-leave-active{
    transition: opacity 0.3s ease;
}

.location__animation-backdrop-enter-from,
.location__animation-backdrop-leave-to {
    opacity: 0;
}

.location__animation-backdrop-enter-to,
.location__animation-backdrop-leave-from {
    opacity: 1;
}

/* Анимация для плавного появления -- Wrapper */
.location__animation-wrapper-enter-active,
.location__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}


.location__animation-wrapper-enter-from{
    transform: translateX(-50%); /* Начальное положение при анимации скрытия */
}

.location__animation-wrapper-leave-to {
    transform: translateX(-100%); /* Начальное положение при анимации скрытия */
}

.location__animation-wrapper-enter-to,
.location__animation-wrapper-leave-from {
    transform: translateY(0);/* Конечное положение при анимации появления */
}

</style>

<style scoped>

/* Backdrop */
.location__backdrop{
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: 0;
}

/* Этот класс добавиться если мы будем на страницы добавления а не Filter иначе там двойное затемнение фона */
.location__wrapper--addAds{
    background: rgba(0,0,0,0.5);
}

.location__wrapper{
    position: fixed;
    top: 0;
    bottom:0;
    right: auto;
    left: 0;
    width: 100%;
    max-width: 450px;
    background: #ffffff;
}

.location__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
}

.location__header{
    display: flex;
    align-items: center;
    background: var(--app-header-bg-color);
    color: #ffffff;
}

.location__body{
    flex-grow: 1;
    overflow-y: scroll;

}

.location__footer{
    padding: 5px 20px;
}

.form-group{
    margin-top: 10px;
}
</style>
