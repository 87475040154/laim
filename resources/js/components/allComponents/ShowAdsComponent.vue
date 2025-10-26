<template>

    <!-- Контейнер для дочерних компонентов (например: фильтр, язык и т.д.) -->
    <div class="child-components-container">
        <router-view></router-view>
    </div>


    <!-- Страница вывод - Превью всех объявлений -->
    <div class="show-ads__page">

        <!-- Верхняя панель: количество объявлений, фильтр и выбор языка -->
        <div class="show-ads-page__top-panel" :class="{ 'show-ads-page__top-panel--sticky': isSticky }">

            <!-- Счетчик найденных объявлений -->
            <div class="show-ads-page__top-panel-count">
                {{ $t('indexFound') }} {{ countAds }}
            </div>

            <!-- Кнопка фильтр -->
            <div class="show-ads-page__top-panel-filter" @click="$router.push({ name: 'filter' })">
                <!-- Иконка фильтра -->
                <svg class="show-ads-page__top-panel-filter-icon" width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 5h10v1H3V5zm0 3h7v1H3V8zm0 3h4v1H3v-1z"/>
                </svg>

                <!-- Текст кнопки -->
                <span class="show-ads-page__top-panel-filter-text">{{ $t('indexFilter') }}</span>

                <!-- Бейдж количества активных фильтров -->
                <span v-if="countFilter > 0" class="show-ads-page__top-panel-filter-badge">{{ countFilter }}</span>
            </div>

            <!-- Выбор языка -->
            <div
                @click="$router.push({ name: $route.name + 'Lang', params: { lang: updateDateLocaleStore.lang.toLowerCase() } })"
                class="show-ads-page__top-panel-lang"
            >
                <span class="show-ads-page__lang-current">
                    {{ updateDateLocaleStore.lang.toUpperCase() }}
                </span>
            </div>

        </div>

        <!-- Кнопка установки приложения PWA -->
        <div v-if="appInstallStore.app" class="install-app-container">
            <button @click="appInstallStore.install()" class="install-app-btn">
                <img src="/img/siteImg/allImg/logo.svg" alt="logo" class="install-app-logo">
                <span>{{ $t('indexInstallLime') }}</span>
            </button>
        </div>


        <!-- Компонент превью объявлений -->
        <ads-preview-component  :ads_arr="ads_arr" :getMyLikeAds="getMyLikeAds" :parent-query="query"  :is-last-load="isLastLoad" @get-ads="getAds"></ads-preview-component>


        <!-- Gif Load - Если объявления еще не загрузились -->
        <div v-if="query" class="ads-loading-spinner">
            <div class="ads-loading-spinner-icon" role="status"></div>
        </div>


        <!-- Если объявления не найдены -->
        <div v-if="adsNotFound" class="ads-not-found">
            <h4>{{ $t('indexAdsNotFound') }}</h4>
        </div>


        <!-- Кнопка показать объекты на карте -->
        <button
            v-if="showMapButton"
            class="show-ads-page__map-btn"
            @click="$router.push({ name: 'allAdsMap' })"
        >
            <i class="bi bi-geo-alt"></i>
            {{ $t('indexOnTheMap') }}
        </button>


    </div>

</template>

<script setup>
import { ref, watch, onMounted, nextTick  } from 'vue';
import axios from 'axios';

import { useRouter, useRoute } from 'vue-router'

// 🧭 Подключаем маршрутизатор
const router = useRouter()
const route = useRoute()


// Импортируем Store
import { useAuthStore } from "../../stores/auth";
import { useAppInstallStore } from "../../stores/AppInstall";
import { useFilterStore } from "../../stores/Filter";
import { useKZLocationStore } from "../../stores/KZLocation";
import { useUpdateDateLocaleStore } from "../../stores/updateDateLocale";

// Импортируем компоненты
import adsPreviewComponent from "./AdsPreviewComponent.vue";
import { useScroll, useLocalStorage  } from '@vueuse/core'


// Stores
const authStore = useAuthStore();
const appInstallStore = useAppInstallStore();
const filterStore = useFilterStore();
const updateDateLocaleStore = useUpdateDateLocaleStore();
const KZLocationStore = useKZLocationStore();

// Reactive state
const query = ref(false);
const controller = ref(null);

const topPanelFixed = ref(false);

const countAds = ref(0);
const adsNotFound = ref(false);

const ads_arr = ref([]);
// создаём реактивный ref, который синхронизирован с localStorage
const getMyLikeAds = useLocalStorage('getMyLikeAds', false)

const countFilter = ref(0);
const showMapButton = ref(false);

const nextCursor = ref(null);
const isFirstLoad = ref(true);
const isLastLoad = ref(false);

