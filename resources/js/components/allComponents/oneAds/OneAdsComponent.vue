<template>
    <!-- КОМПОНЕНТ - ВЫВОД 1-го ОБЪЯВЛЕНИЯ -->

    <!-- Обвертка - Компонента-->
    <div class="oneAds__wrapper"  @click="$router.back()">

        <!-- Сам блок -->
        <div class="oneAds__block" @click.stop>

            <!-- Header - Кнопка назад - Дата публикации -->
            <div class="oneAds__header" :class="{'oneAds__header-show-mobile-if-not-image': ads?.images?.length === 0}">

                <!-- Кнопка назад -->
                <button class="oneAds__header-back-btn" type="button" aria-label="Назад" @click="$router.back()">←</button>

                <!-- Дата публикации объявления -->
                <span class="oneAds__header-date-publication-text">{{ $filters.transformDateRu(ads.created_at) }}</span>

            </div>

            <!-- Body - Полное описание 1-го объявления - кнопка пожаловаться -->
            <div class="oneAds__body">

                <!-- Слайдер Фото - У слайдера высота зависит от высоты фото -->
                <swiper v-if="ads && ads.images"
                        @swiper="(s) => swiperPhoto = s" role="button"
                        :modules="[ Pagination, Keyboard, Navigation ]"
                        :slides-per-view="1" :space-between="0"
                        :pagination="{type: 'fraction', el: '.swiperPhoto__pagination'}" :keyboard="true"
                        :navigation="{prevEl: '.swiperPhoto__prev-btn', nextEl: '.swiperPhoto__next-btn'}"
                        class="swiperPhoto__block"
                >

                    <!-- Вывод самого слайда - то-есть 1-го фото -->
                    <swiper-slide v-for="(img, i) in ads.images" :key="i" class="swiperPhoto__slide-block">
                        <img @click="showImage(ads, i)" :src="'/img/adsImg/' + img " class="swiperPhoto__slide-img">
                    </swiper-slide>

                    <!-- Кнопки навигации -->
                    <div class="swiperPhoto__prev-btn"></div>
                    <div class="swiperPhoto__next-btn"></div>

                    <!-- Пагинация -->
                    <div class="swiperPhoto__pagination"></div>

                    <!-- Для мобильных - Кнопка назад и дата публикации -->
                    <div class="swiperPhoto__back-btn-and-date-publication-block">

                        <!-- Кнопка назад -->
                        <button class="swiperPhoto__back-btn" type="button" aria-label="Назад" @click="$router.back()">←</button>

                        <!-- Дата публикации объявления -->
                        <span class="swiperPhoto__date-publication">{{ $filters.transformDateRu(ads.created_at) }}</span>

                    </div>

                </swiper>

                <!-- Блок - Скачать фото - Поделиться ссылкой, Поставить лайк, к-во просмотров -->
                <div class="oneAds__body-top-buttons-block">

                    <!-- Кнопка - Скачать все фото -->
                    <div class="oneAds__body-top-btn" v-if="ads?.images?.length > 0" @click="showBottomOffCanvas('Скачать или поделиться фото')">
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 4H8.8C7.11984 4 6.27976 4 5.63803 4.32698C5.07354 4.6146 4.6146 5.07354 4.32698 5.63803C4 6.27976 4 7.11984 4 8.8V15.2C4 16.8802 4 17.7202 4.32698 18.362C4.6146 18.9265 5.07354 19.3854 5.63803 19.673C6.27976 20 7.11984 20 8.8 20H15.2C16.8802 20 17.7202 20 18.362 19.673C18.9265 19.3854 19.3854 18.9265 19.673 18.362C20 17.7202 20 16.8802 20 15.2V11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M4 16L8.29289 11.7071C8.68342 11.3166 9.31658 11.3166 9.70711 11.7071L13 15M13 15L15.7929 12.2071C16.1834 11.8166 16.8166 11.8166 17.2071 12.2071L20 15M13 15L15.25 17.25" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18 3V8M18 8L16 6M18 8L20 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <!-- Кнопка -  Поделиться объявлением-->
                    <div class="oneAds__body-top-btn" @click="showBottomOffCanvas('Поделиться объявлением')">
                        <svg viewBox="0 0 24 24" width="24px" height="24px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path opacity="0.1" d="M21 6C21 7.65685 19.6569 9 18 9C16.3431 9 15 7.65685 15 6C15 4.34315 16.3431 3 18 3C19.6569 3 21 4.34315 21 6Z" fill="currentColor"></path> <path opacity="0.1" d="M21 18C21 19.6569 19.6569 21 18 21C16.3431 21 15 19.6569 15 18C15 16.3431 16.3431 15 18 15C19.6569 15 21 16.3431 21 18Z" fill="currentColor"></path> <path opacity="0.1" d="M9 12C9 13.6569 7.65685 15 6 15C4.34315 15 3 13.6569 3 12C3 10.3431 4.34315 9 6 9C7.65685 9 9 10.3431 9 12Z" fill="currentColor"></path> <path d="M21 6C21 7.65685 19.6569 9 18 9C16.3431 9 15 7.65685 15 6C15 4.34315 16.3431 3 18 3C19.6569 3 21 4.34315 21 6Z" stroke="currentColor" stroke-width="2"></path> <path d="M21 18C21 19.6569 19.6569 21 18 21C16.3431 21 15 19.6569 15 18C15 16.3431 16.3431 15 18 15C19.6569 15 21 16.3431 21 18Z" stroke="currentColor" stroke-width="2"></path> <path d="M9 12C9 13.6569 7.65685 15 6 15C4.34315 15 3 13.6569 3 12C3 10.3431 4.34315 9 6 9C7.65685 9 9 10.3431 9 12Z" stroke="currentColor" stroke-width="2"></path> <path d="M8.7207 10.6397L15.0001 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M8.70605 13.353L15 16.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    </div>

                    <!-- Количество просмотров -->
                    <div class="oneAds__body-top-btn">
                        {{ ads.view }}
                        <svg viewBox="0 0 24 24" width="24px" height="24px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M15.0007 12C15.0007 13.6569 13.6576 15 12.0007 15C10.3439 15 9.00073 13.6569 9.00073 12C9.00073 10.3431 10.3439 9 12.0007 9C13.6576 9 15.0007 10.3431 15.0007 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12.0012 5C7.52354 5 3.73326 7.94288 2.45898 12C3.73324 16.0571 7.52354 19 12.0012 19C16.4788 19 20.2691 16.0571 21.5434 12C20.2691 7.94291 16.4788 5 12.0012 5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    </div>

                    <!-- Кнопка лайк -->
                    <div class="oneAds__body-top-btn" @click="authStore.check ? addLikeToggle(): $router.push({name: $route.name + 'Auth'})">
                        {{ ads.count_like }}
                        <svg width="24px" height="24px" :fill="ads.userLike ? '#ff3b30' : 'currentColor'" viewBox="0 0 24 24">
                            <path v-if="ads.userLike"  d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5 c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                            <path v-else d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5 c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zM12 19.55l-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5 18.5 5 20 6.5 20 8.5c0 2.89-3.14 5.74-7.9 10.95l-.1.1z"/>
                        </svg>
                    </div>

                    <!-- Toggle - Переключатель языка - ru <-> kz -->
                    <div @click="langStore.toggleLang()" class="oneAds__body-top-btn">
                        {{ langStore.lang.toUpperCase() }}
                    </div>

                </div>

                <!-- Блок - Заголовок / Цена / Адрес -->
                <div class="oneAds__body-zagolovok-cena-adres-block">

                    <!-- Цена аренды -->
                    <div class="oneAds__body-cena-block">

                        <!-- Цена текст -->
                        <span class="oneAds__body-cena-text">
                            {{ $filters.format_number(ads.cena) }} &#8376;

                            <span v-if="ads.tip_sdelki == 'sdam'">
                                <span v-if="ads.period_arendi == 'na_dlitelno' || ads.period_arendi == 'na_podselenie'" > / {{ $t('one_ads')['mesyac'] }}</span>
                                <span v-if="ads.period_arendi == 'posutochno'"> / {{ $t('one_ads')['sutki'] }}</span>
                                <span v-if="ads.period_arendi == 'po_chasam'"> / {{ $t('one_ads')['chas'] }}</span>
                            </span>

                            <span v-if="ads.cena_tip != undefined && ads.tip_sdelki == 'sdam'">
                                <span v-if="ads.cena_tip == 'za_vse'"> / {{ $t('one_ads')['mesyac'] }}</span>
                                <span v-if="ads.cena_tip == 'za_kvm'"> / {{ $t('one_ads')['mesyac_za_kvm'] }}</span>
                            </span>
                        </span>

                        <!-- Срочно торг -->
                        <div v-if="ads.srochno_torg" class="oneAds__body-cena-srochno-torg">{{ $t('one_ads')['srochno_torg'] }}</div>

                        <!-- В архиве -->
                        <div v-if="ads.control == 'В архиве'" class="oneAds__body-cena-v-arhive">{{ $t('one_ads')['v_arhive'] }}</div>

                    </div>

                    <!-- Заголовок -->
                    <div class="oneAds__body-zagolovok">{{ads.zagolovok}}</div>

                    <!-- Яндекс карта - Показать расположение объекта -->
                    <div  class="oneAds__body-map-container"
                          @click="$router.push({name: $route.name + 'Map', query: {lat: ads.lat, lon:ads.lon, image: ads.images[0]} })"
                    >
                        <YandexMap v-if="showMap" :settings="settings" :coordinates="[ads.lat, ads.lon]" :controls="[]" :detailed-controls="detailedControls" :zoom="16" :behaviors="[]">
                            <YandexMarker :coordinates="[ads.lat, ads.lon]" :marker-id="123" :options="options"></YandexMarker>
                        </YandexMap>
                    </div>

                    <!-- Адрес объекта -->
                    <div class="oneAds__body-adres-block">
                        <svg viewBox="-4 0 32 32" version="1.1" width="24px" height="24px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" fill="currentColor"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>location</title> <desc>Created with Sketch Beta.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-104.000000, -411.000000)" fill="currentColor"> <path d="M116,426 C114.343,426 113,424.657 113,423 C113,421.343 114.343,420 116,420 C117.657,420 119,421.343 119,423 C119,424.657 117.657,426 116,426 L116,426 Z M116,418 C113.239,418 111,420.238 111,423 C111,425.762 113.239,428 116,428 C118.761,428 121,425.762 121,423 C121,420.238 118.761,418 116,418 L116,418 Z M116,440 C114.337,440.009 106,427.181 106,423 C106,417.478 110.477,413 116,413 C121.523,413 126,417.478 126,423 C126,427.125 117.637,440.009 116,440 L116,440 Z M116,411 C109.373,411 104,416.373 104,423 C104,428.018 114.005,443.011 116,443 C117.964,443.011 128,427.95 128,423 C128,416.373 122.627,411 116,411 L116,411 Z" id="location" sketch:type="MSShapeGroup"> </path> </g> </g> </g></svg>
                        <div class="oneAds__body-adres-inner">
                            <span class="oneAds__body-adres-ulica">
                                {{ ads.ulica }}, {{ ads.nomer_doma }}
                            </span>
                            <div class="oneAds__body-adres-gorod">
                                {{ KZLocationStore.translateLocation({gorod: ads.gorod}).gorod }}
                                <span v-if="ads.raion">{{ KZLocationStore.translateLocation({raion: ads.raion}).raion }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Блок - Параметры обьекта -->
                <div class="oneAds__body-parametri-wrapper">

                    <!-- Заголовок -->
                    <div class="oneAds__body-parametri-title">{{ $t('one_ads')['parametri'] }}</div>

                    <!-- Блок с параметрами  -->
                    <div class="oneAds__body-parametri-block">

                        <!-- К-во Комнат  -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.kolichestvo_komnat != undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['kolichestvo_komnat'] }}</div>
                            <div>{{ ads.kolichestvo_komnat }}</div>
                        </div>

                        <!-- Этаж - Этажность -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.etag != undefined||ads.etagnost!=undefined" >
                            <div v-if="ads.etag!=undefined" class="oneAds__body-parametri-item-title">{{ $t('one_ads')['etag'] }} </div>
                            <div v-if="ads.etag==undefined && ads.etagnost!=undefined " class="oneAds__body-parametri-item-title">{{ $t('one_ads')['kolichestvo_etagei'] }} </div>
                            <div>
                                <span v-if="ads.etag!=undefined">{{ ads.etag }} / </span>
                                <span v-if="ads.etagnost!=undefined">{{ ads.etagnost }}</span>
                            </div>
                        </div>

                        <!-- Площадь - Общая, кухня -->
                        <div class="oneAds__body-parametri-item-block">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['ploshad_obshaya'] }} </div>
                            <div>
                                {{ads.ploshad_obshaya}}
                                <span v-if="ads.ploshad_kuhni==undefined">м<sup>2</sup></span>
                                <span v-if="ads.ploshad_kuhni!=undefined">/ {{ ads.ploshad_kuhni }} м<sup>2</sup> </span>
                            </div>
                        </div>

                        <!-- Действующий бизнес -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.deistvuushii_bisnes != undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['deistvuushii_biznes'] }}</div>
                            <div>{{ $t('one_ads')['deistvuushii_biznes_arr'][ads.deistvuushii_bisnes] }}</div>
                        </div>

                        <!-- Для Магазин - Местоположение -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.mestopolojenie!=undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['mestopolojenie'] }}</div>
                            <div>{{ $t('one_ads')['mestopolojenie_arr'][ads.mestopolojenie] }}</div>
                        </div>

                        <!-- Для Офиса - Тип офиса -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.tip_ofisa!=undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['raspolojenie'] }}:</div>
                            <div>{{ $t('one_ads')['tip_ofisa_arr'][ads.tip_ofisa] }}</div>
                        </div>

                        <!-- Для Прочая - Сфера деятельности -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.sfera_deyatelnosti!=undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['sfera_deyatelnosti'] }}</div>
                            <div>{{ $t('one_ads')['sfera_deyatelnosti_arr'][ads.sfera_deyatelnosti] }}</div>
                        </div>

                        <!-- Состояние -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.sostoyanie!=undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['sostoyanie'] }}</div>
                            <div>{{ $t('one_ads')['sostoyanie_arr'][ads.sostoyanie] }}</div>
                        </div>

                        <!-- Мебель -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.mebel != undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['mebel'] }}</div>
                            <div>{{ $t('one_ads')['mebel_arr'][ads.mebel] }}</div>
                        </div>

                        <!-- Площадь - Участка соток -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.ploshad_uchastka!=undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['uchastok_sotok'] }}</div>
                            <div>{{ ads.ploshad_uchastka }}</div>
                        </div>

                        <!-- Для Промбазы -->
                        <!-- Площадь - Площадь производственных помещений -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.ploshad_proizvodstvennih_pomesheniy!=undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['ploshad_proizvodstvennih_pomeshenii'] }}</div>
                            <div>{{ads.ploshad_proizvodstvennih_pomesheniy}} м<sup>2</sup></div>
                        </div>
                        <!-- Высота - Высота производственных помещений -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.visota_proizvodstvennih_pomesheniy!=undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['visota_proizvodstvennih_pomeshenii'] }}</div>
                            <div>{{ads.visota_proizvodstvennih_pomesheniy}} м</div>
                        </div>
                        <!-- Площадь - Площадь складских помещений -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.ploshad_skladskih_pomesheniy!=undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['ploshad_skladskih_pomeshenii'] }}</div>
                            <div>{{ads.ploshad_skladskih_pomesheniy}} м<sup>2</sup></div>
                        </div>
                        <!-- Высота - Высота складских помещений -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.visota_skladskih_pomesheniy!=undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['visota_skladskih_pomeshenii'] }}</div>
                            <div>{{ads.visota_skladskih_pomesheniy}} м</div>
                        </div>
                        <!-- Площадь - Площадь офисных помещений -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.ploshad_ofisnih_pomesheniy!=undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['ploshad_ofisnih_pomeshenii'] }}</div>
                            <div>{{ads.ploshad_ofisnih_pomesheniy}} м<sup>2</sup></div>
                        </div>


                        <!-- Высота потолков -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.visota_potolkov!=undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['visota_potolkov'] }}</div>
                            <div>{{ads.visota_potolkov}}м</div>
                        </div>

                        <!-- Отдельный вход -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.otdelniy_vhod!=undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['otdelniy_vhod'] }}</div>
                            <div>{{ $t('one_ads')['otdelniy_vhod_arr'][ads.otdelniy_vhod] }}</div>
                        </div>

                        <!-- Душ -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.dush != undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['dush'] }}</div>
                            <div>{{ $t('one_ads')['dush_arr'][ads.dush] }}</div>
                        </div>

                        <!-- Санузел -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.sanuzel != undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['sanuzel'] }}</div>
                            <div>{{ $t('one_ads')['sanuzel_arr'][ads.sanuzel] }}</div>
                        </div>

                        <!-- Канализация -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.kanalizaciya != undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['kanalizaciya'] }}</div>
                            <div>{{ $t('one_ads')['kanalizaciya_arr'][ads.kanalizaciya] }}</div>
                        </div>

                        <!-- Вода -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.voda != undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['voda'] }}</div>
                            <div>{{ $t('one_ads')['voda_arr'][ads.voda] }}</div>
                        </div>

                        <!-- Электричество -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.elektrichestvo != undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['elektrichestvo'] }}</div>
                            <div>{{ $t('one_ads')['elektrichestvo_arr'][ads.elektrichestvo] }}</div>
                        </div>

                        <!-- Отопление -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.otoplenie != undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['otoplenie'] }}</div>
                            <div>{{ $t('one_ads')['otoplenie_arr'][ads.otoplenie] }}</div>
                        </div>

                        <!-- Газ -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.gaz != undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['gaz'] }}</div>
                            <div>{{ $t('one_ads')['gaz_arr'][ads.gaz] }}</div>
                        </div>

                        <!-- Балкон -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.balkon != undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['balkon'] }}</div>
                            <div>{{ $t('one_ads')['balkon_arr'][ads.balkon] }}</div>
                        </div>

                        <!-- Тип строения -->
                        <div class="oneAds__body-parametri-item-block" v-if="ads.tip_stroeniya != undefined">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['tip_stroeniya'] }}</div>
                            <div>{{ $t('one_ads')['tip_stroeniya_arr'][ads.tip_stroeniya] }}</div>
                        </div>

                        <!-- Год постройки -->
                        <div class="oneAds__body-parametri-item-block">
                            <div class="oneAds__body-parametri-item-title">{{ $t('one_ads')['god_postroiki'] }}</div>
                            <div>{{ads.god_postroiki}}</div>
                        </div>

                    </div>

                </div>

                <!-- Описание массивы -->
                <div class="p-2">

                    <!-- Мебель и техника -->
                    <div v-if="ads.mebel_arr != undefined && ads.mebel_arr.length != 0" class="my-4">
                        <div class="title mb-2">{{ $t('oneAdsFurnitureAndAppliances') }}</div>

                        <div class="p-2" style="font-size: 17px" v-if="langStore.lang == 'ru'">{{ads.mebel_arr.join(", ")}}</div>
                        <div class="p-2" style="font-size: 17px" v-if="langStore.lang == 'kz'">
                            <span v-for="mebel in ads.mebel_arr">
                                        {{ mebel == 'Холодильник' ? 'Тоңазытқыш, ' : ''}}
                                        {{ mebel == 'Стиральная машина' ? 'Кір жуғыш машина, ' : ''}}
                                        {{ mebel == 'Телевизор' ? 'Теледидар, ' : ''}}
                                        {{ mebel == 'Диван' ? 'Диван, ' : ''}}
                                        {{ mebel == 'Кровать' ? 'Төсек, ' : ''}}
                                        {{ mebel == 'Кухонный гарнитур' ? 'Ас үй жиынтығы, ' : ''}}
                                        {{ mebel == 'Кондиционер' ? 'Кондиционер, ' : ''}}
                                        {{ mebel == 'Микроволновая печь' ? 'Микротолқынды пеш, ' : ''}}
                                        {{ mebel == 'Вся бытовая техника' ? 'Барлық тұрмыстық техника, ' : ''}}
                                    </span>
                        </div>
                    </div>

                    <!-- Разное-->
                    <div v-if="ads.raznoe != undefined && ads.raznoe.length != 0" class="my-4">
                        <div class="title mb-2">{{ $t('oneAdsMiscellaneous') }}</div>

                        <div class="p-2" style="font-size: 17px" v-if="langStore.lang == 'ru'">{{ads.raznoe.join(", ")}}</div>
                        <div class="p-2" style="font-size: 17px" v-if="langStore.lang == 'kz'">
                                    <span v-for="raznoe in ads.raznoe">
                                        {{ raznoe == 'Кухня-студия' ? 'Ас үй студиясы, ' : ''}}
                                        {{ raznoe == 'Комнаты изолированны' ? 'Бөлмелер оқшауланған, ' : ''}}
                                        {{ raznoe == 'Кабельное TV' ? 'Кабельдік теледидар, ' : ''}}
                                        {{ raznoe == 'Кладовка' ? 'Қойма, ' : ''}}
                                        {{ raznoe == 'Бесплатный WI-FI' ? 'Тегін WI-FI, ' : ''}}
                                        {{ raznoe == 'Сад' ? 'Бақша, ' : ''}}
                                        {{ raznoe == 'Баня' ? 'Монша, ' : ''}}
                                        {{ raznoe == 'Бассейн' ? 'Бассейн, ' : ''}}
                                        {{ raznoe == 'Сауна' ? 'Сауна, ' : ''}}
                                        {{ raznoe == 'Джакузи' ? 'Ыстық ванна, ' : ''}}
                                        {{ raznoe == 'Хозпостройки' ? 'Шаруашылық құрылыстары, ' : ''}}
                                        {{ raznoe == '3 Фазы' ? '3 кезең, ' : ''}}
                                        {{ raznoe == 'С товаром' ? 'Тауармен, ' : ''}}
                                        {{ raznoe == 'С торговым оборудованием' ? 'Сауда жабдықтарымен, ' : ''}}
                                        {{ raznoe == 'Ж/д тупик' ? 'Т/ж тұйық, ' : ''}}
                                        {{ raznoe == 'Автовесы' ? 'Жүк таразылары, ' : ''}}
                                        {{ raznoe == 'Холодильные камеры' ? 'Тоңазытқыш камералар, ' : ''}}
                                        {{ raznoe == 'Кран-балка' ? 'Кран арқалығы, ' : ''}}
                                        {{ raznoe == 'Пандус' ? 'Пандус, ' : ''}}
                                        {{ raznoe == 'Своя подстанция' ? 'Өзінің қосалқы станциясы, ' : ''}}
                                    </span>
                        </div>
                    </div>

                    <!-- Расположение -->
                    <div v-if="ads.raspolojenie != undefined && ads.raspolojenie.length != 0" class="my-4">
                        <div class="title mb-2">{{ $t('oneAdsLocation') }}</div>

                        <div class="p-2" style="font-size: 17px" v-if="langStore.lang == 'ru'">{{ads.raspolojenie.join(", ")}}</div>
                        <div class="p-2" style="font-size: 17px" v-if="langStore.lang == 'kz'">
                                    <span v-for="raspolojenie in ads.raspolojenie">
                                        {{ raspolojenie == 'В городе' ? 'Қалада, ' : ''}}
                                        {{ raspolojenie == 'В пригороде' ? 'Қала маңында, ' : ''}}
                                        {{ raspolojenie == 'Вдоль трассы' ? 'Жол бойында, ' : ''}}
                                    </span>
                        </div>
                    </div>

                    <!-- Коммуникации -->
                    <div v-if="ads.kommunikacii != undefined && ads.kommunikacii != ''" class="my-4">
                        <div class="title mb-2">{{ $t('oneAdsCommunications') }}</div>

                        <div class="p-2" style="font-size: 17px" v-if="langStore.lang == 'ru'">{{ads.kommunikacii.join(", ")}}</div>
                        <div class="p-2" style="font-size: 17px" v-if="langStore.lang == 'kz'">
                                    <span v-for="kommunikacii in ads.kommunikacii">
                                        {{ kommunikacii == 'Свет' ? 'Жарық, ' : ''}}
                                        {{ kommunikacii == 'Газ' ? 'Газ, ' : ''}}
                                        {{ kommunikacii == 'Интернет' ? 'ғаламтор, ' : ''}}
                                        {{ kommunikacii == 'Отопление' ? 'Жылыту, ' : ''}}
                                        {{ kommunikacii == 'Вода' ? 'Су, ' : ''}}
                                        {{ kommunikacii == 'Телефон' ? 'Телефон, ' : ''}}
                                        {{ kommunikacii == 'Канализация' ? 'Кәріз, ' : ''}}
                                    </span>
                        </div>
                    </div>

                    <!-- Безопасность-->
                    <div v-if="ads.bezopasnost != undefined && ads.bezopasnost != ''" class="my-4">
                        <div class="title mb-2">{{ $t('oneAdsSafety') }}</div>

                        <div class="p-2" style="font-size: 17px" v-if="langStore.lang == 'ru'">{{ads.bezopasnost.join(", ")}}</div>
                        <div class="p-2" style="font-size: 17px" v-if="langStore.lang == 'kz'">
                                    <span v-for="bezopasnost in ads.bezopasnost">
                                        {{ bezopasnost == 'Домофон' ? 'Домофон, ' : ''}}
                                        {{ bezopasnost == 'Видеодомофон' ? 'Видеодомофон, ' : ''}}
                                        {{ bezopasnost == 'Сигнализация' ? 'Дабыл, ' : ''}}
                                        {{ bezopasnost == 'Решетки на окнах' ? 'Терезелердегі торлар, ' : ''}}
                                        {{ bezopasnost == 'Охрана' ? 'Күзет, ' : ''}}
                                        {{ bezopasnost == 'Видеонаблюдение' ? 'Бейнебақылау, ' : ''}}
                                        {{ bezopasnost == 'Кодовый замок' ? 'Код құлпы, ' : ''}}
                                        {{ bezopasnost == 'Консьерж' ? 'Консьерж, ' : ''}}
                                    </span>
                        </div>
                    </div>

                    <!-- Описание от хозяина-->
                    <div class="my-4">
                        <div class="title mb-2">{{ $t('oneAdsDescription') }}</div>
                        <div class="p-2" style="white-space: pre-line; font-size: 17px">

                            <!-- Это установленный компонент В компоненте - Показать - скрыть полное описание -->
                            <text-clamp :text="ads.text_obyavleniya" :max-lines="8">
                                <template #after="{ toggle, expanded, clamped }">
                                    <!-- Текст - показать - скрыть описание -->
                                    <div v-if="expanded || clamped" @click="toggle" class="text-blue-lighten-1 py-2 row g-0" role="button">
                                        <div class="col">{{expanded ? $t('oneAdsHideDescription'): $t('oneAdsShowAllDescription')}}</div>
                                        <div class="col-auto">
                                            <v-icon v-if="expanded">mdi-chevron-up</v-icon>
                                            <v-icon v-else>mdi-chevron-down</v-icon>
                                        </div>
                                    </div>
                                </template>
                            </text-clamp>
                        </div>
                    </div>

                </div>

                <!-- Кнопки - Все объявления автора / Пожаловаться на объявление -->
                <div class="oneAds__author-ads-and-complain-block">

                    <!-- Все объявления автора -->
                    <button :disabled="$route.name != 'allAdsOneAds'" @click="$router.push({name: 'userAds', params: { author_id: ads.author_id }})" class="oneAds__author-ads-btn">
                        <svg fill="currentColor" width="22px" height="22px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>bullhorn</title> <path d="M26.588 1.896c-0.17-0.092-0.372-0.146-0.587-0.146-0.261 0-0.503 0.080-0.704 0.217l0.004-0.003c-6.053 4.080-12.829 6.047-23.389 6.789-0.651 0.047-1.162 0.588-1.162 1.247v0 12c0 0.666 0.521 1.21 1.177 1.248l0.003 0c0.63 0.035 1.214 0.093 1.819 0.14v6.612c0 0.69 0.56 1.25 1.25 1.25h5c0.69-0 1.25-0.56 1.25-1.25v-5.606c5.325 1.063 10.049 3.031 14.236 5.756l-0.156-0.095c0.19 0.122 0.422 0.195 0.67 0.195 0.221 0 0.428-0.057 0.607-0.158l-0.006 0.003c0.389-0.216 0.649-0.625 0.649-1.094 0-0.001 0-0.001 0-0.002v0-26c-0-0.475-0.265-0.888-0.655-1.1l-0.007-0.003zM3.25 11.159c2.735-0.221 5.202-0.542 7.5-0.953v11.545c-1.776-0.325-3.65-0.577-5.64-0.76-0.038-0.003-0.071-0.022-0.11-0.022-0.015 0-0.028 0.008-0.043 0.009-0.568-0.051-1.119-0.111-1.707-0.151zM8.75 28.75h-2.5v-5.132c0.85 0.095 1.695 0.192 2.5 0.316zM24.75 26.783c-3.313-1.94-7.15-3.492-11.215-4.454l-0.285-0.057v-12.566c4.342-0.941 8.187-2.474 11.682-4.537l-0.182 0.099zM30 10.75c-0.69 0-1.25 0.56-1.25 1.25v8c0 0.69 0.56 1.25 1.25 1.25s1.25-0.56 1.25-1.25v0-8c-0-0.69-0.56-1.25-1.25-1.25h-0z"></path> </g></svg>
                        {{ $t('oneAdsAds') }} <strong>{{ ads.name }}</strong>
                    </button>

                    <!-- Пожаловаться на объявление -->
                    <button @click="authStore.check ? showBottomOffCanvas('Пожаловаться') : $router.push({name: $route.name + 'Auth'})" class="oneAds__complain-btn">
                        <svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"  width="22px" height="22px"  fill="currentColor" class="bi bi-exclamation-diamond"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6.95.435c.58-.58 1.52-.58 2.1 0l6.515 6.516c.58.58.58 1.519 0 2.098L9.05 15.565c-.58.58-1.519.58-2.098 0L.435 9.05a1.482 1.482 0 0 1 0-2.098L6.95.435zm1.4.7a.495.495 0 0 0-.7 0L1.134 7.65a.495.495 0 0 0 0 .7l6.516 6.516a.495.495 0 0 0 .7 0l6.516-6.516a.495.495 0 0 0 0-.7L8.35 1.134z"></path> <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"></path> </g></svg>
                        {{ $t('oneAdsComplain') }}
                    </button>

                </div>

            </div>

            <!-- footer - Кнопка - Позвонить автору -->
            <div class="oneAds__footer">
                <button class="call-button" type="button" @click="showBottomOffCanvas('Позвонить')">
                    <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.5562 14.5477L15.1007 15.0272C15.1007 15.0272 14.0181 16.167 11.0631 13.0559C8.10812 9.94484 9.1907 8.80507 9.1907 8.80507L9.47752 8.50311C10.1841 7.75924 10.2507 6.56497 9.63424 5.6931L8.37326 3.90961C7.61028 2.8305 6.13596 2.68795 5.26145 3.60864L3.69185 5.26114C3.25823 5.71766 2.96765 6.30945 3.00289 6.96594C3.09304 8.64546 3.81071 12.259 7.81536 16.4752C12.0621 20.9462 16.0468 21.1239 17.6763 20.9631C18.1917 20.9122 18.6399 20.6343 19.0011 20.254L20.4217 18.7584C21.3806 17.7489 21.1102 16.0182 19.8833 15.312L17.9728 14.2123C17.1672 13.7486 16.1858 13.8848 15.5562 14.5477Z" fill="#ffffff"/>
                        <path d="M13.2595 1.87983C13.3257 1.47094 13.7122 1.19357 14.1211 1.25976C14.1464 1.26461 14.2279 1.27983 14.2705 1.28933C14.3559 1.30834 14.4749 1.33759 14.6233 1.38082C14.9201 1.46726 15.3347 1.60967 15.8323 1.8378C16.8286 2.29456 18.1544 3.09356 19.5302 4.46936C20.906 5.84516 21.705 7.17097 22.1617 8.16725C22.3899 8.66487 22.5323 9.07947 22.6187 9.37625C22.6619 9.52466 22.6912 9.64369 22.7102 9.72901C22.7197 9.77168 22.7267 9.80594 22.7315 9.83125L22.7373 9.86245C22.8034 10.2713 22.5286 10.6739 22.1197 10.7401C21.712 10.8061 21.3279 10.53 21.2601 10.1231C21.258 10.1121 21.2522 10.0828 21.2461 10.0551C21.2337 9.9997 21.2124 9.91188 21.1786 9.79572C21.1109 9.56339 20.9934 9.21806 20.7982 8.79238C20.4084 7.94207 19.7074 6.76789 18.4695 5.53002C17.2317 4.29216 16.0575 3.59117 15.2072 3.20134C14.7815 3.00618 14.4362 2.88865 14.2038 2.82097C14.0877 2.78714 13.9417 2.75363 13.8863 2.7413C13.4793 2.67347 13.1935 2.28755 13.2595 1.87983Z" fill="#ffffff"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.4857 5.3293C13.5995 4.93102 14.0146 4.7004 14.4129 4.81419L14.2069 5.53534C14.4129 4.81419 14.4129 4.81419 14.4129 4.81419L14.4144 4.81461L14.4159 4.81505L14.4192 4.81602L14.427 4.81834L14.4468 4.8245C14.4618 4.82932 14.4807 4.8356 14.5031 4.84357C14.548 4.85951 14.6074 4.88217 14.6802 4.91337C14.8259 4.97581 15.0249 5.07223 15.2695 5.21694C15.7589 5.50662 16.4271 5.9878 17.2121 6.77277C17.9971 7.55775 18.4782 8.22593 18.7679 8.7154C18.9126 8.95991 19.009 9.15897 19.0715 9.30466C19.1027 9.37746 19.1254 9.43682 19.1413 9.48173C19.1493 9.50418 19.1555 9.52301 19.1604 9.53809L19.1665 9.55788L19.1688 9.56563L19.1698 9.56896L19.1702 9.5705C19.1702 9.5705 19.1707 9.57194 18.4495 9.77798L19.1707 9.57194C19.2845 9.97021 19.0538 10.3853 18.6556 10.4991C18.2607 10.6119 17.8492 10.3862 17.7313 9.99413L17.7276 9.98335C17.7223 9.96832 17.7113 9.93874 17.6928 9.89554C17.6558 9.8092 17.5887 9.66797 17.4771 9.47938C17.2541 9.10264 16.8514 8.53339 16.1514 7.83343C15.4515 7.13348 14.8822 6.73078 14.5055 6.50781C14.3169 6.39619 14.1757 6.32909 14.0893 6.29209C14.0461 6.27358 14.0165 6.26254 14.0015 6.25721L13.9907 6.25352C13.5987 6.13564 13.3729 5.72419 13.4857 5.3293Z" fill="#ffffff"/>
                    </svg>
                </button>
            </div>

        </div>

    </div>

    <!-- Вывожу внутринние стрницы - Фото и тд.-->
    <router-view></router-view>

