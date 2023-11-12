import { defineStore } from 'pinia'

import {useAuthStore} from "./auth";

export const useGetProjectDataStore = defineStore('getProjectData', {

    //Свойства
    state: ()=>({
        COUNT_NEW_MESSSAGE: 0, //Для автора
        COUNT_RETURN_ADS: 0, //Для автора сколько на дороботке или с жалобами
    }),

    //Получаем доступ к свойствам
    getters: {
        countNewMessage: ( state )=> state.COUNT_NEW_MESSAGE,
        countReturnAds: ( state )=> state.COUNT_RETURN_ADS,
    },

    actions: {

        getProjectData(){
            axios.get('/getProjectData', {
                params: {
                    user_id: useAuthStore().user.id,
                    role: useAuthStore().user.role
                }
            })
                .then(response=>{

                    this.COUNT_NEW_MESSAGE = response.data.countNewMessage;
                    this.COUNT_RETURN_ADS = response.data.countReturnAds;

                    //Зацикливаю вызов данной функции если пользователь онлайн
                    if(useAuthStore().check){
                        setTimeout(function(){
                            useGetProjectDataStore().getProjectData();
                        }, 30000);
                    }

                })
                .catch(errors=>{

                    //Зацикливаю вызов данной функции если пользователь онлайн
                    if(useAuthStore().check){
                        setTimeout(function(){
                            this.getProjectData();
                        }, 30000);
                    }
                })
        },

        //Обновить колличество новых смс
        updateCountNewMessage(count){
            this.COUNT_NEW_MESSAGE -= count;
        },

    }

} )

