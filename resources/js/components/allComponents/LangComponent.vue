<template>

    <!-- Затемнённый фон под модальным окном -->
    <transition name="lang__animation-backdrop">
        <div v-if="langAnimation" class="lang__backdrop" @click="$router.back()"></div>
    </transition>

    <!-- Обёртка модального окна с анимацией -->
    <transition name="lang__animation-wrapper">
        <div v-if="langAnimation" class="lang__wrapper">

            <!-- Основной блок модалки -->
            <div class="lang__block">

                <!-- Header: заголовок и кнопка закрытия -->
                <div class="lang__header">
                    <h4>{{ $t('choose_language') }}</h4>
                    <button class="lang__close" @click="$router.back()" aria-label="Close">✕</button>
                </div>

                <!-- Body: список языков -->
                <div class="lang__body">
                    <div class="lang__links-block">

                        <!-- Одна языковая ссылка -->
                        <div
                            v-for="item in languages"
                            :key="item.code"
                            class="lang__link"
                            @click="changeLang(item.code)"
                        >
                            <img :src="item.img" :alt="item.alt" width="24" />
                            <span class="lang__link-text px-2" :class="{ 'text-blue': lang === item.code }">
              {{ item.name }}
            </span>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </transition>

</template>


<script setup>
// Vue
import { ref, onMounted } from 'vue'
import { onBeforeRouteLeave, useRouter } from 'vue-router'

const router = useRouter()

// Мультиязык
import { loadLanguageAsync } from 'laravel-vue-i18n'
import moment from 'moment'

// Store
import { useUpdateDateLocaleStore } from '../../stores/updateDateLocale'

// vee-validate i18n
import { setLocale } from '@vee-validate/i18n'

// Store
const updateDateLocale = useUpdateDateLocaleStore()

// State
const lang = ref('ru')
const langAnimation = ref(false)
const languages = [
    { code: 'kz', name: 'Қазақ', img: '/img/siteImg/allImg/kz.png', alt: 'Kazakhstan' },
    { code: 'ru', name: 'Русский', img: '/img/siteImg/allImg/ru.png', alt: 'Russia' },
    { code: 'en', name: 'English', img: '/img/siteImg/allImg/en.png', alt: 'United Kingdom' }
]

// Methods
const changeLang = async (code) => {
    await getLang(code)
    router.back()
}

const momentMap = { kz: 'kk', ru: 'ru', en: 'en-gb' }
const getLang = async (newLang) => {
    await loadLanguageAsync(newLang)
    lang.value = newLang
    localStorage.setItem('lang', newLang)
    updateDateLocale.updateLang(newLang)
    setLocale(newLang)
    moment.locale(momentMap[newLang] || 'ru')
}

// Lifecycle
onMounted(() => {
    langAnimation.value = true

    document.querySelector(':root').classList.add('PATCH_modal')

    const savedLang = localStorage.getItem('lang')
    if (savedLang) {
        getLang(savedLang)
    }
})

// Route Leave (анимация)
onBeforeRouteLeave((to, from, next) => {
    langAnimation.value = false

    if (to.name === 'allAds') {
        document.querySelector(':root').classList.remove('PATCH_modal')
    }

    setTimeout(() => {
        next()
    }, 350)
})
</script>
<style>

/* Анимация -- Backdrop */
.lang__animation-backdrop-enter-active,
.lang__animation-backdrop-leave-active{
    transition: opacity 0.3s ease;
}

.lang__animation-backdrop-enter-from,
.lang__animation-backdrop-leave-to    {
    opacity: 0;
}

.lang__animation-backdrop-enter-to,
.lang__animation-backdrop-leave-from{
    opacity: 1;
}

/* Анимация для -- Wrapper */
.lang__animation-wrapper-enter-active,
.lang__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}

.lang__animation-wrapper-enter-from{
    transform: translateY(50%); /* Начальное положение при анимации скрытия */
}

.lang__animation-wrapper-leave-to {
    transform: translateY(100%); /* Начальное положение при анимации скрытия */
}

.lang__animation-wrapper-enter-to,
.lang__animation-wrapper-leave-from {
    transform: translateY(0);/* Конечное положение при анимации появления */
}

</style>

<style scoped>
/* ============================
   Backdrop - затемнённый фон
============================ */
.lang__backdrop {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.8);
    z-index: 3;
}

/* ============================
   Wrapper - центрирует модалку
============================ */
.lang__wrapper {
    position: fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    bottom: 0;
    left: 0;
    width: 100%;
    height: auto;
    max-height: 100%;
    overflow-y: auto;
    user-select: none;
    z-index: 3;
}

/* ============================
   Модальный блок
============================ */
.lang__block {
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 800px;
    border-radius: 10px 10px 0 0;
    background: #ffffff;
}

/* ============================
   Header - заголовок и кнопка
============================ */
.lang__header {
    position: relative;
    text-align: center;
    padding: 20px;
}

.lang__close {
    position: absolute;
    top: 10px;
    right: 10px;
    background: transparent;
    border: none;
    font-size: 20px;
    cursor: pointer;
    line-height: 1;
    padding: 4px;
}

.lang__close:hover {
    opacity: 0.7;
}

/* ============================
   Body - содержимое модалки
============================ */
.lang__body {
    flex-grow: 1;
    width: 100%;
    font-size: 1.1em;
    padding-bottom: 20px;
}

.lang__links-block {
    padding: 10px;
}

/* ============================
   Ссылка на язык
============================ */
.lang__link {
    color: #2a2a2a;
    padding: 15px;
    display: flex;
    align-items: center;
    border-radius: 10px;
    transition: background 0.2s;
}

.lang__link:hover {
    background: rgba(0, 0, 0, 0.1);
    cursor: pointer;
}

.lang__link-text {
    font-size: 1.1em;
    margin-left: 8px; /* небольшой отступ между флагом и текстом */
}

/* ============================
   Адаптивность
============================ */
@media screen and (min-width: 992px) {
    .lang__block {
        margin-right: 10px;
    }
}
</style>
<!--272-->