</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router';
import axios from 'axios';
import Swal from 'sweetalert2';

// Swiper
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination, Mousewheel, Keyboard, Navigation } from 'swiper';
import 'swiper/scss';
import 'swiper/scss/pagination';
import 'swiper/scss/navigation';

// Stores
import { useAuthStore } from "../../../stores/auth";
import { useImagesStore } from "../../../stores/images";
import { useAdsStore } from "../../../stores/ads";
import { useKZLocationStore } from "../../../stores/KZLocation";
import { useLangStore } from '../../../stores/lang';


// TextClamp
import TextClamp from 'vue3-text-clamp';

// Yandex Map
import { YandexMap, YandexMarker } from 'vue-yandex-maps';

// Router
const route = useRoute();
const router = useRouter();

// Stores
const authStore = useAuthStore();
const imageStore = useImagesStore();
const adsStore = useAdsStore();
const langStore = useLangStore()
const KZLocationStore = useKZLocationStore();

// Data
const ads = ref('');
const query = ref(false);
const showMap = ref(false);
const swiperPhoto = ref(null);

const settings = reactive({
    apiKey: '8740b571-75d9-47f0-a5c4-582b1feaf201',
    lang: 'ru_RU',
    coordorder: 'latlong',
    enterprise: false,
    version: '2.1'
});

