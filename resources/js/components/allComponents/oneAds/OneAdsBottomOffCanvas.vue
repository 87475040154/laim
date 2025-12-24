<template>
    <!-- КОМПОНЕНТ - ВЫВОД ИНФОРМАЦИЯ ДЛЯ ОНДНОГО ОБЪЯВЛЕНИЯ ВНИЗУ СТРАНИЦЫ  -->

    <!-- Backdrop - Затемнённый фон под модальным окном  -->
    <transition name="oneAdsBottom__animation-backdrop">

        <div v-if="oneAdsBottomAnimation" class="oneAdsBottom__backdrop" @click="$router.back()"></div>

    </transition>

    <!-- Обвертка - Компонента -->
    <transition name="oneAdsBottom__animation-wrapper">

        <!-- Обвертка - Компонента -->
        <div v-if="oneAdsBottomAnimation" class="oneAdsBottom__wrapper">

            <!-- Сам блок -->
            <div class="oneAdsBottom__block">

                <!-- Header - Заголовок | Имя автора | причина жалобы и тд. -->
                <div class="oneAdsBottom__header">

                    <!-- Заголовок - Имя автора | Причина жалобы и тд. -->
                    <h4 v-if="$route.params.type == 'Позвонить' ">{{ads.name}}</h4>
                    <h4 v-if="$route.params.type == 'Пожаловаться'">{{ $t('oneAdsBottomOffCanvasCallReasonForComplaint') }}</h4>
                    <h4 v-if="$route.params.type == 'Скачать или поделиться фото'">{{ $t('oneAdsBottomOffCanvasDownloadPhotos') }}</h4>
                    <h4 v-if="$route.params.type == 'Поделиться объявлением'">{{ $t('oneAdsBottomOffCanvasShareAnAd') }}</h4>

                    <!-- Кнопка закрыть блок -->
                    <button class="btn__close" @click="$router.back()" aria-label="Close">✕</button>

                </div>

                <!-- Body -->
                <div class="oneAdsBottom__body">

                    <!-- Кнопки: Телефон и WhatsApp -->
                    <div v-if="$route.params.type==='Позвонить'" class="call-btn-block">
                        <div class="row">
                            <a v-if="ads.whatsapp_tel != 0" :href="`https://api.whatsapp.com/send?phone=${ads.tel}&text=${SiteDomainLocal}/allAds/${$route.params.table_name}/allAdsOneAds/${$route.params.ads_id}`" class="btn wa">
                                <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 22L3.41152 16.8691C2.54422 15.3639 2.08876 13.6568 2.09099 11.9196C2.08095 6.44549 6.52644 2 11.99 2C14.6417 2 17.1315 3.02806 19.0062 4.9034C19.9303 5.82266 20.6627 6.91616 21.1611 8.12054C21.6595 9.32492 21.9139 10.6162 21.9096 11.9196C21.9096 17.3832 17.4641 21.8287 12 21.8287C10.3368 21.8287 8.71374 21.4151 7.26204 20.6192L2 22ZM7.49424 18.8349L7.79675 19.0162C9.06649 19.7676 10.5146 20.1644 11.99 20.1654C16.5264 20.1654 20.2263 16.4662 20.2263 11.9291C20.2263 9.73176 19.3696 7.65554 17.8168 6.1034C17.0533 5.33553 16.1453 4.72636 15.1453 4.31101C14.1452 3.89565 13.0728 3.68232 11.99 3.68331C7.44343 3.6839 3.74476 7.38316 3.74476 11.9202C3.74476 13.4724 4.17843 14.995 5.00502 16.3055L5.19645 16.618L4.35982 19.662L7.49483 18.8354L7.49424 18.8349Z" fill="currentColor"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.52024 7.76662C9.33885 7.35303 9.13737 7.34298 8.96603 7.34298C8.81477 7.33294 8.65288 7.33294 8.48154 7.33294C8.32083 7.33294 8.04845 7.39321 7.81684 7.64549C7.58464 7.89719 6.95007 8.49217 6.95007 9.71167C6.95007 10.9318 7.83693 12.1111 7.95805 12.2724C8.07858 12.4337 9.67149 15.0139 12.192 16.0124C14.2883 16.839 14.712 16.6777 15.1657 16.6269C15.6189 16.5767 16.6275 16.0325 16.839 15.4476C17.0405 14.8733 17.0405 14.3693 16.9802 14.2682C16.9199 14.1678 16.748 14.1069 16.5064 13.9758C16.2541 13.8552 15.0446 13.2502 14.813 13.1693C14.5808 13.0889 14.4195 13.0487 14.2582 13.2904C14.0969 13.5427 13.623 14.0969 13.4724 14.2582C13.3306 14.4195 13.1799 14.4396 12.9377 14.3185C12.686 14.1979 11.8895 13.9356 10.9418 13.0889C10.2056 12.4331 9.71167 11.6171 9.56041 11.3755C9.41979 11.1232 9.54032 10.992 9.67149 10.8709C9.78257 10.7604 9.92378 10.579 10.0449 10.4378C10.1654 10.296 10.2056 10.1855 10.2966 10.0242C10.377 9.86292 10.3368 9.71167 10.2765 9.59114C10.2157 9.48006 9.74239 8.25997 9.52024 7.76603V7.76662Z" fill="currentColor"/>
                                </svg>
                                {{ $t('oneAdsBottomOffCanvasGoToWA') }}
                            </a>
                            <a :href="`tel:${ads.tel}`" class="btn tel">
                                <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 5.5C3 14.0604 9.93959 21 18.5 21C18.8862 21 19.2691 20.9859 19.6483 20.9581C20.0834 20.9262 20.3009 20.9103 20.499 20.7963C20.663 20.7019 20.8185 20.5345 20.9007 20.364C21 20.1582 21 19.9181 21 19.438V16.6207C21 16.2169 21 16.015 20.9335 15.842C20.8749 15.6891 20.7795 15.553 20.6559 15.4456C20.516 15.324 20.3262 15.255 19.9468 15.117L16.74 13.9509C16.2985 13.7904 16.0777 13.7101 15.8683 13.7237C15.6836 13.7357 15.5059 13.7988 15.3549 13.9058C15.1837 14.0271 15.0629 14.2285 14.8212 14.6314L14 16C11.3501 14.7999 9.2019 12.6489 8 10L9.36863 9.17882C9.77145 8.93713 9.97286 8.81628 10.0942 8.64506C10.2012 8.49408 10.2643 8.31637 10.2763 8.1317C10.2899 7.92227 10.2096 7.70153 10.0491 7.26005L8.88299 4.05321C8.745 3.67376 8.67601 3.48403 8.55442 3.3441C8.44701 3.22049 8.31089 3.12515 8.15802 3.06645C7.98496 3 7.78308 3 7.37932 3H4.56201C4.08188 3 3.84181 3 3.63598 3.09925C3.4655 3.18146 3.29814 3.33701 3.2037 3.50103C3.08968 3.69907 3.07375 3.91662 3.04189 4.35173C3.01413 4.73086 3 5.11378 3 5.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <small>{{ ads.tel }}</small>
                            </a>
                        </div>

                        <div class="row" v-if="ads.tel2">
                            <a v-if="ads.whatsapp_tel2 != 0" :href="`https://api.whatsapp.com/send?phone=${ads.tel2}&text=${SiteDomainLocal}/allAds/${$route.params.table_name}/allAdsOneAds/${$route.params.ads_id}`" class="btn wa">
                                <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 22L3.41152 16.8691C2.54422 15.3639 2.08876 13.6568 2.09099 11.9196C2.08095 6.44549 6.52644 2 11.99 2C14.6417 2 17.1315 3.02806 19.0062 4.9034C19.9303 5.82266 20.6627 6.91616 21.1611 8.12054C21.6595 9.32492 21.9139 10.6162 21.9096 11.9196C21.9096 17.3832 17.4641 21.8287 12 21.8287C10.3368 21.8287 8.71374 21.4151 7.26204 20.6192L2 22ZM7.49424 18.8349L7.79675 19.0162C9.06649 19.7676 10.5146 20.1644 11.99 20.1654C16.5264 20.1654 20.2263 16.4662 20.2263 11.9291C20.2263 9.73176 19.3696 7.65554 17.8168 6.1034C17.0533 5.33553 16.1453 4.72636 15.1453 4.31101C14.1452 3.89565 13.0728 3.68232 11.99 3.68331C7.44343 3.6839 3.74476 7.38316 3.74476 11.9202C3.74476 13.4724 4.17843 14.995 5.00502 16.3055L5.19645 16.618L4.35982 19.662L7.49483 18.8354L7.49424 18.8349Z" fill="currentColor"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.52024 7.76662C9.33885 7.35303 9.13737 7.34298 8.96603 7.34298C8.81477 7.33294 8.65288 7.33294 8.48154 7.33294C8.32083 7.33294 8.04845 7.39321 7.81684 7.64549C7.58464 7.89719 6.95007 8.49217 6.95007 9.71167C6.95007 10.9318 7.83693 12.1111 7.95805 12.2724C8.07858 12.4337 9.67149 15.0139 12.192 16.0124C14.2883 16.839 14.712 16.6777 15.1657 16.6269C15.6189 16.5767 16.6275 16.0325 16.839 15.4476C17.0405 14.8733 17.0405 14.3693 16.9802 14.2682C16.9199 14.1678 16.748 14.1069 16.5064 13.9758C16.2541 13.8552 15.0446 13.2502 14.813 13.1693C14.5808 13.0889 14.4195 13.0487 14.2582 13.2904C14.0969 13.5427 13.623 14.0969 13.4724 14.2582C13.3306 14.4195 13.1799 14.4396 12.9377 14.3185C12.686 14.1979 11.8895 13.9356 10.9418 13.0889C10.2056 12.4331 9.71167 11.6171 9.56041 11.3755C9.41979 11.1232 9.54032 10.992 9.67149 10.8709C9.78257 10.7604 9.92378 10.579 10.0449 10.4378C10.1654 10.296 10.2056 10.1855 10.2966 10.0242C10.377 9.86292 10.3368 9.71167 10.2765 9.59114C10.2157 9.48006 9.74239 8.25997 9.52024 7.76603V7.76662Z" fill="currentColor"/>
                                </svg>
                                {{ $t('oneAdsBottomOffCanvasGoToWA') }}
                            </a>
                            <a :href="`tel:${ads.tel2}`" class="btn tel">
                                <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 5.5C3 14.0604 9.93959 21 18.5 21C18.8862 21 19.2691 20.9859 19.6483 20.9581C20.0834 20.9262 20.3009 20.9103 20.499 20.7963C20.663 20.7019 20.8185 20.5345 20.9007 20.364C21 20.1582 21 19.9181 21 19.438V16.6207C21 16.2169 21 16.015 20.9335 15.842C20.8749 15.6891 20.7795 15.553 20.6559 15.4456C20.516 15.324 20.3262 15.255 19.9468 15.117L16.74 13.9509C16.2985 13.7904 16.0777 13.7101 15.8683 13.7237C15.6836 13.7357 15.5059 13.7988 15.3549 13.9058C15.1837 14.0271 15.0629 14.2285 14.8212 14.6314L14 16C11.3501 14.7999 9.2019 12.6489 8 10L9.36863 9.17882C9.77145 8.93713 9.97286 8.81628 10.0942 8.64506C10.2012 8.49408 10.2643 8.31637 10.2763 8.1317C10.2899 7.92227 10.2096 7.70153 10.0491 7.26005L8.88299 4.05321C8.745 3.67376 8.67601 3.48403 8.55442 3.3441C8.44701 3.22049 8.31089 3.12515 8.15802 3.06645C7.98496 3 7.78308 3 7.37932 3H4.56201C4.08188 3 3.84181 3 3.63598 3.09925C3.4655 3.18146 3.29814 3.33701 3.2037 3.50103C3.08968 3.69907 3.07375 3.91662 3.04189 4.35173C3.01413 4.73086 3 5.11378 3 5.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <small>{{ ads.tel2 }}</small>
                            </a>
                        </div>
                    </div>

                    <!-- Общие действия (жалобы, фото, шаринг) -->
                    <div v-if="$route.params.type==='Пожаловаться' || $route.params.type==='Скачать или поделиться фото' || $route.params.type==='Поделиться объявлением'" class="action-list">

                        <!-- Жалобы -->
                        <template v-if="$route.params.type==='Пожаловаться'">
                            <div @click="addComplain('Ответил риелтор')" class="action-item">{{ $t('oneAdsBottomOffCanvasTheRealtorReplied') }}</div>
                            <div @click="addComplain('Объявление не актуально')" class="action-item">{{ $t('oneAdsBottomOffCanvasTheAdIsNotRelevant') }}</div>
                            <div @click="addComplain('Ошибка в цене')" class="action-item">{{ $t('oneAdsBottomOffCanvasPriceError') }}</div>
                            <div @click="addComplain('Некорректные фотографии')" class="action-item">{{ $t('oneAdsBottomOffCanvasIncorrectPhotos') }}</div>
                            <div @click="addComplain('Телефон не отвечает')" class="action-item">{{ $t('oneAdsBottomOffCanvasThePhoneIsNotAnswering') }}</div>
                            <div @click="addComplain('Обман или ложное объявление')" class="action-item last">{{ $t('oneAdsBottomOffCanvasDeceptionOrFalseAnnouncement') }}</div>
                        </template>

                        <!-- Скачать / поделиться фото -->
                        <template v-if="$route.params.type==='Скачать или поделиться фото'">
                            <div @click="downloadImage" class="action-item">
                                <img src="/img/icons/download-photo-svgrepo-com.svg" alt="Download">
                                {{ $t('oneAdsBottomOffCanvasSaveToDevice') }}
                            </div>
                            <div @click="shareImage" class="action-item last">
                                <img src="/img/icons/share-svgrepo-com.svg" alt="Share">
                                {{ $t('oneAdsBottomOffCanvasToShare') }}
                            </div>
                        </template>

                        <!-- Поделиться объявлением -->
                        <template v-if="$route.params.type==='Поделиться объявлением'">
                            <div @click="linkShare" class="action-item">
                                <img src="/img/icons/share-svgrepo-com.svg" alt="Share">
                                {{ $t('oneAdsBottomOffCanvasToShare') }}
                            </div>
                        </template>

                    </div>

                </div>


            </div>

        </div>

    </transition>

    <!-- Дочерние компоненты -->
    <router-view></router-view>

