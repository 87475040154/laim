<template>

    <!-- Превью блок -->
    <div style="width: 100%;" ref="scrollParent">
        <div :style="{ height: totalSize + 'px', width: '100%', position: 'relative' }">
        <div
            v-for="virtualRow in virtualRows"
            :key="props.ads_arr[virtualRow.index].id || virtualRow.index"
            class="virtual-row"
            :style="{
                position: 'absolute',
                top: 0,
                left: 0,
                width: '100%',
                height: virtualRow.size + 'px',
                transform: 'translateY(' + virtualRow.start + 'px)'
             }"
        >
            <!-- Сам блок с превью -->
            <div class="mx-3 my-2 mx-sm-auto ads__preview" :style="{ minHeight: virtualRow.size + 'px' }">

                <div style="width: 100%; height: 150px; border: 2px solid black; background: #1e856f">

                </div>
            </div>

        </div>
    </div>
    </div>

    <!--    Управление объявлением-->
    <!-- Backdrop -->
    <transition name="adsPreviewControl__animation-backdrop">
        <div v-if="adsPreviewControlAnimation" class="adsPreviewControl__backdrop" @click="adsPreviewControlAnimation = false"></div>
    </transition>

    <!-- Обвертка - Компонента -->
    <transition name="adsPreviewControl__animation-wrapper">

        <!-- Обвертка - Компонента -->
        <div v-if="adsPreviewControlAnimation" class="adsPreviewControl__wrapper">

            <!-- Сам блок -->
            <div class="adsPreviewControl__block">

                <!-- Header -->
                <div class="adsPreviewControl__header">

                    <!-- Заголовок -->
                    <div v-if="controlBlockType == 'Управление' && !deleteAdsBlock">{{ $t('AdsPreviewAddControl') }}</div>
                    <div v-if="controlBlockType == 'Статистика' && !deleteAdsBlock">{{ $t('AdsPreviewAddStatistics') }}</div>
                    <div v-if="deleteAdsBlock">{{ $t('adsPreviewComponentSendToArchive') }}</div>

                    <!-- Кнопка назад -->
                    <v-btn icon dark variant="text"
                           @click="adsPreviewControlAnimation = false"
                           style="position: absolute; top: 0; right: 10px;"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>

                </div>

                <!-- Body -->
                <div class="adsPreviewControl__body text-center">

                    <!-- Редактировать - Остановить - Удалить -->
                    <div v-if="controlBlockType == 'Управление' && !deleteAdsBlock" class="text-center">

                        <!-- Кнопка - Рекламировать или Отановить объявление -->
                        <v-btn dark
                               color="blue-darken-2"
                               class="text-body-1 my-2"
                               size="x-large"
                               @click="adsActiveToggle( ads.id, ads.table_name, ads.control,index)"
                               :disabled="query"
                        >
                            {{ads.control == 'Активно' ? $t('adsPreviewComponentStop') : $t('adsPreviewComponentToAdvertise')}}
                        </v-btn>

                        <!-- Кнопка - Редактировать обьявление -->
                        <v-btn dark
                               color="blue-darken-2"
                               class="text-body-1 mx-2 my-2"
                               size="x-large"
                               @click="$router.push({name: $route.name + 'AddAds', params: {table_name: ads.table_name, id: ads.id, step:1}})"
                        >
                            <i class="bi bi-pencil-square"></i> {{ $t('adsPreviewComponentEdit') }}
                        </v-btn>

                        <!-- Кнопка - Удалить объявление -->
                        <v-btn dark
                               color="blue-darken-2"
                               class="text-body-1 my-2"
                               size="x-large"
                               @click="deleteAdsBlock = true"
                        >
                            <i class="bi bi-trash"></i>
                            {{ $t('adsPreviewComponentDelete') }}
                        </v-btn>
                    </div>

                    <!-- Блок статистика -->
                    <div v-if="controlBlockType == 'Статистика' && !deleteAdsBlock">
                        <div>{{ $t('AdsPreviewAddViewed') }} : <span class="fw-bold">{{ads.view}}</span></div>
                        <div class="mt-2">{{ $t('AdsPreviewAddGotTheNumber') }} : <span class="fw-bold">{{ads.viewTel}}</span></div>
                    </div>

                    <!-- блок подтвердить удаление объявления -->
                    <div v-if="deleteAdsBlock">
                        <v-btn dark
                               color="green"
                               class="text-body-1 m-2"
                               size="x-large"
                               @click="deleteAds( ads.id, ads.table_name, ads.control, index)"
                               :disabled="query">{{ $t('adsPreviewComponentYes') }}
                        </v-btn>
                        <v-btn dark
                               color="grey"
                               class="text-body-1 m-2"
                               size="x-large"
                               @click="deleteAdsBlock = false"
                        >
                            {{ $t('adsPreviewComponentNo') }}
                        </v-btn>
                    </div>

                </div>

            </div>

        </div>

    </transition>