// Методы
// 📜 Отслеживаем прокрутку страницы
const { y } = useScroll(window)

// 📌 Состояние "прилипания" верхней панели
const isSticky = ref(false)
let lastY = 0
const DELTA = 10 // порог чувствительности — мелкие движения игнорируем

// 🎯 Логика прилипания верхней панели
watch(y, (newY) => {
    const diff = newY - lastY

    // Если вернулись к самому верху — хедер обычный (не липкий)
    if (newY <= 0) {
        isSticky.value = false
        lastY = newY
        return
    }

    // Игнорируем мелкие движения (чтобы не "дрожал")
    if (Math.abs(diff) <= DELTA) return

    if (diff < 0) {
        // Скролл вверх — хедер становится "sticky"
        isSticky.value = true
    } else if (diff > 0) {
        // Скролл вниз — хедер отпускается
        isSticky.value = false
    }

    lastY = newY
})


const clearData = () => {

    ads_arr.value = [];
    nextCursor.value = null;
    isFirstLoad.value = true;
    isLastLoad.value = false;
    adsNotFound.value = false;

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });

};

const filterLength = () => {
    const filter = JSON.parse(localStorage.getItem("filter=" + route.params.table_name));
    if (!filter) {
        countFilter.value = 0;
        showMapButton.value = false;
        return;
    }

    let count = 0;
    for (let key in filter) {
        if (!['originalData', 'busy', 'successful', 'recentlySuccessful', 'recentlySuccessfulTimeoutId', 'errors', 'progress'].includes(key)) {
            if (filter[key] !== '' && filter[key] != null) count++;
        }
    }

    if (count > 0) {
        if (filter.oblast != null) {
            if (filter.gorod != null) count--;
            if (filter.raion != null) count--;
            showMapButton.value = true;
        } else {
            showMapButton.value = false;
        }
        countFilter.value = count;
    } else {
        countFilter.value = 0;
        showMapButton.value = false;
    }
};

const countAdsFn = async () => {
    try {
        const response = await axios.get('countAds', {
            params: {
                user_id: authStore.check ? authStore.user.id : 0,
                table_name: route.params.table_name,
                filter: JSON.parse(localStorage.getItem("filter=" + route.params.table_name)) ?? 'Фильтр не применен',
                getMyLikeAds: getMyLikeAds.value ? 'Получить мои лайки' : 'Не получать мои лайки',
            }
        });

        // Записываем количество объявлений
        countAds.value = response.data.countAds ?? 0;

        // Устанавливаем флаг adsNotFound
        adsNotFound.value = countAds.value === 0;

    } catch (error) {
        console.error('Ошибка при подсчёте объявлений:', error);
        // В случае ошибки тоже можно считать, что объявлений нет
        countAds.value = 0;
        adsNotFound.value = true;
    }
};

const getAds = async () => {
    if (isLastLoad.value) return;

    if (controller.value) controller.value.abort();
    controller.value = new AbortController();
    const signal = controller.value.signal;

    const showGifDelay = setTimeout(() => query.value = true, 100);

    if (!authStore.check) getMyLikeAds.value = false;

    try {
        const response = await axios.get('getAllAds', {
            params: {
                cursor: nextCursor.value,
                user_id: authStore.check ? authStore.user.id : 0,
                table_name: route.params.table_name,
                filter: JSON.parse(localStorage.getItem("filter=" + route.params.table_name)) || {},
                getMyLikeAds: getMyLikeAds.value ? 'Получить мои лайки' : 'Не получать мои лайки',
            },
            signal
        });

        ads_arr.value = isFirstLoad.value ? response.data.ads.data : [...ads_arr.value, ...response.data.ads.data];
        if (isFirstLoad.value) countAdsFn();
        nextCursor.value = response.data.ads.next_cursor;
        isLastLoad.value = nextCursor.value === null;
        filterLength();
        isFirstLoad.value = false;
    } catch (error) {
        if (error.name !== 'CanceledError') console.error(error);
    } finally {
        clearTimeout(showGifDelay);
        query.value = false;
    }
};


watch(getMyLikeAds, (newVal) => {
    clearData();
    getAds();
}, { immediate: true })

watch(
    () => route.params.table_name,
    (newTable, oldTable) => {
        if (newTable !== oldTable) {
            clearData()
            getAds()
            window.scrollTo({ top: 0 })
        }
    },
    { deep: true } // отслеживает вложенные свойства объекта
)

watch(() => filterStore.make_filter, () => {
    clearData();
    getAds();
});

// Mounted
onMounted(() => {
    getAds();
});

</script>

<style scoped>

.child-components-container {
    position: relative;
    z-index: 2;
}

.show-ads__page {
    user-select: none;
    min-height: 500px;
}

