<template>

    <!-- Вывожу превью объявлений через виртуальный скроллер - Tanstak vue -->
    <div class="rowVirtual__wrapper" :style="{ height: totalSize + 'px' }">

        <!-- Внешний котейнер - 1 контейнер > 1 превью объявления -->
        <div
            v-for="virtualRow in virtualRows"
            :key="props.ads_arr[virtualRow.index].id || virtualRow.index"
            class="rowVirtual__item"
            :style="{ height: `${virtualRow.size}px`,transform: `translateY(${virtualRow.start}px)` }"
        >
            <!-- Обвертка блока -->
            <div class="adsPreviewBlock__wrapper">

                <!--  Сам блок - Фото, описание и тд. -->
                <div class="adsPreview__block">

                    <!-- Фото -->
                    <div class="adsPreviewImage__block">

                        <template v-if="!shouldHideContent">

                            <!-- Срочно торг -->
                            <span
                                v-if="props.ads_arr[virtualRow.index].srochno_torg"
                                class="adsPreviewImage__srochnoTorg"
                            >
                                {{ $t('adsPreviewComponentUrgentBargaining') }}
                            </span>

                            <!-- Фото -->
                            <img loading="lazy"
                                 @click="props.ads_arr[virtualRow.index].images.length ? showImage(props.ads_arr[virtualRow.index]) : null"
                                 class="adsPreviewImage__image"
                                 :src="props.ads_arr[virtualRow.index].images.length > 0 ? '/img/adsImg/' + props.ads_arr[virtualRow.index].images[0] : '/img/siteImg/allImg/no-image-buildings.png'"
                                 alt="Недвижимость"
                            >

                            <!-- Статус - В архиве - Не активно - Хозяин и тд. -->
                            <div class="adsPreviewImage__status">
                                <div :class="getStatus(props.ads_arr[virtualRow.index]).style">
                                    {{ getStatus(props.ads_arr[virtualRow.index]).text }}
                                </div>
                            </div>

                        </template>

                    </div>

                    <!--Блок - Описание объявления -->
                    <div class="adsPreviewDescription__wrapper">

                        <!-- Заголовок / Цена / Адрес -->
                        <div
                            @click=" showOneAds(props.ads_arr[virtualRow.index],virtualRow.index) "
                            role="button"
                            class="adsPreviewDescription__block"
                        >

                            <!-- Заголовок -->
                            <div class="adsPreviewDescription__zagolovok">
                                {{props.ads_arr[virtualRow.index].zagolovok}}
                            </div>

                            <!-- Цена аренды -->
                            <div class="adsPreviewDescription__cena">
                                {{ $filters.format_number(props.ads_arr[virtualRow.index].cena) }} &#8376;
                            </div>

                            <!-- Адрес -->
                            <div v-if="!shouldHideContent" class="adsPreviewDescription__adress">
                                {{ getFullAddress(props.ads_arr[virtualRow.index]) }}
                            </div>

                        </div>

                        <!-- Дата публикации - Лайк -->
                        <div class="adsPreviewDescription__date">

                            <template v-if="!shouldHideContent">
                                <!-- Дата публикации -->
                                <div class="adsPreview__date">
                                    {{ $filters.transformDateRu(props.ads_arr[virtualRow.index].created_at) }}
                                </div>

                                <v-spacer></v-spacer>

                                <!-- Если Отправленно в ТОП или ТОП х7, ТОП х30-->
                                <div class="adsPreview__reclama">
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
                                        @click="authStore.check ? addLikeToggle( props.ads_arr[virtualRow.index], virtualRow.index): $router.push({name: $route.name + 'Auth'})"
                                >mdi-heart
                                </v-icon>
                            </span>

                            </template>

                        </div>

                    </div>

                </div>

                <!--  - Управление объявлением - Продвигать рекламу - Сдать быстрее -->
                <template v-if="!shouldHideContent">

                    <div class="px-md-2"
                         v-if="authStore.check && authStore.user.id == props.ads_arr[virtualRow.index].author_id
                                    && $route.name == 'userAds' && props.ads_arr[virtualRow.index].control != 'В архиве'
                                    || authStore.check && authStore.user.role == 'admin' && props.ads_arr[virtualRow.index].control != 'В архиве'"
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
                    <div v-if="authStore.check && authStore.user.id == props.ads_arr[virtualRow.index].author_id && $route.name == 'userAds'">

                        <div v-if="props.ads_arr[virtualRow.index].control == 'Поступили жалобы' " class="col-12 alert" style="background: #efa6a6; padding: 1.7px 10px!important;">
                            <i class="bi bi-exclamation-octagon"></i>
                            {{ $t('adsPreviewComponentReturnForRevision')}}
                            <div>{{ $t('adsPreviewComponentCause') }} : {{ getComplainText(ads) }}</div>
                        </div>

                    </div>

                </template>

            </div>

        </div>

    </div>

    <!--    Управление объявлением - Backdrop-->
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

import { useWindowVirtualizer  } from '@tanstack/vue-virtual'

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
    getItemKey: (i) => props.ads_arr[i]?.id || i,
    estimateSize: () => estimateSize.value,
    overscan: 5,
    gap: 16,
    isScrollingResetDelay: 400
}))
// 🧩 Фиксированная высота по маршруту
const estimateSize = computed(() => {
    switch (route.name) {
        case 'allAds':      // пример: главная страница
            return 160
        case 'userAds':      // пример: объявления пользователя
            return 300
        default:
            return 170
    }
})
const rowVirtualizer = useWindowVirtualizer(rowVirtualizerOptions)

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