</template>

<script setup>
import { ref,  reactive, computed, watch, onMounted,onUnmounted, onBeforeUnmount, nextTick } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useThrottleFn, useScroll, useDebounceFn  } from '@vueuse/core'
import axios from 'axios'
import { getCurrentInstance } from 'vue'

const { appContext } = getCurrentInstance()
const t = appContext.config.globalProperties.$t
const filters = appContext.config.globalProperties.$filters

// Импорт стора
import { useAuthStore } from "../../stores/auth"
import { useImagesStore } from "../../stores/images"
import { useAdsStore } from "../../stores/ads"
import { useUpdateDateLocaleStore } from "../../stores/updateDateLocale"
import { useKZLocationStore } from "../../stores/KZLocation"

import { useVirtualizer } from '@tanstack/vue-virtual'

const props = defineProps({
    ads_arr: {
        type: [Array, Object],
        required: true
    },
    getMyLikeAds: Boolean,
    parentQuery: Boolean,
    isLastLoad: Boolean
})

const emit = defineEmits(['get-ads'])

const router = useRouter()
const route = useRoute()

// Stores
const authStore = useAuthStore()
const imageStore = useImagesStore()
const adsStore = useAdsStore()
const updateDateLocale = useUpdateDateLocaleStore()
const KZLocationStore = useKZLocationStore()

// ------------------ STATE ------------------
const debounceTimer = ref(null)
const query = ref(false)

// Создаём виртуализатор ОДИН РАЗ в setup.
// count — computed, автоматически отслеживает длину ads_array
const scrollParent = ref(null)
// Используем computed, который обращается к props напрямую.
const rowVirtualizerOptions = computed(() => ({
    count: props.ads_arr.length,
    getScrollElement: () => document.documentElement,
    getItemKey: (i) => props.ads_arr[i]?.id || i,
    estimateSize: () => estimateSize.value,
    overscan: 6,
}))
// 🧩 Фиксированная высота по маршруту
const estimateSize = computed(() => {
    switch (route.name) {
        case 'allAds':       // пример: профиль пользователя
            return 170
        case 'userAds':      // пример: главная страница
            return 300
        default:
            return 170
    }
})
const rowVirtualizer = useVirtualizer(rowVirtualizerOptions)

const adsPreviewControlAnimation = ref(false)
const controlBlockType = ref('')
const ads = ref('')
const index = ref('')
const deleteAdsBlock = ref(false)

const ownerText = reactive({ ru: 'Хозяин', kz: 'Иесі', en: 'Owner' })
const topIcons = reactive([
    { key: 'top_x30', icon: 'mdi-crown', class: 'icon__crown' },
    { key: 'top_x7', icon: 'mdi-diamond', class: 'icon__diamond' },
    { key: 'top', icon: 'mdi-triangle', class: 'icon__triangle' },
    { key: 'top_8', icon: 'mdi-triangle', class: 'icon__triangle icon__triangleTOP8' }
])

// ------------------ COMPUTED ------------------
// Виртуальные элементы, которые должны быть отрисованы в данный момент
const virtualRows = computed(() => rowVirtualizer.value.getVirtualItems())

// Общая высота виртуализированного списка (нужна для корректного позиционирования элементов)
const totalSize = computed(() => rowVirtualizer.value.getTotalSize())

// Флаг, показывающий, выполняется ли сейчас прокрутка (true во время скролла)
const isScrolling = computed(() => rowVirtualizer.value.isScrolling)


// ------------------ METHODS ------------------


// Асинхронная функция для подгрузки новых объявлений при достижении конца списка
const getNewAds = async () => {
    // Если достигнут конец всех данных — выходим
    if (props.isLastLoad) {
        return
    }

    // Проверяем, находится ли прокрутка внизу страницы
    const scrollHeight = document.documentElement.scrollHeight
    const scrollTop = document.documentElement.scrollTop
    const clientHeight = document.documentElement.clientHeight
    const isAtBottom = scrollTop + clientHeight >= scrollHeight - 200 // С небольшим отступом в 200px для предзагрузки

    if (isAtBottom) {
        emit('get-ads')
    }
}

// 1️⃣ Создаём троттлированную (ограниченную по частоте) версию функции
const throttledGetNewAds = useThrottleFn(getNewAds, 500)


// 2️⃣ Добавляем и удаляем слушатель события прокрутки
onMounted(() => {
    window.addEventListener('scroll', throttledGetNewAds)
})

onUnmounted(() => {
    window.removeEventListener('scroll', throttledGetNewAds)
})


// Открыть объявление
function showOneAds(ads, i) {
    localStorage.setItem('oneAds', JSON.stringify(ads))
    router.push({ name: route.name + "OneAds", params: { ads_id: ads.id, table_name: ads.table_name } })
}