/* Блок верхней панели */
.show-ads-page__top-panel {
    position: relative; /* важно */
    top: 0;
    left: 0;
    right: 0;
    background: #ffffff;
    border-radius: 12px; /* закругление углов */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* мягкая тень */
    will-change: transform;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 20px; /* чуть больше внутренних отступов */
    z-index: 1;
    transition: background 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
    margin-bottom: 16px;

}
/* Прилипающая панель */
.show-ads-page__top-panel--sticky {
    position: sticky;
    top: 112px;
    z-index: 1;
    backdrop-filter: blur(6px);
    transition: background 0.2s ease, box-shadow 0.2s ease;
}

/* Счетчик найденных объявлений — второстепенный элемент */
.show-ads-page__top-panel-count {
    font-size: 14px;
    font-weight: 500;
    color: #666; /* менее яркий цвет */
}
/* Кнопка фильтра — основной акцент */
.show-ads-page__top-panel-filter {
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    color: #007bff; /* яркий синий */
}
/* Иконка фильтра */
.show-ads-page__top-panel-filter-icon {
    width: 18px;
    height: 18px;
    fill: currentColor; /* цвет иконки совпадает с текстом */
}
/* Бейдж активных фильтров */
.show-ads-page__top-panel-filter-badge {
    display: inline-block;
    background-color: #ff3b30;
    color: #fff;
    font-size: 12px;
    font-weight: 500;
    line-height: 1;
    padding: 2px 6px;
    border-radius: 10px;
}
/* Выбор языка */
.show-ads-page__top-panel-lang {
    display: flex;
    align-items: center;
    justify-content: center;
}
.show-ads-page__lang-current {
    font-size: 14px;
    font-weight: 600;
    color: #007bff;
    padding: 4px 8px;
    border-radius: 6px;
    background-color: #e6f0ff; /* лёгкий фон для выделения */
}

/* Кнопка установка приложения */
.install-app-container {
    display: flex;
    justify-content: center;
    margin: 1rem 0;
}
.install-app-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px 20px;
    font-size: 15px;
    font-weight: 600;
    color: #ffffff; /* белый текст */
    background: linear-gradient(135deg, #6c5ce7, #00b894); /* мягкий градиент */
    border: none;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 600px;
    cursor: pointer;
}
.install-app-logo {
    width: 25px;
    height: 25px;
    border-radius: 6px;
}


/* Контейнер для спиннера */
.ads-loading-spinner {
    display: flex;
    justify-content: center;
    padding: 12px 0; /* чуть больше отступов */
}
/* Спиннер увеличенного размера */
.ads-loading-spinner-icon {
    width: 2rem;                   /* увеличенный размер */
    height: 2rem;
    border: 3px solid rgba(0, 0, 0, 0.1);  /* толще рамка */
    border-top-color: var(--app-text-color);
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
}
/* Анимация вращения */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}


/* Блок отображения отсутствия объявлений */
.ads-not-found {
    text-align: center;
    color: #888888;
    margin: 16px 0;
    font-size: 1rem;
    font-weight: 500;
}
.ads-not-found h4 {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
    line-height: 1.4;
}



/* Кнопка показать объекты на карте */
.show-ads-page__map-btn {
    position: fixed;
    bottom: 60px;
    right: 10px;
    background-color: #ffdb4d; /* желтый фон */
    color: #333;               /* цвет текста */
    font-size: 0.875rem;       /* text-caption */
    font-weight: 500;
    z-index: 100;
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 0 12px;
    height: 45px;
    border-radius: 22px;       /* закругленные края */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border: none;              /* убираем стандартную границу */
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
/* Иконка внутри кнопки */
.show-ads-page__map-btn i {
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
}


/* 📞 Маленькие телефоны (≥480px) */
@media (min-width: 480px) {

}

/* 📱 Средние телефоны (≥576px) */
@media (min-width: 576px) {

}

/* 💼 Планшеты (≥768px) */
@media (min-width: 768px) {

}

/* при ширине экрана ≥823px — по центру */
@media (min-width: 823px) {

}

/* 💻 Ноутбуки (≥992px) */
@media (min-width: 992px) {
    /* Прилипающая панель */
    .show-ads-page__top-panel--sticky {
        top: 190px; /* от верха окна или родителя, с которым нет overflow */
    }
}

/* 🖥 Десктопы (≥1200px) */
@media (min-width: 1200px) {

}

/* 🖥💎 Большие мониторы (≥1400px) */
@media (min-width: 1400px) {

}

/* 🖥 Ultra-wide 2K (≥1600px) */
@media (min-width: 1600px) {

}

/* 🖥 4K дисплеи (≥1920px) */
@media (min-width: 1920px) {

}
</style>
