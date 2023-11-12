import { defineStore } from 'pinia'

export const useAdsStore = defineStore('ads', {

    //Свойства
    state: ()=>({
        ADS : {
            index: '',
            ads: {},
        }
    }),

    //Получаем доступ к свойствам
    getters: {
        ads: ( state )=> state.ADS
    },

    actions: {

        //Занесем объявление
        addAds(data){
            this.ADS.ads = data.ads;
            this.ADS.index = data.index;
        }
    }

} )