</template>

<script setup>

// vue
import { ref, onMounted } from 'vue'
import { useRouter, onBeforeRouteLeave } from 'vue-router'

// router
const router = useRouter()

// stores
import { useAuthStore } from "../../../stores/auth"
import { useUpdateDateLocaleStore } from "../../../stores/updateDateLocale"


// stores init
const authStore = useAuthStore()
const updateDateLocale = useUpdateDateLocaleStore()

// state
const ads = ref('')
const oneAdsBottomAnimation = ref(false)
const query = ref(false)
const SiteDomainLocal = SiteDomain

// methods
const addComplain = async (complain) => {
    query.value = true

    try {
        await axios.post('/addComplain', {
            ads_id: ads.value.id,
            table_name: ads.value.table_name,
            user_id: authStore.user.id,
            complain
        })

        router.back()
        Toast.fire({
            title: app.config.globalProperties.$t('oneAdsBottomOffCanvasThanks'),
            text: app.config.globalProperties.$t('oneAdsBottomOffCanvasComplainText')
        })
    } catch (errors) {
        router.back()
        Toast.fire({
            title: app.config.globalProperties.$t('oneAdsBottomOffCanvasErrorText'),
            text: errors.response.data.error
        })
    } finally {
        query.value = false
    }
}

const downloadImage = async () => {
    ads.value.images.forEach(img => {
        const link = document.createElement('a')
        link.href = '/img/adsImg/' + img
        link.download = img
        link.click()
    })
}

