import { defineStore } from 'pinia'
export const useImagesStore = defineStore('images', {

    //Свойства
    state: ()=>({
        INDEX: '',
        IMAGES: [],
    }),

    //Получаем доступ к свойствам
    getters: {
        index: ( state )=> state.INDEX,
        images: ( state )=> state.IMAGES,
    },

    actions: {

        //Открыть фото при клике на фото
        showImages(data){
            this.IMAGES = [];

            //Если открываю фото с превью или с фото
            if(data.allImg != undefined){
                data.images.forEach(img=>{
                    let obj = {};
                    obj.previewImg = '/img/adsImg/' + img
                    this.IMAGES.push(obj)
                })
            }

            //Если открываю фото с чатов
            if(data.chatImg != undefined){
                data.images.forEach(img=>{
                    let obj = {};
                    obj.previewImg = '/img/messageImg/' + img
                    this.IMAGES.push(obj)
                })
            }

            //Если открываю фото с Загрузки Фото
            if(data.addAdsImg != undefined){
                data.images.forEach(img=>{
                    let obj = {};
                    if(img.previewImg.substr(0, 3 == 'blob')){
                        obj.previewImg = '/img/adsImg/' + img
                    }
                    else{
                        obj.previewImg = img.previewImg
                    }

                    this.IMAGES.push(obj)
                })
            }

            this.INDEX = data.index;
        },

        //Удалить все фото
        deleteAll(){
            this.IMAGES = [];
        }

    },

    persist: {
        enabled: true,
        strategies: [
            { storage: sessionStorage, paths: ['INDEX', 'IMAGES',] },
        ],
    },

} )