const detailedControls = reactive({ zoomControl: { position: { right: 0, top: 0 } } });
const options = reactive({});



// Computed
const tipObekta = computed(() => {
    if (!ads.value) return '';
    return ads.value.tip_obekta === 'Прочая' ? ads.value.zagolovok : ads.value.tip_obekta;
});

// Methods
const getOneAds = async (table_name, ads_id) => {
    if (localStorage.getItem('oneAds')) {
        ads.value = JSON.parse(localStorage.getItem('oneAds'));
        if (ads.value.lat) showMap.value = true;
    }

    try {
        const response = await axios.get('/getOneAds', {
            params: {
                user_id: authStore.check ? authStore.user.id : 0,
                role: authStore.check ? authStore.user.role : 'user',
                ads_id,
                table_name
            }
        });

        ads.value = response.data;
        localStorage.setItem('oneAds', JSON.stringify(response.data));

        showMap.value = true;
        addAdsStatistic();
    } catch (errors) {
        Swal.fire({ text: errors.response.data.error });
        router.back();
    }
};

const showImage = (ad, i) => {
    imageStore.showImages({ images: ad.images, index: i, allImg: true });
    router.push({ name: route.name + "Image" });
};

const showBottomOffCanvas = (type) => {
    if (type === 'Позвонить') addAdsViewTelStatistic();
    router.push({ name: route.name + "BottomOffCanvas", params: { type } });
};