//Получить новые обьявления Отслеживаем изменения в списке видимых виртуальных строк
const emitThrottledGetAds = useThrottleFn(() => {
    // Внутри этой функции мы отправляем событие родителю
    emit('get-ads')
}, 500)// Ограничиваем вызов до одного раза в 500 мс

// Отслеживаем изменения в списке видимых виртуальных строк
watch(virtualRows, (newVirtualRows) => {
    // Если больше нет данных, не делаем ничего
    if (props.isLastLoad) return

    const lastVirtualRow = newVirtualRows[newVirtualRows.length - 1]

    // Проверяем, если последняя видимая строка - одна из последних 5
    if (lastVirtualRow && lastVirtualRow.index >= props.ads_arr.length - 5) {
        // 2️⃣ Вызываем троттлированную функцию, которая отправит событие
        // только если с момента последнего вызова прошло достаточно времени
        emitThrottledGetAds()
    }
}) // Глубокое отслеживание, чтобы следить за изменениями внутри массива


// Состояние для отслеживания скорости прокрутки чтобы скрывать при быстрой часть контента чтобы не было прыжков
const shouldHideContent = computed(() => {
    return isFastScrolling.value && isScrolling.value
})

const scrollSpeed = ref(0)
const isFastScrolling = ref(false)
let lastScrollY = 0
let lastScrollTime = 0

// Порог скорости, при превышении которого считаем прокрутку быстрой
const speedThreshold = 3 // Настройте это значение

const handleScroll = useThrottleFn(() => {
    const currentScrollY = window.scrollY
    const currentTime = Date.now()
    const timeDelta = currentTime - lastScrollTime

    // Избегаем деления на ноль, если событие срабатывает слишком быстро
    if (timeDelta > 0) {
        scrollSpeed.value = Math.abs(currentScrollY - lastScrollY) / timeDelta
        isFastScrolling.value = scrollSpeed.value > speedThreshold
    }

    lastScrollY = currentScrollY
    lastScrollTime = currentTime
}, 100) // Частота проверки скорости

onMounted(() => {
    window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
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

.rowVirtual__wrapper {
    width: 100%;
    position: relative;
    margin-bottom: 20px;
}

.rowVirtual__item {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    transition: transform 0.1s linear;
    will-change: transform;
}

.adsPreviewBlock__wrapper {
    user-select: none;
    background: #ffffff;
    box-shadow: 0 0 1px silver;
    border-radius: 2px;
    max-width: 600px;
    height: 100%;
    margin-right: auto;
    margin-left: auto;
}

.adsPreview__block {
    width: 100%;
    height: 100%;
    display: flex;
    gap: 0.5rem; /* небольшой отступ между фото и описанием */
}


/* Левая колонка - фото */
.adsPreviewImage__block {
    position: relative;
    width: 110px;
    height: 100%;
    overflow: hidden;
    background: linear-gradient(135deg, #e6ffe6 0%, #d9e9c7 100%);
}

.adsPreviewImage__srochnoTorg {
    position: absolute;
    top: 5px;
    left: 5px;
    background-color: #ffeb3b; /* аналог bg-yellow-darken-2 */
    border-radius: 4px; /* заменяет rounded-sm */
    font-size: 0.75rem; /* text-caption */
    padding: 2px 4px; /* px-1 */
    font-weight: 500;
    color: #333;
    line-height: 1.2;
    z-index: 2;
    user-select: none;
}

.adsPreviewImage__status {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: auto;
    display: flex;
    gap: 4px;      /* аналог gap-1 */
    padding: 4px;  /* аналог p-1 */
    box-sizing: border-box;
    align-items: center;
    justify-content: flex-start;
    z-index: 2;
}

.adsPreviewImage__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    border-radius: 4px;
}



/* Правая колонка - описание */
.adsPreviewDescription__wrapper {
    flex: 1; /* занимает всё оставшееся пространство */
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* чтобы заголовок сверху, дата и лайк снизу */
}

.adsPreviewDescription__block {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    min-height: 115px;
}

.adsPreviewDescription__zagolovok {
    font-size: 17px;
    color: #4b4b4b;
    line-height: 22px;
}

.adsPreviewDescription__cena {
    font-size: 1.2em;
    margin-top: auto;
    margin-bottom: auto;
    font-weight: 700;
}

.adsPreviewDescription__adress {
    font-size: 0.9em;
    color: #5d6f6a;
    margin-top: auto;
}

.adsPreviewDescription__date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    position: relative;
}


.adsPreview__date {
    font-size: 0.9em;
    color: #5d6f6a;
}

.adsPreview__reclama {
    position: absolute;
    bottom: 0;
    right: 30px;
    display: flex;
    gap: 4px;
    padding: 4px;
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



/* Смартфоны шире 360px */
@media screen and (min-width: 360px) {
    .adsPreviewImage__block{
        width: 140px; /* ширина увеличивается на больших экранах */
    }
}

/* Смартфоны шире 430px (iPhone Pro Max) */
@media screen and (min-width: 430px) {
    .adsPreviewImage__block{
        width: 160px; /* ширина увеличивается на больших экранах */
    }
}

/* Планшеты (iPad) */
@media screen and (min-width: 768px) {

}

/* Ноутбуки (1024px+) */
@media screen and (min-width: 1024px) {

}

/* Большие экраны (1440px+) */
@media screen and (min-width: 1440px) {

}

</style>

