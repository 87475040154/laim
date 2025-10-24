<template>

    <!-- Превью блок -->
    <div style="height: 100vh; overflow-y: auto; width: 100%;" ref="scrollParent">
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
            <v-card class="mx-3 my-2 mx-sm-auto ads__preview" :style="{ minHeight: virtualRow.size + 'px' }">


                <!--  Описание объявления -->
                <div class="d-flex p-md-2">

                    <!-- Фото -->
                    <div class="image__block">

                        <!-- Срочно торг -->
                        <div v-show="!isScrolling && props.ads_arr[virtualRow.index].srochno_torg" style="position: absolute; top: 5px; left: 5px;" class="bg-yellow-darken-2 rounded-sm text-caption px-1">
                            {{ $t('adsPreviewComponentUrgentBargaining') }}
                        </div>

                        <img
                            loading="lazy"
                            @click="props.ads_arr[virtualRow.index].images.length ? showImage(props.ads_arr[virtualRow.index]) : null"
                            class="ads__preview-img rounded-sm"
                            :src="props.ads_arr[virtualRow.index].images.length > 0 ? '/img/adsImg/' + props.ads_arr[virtualRow.index].images[0] : '/img/siteImg/allImg/no-image-buildings.png'"
                            :alt="props.ads_arr[virtualRow.index].images.length > 0 ? 'Фото недвижимости' : 'Нет фото'"
                        >

                        <!-- Статус - В архиве - Не активно - Хозяин и тд. -->
                        <div v-if="!isScrolling" class="d-flex gap-1 p-1" style="position: absolute; bottom: 0; left: 0; width: 100%; height: auto">
                            <div :class="getStatus(props.ads_arr[virtualRow.index]).style">{{ getStatus(props.ads_arr[virtualRow.index]).text }}</div>
                        </div>

                    </div>

                    <!--Блок - Описание объявления -->
                    <div class="col pl-2">

                        <!--Блок - Описание объявления -->
                        <div @click="showOneAds(props.ads_arr[virtualRow.index],virtualRow.index)" role="button" class="d-flex align-start flex-column" style="min-height: 115px">

                            <!-- Заголовок -->
                            <div style="font-size: 17px; color: #4b4b4b; line-height: 22px">
                                {{props.ads_arr[virtualRow.index].zagolovok}}
                            </div>

                            <!-- Цена аренды -->
                            <div class="my-auto fw-bold" style="font-size: 1.2em">
                                {{ $filters.format_number(props.ads_arr[virtualRow.index].cena) }} &#8376;
                            </div>

                            <!-- Адрес -->
                            <div class="mt-auto" style="font-size: 0.9em; color: #5d6f6a">
                                {{ getFullAddress(props.ads_arr[virtualRow.index]) }}
                            </div>

                        </div>

                        <!-- Дата публикации - Лайк -->
                        <div class="d-flex align-center gap-2 position-relative">

                            <!-- Дата публикации -->
                            <div style="font-size: 0.9em; color: #5d6f6a">
                                {{ $filters.transformDateRu(props.ads_arr[virtualRow.index].created_at) }}
                            </div>

                            <v-spacer></v-spacer>


                            <!-- Если Отправленно в ТОП или ТОП х7, ТОП х30-->
                            <div v-if="!isScrolling" class="d-flex gap-1 p-1" style="position: absolute; bottom: 0; right: 30px">
                                <div
                                    v-for="item in topIcons.filter(i => props.ads_arr[virtualRow.index][i.key] != null)"
                                    :key="item.key"
                                    :class="item.class"
                                >
                                    <v-icon :icon="item.icon" size="x-small" color="white"></v-icon>
                                </div>
                            </div>

                            <!-- Кнопка лайк -->
                            <span>
                                <v-icon :color="props.ads_arr[virtualRow.index].likes.length > 0 ? 'red' : 'grey-lighten-1'"
                                        class="icon__heart mx-1"
                                        size="large"
                                        @click="authStore.check ? addLikeToggle(virtualRow.index, props.ads_arr[virtualRow.index]): $router.push({name: $route.name + 'Auth'})"
                                >mdi-heart
                                </v-icon>
                            </span>


                        </div>

                    </div>

                </div>

                <!--  - Управление объявлением - Продвигать рекламу - Сдать быстрее -->
                <div class="px-md-2"
                     v-if="!isScrolling && authStore.check && authStore.user.id == props.ads_arr[virtualRow.index].author_id
                                    && $route.name == 'userAds' && props.ads_arr[virtualRow.index].control != 'В архиве'
                                    || !isScrolling && authStore.check && authStore.user.role == 'admin' && props.ads_arr[virtualRow.index].control != 'В архиве'"
                >

                    <div class="d-flex justify-content-between align-center">

                        <!-- Кнопка сдать быстрее -->
                        <v-btn dark color="grey-lighten-4"
                               size="x-large"
                               @click="$router.push({ name: $route.name + 'BueAds', params: {ads_id: props.ads_arr[virtualRow.index].id} } )"
                               class="text-body-1"
                               style="min-width: 170px"
                        >
                            {{ $t('adsPreviewComponentPassFaster') }}
                        </v-btn>

                        <!-- Просмотров - Взяли номера -->
                        <v-btn icon size="x-large" color="grey-lighten-4" @click="showControlBlock('Статистика', props.ads_arr[virtualRow.index],virtualRow.index)">
                            <v-icon>mdi-finance</v-icon>
                        </v-btn>

                        <!-- Блок - Управление объявлением - для автора и админа -->
                        <v-btn icon size="x-large" color="grey-lighten-4" @click="showControlBlock('Управление', props.ads_arr[virtualRow.index],virtualRow.index)">
                            <v-icon>mdi-dots-vertical</v-icon>
                        </v-btn>

                    </div>

                    <!-- На сайте до-->
                    <div class="px-1 px-md-0">
                        <span v-if="updateDateLocale.lang == 'ru'">На сайте до: </span>
                        <span v-if="updateDateLocale.lang == 'en'">Before: </span>
                        <span v-if="props.ads_arr[virtualRow.index].top_x30 != null">{{ addDaysToCurrentDate(props.ads_arr[virtualRow.index].top_x30, 30) }}</span>
                        <span v-else-if="props.ads_arr[virtualRow.index].top_x7 != null">{{ addDaysToCurrentDate(ads.top_x7, 7) }}</span>
                        <span v-else>{{ addDaysToCurrentDate(props.ads_arr[virtualRow.index].updated_at, 7) }}</span>
                        <span v-if="updateDateLocale.lang == 'kz'" class="pl-1"> дейін</span>

                    </div>

                </div>

                <!-- Жалобы на объявления - Если поступили 5 жалоб - Они видны автору - Объявление отправиться на доработку  -->
                <div v-if="!isScrolling && authStore.check && authStore.user.id == props.ads_arr[virtualRow.index].author_id && $route.name == 'userAds'">

                    <div v-if="props.ads_arr[virtualRow.index].control == 'Поступили жалобы' " class="col-12 alert" style="background: #efa6a6; padding: 1.7px 10px!important;">
                        <i class="bi bi-exclamation-octagon"></i>
                        {{ $t('adsPreviewComponentReturnForRevision')}}
                        <div>{{ $t('adsPreviewComponentCause') }} : {{ getComplainText(ads) }}</div>
                    </div>

                </div>

            </v-card>

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
                               @click="adsActiveToggle(index, ads.id, ads.table_name, ads.control)"
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
                               @click="deleteAds(index, ads.id, ads.table_name, ads.control)"
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
import { ref,  reactive, computed, watch, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useThrottleFn, useScroll, useDebounceFn  } from '@vueuse/core'
import axios from 'axios'

