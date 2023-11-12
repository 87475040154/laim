import { defineStore } from 'pinia'


export const useAppInstallStore = defineStore('AppInstall', {

    //Свойства
    state: ()=>({
        APP: '',
        THEME: ''
    }),

    //Получаем доступ к свойствам
    getters: {
        app: ( state )=> state.APP,
        theme: ( state )=> state.THEME,
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

        // Изменить тему
        changeTheme(){
            this.THEME  =  this.THEME == '' ? 'dark-theme': '';
        }

    }

} )