const addAdsStatistic = async () => {
    if (ads.value.control === 'В архиве') return;

    try {
        await axios.post('/addAdsStatistic', {
            user_id: authStore.check ? authStore.user.id : 0,
            ads_id: ads.value.id,
            table_name: ads.value.table_name
        });
        ads.value.view += 1;
    } catch (error) {}
};

const addAdsViewTelStatistic = async () => {
    if (ads.value.control === 'В архиве') return;

    try {
        await axios.post('/addAdsViewTelStatistic', {
            user_id: authStore.check ? authStore.user.id : 0,
            ads_id: ads.value.id,
            table_name: ads.value.table_name
        });
    } catch (error) {}
};

const addLikeToggle = async () => {
    ads.value.userLike = !ads.value.userLike;
    ads.value.userLike ? ads.value.count_like++ : ads.value.count_like--;

    try {
        await axios.post('/like', {
            author_id: authStore.user.id,
            table_name: ads.value.table_name,
            ads_id: ads.value.id
        });
    } catch (errors) {
        ads.value.userLike = !ads.value.userLike;
        ads.value.userLike ? ads.value.count_like++ : ads.value.count_like--;
        Toast.fire({
            icon: 'error',
            title: errors.response.data.error
        });
    }
};

// Lifecycle
onMounted(() => {
    document.querySelector(':root').classList.add('PATCH_modal');
    getOneAds(route.params.table_name, route.params.ads_id);
});