// Импорт стора
import { useAuthStore } from "../../stores/auth"
import { useImagesStore } from "../../stores/images"
import { useAdsStore } from "../../stores/ads"
import { useUpdateDateLocaleStore } from "../../stores/updateDateLocale"
import { useKZLocationStore } from "../../stores/KZLocation"

import { useVirtualizer } from '@tanstack/vue-virtual'

const props = defineProps({
    ads_arr: Array,
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
    getScrollElement: () => scrollParent.value,
    getItemKey: (i) => props.ads_arr[i]?.id || i,
    estimateSize: () => 170,
    overscan: 10,
}))
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
    // Получаем последний виртуальный элемент (тот, что виден последним в области виртуализации)
    const [lastItem] = [...virtualRows.value].reverse()

    // Если элементов нет или достигнут конец всех данных — выходим
    if (!lastItem || props.isLastLoad) {
        return
    }

    // Проверяем, если последний видимый элемент — это последний элемент массива объявлений
    // (т.е. пользователь проскроллил до самого конца списка)
    if (lastItem.index >= props.ads_arr.length - 1) {
        console.log('Достигнут конец списка. Загружаем ещё...')
        // Вызываем событие в родительский компонент для подгрузки новой порции объявлений
        emit('get-ads')
    }
}

// 1️⃣ Создаём троттлированную (ограниченную по частоте) версию функции,
// чтобы избежать частых запросов при быстрой прокрутке.
// useThrottleFn — вызывается не чаще, чем раз в 500 мс.
const throttledGetNewAds = useThrottleFn(getNewAds, 500)


