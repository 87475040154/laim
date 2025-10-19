import { defineStore } from 'pinia'
import { useUpdateDateLocaleStore } from "./updateDateLocale"

export const useCheckInternetStore = defineStore('checkInternet', {
    state: () => ({
        ONLINE: true,
        SHOW_ALERT: false,
        MESSAGE: '',
        CHECK_INTERVAL: null,
        FAIL_COUNT: 0,
    }),

    getters: {
        online: (state) => state.ONLINE,
        showAlert: (state) => state.SHOW_ALERT,
        message: (state) => state.MESSAGE,
    },

    actions: {
        // 🔄 Запуск проверки каждые 15 секунд
        startAutoCheck() {
            this.checkInternet()
            this.CHECK_INTERVAL = setInterval(() => {
                this.checkInternet()
            }, 15000)
        },

        // 🚨 Проверка интернет-соединения
        async checkInternet() {
            const lang = useUpdateDateLocaleStore().lang

            // 1️⃣ Проверяем статус браузера
            if (!navigator.onLine) {
                this.setOffline(lang, 'offline')
                return
            }

            // 2️⃣ Проверяем доступ к ping.txt (настоящий интернет)
            try {
                const res = await fetch('/ping.txt?_=' + Date.now(), { cache: 'no-store' })

                if (res.ok) {
                    this.FAIL_COUNT = 0
                    this.ONLINE = true
                    this.SHOW_ALERT = false
                } else {
                    this.FAIL_COUNT++
                    if (this.FAIL_COUNT >= 3) this.setOffline(lang, 'server')
                }
            } catch {
                this.FAIL_COUNT++
                if (this.FAIL_COUNT >= 3) this.setOffline(lang, 'server')
            }
        },

        setOffline(lang, type) {
            this.ONLINE = false
            this.SHOW_ALERT = true

            if (type === 'offline') {
                if (lang === 'ru') this.MESSAGE = 'Нет интернет соединения!'
                else if (lang === 'kz') this.MESSAGE = 'Интернет байланысы жоқ!'
                else this.MESSAGE = 'No internet connection!'
            } else {
                if (lang === 'ru') this.MESSAGE = 'Сервер не отвечает! Ведутся технические работы.'
                else if (lang === 'kz') this.MESSAGE = 'Сервер жауап бермейді! Техникалық жұмыстар жүргізілуде.'
                else this.MESSAGE = 'Server is not responding! Technical work is in progress.'
            }
        },

        showAlertBlock(type) {
            this.SHOW_ALERT = type
        },
    },
})