onBeforeRouteLeave((to, from, next) => {
    if (to.name === 'allAds') document.querySelector(':root').classList.remove('PATCH_modal');
    next();
});
</script>



<style>
/*В этот контейнер монтируется - Яндекс карта */
.yandex-container {
    width: 100%;
    height: 100%;
}

/* Уберем значки на карте - Например: как добраться, создать новую карту, кнопка увеличения уменьшения карты */
.ymaps-2-1-79-controls__control_toolbar, .ymaps-2-1-79-gototech, .ymaps-2-1-79-controls__control, .ymaps-2-1-79-map-copyrights-promo{
    display: none!important;
}
</style>

<style scoped>

.oneAds__wrapper{
    position: fixed;
    top:0;
    left:0;
    bottom: 0;
    right: 0;
    background: rgba(0,0,0,0.8);
    overflow-y: scroll;
}
.oneAds__block{
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
    background: #ffffff;
}

.oneAds__header {
    display: none;
}
.oneAds__header-show-mobile-if-not-image{
    display: flex;
    align-items: center;
    gap: 20px;
}
.oneAds__header-back-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #ffffff;
    border: none;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    transition: background-color 0.2s ease, transform 0.1s ease;
}
.oneAds__header-back-btn:hover {
    background-color: #f0f0f0;
}
.oneAds__header-date-publication-text {
    font-size: 14px;
    color: #666;
}


