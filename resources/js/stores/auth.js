import { defineStore } from 'pinia'
import axios from 'axios'

import router from '../router/index'

import { useGetProjectDataStore } from "./getProjectData";

export const useAuthStore = defineStore('auth', {

    //Свойства
    state: ()=>({
        CHECK_AUTH : false,
        USER_DATA : {},

        DESKTOP_OR_MOBILE: ''
    }),

    //Получаем доступ к свойствам
    getters: {
        check: ( state )=> state.CHECK_AUTH,
        user: ( state )=> state.USER_DATA,

        desktopOrMobile: ( state )=> state.DESKTOP_OR_MOBILE,
    },

    actions: {

        //Получим данные авторизованного пользователя если он онлайн
        async getUser(){
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.get('/auth/user')
                    .then(response=>{
                        this.CHECK_AUTH = true;
                        this.USER_DATA = response.data.user;

                        //Получаем данные проекта
                        useGetProjectDataStore().getProjectData();
                    })
                    .catch(errors=>{
                        this.CHECK_AUTH = false;
                        this.USER_DATA = {};
                    })
            }).catch(errors=>{
                this.CHECK_AUTH = false;
                this.USER_DATA = {};
            })

        },

        //Выход пользователя
        async logout(){

            try {
                // Отправить запрос на сервер для завершения сеанса (если это необходимо)
                await axios.post('/auth/logout');

                // Очистить данные сеанса на стороне клиента
                // this.$session.clear(); // Очищает все данные сеанса

                this.CHECK_AUTH = false;
                this.USER_DATA = {};

                // Перенаправить пользователя на главную страницу
                router.push('/allAds/Kvartira/1');

            } catch (error) {
                // Обработать ошибку, если есть
                console.error('Ошибка выхода из системы', error);
            }
        },

        //Определяем Устройство - Mobile или Desktop
        desktopOrMob(type){
            this.DESKTOP_OR_MOBILE = type;
        }
    },

    persist: {
        enabled: true,
        strategies: [
            { storage: sessionStorage, paths: ['CHECK_AUTH','USER_DATA'] },
        ],
    },

} )

