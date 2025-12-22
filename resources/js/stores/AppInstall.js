import { defineStore } from 'pinia'


export const useAppInstallStore = defineStore('AppInstall', {

    //Свойства
    state: ()=>({
        APP: '',
    }),

    //Получаем доступ к свойствам
    getters: {
        app: ( state )=> state.APP,
    },

    actions: {

        //Занесем установшик
        appInstallerAdd(app){
            this.APP = app;
        },

        //Установка
        async install(){
            this.APP.prompt(); //Показать окно установки
            const { outcome } = await this.APP.userChoice;
            this.APP =  '';
        },

    }

} )