// 2️⃣ Следим за изменениями виртуальных элементов
// (каждый раз, когда пользователь прокручивает список и изменяется набор видимых элементов)
watch(virtualRows, async () => {
    // Ждём, пока DOM обновится после очередной отрисовки виртуальных элементов
    await nextTick()

    // Проверяем, нужно ли подгрузить новую партию объявлений
    throttledGetNewAds()
})

// Открыть объявление
function showOneAdsFn(one, i) {
    localStorage.setItem('oneAds', JSON.stringify(one))
    router.push({ name: route.name + "OneAds", params: { ads_id: one.id, table_name: one.table_name } })
}

// Показать фото
function showImage(one) {
    imageStore.showImages({ images: one.images, index: 0, allImg: true })
    router.push({ name: route.name + "Image" })
}

// Активировать/остановить объявление
async function adsActiveToggleFn(i, ads_id, table_name, control) {
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
async function addLikeToggleFn(i, one) {
    query.value = true
    one.likes = one.likes.length > 0 ? [] : ['Есть лайк']

    try {
        await axios.post('/like', {
            author_id: authStore.user.id,
            table_name: one.table_name,
            ads_id: one.id
        })
        query.value = false
        if (props.getMyLikeAds) props.ads_arr.splice(i, 1)
    } catch (errors) {
        query.value = false
        one.likes = []
        Toast.fire({ icon: 'error', title: errors.response.data.error })
    }
}

// Удалить объявление
async function deleteAdsFn(i, ads_id, table_name) {
    query.value = true
    try {
        await axios.delete('/deleteAds', { params: { ads_id, table_name } })
        query.value = false
        Toast.fire({ title: $t('adsPreviewComponentRemoved') })
        adsPreviewControlAnimation.value = false
        props.ads_arr.splice(i, 1)
    } catch {
        query.value = false
        Toast.fire({ title: $t('adsPreviewComponentError') })
    }
}

// Показать блок управления
function showControlBlockFn(type, one, i) {
    controlBlockType.value = type
    ads.value = one
    index.value = i
    adsPreviewControlAnimation.value = true
}

// Добавить дни к дате
function addDaysToCurrentDate(date, days) {
    if (date) {
        const newDate = new Date(date)
        newDate.setDate(newDate.getDate() + days)
        return $filters.transformDateRuNotWatch(newDate)
    }
}

// Статус объявления
function getStatus(one) {
    const { control, author_id } = one
    const { user } = authStore

    if (control === 'В архиве') return { style: 'bg-red-darken-1 p-1 px-2 rounded-lg', text: $t('AdsPreviewAddArhive') }
    if (control === 'Поступили жалобы' && author_id === user.id) return { style: 'bg-red-darken-1 p-1 px-2 rounded-lg', text: $t('AdsPreviewAddComplain') }
    if (control === 'Активно' && author_id === user.id) return { style: 'bg-green-darken-1 p-1 px-2 rounded-lg', text: $t('AdsPreviewAddActive') }
    if (control === 'Не активно') return { style: 'bg-blue-darken-1 p-1 px-2 rounded-lg', text: $t('AdsPreviewAddNoActive') }
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
    return $t(map[one.complain[0]] || '')
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