// Показать фото
function showImage(ads) {
    imageStore.showImages({ images: ads.images, index: 0, allImg: true })
    router.push({ name: route.name + "Image" })
}

// Активировать/остановить объявление
async function adsActiveToggle( ads_id, table_name, control, i) {
    query.value = true
    try {
        await axios.post('/adsActiveToggle', { ads_id, table_name })
        query.value = false
        control === 'Активно'
            ? props.ads_arr[i].control = 'Не активно'
            : props.ads_arr[i].control = 'Активно'
    } catch (errors) {
        query.value = false
        const err = errors.response?.data?.error
        if (err === 'Объявление было удалено') props.ads_arr.splice(i, 1)
        if (err === 'Объявление находиться в архиве') props.ads_arr[i].control = 'В архиве'
        Toast.fire({ text: err })
    }
}

// Лайк
async function addLikeToggle(ads, i) {
    query.value = true

    ads.likes = ads.likes.length > 0 ? [] : ['Есть лайк']

    try {
        await axios.post('/like', {
            author_id: authStore.user.id,
            table_name: ads.table_name,
            ads_id: ads.id
        })
        query.value = false
        if (props.getMyLikeAds) props.ads_arr.splice(i, 1)
    } catch (errors) {
        query.value = false
        ads.likes = []
        Toast.fire({ icon: 'error', title: errors.response.data.error })
    }
}
// Удалить объявление
async function deleteAds( ads_id, table_name, i) {
    query.value = true
    console.log(ads_id, i)
    try {
        await axios.delete('/deleteAds', { params: { ads_id, table_name } })
        query.value = false
        Toast.fire({ title: t('adsPreviewComponentRemoved') })
        adsPreviewControlAnimation.value = false
        props.ads_arr.splice(i, 1)
    } catch {
        query.value = false
        Toast.fire({ title: t('adsPreviewComponentError') })
    }
}

// Показать блок управления
function showControlBlock(type, one_ads, i) {
    controlBlockType.value = type
    ads.value = one_ads
    index.value = i
    adsPreviewControlAnimation.value = true
}

// Добавить дни к дате
function addDaysToCurrentDate(date, days) {
    if (date) {
        const newDate = new Date(date)
        newDate.setDate(newDate.getDate() + days)
        return filters.transformDateRuNotWatch(newDate)
    }
}

// Статус объявления
function getStatus(one) {
    const { control, author_id } = one
    const { user } = authStore

    if (control === 'В архиве') return { style: 'bg-red-darken-1 p-1 px-2 rounded-lg', text: t('AdsPreviewAddArhive') }
    if (control === 'Поступили жалобы' && author_id === user.id) return { style: 'bg-red-darken-1 p-1 px-2 rounded-lg', text: t('AdsPreviewAddComplain') }
    if (control === 'Активно' && author_id === user.id) return { style: 'bg-green-darken-1 p-1 px-2 rounded-lg', text: t('AdsPreviewAddActive') }
    if (control === 'Не активно') return { style: 'bg-blue-darken-1 p-1 px-2 rounded-lg', text: t('AdsPreviewAddNoActive') }
    if (author_id !== user.id && control !== 'В архиве') return { style: 'bg-green-darken-1 p-1 px-2 rounded-lg', text: ownerText[updateDateLocale.lang] }
    return { style: '', text: '' }
}

// Причина жалобы
function getComplainText(one) {
    if (!one.complain || !one.complain[0]) return ''
    const map = {
        'Ответил риелтор': 'oneAdsBottomOffCanvasTheRealtorReplied',
        'Объявление не актуально': 'oneAdsBottomOffCanvasTheAdIsNotRelevant',
        'Ошибка в цене': 'oneAdsBottomOffCanvasPriceError',
        'Некорректные фотографии': 'oneAdsBottomOffCanvasIncorrectPhotos',
        'Телефон не отвечает': 'oneAdsBottomOffCanvasThePhoneIsNotAnswering',
        'Обман или ложное объявление': 'oneAdsBottomOffCanvasDeceptionOrFalseAnnouncement'
    }
    return t(map[one.complain[0]] || '')
}

// Полный адрес
function getFullAddress(one) {
    const city = KZLocationStore.translateLocation({ gorod: one.gorod }).gorod
    const raion = one.raion ? `, ${KZLocationStore.translateLocation({ raion: one.raion }).raion}` : ''
    return city + raion
}

// ------------------ LIFECYCLE ------------------

</script>


<style>

/* Анимация -- Backdrop  */
.addAdsMenu__animation-backdrop-enter-active,
.addAdsMenu__animation-backdrop-leave-active{
    transition: opacity 0.3s ease;
}


