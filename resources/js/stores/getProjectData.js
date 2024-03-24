import { defineStore } from 'pinia'
import {useAuthStore} from "./auth";

export const useGetProjectDataStore = defineStore('getProjectData', {

    //Свойства
    state: ()=>({
        COUNT_RETURN_ADS: 0, //Для автора сколько на дороботке или с жалобами
    }),

    //Получаем доступ к свойствам
    getters: {
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

                    this.COUNT_RETURN_ADS = response.data.countReturnAds;

                    //Зацикливаю вызов данной функции если пользователь онлайн
                    if(useAuthStore().check){
                        setTimeout(function(){
                            useGetProjectDataStore().getProjectData();
                        }, 30000);
                    }

                })
        },

    },
} )

// Вызываем метод getProjectData
window.onload = function() {
    useGetProjectDataStore().getProjectData();
};
