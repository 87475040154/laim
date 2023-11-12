import { defineStore } from 'pinia'
import { useUpdateDateLocaleStore } from "./updateDateLocale";

export const useCheckInternetStore = defineStore('checkInternet', {

    //Свойства
    state: ()=>({
        ONLINE: true,
        SHOW_ALERT: false,
        MESSAGE: ''
    }),

    //Получаем доступ к свойствам
    getters: {
        online: ( state )=> state.ONLINE,
        showAlert: ( state )=> state.SHOW_ALERT,
        message: ( state )=> state.MESSAGE
    },

    actions: {

        // Проверить интернет
        checkInternet(){
            const lang = useUpdateDateLocaleStore().lang;

            // Сначала проверьте наличие интернет-соединения через navigator.onLine
            if (navigator.onLine) {
                axios.get('/checkInternet').then((response) => {
                    this.ONLINE = true;
                    this.SHOW_ALERT = false;
                    return true;
                }).catch((errors) => {
                    this.ONLINE = false;
                    this.SHOW_ALERT = true;

                    if(errors.code == 'ERR_NETWORK'){
                        if(lang === 'ru'){
                            this.MESSAGE = 'Нет интернет соединения!';
                        } else if(lang === 'kz'){
                            this.MESSAGE = 'Интернет байланысы жоқ!';
                        } else if(lang === 'en'){
                            this.MESSAGE = 'No internet connection!';
                        }
                    }
                    else{
                        if (lang === 'ru') {
                            this.MESSAGE = 'Сервер не отвечает! Ведутся технические работы.';
                        } else if (lang === 'kz') {
                            this.MESSAGE = 'Сервер жауап бермейді! Техникалық жұмыстар жүргізілуде.';
                        } else if (lang === 'en') {
                            this.MESSAGE = 'Server is not responding! Technical work is in progress.';
                        }
                    }
                    return;
                })
            }
            else{
                this.ONLINE = false;
                this.SHOW_ALERT = true;

                // Текст ошибки в зависимости от локали
                if(lang === 'ru'){
                    this.MESSAGE = 'Нет интернет соединения! Или ведутся технические работы.';
                } else if(lang === 'kz'){
                    this.MESSAGE = 'Интернет байланысы жоқ! Немесе техникалық жұмыстар жүргізілуде.';
                } else if(lang === 'en'){
                    this.MESSAGE = 'No internet connection! Or technical work is in progress.';
                }
                return;
            }
        },

        //Закрыть алерт в AppComponent.vue
        showAlertBlock(type){
            this.SHOW_ALERT = type;
        }
    }

} )