const linkShare = async () => {
    navigator.share({
        url: SiteDomainLocal + '/allAds/' + ads.value.table_name + '/allAdsOneAds/' + ads.value.id
    })
}

const shareImage = async () => {
    const files = []

    for (const img of ads.value.images) {
        const response = await fetch('/img/adsImg/' + img)
        const blob = await response.blob()
        files.push(new File([blob], img, { type: blob.type }))
    }

    if (navigator.canShare({ files })) {
        await navigator.share({ files })
    }
}


// lifecycle
onMounted(() => {
    oneAdsBottomAnimation.value = true
    ads.value = JSON.parse(localStorage.getItem('oneAds'))
    document.documentElement.classList.add('PATCH_modal')
})

onBeforeRouteLeave((to, from, next) => {
    oneAdsBottomAnimation.value = false
    setTimeout(() => next(), 350)
})

</script>


<style>

/* Анимация -- Backdrop */
.oneAdsBottom__animation-backdrop-enter-active,
.oneAdsBottom__animation-backdrop-leave-active{
    transition: opacity 0.3s ease;
}

.oneAdsBottom__animation-backdrop-enter-from,
.oneAdsBottom__animation-backdrop-leave-to    {
    opacity: 0;
}

.oneAdsBottom__animation-backdrop-enter-to,
.oneAdsBottom__animation-backdrop-leave-from{
    opacity: 1;
}