/* Тело центрального основного блока */
.oneAds__body{
    padding-bottom: 100px;
}

/* Стили для Слайдера - Swiper  */
.swiperPhoto__block {
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
    position: relative;
}
.swiperPhoto__slide-block {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}
.swiperPhoto__slide-img {
    width: 100%;
    height: 350px;
    object-fit: cover;
    object-position: center;
    border-radius: 3px 3px 0 0;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: transform 0.2s ease;
}
.swiperPhoto__pagination {
    position: absolute;
    bottom: 12px;
    right: 10px;
    left: auto;
    width: auto;
    z-index: 1;
    background-color: rgba(28,24,25,.7); /* тёмный фон на всю фракцию */
    border-radius: 3px;
    padding: 2px 6px;
    font-size: 13px;
    line-height: 20px;
    color: #ffffff
}
.swiperPhoto__prev-btn {
    position: absolute;
    top: 0;
    left: 0;
    width: 40px;      /* ширина клика */
    height: 100%;     /* на всю высоту слайда */
    z-index: 10;
    cursor: pointer;
    background: transparent; /* прозрачная, чтобы не видно было */
}
.swiperPhoto__next-btn {
    position: absolute;
    top: 0;
    right: 0;
    width: 40px;      /* ширина клика */
    height: 100%;     /* на всю высоту слайда */
    z-index: 10;
    cursor: pointer;
    background: transparent; /* прозрачная, чтобы не видно было */
}
.swiperPhoto__back-btn-and-date-publication-block {
    position: absolute;
    top: 20px;
    left: 20px;
    display: flex;
    align-items: center;
    gap: 10px; /* расстояние между кнопкой и датой */
    z-index: 20; /* чтобы было над фото и кнопками навигации */
}
.swiperPhoto__back-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #ffffff;
    border: none;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    transition: background-color 0.2s ease, transform 0.1s ease;
}
.swiperPhoto__back-btn:hover {
    background-color: #f0f0f0;
}
.swiperPhoto__back-btn:active {
    transform: scale(0.95);
}
.swiperPhoto__date-publication {
    background-color: rgba(28, 24, 25, 0.7); /* как фракция */
    color: #fff;
    font-size: 13px;
    line-height: 20px;
    padding: 4px 8px;
    border-radius: 4px;
    white-space: nowrap;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}


