import { defineStore } from 'pinia'

export const useFilterStore = defineStore('filter', {

    //Свойства
    state: ()=>({
        MAKE_FILTER: false
    }),

    //Получаем доступ к свойствам
    getters: {
        make_filter: ( state )=> state.MAKE_FILTER
    },

    actions: {
        makeFilter(){
            this.MAKE_FILTER = !this.MAKE_FILTER
        }
    }

} )