/* Анимация для -- Wrapper */
.oneAdsBottom__animation-wrapper-enter-active,
.oneAdsBottom__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}

.oneAdsBottom__animation-wrapper-enter-from{
    transform: translateY(50%); /* Начальное положение при анимации скрытия */
}

.oneAdsBottom__animation-wrapper-leave-to {
    transform: translateY(100%); /* Начальное положение при анимации скрытия */
}

.oneAdsBottom__animation-wrapper-enter-to,
.oneAdsBottom__animation-wrapper-leave-from {
    transform: translateY(0);/* Конечное положение при анимации появления */
}

</style>

<style scoped>

/* Backdrop */
.oneAdsBottom__backdrop{
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: 0;
    background: rgba(0,0,0,0.5);
}

.oneAdsBottom__wrapper{
    position: fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    bottom:0;
    left: 0;
    width: 100%;
    height: auto;
    max-height: 100%;
    overflow-y: auto;
    user-select: none;
}

.oneAdsBottom__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    max-width: 800px;
    border-radius: 10px 10px 0 0;
    background: #ffffff;
}

.oneAdsBottom__header{
    position: relative;
    text-align: center;
    padding: 20px;
}
.btn__close {
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
.btn__close:hover {
    opacity: 0.7;
}

.oneAdsBottom__body {
    width: 100%;
    padding-bottom: 1.25rem; /* 20px */
    font-size: 1.1rem;
}

/* Кнопки Телефон / WhatsApp */
.call-btn-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem; /* 8px */
}
.row {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap; /* для мобильных */
}
.btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem; /* 6px */
    padding: 0.625rem 0.875rem; /* 10px 14px */
    min-width: 120px; /* адаптивная ширина */
    border-radius: 6px;
    color: #fff;
    text-decoration: none;
    font-size: 0.875rem; /* 14px */
}
.btn img {
    width: 16px;
    height: 16px;
    vertical-align: middle;
    flex-shrink: 0;
}

.wa {
    background-color: #10a37f;
    color: white
}
.tel {
    background-color: #2563eb;
    color: white;
}

/* Общие действия */
.action-list {
    display: flex;
    flex-direction: column;
    margin-top: 0.5rem;
}
.action-item {
    display: flex;
    align-items: center;
    gap: 0.375rem; /* 6px */
    padding: 0.875rem 0.75rem; /* 14px 12px */
    border-bottom: 1px solid #e5e7eb;
    cursor: pointer;
    font-size: 0.9375rem; /* 15px */
    transition: background 0.2s ease;
}
.action-item img {
    width: 16px;
    height: 16px;
    vertical-align: middle;
    flex-shrink: 0;
}
.action-item:hover {
    background: #f3f4f6;
}
.action-item.last {
    border-bottom: none;
}


/*При экранее более 992px */
@media screen and  (min-width: 992px) {
    .oneAdsBottom__block{
        margin-right: 10px;
    }
}

</style>
<!--355-->