/* Блок с кнопками ниже фото */
.oneAds__body-top-buttons-block {
    display: flex;
    justify-content: flex-end;
    gap: 8px;              /* gap-2 = 0.5rem = 8px */
    padding: 8px;          /* p-2 = 0.5rem */
}
.oneAds__body-top-btn{
    color: rgb(93, 111, 106);
    padding: 7px 12px;
    background: #F5F5F5;
    border-radius: 10px;
    cursor: pointer;
}
.oneAds__body-top-btn:hover{
    background: rgba(229, 229, 229, 0.91);
}


/* Блок - Заголовок, Цена, Адрес, Карта */
.oneAds__body-zagolovok-cena-adres-block {
    padding: 0.5rem;
    margin-bottom: 2.5rem;
}
.oneAds__body-cena-block {
    display: flex;
    align-items: center;
    margin-top: .5rem;
    padding-bottom: .5rem;
    font-size: 1rem;
}
.oneAds__body-cena-text{
    font-size: 20px;
    font-family: "Open Sans",sans-serif;;
    font-weight: 600;
    color: #1c1819;
    line-height: 28px;
}
.oneAds__body-cena-srochno-torg {
    background-color: #FBC02D;
    border-radius: 4px;
    font-size: 0.75rem;
    padding: 2px 4px;
    margin: 0 8px;
    line-height: 1.2;
    white-space: nowrap;
}
.oneAds__body-cena-v-arhive {
    background-color: #ea2a2a;  /* мягкий красный */
    color: #FFFFFF;
    border-radius: 4px;
    font-size: 0.75rem;
    padding: 2px 4px;
    margin: 0 8px;
    line-height: 1.2;
    white-space: nowrap;
}
.oneAds__body-zagolovok {
    margin-bottom: 48px;
    font-size: 18px;
    line-height: 24px;
}
.oneAds__body-map-container{
    position: relative;
    width: 95%;
    height: 100px;
    margin: auto;
    border: 1px solid rgba(28,24,25,.05);
    border-radius: 12px;
    overflow: hidden;
}
.oneAds__body-adres-block {
    display: flex;
    align-items: flex-start;
    margin-top: 16px;
}
.oneAds__body-adres-inner {
    margin-left: 12px;        /* ml-3 = 0.75rem */
    display: flex;
    flex-direction: column;
}
.oneAds__body-adres-ulica {
    text-transform: capitalize;
    font-size: 1rem;          /* text-body-1 */
    line-height: 1.5;
}
.oneAds__body-adres-gorod {
    color: #757575;           /* text-grey */
    font-size: 0.875rem;
}