.adsPreviewControl__animation-backdrop-enter-from,
.adsPreviewControl__animation-backdrop-leave-to{
    opacity: 0;
}

.adsPreviewControl__animation-backdrop-enter-to,
.adsPreviewControl__animation-backdrop-leave-from{
    opacity: 1;
}

/* Анимация для -- Wrapper */
.adsPreviewControl__animation-wrapper-enter-active,
.adsPreviewControl__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}

.adsPreviewControl__animation-wrapper-enter-from {
    transform: translateY(50%); /* Начальное положение при анимации скрытия */
}

.adsPreviewControl__animation-wrapper-leave-to {
    transform: translateY(100%); /* Начальное положение при анимации скрытия */
}

.adsPreviewControl__animation-wrapper-enter-to,
.adsPreviewControl__animation-wrapper-leave-from {
    transform: translateY(0);/* Конечное положение при анимации появления */
}

/* При экране более 992px */
@media screen and (min-width: 992px) {
    .addAdsMenu__animation-wrapper-enter-from{
        transform: translateX(50%); /* Начальное положение при анимации скрытия */
    }

    .addAdsMenu__animation-wrapper-leave-to {
        transform: translateX(100%); /* Начальное положение при анимации скрытия */
    }

    .addAdsMenu__animation-wrapper-enter-to,
    .addAdsMenu__animation-wrapper-leave-from {
        transform: translateX(0);/* Конечное положение при анимации появления */
    }

}

</style>

<style scoped>
.ads__preview{
    user-select: none;
    background: #ffffff;
    box-shadow: 0 0 1px silver;
    border-radius: 2px;
    max-width: 600px;
}

.virtual-row {
    will-change: transform;
}

/* Стили для слайдера */
::v-deep(.swiper-pagination-fraction){
    width: auto;
    color: #fff;
    background: rgb(0,0,0,.7);
    border-radius: 3px;
    padding: 0 2px;
    right: 5px;
    left: auto;
    bottom: 22px;
    font-size: 0.7em;
}

.image__block{
    position: relative;
    width: 90px;
    height: 100%;
    overflow: hidden;
}

.ads__preview-img{
    width: 100%;
    height: 150px;
    object-fit: cover;
    object-position: center;
    background: silver;
}

/* Стили для плейсхолдера */
.ads__preview .placeholder-bg {
    background-color: #e0e0e0;
    border-radius: 4px;
}
.ads__preview .placeholder-line {
    background-color: #e0e0e0;
    height: 16px;
    border-radius: 4px;
    margin-bottom: 8px;
}
.ads__preview .placeholder-circle {
    background-color: #e0e0e0;
    width: 40px; /* Размер иконки */
    height: 40px; /* Размер иконки */
    border-radius: 50%;
}

/* Примените пульсирующую анимацию, если хотите */
.ads__preview .image__block.placeholder-bg {
    background-color: #e0e0e0;
    border-radius: 4px;
    height: 150px; /* Задаем ту же высоту, что и у .ads__preview-img */
}
.ads__preview .image__block.placeholder-bg::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
    transform: translateX(-100%);
    animation: pulse 1.5s infinite;
}
@keyframes pulse {
    to {
        transform: translateX(100%);
    }
}


@media screen and (min-width: 321px){
    .image__block{
        width: 130px;
    }
}

@media screen and (min-width: 390px){
    .image__block{
        width: 160px;
    }
}


/* Стили для иконок */
.icon__crown, .icon__diamond, .icon__triangle{
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    width: 19px;
    height: 19px;
    background: red
}
.icon__crown{
    background: red
}
.icon__diamond{
    background: #10a37f;
}
.icon__triangle{
    background: orange
}
.icon__triangleTOP8{
    background: #710250
}


.icon__crown:hover,
.icon__diamond:hover,
.icon__triangle:hover,
.icon__heart:hover {
    transform: scale(1.20);
    transition: transform 0.1s ease;
}

.bueAds__card{
    width: 100%;
    max-width: 500px;
    border: 1px solid rgba(0,0,0,.1);
    border-radius: 5px;
    padding: 10px;
    margin: 10px auto;
    background: #ffffff;
}


/* Стили для блока управление объявлением */
.adsPreviewControl__backdrop{
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: 0;
    background: rgba(0,0,0,0.8);
}

.adsPreviewControl__wrapper{
    position: fixed;
    bottom:0;
    left: 0;
    width: 100%;
    height: auto;
    overflow-y: auto;
    border-radius: 10px 10px 0 0;
    background: #ffffff;
    user-select: none;
}

.adsPreviewControl__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
}

.adsPreviewControl__header{
    width: 100%;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5em;
    font-weight: bold;
    position: relative;
    background: #ffffff;
    color: #000000;
}

.adsPreviewControl__body{
    flex-grow: 1;
}

</style>

