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

                    <!-- Кнопки номер телефона и Whatsapp-->
                    <div v-if="$route.params.type=='Позвонить'" class="callBtn__block">

                        <div class="row">
                            <a :href="'https://api.whatsapp.com/send?phone='+ads.tel+'&text=' + SiteDomain +'/allAds/'+ $route.params.table_name + '/allAdsOneAds/' + $route.params.ads_id" class="btn wa">
                                {{ $t('oneAdsBottomOffCanvasGoToWA') }}
                                <i class="bi bi-whatsapp"></i>
                            </a>

                            <a :href="'tel:'+ ads.tel" class="btn tel">
                                <i class="bi bi-telephone-fill"></i>
                                <small>{{ ads.tel }}</small>
                            </a>
                        </div>

                        <div class="row">
                            <a v-if="ads.tel2" :href="'https://api.whatsapp.com/send?phone='+ads.tel2+'&text=' + SiteDomain +'/allAds/'+ $route.params.table_name + '/allAdsOneAds/' + $route.params.ads_id" class="btn wa">
                                {{ $t('oneAdsBottomOffCanvasGoToWA') }}
                                <i class="bi bi-whatsapp"></i>
                            </a>

                            <a v-if="ads.tel2" :href="'tel:'+ ads.tel2" class="btn tel">
                                <i class="bi bi-telephone-fill"></i>
                                <small>{{ ads.tel2 }}</small>
                            </a>
                        </div>

                    </div>

                    <!-- Отправить жалобы -->
                    <div v-if="$route.params.type=='Пожаловаться'" role="button">

                        <div @click="addComplain('Ответил риелтор')" class="border-bottom p-2 py-3">{{ $t('oneAdsBottomOffCanvasTheRealtorReplied') }}</div>
                        <div @click="addComplain('Объявление не актуально')" class="border-bottom p-2 pb-3">{{ $t('oneAdsBottomOffCanvasTheAdIsNotRelevant') }}</div>
                        <div @click="addComplain('Ошибка в цене')" class="border-bottom p-2 py-3">{{ $t('oneAdsBottomOffCanvasPriceError') }}</div>
                        <div @click="addComplain('Некорректные фотографии')" class="border-bottom p-2 py-3">{{ $t('oneAdsBottomOffCanvasIncorrectPhotos') }}</div>
                        <div @click="addComplain('Телефон не отвечает')" class="border-bottom p-2 py-3">{{ $t('oneAdsBottomOffCanvasThePhoneIsNotAnswering') }}</div>
                        <div @click="addComplain('Обман или ложное объявление')" class="p-2 py-3">{{ $t('oneAdsBottomOffCanvasDeceptionOrFalseAnnouncement') }}</div>

                    </div>

                    <!-- Скачать или поделиться фото -->
                    <div v-if="$route.params.type=='Скачать или поделиться фото'">

                        <!-- Ссылка счакать фото -->
                        <div @click="downloadImage" class="border-bottom p-2 pb-3" role="button">
                            <i class="bi bi-cloud-arrow-down mx-1"></i>
                            {{ $t('oneAdsBottomOffCanvasSaveToDevice') }}
                        </div>

                        <!-- Ссылка поделиться фото -->
                        <div @click="shareImage" class="p-2 py-3" role="button">
                            <i class="bi bi-share mx-1"></i>
                            {{ $t('oneAdsBottomOffCanvasToShare') }}
                        </div>

                    </div>

                    <!-- Поделиться объявлением -->
                    <div v-if="$route.params.type=='Поделиться объявлением'">

                        <!-- Поделиться с номером телефона автора -->
                        <div @click="linkShare" class="border-bottom p-2 pb-3" role="button">
                            <i class="bi bi-share mx-1"></i>
                            {{ $t('oneAdsBottomOffCanvasToShare') }}
                        </div>

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

.oneAdsBottom__body{
    flex-grow: 1;
    width: 100%;
    font-size: 1.1em;
    padding-bottom: 20px;
}
.callBtn__block {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}
.row {
    display: flex;
    gap: 8px;
}
.btn {
    width: 170px;
    padding: 12px;
    border-radius: 6px;
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}
.wa {
    background: #10a37f;
}
.tel {
    background: #2563eb;
}

/*При экранее более 992px */
@media screen and  (min-width: 992px) {
    .oneAdsBottom__block{
        margin-right: 10px;
    }
}

</style>
<!--355-->
