<template>

    <!-- Контейнер для дочерних компонентов (например: фильтр, язык и т.д.) -->
    <div class="child-components-container">
        <router-view></router-view>
    </div>


    <!-- Страница вывод - Превью всех объявлений -->
    <div class="show-ads__page">

        <!-- Верхняя панель: количество объявлений, фильтр и выбор языка -->
        <div class="show-ads-page__top-panel">

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

// 🔹 Импорт реактивных функций из Vue
import { ref, watch, onMounted } from 'vue';

// 🔹 Импорт Axios для HTTP-запросов
import axios from 'axios';


// 🔹 Импорт функций маршрутизатора Vue
import { useRouter, useRoute } from 'vue-router'
const router = useRouter()// - объект router позволяет программно переходить на другие страницы (router.push, router.replace)
const route = useRoute()// - объект route содержит текущий маршрут, параметры, query и имя страницы

// 📦 Импортируем Stores из разных модулей
import { useAuthStore } from "../../stores/auth";
import { useAppInstallStore } from "../../stores/AppInstall";
import { useFilterStore } from "../../stores/Filter";
import { useKZLocationStore } from "../../stores/KZLocation";
import { useUpdateDateLocaleStore } from "../../stores/updateDateLocale";

// ⚡ Инициализация Stores
const authStore = useAuthStore();
const appInstallStore = useAppInstallStore();
const filterStore = useFilterStore();
const updateDateLocaleStore = useUpdateDateLocaleStore();
const KZLocationStore = useKZLocationStore();

// 📦 Импортируем компоненты и утилиты
import AdsPreviewComponent from "./AdsPreviewComponent.vue"; // - Компонент для превью объявлений, который используется в шаблоне <ads-preview-component>

import { useScroll, useLocalStorage } from '@vueuse/core';
// - useScroll: позволяет отслеживать положение скролла на странице
// - useLocalStorage: реактивная работа с localStorage, данные автоматически синхронизируются


// Реактивные переменные
const query = ref(false); // :Флаг -  отправлен запрос на сервер или нет
const controller = ref(null); // 🔹 Создаём реактивную переменную для контроллера отмены запросов axios // - controller.value будет хранить объект AbortController // - если нужно отменить предыдущий запрос, можно вызвать: controller.value.abort()
const topPanelFixed = ref(false); // Верхняя панель - Фиксированна  position : Sticky или relative
const ads_arr = ref([]); // Массив объявлений полученный с БД
const getMyLikeAds = useLocalStorage('getMyLikeAds', false) // создаём реактивный ref, который синхронизирован с localStorage
const countFilter = ref(0); // Колличество активных значений в фильре
const showMapButton = ref(false); // :Флаг - Показать кнопку "Установить приложение Лайм.kz"
const countAds = ref(0); // Колличество найденных объявлений
const adsNotFound = ref(false); // :Флаг объявления не найденны
const nextCursor = ref(null); // Следующий курсор для получения объявлений
const isFirstLoad = ref(true); // :Флаг - Первый запрос объявлений
const isLastLoad = ref(false); // :Флаг - Получаем последние объявления

// Методы

// Метод отчищает, скидывает все данные до базовых
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

// 🔹 Метод для подсчёта активных фильтров
const filterLength = () => {
    // Получаем фильтр из localStorage по текущей таблице
    const filter = JSON.parse(localStorage.getItem("filter=" + route.params.table_name));

    // Если фильтра нет — сбрасываем счетчик и скрываем кнопку карты
    if (!filter) {
        countFilter.value = 0;
        showMapButton.value = false;
        return;
    }

    // Список служебных ключей, которые не учитываются
    const excludedKeys = [
        'originalData', 'busy', 'successful',
        'recentlySuccessful', 'recentlySuccessfulTimeoutId',
        'errors', 'progress'
    ];

    // Подсчёт активных фильтров
    let count = Object.keys(filter)
        .filter(key => !excludedKeys.includes(key) && filter[key] !== '' && filter[key] != null)
        .length;

    // Корректировка для областей/городов/районов
    if (count > 0 && filter.oblast != null) {
        if (filter.gorod != null) count--;
        if (filter.raion != null) count--;
        showMapButton.value = true;   // показываем кнопку "Показать объекты на карте"
    } else {
        showMapButton.value = false;  // иначе скрываем кнопку
    }

    // Сохраняем результат, гарантируя, что не будет отрицательного числа
    countFilter.value = Math.max(count, 0);
};

// Метод узнать сколько есть объявлений в БД по данному запросу
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

// Метод запрос объявлений с БД
const getAds = async () => {
    if (isLastLoad.value) return;

    if (controller.value) controller.value.abort();
    controller.value = new AbortController();
    const signal = controller.value.signal;

    // Покажем гивку загрузки с задержкой
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

// 🔹 WATCHERS

// Следим за изменением флага "мои избранные"
watch(getMyLikeAds, (newVal) => {
    clearData();
    getAds();
}, { immediate: true });


// Следим за сменой категории (table_name)
watch(
    () => route.params.table_name,
    (newTable, oldTable) => {
        if (newTable !== oldTable) {
            clearData();
            getAds();
            window.scrollTo({ top: 0 }); // прокрутка вверх
        }
    },
    { deep: true }
);

// Следим за применением фильтра
watch(() => filterStore.make_filter, () => {
    clearData();
    getAds();
});

// 🔹 ON MOUNTED
onMounted(() => {
    getAds();  // загружаем объявления при монтировании компонента
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
    cursor: pointer;
}
.show-ads-page__lang-current {
    font-size: 14px;
    font-weight: 600;
    color: #007bff;
    padding: 4px 8px;
    border-radius: 6px;
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
    border-top-color: #000000;
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
    z-index: 1;
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
<!--515-->
