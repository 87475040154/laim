import { defineStore } from 'pinia'

import { loadLanguageAsync } from 'laravel-vue-i18n'
import { setLocale } from '@vee-validate/i18n'
import moment from 'moment'

// ВАЖНО: импорт локалей
import 'moment/locale/ru'
import 'moment/locale/kk'

const momentMap = { ru: 'ru', kz: 'kk' }// moment использует kk

export const useLangStore = defineStore('lang', {

    //Свойства
    state: ()=>({
        LANG : 'ru',
        initialized: false,
    }),

    //Получаем доступ к свойствам
    getters: {
        lang: ( state )=> state.LANG
    },

    actions: {

        // Установить конкретный язык
        async setLang(lang) {
            if (this.initialized && this.LANG === lang) return
            await loadLanguageAsync(lang)
            this.LANG = lang
            this.initialized = true
            setLocale(lang)
            moment.locale(momentMap[lang] ?? 'ru')
        },

        // Toggle ru <-> kz
        async toggleLang() {
            await this.setLang(this.LANG === 'ru' ? 'kz' : 'ru')
        },


    },

    persist: {
        enabled: true,
        strategies: [
            { storage: localStorage, paths: ['LANG'] },
        ],
    },

} )