/* Блок - Параметры объекта  */
.oneAds__body-parametri-wrapper {
    padding-left: 8px;
    padding-right: 8px;
}
.oneAds__body-parametri-title {
    font-size: 20px;
    font-family: "Open Sans",sans-serif;;
    font-weight: 600;
    color: #1c1819;
    line-height: 28px;
}
.oneAds__body-parametri-block {
    padding-left: 0.5rem;
}
.oneAds__body-parametri-item-block{
    font-size: 17px;
    display: flex;
    justify-content: space-between;
    max-width: 500px;
}
.oneAds__body-parametri-item-block > div {
    flex: 1;
    line-height: 28px;
}
.oneAds__body-parametri-item-title{
    color: #8d8d8d;
}


/* Стили для кнопок - Обьявления автора, Пожаловаться */
.oneAds__author-ads-and-complain-block {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin: 16px auto 0;
}
.oneAds__author-ads-btn,
.oneAds__complain-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    padding: 14px;
    border-radius: 12px;
}
.oneAds__author-ads-btn:active,
.oneAds__complain-btn:active {
    transform: scale(0.97);
}
.oneAds__author-ads-btn {
    color: #5c6f82;
    background: #f4f6f9;
}
.oneAds__author-ads-btn:hover {
    background: #e7eef9;
}
.oneAds__complain-btn {
    color: #8a5a5a;
    background: #f6f1f1;
}
.oneAds__complain-btn:hover {
    background: #f5e6e6;
}

.oneAds__footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 12px;
    z-index: 10;
}
.call-button {
    width: 100%;
    height: 52px;
    background-color: #10a37f;
    color: #fff;
    border: none;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.1s ease;
}
.call-button:hover {
    background-color: #0e8e6c;
}
.call-button:active {
    transform: scale(0.98);
}
.call-button svg {
    flex-shrink: 0;
}

/*Для заголовков*/
.title{
    font-size: 20px;
    font-family: "Open Sans",sans-serif;;
    font-weight: 600;
    color: #1c1819;
    line-height: 28px;
}



/* 📞 Маленькие телефоны (≥480px) */
@media (min-width: 480px) {

}

/* 📱 Средние телефоны (≥576px) */
@media (min-width: 576px) {

}

/* 💼 Планшеты (≥768px) */
@media (min-width: 768px) {

    /* Стили для кнопок - Обьявления автора, Пожаловаться */
    .oneAds__author-ads-and-complain-block {
        flex-direction: row;
        justify-content: center;
    }
    .oneAds__author-ads-btn,
    .oneAds__complain-btn {
        width: auto;
        min-width: 200px;
    }
}

/* Для экрана - Более 992px */
@media screen and  (min-width: 992px) {

    .oneAds__block{
        margin: 25px auto;
        border-radius: 20px 20px 10px 10px;
    }


    /* Стили для шапки блока */
    .oneAds__header {
        display: flex;
        align-items: center;
        gap: 20px;
    }


    /* Стили для тела блока */
    .oneAds__body{
        padding-bottom: 50px;
    }

    /* Слайдет - Swiper */
    .swiperPhoto__block{
        max-width: 700px;
    }
    .swiperPhoto__prev-btn, .swiperPhoto__next-btn{
        width: 60px;      /* ширина клика */
    }
    .swiperPhoto__back-btn-and-date-publication-block{
        display: none;
    }

    /* Блок с кнопками ниже фото  */
    .oneAds__body-top-buttons-block {
        padding-left: 48px;   /* px-lg-5 = 3rem = 48px */
        padding-right: 48px;
    }

    .oneAds__footer {
        position: fixed; /* Остается фиксированной */
        bottom: 0;
        left: 50%; /* Центрируем по горизонтали */
        transform: translateX(-50%);
        width: 100%; /* ширина пока 100% родителя */
        max-width: 800px; /* совпадает с .oneAds__block */
        padding: 12px;
        z-index: 1;
        display: flex;
        justify-content: center;
    }
    .call-button {
        width: 100%; /* теперь кнопка занимает всю ширину футера */
        max-width: 400px; /* или любая нужная ширина */
        height: 52px;
        font-size: 16px;
        border-radius: 14px;
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
/*1239*/
