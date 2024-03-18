import { defineStore } from 'pinia'

export const useUpdateDateLocaleStore = defineStore('updateDateLocale', {

    //Свойства
    state: ()=>({
        LANG : 'ru'
    }),

    //Получаем доступ к свойствам
    getters: {
        lang: ( state )=> state.LANG
    },

    actions: {

        //Занесем объявление
        updateLang(lang){
           this.LANG = lang;
        }
    },

    persist: {
        enabled: true,
        strategies: [
            { storage: localStorage, paths: ['LANG'] },
        ],
    },

} )

