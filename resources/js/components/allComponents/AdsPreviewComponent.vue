<template>

    <!-- Компонент вывод превью объявлений -->

    <!-- Это компонент для виртуального скрола- чтоб убрать лишнее элементы в DOM -->
    <DynamicScroller
        v-if="ads_arr && ads_arr.length > 0"
        :page-mode="true"
        class="scroller"
        :items="ads_arr"
        :min-item-size="100"
        key-field="id"
        @scroll-end="$emit('get-ads-cursor-paginate')"

    >

        <template v-slot="{ item:ads, index, active }">
            <DynamicScrollerItem
                :item="ads"
                :active="active"
                :size-dependencies="[
                  ]"
                :data-index="index"
                style="padding: 1px;"
            >
                <!-- Сам блок с превью -->
                <v-card max-width="650" style="background: #ffffff; box-shadow: none; border-radius: 6px"
                        class="mx-3 my-2 mx-sm-auto ads__preview"
                        :class="{
                    'bg-green-lighten-5': ads.top_x7 != undefined && ads.top_x7 != null,
                    'bg-red-lighten-5': ads.top_x30 != undefined && ads.top_x30 != null,
                }"
                >

                    <!--  Описание объявления -->
                    <div class="d-flex p-md-2">

                        <!-- Фото -->
                        <div class="position-relative" role="button" style="width: 170px; max-height: 150px; overflow: hidden">

                            <!-- Срочно торг -->
                            <div v-if="ads.srochno_torg" style="position: absolute; top: 5px; left: 5px;" class="bg-yellow-darken-2 rounded-sm text-caption px-1">
                                {{ $t('adsPreviewComponentUrgentBargaining') }}
                            </div>

                            <img v-if="ads.images.length > 0" @click="showImage(ads)" class="ads__preview-img rounded-2" :src=" '/img/adsImg/' + ads.images[0] " style="width: 100%; height: 150px; object-fit: cover; object-position: center" alt="Фото недвижимости">
                            <img v-else src="/public/img/siteImg/allImg/no-image-buildings.png" alt="Нет фото" style="width: 100%; height: 150px" class="ads__preview-img">

                            <!-- Если Отправленно в ТОП или ТОП х7, ТОП х30-->
                            <div class="d-flex gap-1 p-1" style="position: absolute; bottom: 0; left: 0; width: 100%; height: auto">

                                <!-- ТОП 30 -->
                                <v-menu open-on-hover>
                                    <template v-slot:activator="{ props }">
                                        <div v-if="ads.top_x30 != null" class="icon__crown" v-bind="props">
                                            <v-icon icon="mdi-crown" size="x-small" color="white"></v-icon>
                                        </div>
                                    </template>
                                    <!-- x30 просмотров на месяц -->
                                    <div class="bueAds__card">

                                        <!-- Заголовок - x30 просмотров на месяц -->
                                        <div class="d-flex py-2">

                                            <!-- Иконка -->
                                            <div class="mx-2 d-flex justify-center align-center rounded-circle"
                                                 style="width: 21px; height: 21px; background: red"
                                            >
                                                <v-icon icon="mdi-crown" size="x-small" color="white"></v-icon>
                                            </div>

                                            <!-- Текст заголовок -->
                                            <h5 class="fw-bold px-1">{{ $t('bueAdsIndexx30ViewsPerMonth') }}</h5>

                                        </div>

                                        <!-- Текст описания -->
                                        <div class="d-flex">
                                            <v-icon icon="mdi-check mx-2"></v-icon>
                                            <div class="text-grey">{{ $t('bueAdsIndex28DaysOfActivePromotion') }}</div>
                                        </div>
                                        <div class="d-flex py-2">
                                            <v-icon icon="mdi-check mx-2"></v-icon>
                                            <div class="text-grey">{{ $t('bueAdsIndexEveryDayInTheTOP') }}</div>
                                        </div>
                                        <div class="d-flex">
                                            <v-icon icon="mdi-check mx-2"></v-icon>
                                            <div class="text-grey">{{ $t('bueAdsIndexRedPainting') }}</div>
                                        </div>

                                    </div>
                                </v-menu>

                                <!-- ТОП 7 -->
                                <v-menu open-on-hover>
                                    <template v-slot:activator="{ props }">
                                        <div v-if="ads.top_x7 != null" class="icon__diamond" v-bind="props">
                                            <v-icon icon="mdi-diamond" size="x-small" color="white"></v-icon>
                                        </div>
                                    </template>
                                    <!-- x7 просмотров на неделю -->
                                    <div class="bueAds__card">

                                        <!-- Заголовок - x7 просмотров на неделю -->
                                        <div class="d-flex py-2">

                                            <!-- Иконка -->
                                            <div class="mx-2 d-flex justify-center align-center rounded-circle"
                                                 style="width: 21px; height: 21px; background: #10a37f"
                                            >
                                                <v-icon icon="mdi-diamond" size="x-small" color="white"></v-icon>
                                            </div>

                                            <!-- Текст заголовок -->
                                            <h5 class="fw-bold px-1">{{ $t('bueAdsIndexx7ViewsPerWeek') }}</h5>

                                        </div>

                                        <!-- Текст описания -->
                                        <div class="d-flex ">
                                            <v-icon icon="mdi-check mx-2"></v-icon>
                                            <div class="text-grey">{{ $t('bueAdsIndex7DaysOfActivePromotion') }}</div>
                                        </div>
                                        <div class="d-flex py-2">
                                            <v-icon icon="mdi-check mx-2"></v-icon>
                                            <div class="text-grey">{{ $t('bueAdsIndexEveryDayInTheTOP') }}</div>
                                        </div>
                                        <div class="d-flex">
                                            <v-icon icon="mdi-check mx-2"></v-icon>
                                            <div class="text-grey">{{ $t('bueAdsIndexGreenPainting') }}</div>
                                        </div>
                                    </div>
                                </v-menu>

                                <!-- ТОП 24 час -->
                                <v-menu open-on-hover>
                                    <template v-slot:activator="{ props }">
                                        <div v-if="ads.top != null" class="icon__triangle" v-bind="props">
                                            <v-icon icon="mdi-triangle" size="x-small" color="white"></v-icon>
                                        </div>
                                    </template>
                                    <div class="bueAds__card">

                                        <!-- ТОП 24 часа -->
                                        <div class="d-flex align-center">

                                            <!-- Иконка -->
                                            <div class="mx-3 d-flex justify-center align-center rounded-circle"
                                                 style="width: 21px; height: 21px; background: orange"
                                            >
                                                <v-icon icon="mdi-triangle" size="x-small" color="white"></v-icon>
                                            </div>

                                            <!-- Описание -->
                                            <div class="flex-grow-1 align-center" >
                                                <div>{{ $t('bueAdsIndexSendToTheTOP24Hours') }}</div>
                                            </div>

                                        </div>
                                    </div>
                                </v-menu>

                                <!-- 8 Раз в ТОП за 24 часа -->
                                <v-menu open-on-hover>
                                    <template v-slot:activator="{ props }">
                                        <div v-if="ads.top_8 != null" class="icon__triangle icon__triangleTOP8" v-bind="props">
                                            <v-icon icon="mdi-triangle" size="x-small" color="white"></v-icon>
                                        </div>
                                    </template>
                                    <div class="bueAds__card">

                                        <!-- Поднятие в ТОП каждые 3 часа -->
                                        <div class="d-flex align-center">

                                            <!-- Иконка -->
                                            <div class="mx-3 d-flex justify-center align-center rounded-circle"
                                                 style="width: 21px; height: 21px; background: #710250"
                                            >
                                                <v-icon icon="mdi-triangle" size="x-small" color="white"></v-icon>
                                            </div>

                                            <!-- Описание -->
                                            <div class="flex-grow-1 align-center" >
                                                <div>{{ $t('bueAdsIndexSendToTheTOP8') }}</div>
                                            </div>

                                        </div>
                                    </div>
                                </v-menu>

                            </div>
                        </div>

                        <!--Блок - Описание объявления -->
                        <div class="col pl-2">

                            <!--Блок - Описание объявления -->
                            <div @click="showOneAds(ads, index)" role="button" class="d-flex align-start flex-column" style="min-height: 115px">

                                <!-- Заголовок -->
                                <div style="font-size: 17px; color: #4b4b4b">
                                    {{ads.zagolovok}}
                                </div>

                                <!-- Цена аренды -->
                                <div class="my-auto fw-bold" style="font-size: 1.2em">
                                    {{ $filters.format_number(ads.cena) }} &#8376;
                                </div>

                                <!-- Адрес объекта -->
                                <div class="mt-auto" style="font-size: 0.9em; color: #5d6f6a">
                                    <span v-if="updateDateLocale.lang == 'ru'">{{ads.gorod}}</span>
                                    <span v-if="updateDateLocale.lang == 'kz'">
                                    {{ads.gorod == 'Астана' ? 'Астана' : ''}}
                                    {{ads.gorod == 'Алматы' ? 'Алматы' : ''}}
                                    {{ads.gorod == 'Шымкент' ? 'Шымкент' : ''}}
                                    {{ads.gorod == 'Семей' ? 'Семей' : ''}}
                                    {{ads.gorod == 'Аягоз' ? 'Аягөз' : ''}}
                                    {{ads.gorod == 'Курчатов' ? 'Курчатов' : ''}}
                                    {{ads.gorod == 'Урджар' ? 'Үржар' : ''}}
                                    {{ads.gorod == 'Чарск' ? 'Шар' : ''}}
                                    {{ads.gorod == 'Кокшетау' ? 'Көкшетау' : ''}}
                                    {{ads.gorod == 'Косшы' ? 'Қосшы' : ''}}
                                    {{ads.gorod == 'Щучинск' ? 'Щучинск' : ''}}
                                    {{ads.gorod == 'Степногорск' ? 'Степногорск' : ''}}
                                    {{ads.gorod == 'Атбасар' ? 'Атбасар' : ''}}
                                    {{ads.gorod == 'Акколь' ? 'Ақкөл' : ''}}
                                    {{ads.gorod == 'Державинск' ? 'Державин' : ''}}
                                    {{ads.gorod == 'Макинск' ? 'Макинск' : ''}}
                                    {{ads.gorod == 'Степняк' ? 'Степняк' : ''}}
                                    {{ads.gorod == 'Есиль' ? 'Есіл' : ''}}
                                    {{ads.gorod == 'Ерейментау' ? 'Ерейментау' : ''}}
                                    {{ads.gorod == 'Актобе' ? 'Ақтөбе' : ''}}
                                    {{ads.gorod == 'Кандыагаш' ? 'Қандыағаш' : ''}}
                                    {{ads.gorod == 'Алга' ? 'Алға' : ''}}
                                    {{ads.gorod == 'Жем' ? 'Жем' : ''}}
                                    {{ads.gorod == 'Темир' ? 'Темір' : ''}}
                                    {{ads.gorod == 'Хромтау' ? 'Хромтау' : ''}}
                                    {{ads.gorod == 'Шалкар' ? 'Шалқар' : ''}}
                                    {{ads.gorod == 'Эмба' ? 'Ембі' : ''}}
                                    {{ads.gorod == 'Каскелен' ? 'Қаскелең' : ''}}
                                    {{ads.gorod == 'Конаев' ? 'Қонаев' : ''}}
                                    {{ads.gorod == 'Есик' ? 'Есік' : ''}}
                                    {{ads.gorod == 'Талгар' ? 'Талғар' : ''}}
                                    {{ads.gorod == 'Узынагаш' ? 'Ұзынағаш' : ''}}
                                    {{ads.gorod == 'Шелек' ? 'Шелек' : ''}}
                                    {{ads.gorod == 'Атырау' ? 'Атырау' : ''}}
                                    {{ads.gorod == 'Кульсары' ? 'Құлсары' : ''}}
                                    {{ads.gorod == 'Усть-Каменогорск' ? 'Өскемен' : ''}}
                                    {{ads.gorod == 'Риддер' ? 'Риддер' : ''}}
                                    {{ads.gorod == 'Алтай' ? 'Алтай' : ''}}
                                    {{ads.gorod == 'Зайсан' ? 'Зайсаң' : ''}}
                                    {{ads.gorod == 'Серебрянск' ? 'Серебрянск' : ''}}
                                    {{ads.gorod == 'Шемонаиха' ? 'Шемонаиха' : ''}}
                                    {{ads.gorod == 'Тараз' ? 'Тараз' : ''}}
                                    {{ads.gorod == 'Шу' ? 'Шу' : ''}}
                                    {{ads.gorod == 'Жанатас' ? 'Жаңатас' : ''}}
                                    {{ads.gorod == 'Каратау' ? 'Қаратау' : ''}}
                                    {{ads.gorod == 'Кордай' ? 'Қордай' : ''}}
                                    {{ads.gorod == 'Сарыкемер' ? 'Сарыкемер' : ''}}
                                    {{ads.gorod == 'Толе би' ? 'Төле би' : ''}}
                                    {{ads.gorod == 'Талдыкорган' ? 'Талдықорған' : ''}}
                                    {{ads.gorod == 'Жаркент' ? 'Жаркент' : ''}}
                                    {{ads.gorod == 'Сарканд' ? 'Сарқант' : ''}}
                                    {{ads.gorod == 'Текели' ? 'Текелі' : ''}}
                                    {{ads.gorod == 'Уштобе' ? 'Үштөбе' : ''}}
                                    {{ads.gorod == 'Ушарал' ? 'Ушарал' : ''}}
                                    {{ads.gorod == 'Уральск' ? 'Орал' : ''}}
                                    {{ads.gorod == 'Аксай' ? 'Ақсай' : ''}}
                                    {{ads.gorod == 'Зачаганск' ? 'Зачаганск' : ''}}
                                    {{ads.gorod == 'Караганда' ? 'Қарағанды' : ''}}
                                    {{ads.gorod == 'Темиртау' ? 'Теміртау' : ''}}
                                    {{ads.gorod == 'Абай' ? 'Абай' : ''}}
                                    {{ads.gorod == 'Балхаш' ? 'Балқаш' : ''}}
                                    {{ads.gorod == 'Каркаралинск' ? 'Қарқаралы' : ''}}
                                    {{ads.gorod == 'Приозерск' ? 'Приозер' : ''}}
                                    {{ads.gorod == 'Сарань' ? 'Саран' : ''}}
                                    {{ads.gorod == 'Шахтинск' ? 'Шахтинск' : ''}}
                                    {{ads.gorod == 'Костанай' ? 'Қостанай' : ''}}
                                    {{ads.gorod == 'Рудный' ? 'Рудный' : ''}}
                                    {{ads.gorod == 'Аркалык' ? 'Арқалық' : ''}}
                                    {{ads.gorod == 'Аулиеколь' ? 'Әулиекөл' : ''}}
                                    {{ads.gorod == 'Житикара' ? 'Жітіқара' : ''}}
                                    {{ads.gorod == 'Качар' ? 'Қашар' : ''}}
                                    {{ads.gorod == 'Карабалык' ? 'Қарабалық' : ''}}
                                    {{ads.gorod == 'Лисаковск' ? 'Лисаковск' : ''}}
                                    {{ads.gorod == 'Сарыколь' ? 'Сарыкөл' : ''}}
                                    {{ads.gorod == 'Тобыл' ? 'Тобыл' : ''}}
                                    {{ads.gorod == 'Кызылорда' ? 'Қызылорда' : ''}}
                                    {{ads.gorod == 'Байконур' ? 'Байқоңыр' : ''}}
                                    {{ads.gorod == 'Аральск' ? 'Арал' : ''}}
                                    {{ads.gorod == 'Айтеке би' ? 'Әйтеке би' : ''}}
                                    {{ads.gorod == 'Жанакорган' ? 'Жаңақорған' : ''}}
                                    {{ads.gorod == 'Казалинск' ? 'Қазалы' : ''}}
                                    {{ads.gorod == 'Тасбогет' ? 'Тасбөгет' : ''}}
                                    {{ads.gorod == 'Шиели' ? 'Шиелі' : ''}}
                                    {{ads.gorod == 'Актау' ? 'Ақтау' : ''}}
                                    {{ads.gorod == 'Жанаозен' ? 'Жаңаөзен' : ''}}
                                    {{ads.gorod == 'Форт-Шевченко' ? 'Форт-Шевченко' : ''}}
                                    {{ads.gorod == 'Бейнеу' ? 'Бейнеу' : ''}}
                                    {{ads.gorod == 'Мангистау' ? 'Маңғыстау' : ''}}
                                    {{ads.gorod == 'Павлодар' ? 'Павлодар' : ''}}
                                    {{ads.gorod == 'Экибастуз' ? 'Екібастұз' : ''}}
                                    {{ads.gorod == 'Аксу' ? 'Ақсу' : ''}}
                                    {{ads.gorod == 'Петропавловск' ? 'Петропавл' : ''}}
                                    {{ads.gorod == 'Бесколь' ? 'Бескөл' : ''}}
                                    {{ads.gorod == 'Булаево' ? 'Булаев' : ''}}
                                    {{ads.gorod == 'Мамлютка' ? 'Мамлют' : ''}}
                                    {{ads.gorod == 'Сергеевка' ? 'Сергеев' : ''}}
                                    {{ads.gorod == 'Тайынша' ? 'Тайынша' : ''}}
                                    {{ads.gorod == 'Туркестан' ? 'Түркістан' : ''}}
                                    {{ads.gorod == 'Кентау' ? 'Кентау' : ''}}
                                    {{ads.gorod == 'Арыс' ? 'Арыс' : ''}}
                                    {{ads.gorod == 'Жетысай' ? 'Жетісай' : ''}}
                                    {{ads.gorod == 'Карабулак' ? 'Қарабұлақ' : ''}}
                                    {{ads.gorod == 'Ленгер' ? 'Леңгір' : ''}}
                                    {{ads.gorod == 'Сарыагаш' ? 'Сарыагаш' : ''}}
                                    {{ads.gorod == 'Шардара' ? 'Шардара' : ''}}
                                    {{ads.gorod == 'Жезказган' ? 'Жезқазған' : ''}}
                                    {{ads.gorod == 'Каражал' ? 'Қаражал' : ''}}
                                    {{ads.gorod == 'Сатпаев' ? 'Сәтбаев' : ''}}
                                </span>
                                    <span v-if="updateDateLocale.lang == 'en'">
                                    {{ads.gorod == 'Астана' ? 'Astana' : ''}}
                                    {{ads.gorod == 'Алматы' ? 'Almaty' : ''}}
                                    {{ads.gorod == 'Шымкент' ? 'Shymkent' : ''}}
                                    {{ads.gorod == 'Семей' ? 'Semey' : ''}}
                                    {{ads.gorod == 'Аягоз' ? 'Ayagoz' : ''}}
                                    {{ads.gorod == 'Курчатов' ? 'Kurchatov' : ''}}
                                    {{ads.gorod == 'Урджар' ? 'Urjar' : ''}}
                                    {{ads.gorod == 'Чарск' ? 'Charsk' : ''}}
                                    {{ads.gorod == 'Кокшетау' ? 'Kokshetau' : ''}}
                                    {{ads.gorod == 'Косшы' ? 'Koschi' : ''}}
                                    {{ads.gorod == 'Щучинск' ? 'Shchuchinsk' : ''}}
                                    {{ads.gorod == 'Степногорск' ? 'Stepnogorsk' : ''}}
                                    {{ads.gorod == 'Атбасар' ? 'Atbasar' : ''}}
                                    {{ads.gorod == 'Акколь' ? 'Accol' : ''}}
                                    {{ads.gorod == 'Державинск' ? 'Derzhavinsk' : ''}}
                                    {{ads.gorod == 'Макинск' ? 'Makinsk' : ''}}
                                    {{ads.gorod == 'Степняк' ? 'Stepnyak' : ''}}
                                    {{ads.gorod == 'Есиль' ? 'Esil' : ''}}
                                    {{ads.gorod == 'Ерейментау' ? 'Ereymentau' : ''}}
                                    {{ads.gorod == 'Актобе' ? 'Aktobe' : ''}}
                                    {{ads.gorod == 'Кандыагаш' ? 'Kandyagash' : ''}}
                                    {{ads.gorod == 'Алга' ? 'Alga' : ''}}
                                    {{ads.gorod == 'Жем' ? 'Jem' : ''}}
                                    {{ads.gorod == 'Темир' ? 'Temir' : ''}}
                                    {{ads.gorod == 'Хромтау' ? 'Khromtau' : ''}}
                                    {{ads.gorod == 'Шалкар' ? 'Shalkar' : ''}}
                                    {{ads.gorod == 'Эмба' ? 'Emba' : ''}}
                                    {{ads.gorod == 'Каскелен' ? 'Kaskelen' : ''}}
                                    {{ads.gorod == 'Конаев' ? 'Konaev' : ''}}
                                    {{ads.gorod == 'Есик' ? 'Esik' : ''}}
                                    {{ads.gorod == 'Талгар' ? 'Talgar' : ''}}
                                    {{ads.gorod == 'Узынагаш' ? 'Uzynagash' : ''}}
                                    {{ads.gorod == 'Шелек' ? 'Shelek' : ''}}
                                    {{ads.gorod == 'Атырау' ? 'Atyrau' : ''}}
                                    {{ads.gorod == 'Кульсары' ? 'Kulsary' : ''}}
                                    {{ads.gorod == 'Усть-Каменогорск' ? 'Ust-Kamenogorsk' : ''}}
                                    {{ads.gorod == 'Риддер' ? 'Ridder' : ''}}
                                    {{ads.gorod == 'Алтай' ? 'Altai' : ''}}
                                    {{ads.gorod == 'Зайсан' ? 'Zaisan' : ''}}
                                    {{ads.gorod == 'Серебрянск' ? 'Serebryansk' : ''}}
                                    {{ads.gorod == 'Шемонаиха' ? 'Shemonaiha' : ''}}
                                    {{ads.gorod == 'Тараз' ? 'Taraz' : ''}}
                                    {{ads.gorod == 'Шу' ? 'Shu' : ''}}
                                    {{ads.gorod == 'Жанатас' ? 'Zhanatas' : ''}}
                                    {{ads.gorod == 'Каратау' ? 'Karatau' : ''}}
                                    {{ads.gorod == 'Кордай' ? 'Kordai' : ''}}
                                    {{ads.gorod == 'Сарыкемер' ? 'Sarykemer' : ''}}
                                    {{ads.gorod == 'Толе би' ? 'Tole bi' : ''}}
                                    {{ads.gorod == 'Талдыкорган' ? 'Taldykorgan' : ''}}
                                    {{ads.gorod == 'Жаркент' ? 'Zharkent' : ''}}
                                    {{ads.gorod == 'Сарканд' ? 'Sarkand' : ''}}
                                    {{ads.gorod == 'Текели' ? 'Tekeli' : ''}}
                                    {{ads.gorod == 'Уштобе' ? 'Ushtobe' : ''}}
                                    {{ads.gorod == 'Ушарал' ? 'Usharal' : ''}}
                                    {{ads.gorod == 'Уральск' ? 'Uralsk' : ''}}
                                    {{ads.gorod == 'Аксай' ? 'Aksay' : ''}}
                                    {{ads.gorod == 'Зачаганск' ? 'Zachagansk' : ''}}
                                    {{ads.gorod == 'Караганда' ? 'Karaganda' : ''}}
                                    {{ads.gorod == 'Темиртау' ? 'Temirtau' : ''}}
                                    {{ads.gorod == 'Абай' ? 'Abai' : ''}}
                                    {{ads.gorod == 'Балхаш' ? 'Balkhash' : ''}}
                                    {{ads.gorod == 'Каркаралинск' ? 'Karkaralinsk' : ''}}
                                    {{ads.gorod == 'Приозерск' ? 'Priozersk' : ''}}
                                    {{ads.gorod == 'Сарань' ? 'Saran' : ''}}
                                    {{ads.gorod == 'Шахтинск' ? 'Shakhtinsk' : ''}}
                                    {{ads.gorod == 'Костанай' ? 'Kostanay' : ''}}
                                    {{ads.gorod == 'Рудный' ? 'Rudny' : ''}}
                                    {{ads.gorod == 'Аркалык' ? 'Arkalyk' : ''}}
                                    {{ads.gorod == 'Аулиеколь' ? 'Auliekol' : ''}}
                                    {{ads.gorod == 'Житикара' ? 'Zhitikara' : ''}}
                                    {{ads.gorod == 'Качар' ? 'Kachar' : ''}}
                                    {{ads.gorod == 'Карабалык' ? 'Karabalyk' : ''}}
                                    {{ads.gorod == 'Лисаковск' ? 'Lisakovsk' : ''}}
                                    {{ads.gorod == 'Сарыколь' ? 'Sarykol' : ''}}
                                    {{ads.gorod == 'Тобыл' ? 'Tobyl' : ''}}
                                    {{ads.gorod == 'Кызылорда' ? 'Kyzylorda' : ''}}
                                    {{ads.gorod == 'Байконур' ? 'Baikonur' : ''}}
                                    {{ads.gorod == 'Аральск' ? 'Aralsk' : ''}}
                                    {{ads.gorod == 'Айтеке би' ? 'Aiteke bi' : ''}}
                                    {{ads.gorod == 'Жанакорган' ? 'Zhanakorgan' : ''}}
                                    {{ads.gorod == 'Казалинск' ? 'Kazalinsk' : ''}}
                                    {{ads.gorod == 'Тасбогет' ? 'Tasboget' : ''}}
                                    {{ads.gorod == 'Шиели' ? 'Shieli' : ''}}
                                    {{ads.gorod == 'Актау' ? 'Aktau' : ''}}
                                    {{ads.gorod == 'Жанаозен' ? 'Zhanaozen' : ''}}
                                    {{ads.gorod == 'Форт-Шевченко' ? 'Fort Shevchenko' : ''}}
                                    {{ads.gorod == 'Бейнеу' ? 'Beyneu' : ''}}
                                    {{ads.gorod == 'Мангистау' ? 'Mangystau' : ''}}
                                    {{ads.gorod == 'Павлодар' ? 'Pavlodar' : ''}}
                                    {{ads.gorod == 'Экибастуз' ? 'Ekibastuz' : ''}}
                                    {{ads.gorod == 'Аксу' ? 'Aksu' : ''}}
                                    {{ads.gorod == 'Петропавловск' ? 'Petropavlovsk' : ''}}
                                    {{ads.gorod == 'Бесколь' ? 'Beskol' : ''}}
                                    {{ads.gorod == 'Булаево' ? 'Bulaevo' : ''}}
                                    {{ads.gorod == 'Мамлютка' ? 'Mamlyutka' : ''}}
                                    {{ads.gorod == 'Сергеевка' ? 'Sergeyevka' : ''}}
                                    {{ads.gorod == 'Тайынша' ? 'Tayynsha' : ''}}
                                    {{ads.gorod == 'Туркестан' ? 'Turkestan' : ''}}
                                    {{ads.gorod == 'Кентау' ? 'Kentau' : ''}}
                                    {{ads.gorod == 'Арыс' ? 'Arys' : ''}}
                                    {{ads.gorod == 'Жетысай' ? 'Zhetysay' : ''}}
                                    {{ads.gorod == 'Карабулак' ? 'Karabulak' : ''}}
                                    {{ads.gorod == 'Ленгер' ? 'Lenger' : ''}}
                                    {{ads.gorod == 'Сарыагаш' ? 'Saryagash' : ''}}
                                    {{ads.gorod == 'Шардара' ? 'Shardara' : ''}}
                                    {{ads.gorod == 'Жезказган' ? 'Zhezkazgan' : ''}}
                                    {{ads.gorod == 'Каражал' ? 'Karazhal' : ''}}
                                    {{ads.gorod == 'Сатпаев' ? 'Satpaev' : ''}}
                                </span>

                                    <span v-if="ads.raion != undefined && ads.raion != ''">,
                                <span v-if="updateDateLocale.lang == 'ru'">{{ads.raion}}</span>
                                <span v-if="updateDateLocale.lang == 'kz'">
                                        {{ads.raion == 'Алматинский р-н' ? 'Алматы ау.' : ''}}
                                        {{ads.raion == 'Есильский р-н' ? 'Есіл ау.' : ''}}
                                        {{ads.raion == 'Байконурский р-н' ? 'Байқоңыр ау.' : ''}}
                                        {{ads.raion == 'Сарыаркинский р-н' ? 'Сарыарқа ау.' : ''}}
                                        {{ads.raion == 'Нуринский р-н' ? 'Нұра ау.' : ''}}
                                        {{ads.raion == 'Алатауский р-н' ? 'Алатау ау.' : ''}}
                                        {{ads.raion == 'Алмалинский р-н' ? 'Алмалы ау.' : ''}}
                                        {{ads.raion == 'Ауэзовский р-н' ? 'Әуезов ау.' : ''}}
                                        {{ads.raion == 'Бостандыкский р-н' ? 'Бостандық ау.' : ''}}
                                        {{ads.raion == 'Жетысуский р-н' ? 'Жетісу ау.' : ''}}
                                        {{ads.raion == 'Медеуский р-н' ? 'Медеу ау.' : ''}}
                                        {{ads.raion == 'Наурызбайский р-н' ? 'Наурызбай ау.' : ''}}
                                        {{ads.raion == 'Турксибский р-н' ? 'Түрксіб ау.' : ''}}
                                        {{ads.raion == 'Абайский р-н' ? 'Абай ау.' : ''}}
                                        {{ads.raion == 'Аль-Фарабийский р-н' ? 'Әл-Фараби ау.' : ''}}
                                        {{ads.raion == 'Енбекшинский р-н' ? 'Еңбекші ау.' : ''}}
                                        {{ads.raion == 'Каратауский р-н' ? 'Қаратау ау.' : ''}}
                                        {{ads.raion == 'Туранский р-н' ? 'Тұран ау.' : ''}}
                                        {{ads.raion == 'Казыбек би р-н' ? 'Қазыбек би ау.' : ''}}
                                        {{ads.raion == 'Алихана Бокейханова р-н' ? 'Әлихан Бөкейханов ау.' : ''}}
                                    </span>
                                <span v-if="updateDateLocale.lang == 'en'">
                                        {{ads.raion == 'Алматинский р-н' ? 'Almaty district' : ''}}
                                        {{ads.raion == 'Есильский р-н' ? 'Yesil district' : ''}}
                                        {{ads.raion == 'Байконурский р-н' ? 'Baikonur district' : ''}}
                                        {{ads.raion == 'Сарыаркинский р-н' ? 'Saryarka district' : ''}}
                                        {{ads.raion == 'Нуринский р-н' ? 'Nura district' : ''}}
                                        {{ads.raion == 'Алатауский р-н' ? 'Alatau district' : ''}}
                                        {{ads.raion == 'Алмалинский р-н' ? 'Almaly district' : ''}}
                                        {{ads.raion == 'Ауэзовский р-н' ? 'Auezov district' : ''}}
                                        {{ads.raion == 'Бостандыкский р-н' ? 'Bostandyk district' : ''}}
                                        {{ads.raion == 'Жетысуский р-н' ? 'Zhetysu district' : ''}}
                                        {{ads.raion == 'Медеуский р-н' ? 'Medeu district' : ''}}
                                        {{ads.raion == 'Наурызбайский р-н' ? 'Nauryzbay district' : ''}}
                                        {{ads.raion == 'Турксибский р-н' ? 'Turksib district' : ''}}
                                        {{ads.raion == 'Абайский р-н' ? 'Abay district' : ''}}
                                        {{ads.raion == 'Аль-Фарабийский р-н' ? 'Al-Farabi district' : ''}}
                                        {{ads.raion == 'Енбекшинский р-н' ? 'Enbekshi district' : ''}}
                                        {{ads.raion == 'Каратауский р-н' ? 'Karatau district' : ''}}
                                        {{ads.raion == 'Туранский р-н' ? 'Turan district' : ''}}
                                        {{ads.raion == 'Казыбек би р-н' ? 'Kazybek bi district' : ''}}
                                        {{ads.raion == 'Алихана Бокейханова р-н' ? 'Alikhana Bokeikhanov district' : ''}}
                                    </span>
                            </span>
                                </div>

                            </div>

                            <!-- Дата публикации - Лайк -->
                            <div class="d-flex align-center gap-2 position-relative">

                                <!-- Дата публикации -->
                                <div style="font-size: 0.9em; color: #5d6f6a">
                                    {{ $filters.transformDateRu(ads.created_at) }}
                                </div>

                                <v-spacer></v-spacer>

                                <!-- Кнопка лайк -->
                                <span>
                                    <v-icon :color="ads.userLike ? 'red' : 'grey-lighten-3'"
                                            class="icon__heart mx-1"
                                            @click="authStore.check ? addLikeToggle(index, ads): $router.push({name: $route.name + 'Auth'})"
                                    >
                                    mdi-heart
                                    </v-icon>
                                    <v-tooltip activator="parent" location="bottom">{{ $t('AdsPreviewAddFavorites') }}</v-tooltip>
                                </span>


                            </div>

                        </div>

                    </div>

                    <!--  - Управление объявлением - Продвигать рекламу - Сдать быстрее -->
                    <div class="px-md-2 py-1 bg-grey-lighten-2"
                         v-if="authStore.check && authStore.user.id == ads.author_id
                    && $route.name == 'userAds' && ads.control != 'В архиве'
                    || authStore.check && authStore.user.role == 'admin' && ads.control != 'В архиве'"
                    >

                        <div class="d-flex justify-content-between align-center">

                            <!-- Кнопка сдать быстрее -->
                            <v-btn dark color="blue-darken-2"
                                   size="x-large"
                                   @click="$router.push({ name: $route.name + 'BueAds', params: {ads_id: ads.id} } )"
                                   class="text-body-1"
                                   style="min-width: 170px"
                            >
                                {{ $t('adsPreviewComponentPassFaster') }}
                            </v-btn>

                            <!-- Просмотров - Взяли номера -->
                            <div class="dropup-center dropup">

                                <!-- Кнопка - Окрыть скрытое меню -->
                                <v-btn icon size="x-large" data-bs-toggle="dropdown" aria-expanded="false">
                                    <v-icon>mdi-finance</v-icon>
                                </v-btn>

                                <!-- Тело - Скрытого меню-->
                                <div class="dropdown-menu p-3">
                                    <h4 class="text-center fw-bold mb-3">{{ $t('AdsPreviewAddStatistics') }}</h4>
                                    <div>{{ $t('AdsPreviewAddViewed') }} : <span class="fw-bold">{{ads.view}}</span></div>
                                    <div class="mt-2">{{ $t('AdsPreviewAddGotTheNumber') }} : <span class="fw-bold">{{ads.viewTel}}</span></div>
                                </div>
                            </div>

                            <!-- Блок - Управление объявлением - для автора и админа -->
                            <div class="dropstart">

                                <!-- Кнопка - Окрыть скрытое меню -->
                                <v-btn icon size="x-large" data-bs-toggle="dropdown" aria-expanded="false">
                                    <v-icon>mdi-dots-vertical</v-icon>
                                </v-btn>

                                <!-- Тело - Скрытого меню-->
                                <div class="dropdown-menu p-0 border-none">
                                    <div class="d-flex align-center justify-end gap-3 px-3 bg-grey-lighten-2"
                                         style="width: 90vw; max-width: 600px"
                                    >

                                        <!-- Кнопка - Рекламировать или Отановить объявление -->
                                        <v-btn dark
                                               color="blue-darken-2"
                                               class="text-body-1"
                                               size="x-large"
                                               @click="adsActiveToggle(index, ads.id, ads.table_name, ads.control)"
                                               :disabled="query"
                                        >
                                            {{ads.control == 'Активно' ? $t('adsPreviewComponentStop') : $t('adsPreviewComponentToAdvertise')}}
                                        </v-btn>

                                        <!-- Кнопка - Редактировать обьявление -->
                                        <v-btn dark icon
                                               size="x-large"
                                               color="blue-darken-2"
                                               @click="$router.push({name: $route.name + 'AddAds', params: {id: ads.id, step:1}})"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </v-btn>

                                        <!-- Кнопка - Удалить объявление -->
                                        <v-btn dark icon
                                               size="x-large"
                                               color="blue-darken-2"
                                               @click="deleteAds(index, ads.id, ads.table_name, ads.control)"
                                               :disabled="query"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </v-btn>

                                    </div>
                                </div>

                            </div>

                        </div>

                        <!-- На сайте до-->
                        <div class="px-1 px-md-0">
                            <span v-if="updateDateLocale.lang == 'ru'">На сайте до: </span>
                            <span v-if="updateDateLocale.lang == 'en'">Before: </span>
                            <span v-if="ads.top_x30 != null">{{ addDaysToCurrentDate(ads.top_x30, 30) }}</span>
                            <span v-else-if="ads.top_x7 != null">{{ addDaysToCurrentDate(ads.top_x7, 7) }}</span>
                            <span v-else>{{ addDaysToCurrentDate(ads.updated_at, 7) }}</span>
                            <span v-if="updateDateLocale.lang == 'kz'" class="pl-1"> дейін</span>

                        </div>

                    </div>

                    <!-- Жалобы на объявления - Если поступили 5 жалоб - Они видны автору - Объявление отправиться на доработку  -->
                    <div v-if="authStore.check && authStore.user.id == ads.author_id && $route.name == 'userAds'">

                        <div v-if="ads.control == 'Поступили жалобы' " class="col-12 alert" style="background: #efa6a6; padding: 1.7px 10px!important;">
                            <i class="bi bi-exclamation-octagon"></i>
                            {{ $t('adsPreviewComponentReturnForRevision')}}
                            <div>{{ $t('adsPreviewComponentCause') }} :
                                <span v-if="ads.complain[0] == 'Объявление не актуально'">{{ $t('oneAdsBottomOffCanvasTheAdIsNotRelevant') }}</span>
                                <span v-if="ads.complain[0] == 'Ошибка в цене'">{{ $t('oneAdsBottomOffCanvasPriceError') }}</span>
                                <span v-if="ads.complain[0] == 'Некорректные фотографии'">{{ $t('oneAdsBottomOffCanvasIncorrectPhotos') }}</span>
                                <span v-if="ads.complain[0] == 'Ответил риелтор'">{{ $t('oneAdsBottomOffCanvasTheRealtorReplied') }}</span>
                                <span v-if="ads.complain[0] == 'Телефон не отвечает'">{{ $t('oneAdsBottomOffCanvasThePhoneIsNotAnswering') }}</span>
                                <span v-if="ads.complain[0] == 'Обман или ложное объявление'">{{ $t('oneAdsBottomOffCanvasDeceptionOrFalseAnnouncement') }}</span>
                            </div>
                        </div>

                    </div>

                </v-card>

            </DynamicScrollerItem>

        </template>

    </DynamicScroller>


</template>

<script>

//Импортирую Store - Общее состояние
import { useAuthStore } from "../../stores/auth";
import { useCheckInternetStore } from "../../stores/checkInternet";
import { useImagesStore } from "../../stores/images";
import { useGetProjectDataStore } from "../../stores/getProjectData";
import { useAdsStore } from "../../stores/ads";
import {useUpdateDateLocaleStore} from "../../stores/updateDateLocale";

// Компонент динамический скроллер - Скрывает лишние элементы из DOM
import { DynamicScroller, DynamicScrollerItem } from 'vue-virtual-scroller'
import 'vue-virtual-scroller/dist/vue-virtual-scroller.css'

export default {
    name: "AdsPreviewComponent",

    components: {
        DynamicScroller,
        DynamicScrollerItem
    },

    props: {
        ads_arr: Array,
        getMyLikeAds: Boolean, //Это нужно чтоб знать сейчас запрос избранного или нет если да то мы можем при отмене избранного удалить его из массива
    },

    data(){
        return {

            //Подключаю Store - Наше общее состояние
            authStore: useAuthStore(),
            checkInternetStore: useCheckInternetStore(),
            imageStore: useImagesStore(),
            getProjectDataStore: useGetProjectDataStore(),
            adsStore: useAdsStore(),
            updateDateLocale: useUpdateDateLocaleStore(),

            ads_array: '',
            query: false,

        }
    },


    watch: {
        ads_arr(){
            this.ads_array = this.ads_arr;
        },

        //Отслеживаем изменение маршрута - И обновляем данные 1-го
        '$route' (to, from) {
            if(to.name == 'allAds' || to.name == 'mapClusterShowAds'){

                if(localStorage.getItem('oneAds') != undefined){
                    this.ads_array[localStorage.getItem('oneAdsIndex')] = JSON.parse(localStorage.getItem('oneAds'));
                    //Удалим
                    localStorage.removeItem('oneAdsIndex')
                    localStorage.removeItem('oneAds')
                }

            }
        },

        //Следим за сменой языка
        async 'updateDateLocale.lang' (){
            this.ads_array = {};
            this.ads_array = this.ads_arr;
        }
    },

    methods: {

        //Открыть 1-но объявление
        async showOneAds(ads,index){
            this.query = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            await this.checkInternetStore.checkInternet();

            localStorage.setItem('oneAdsIndex', index)
            localStorage.setItem('oneAds', JSON.stringify(ads))

            this.$router.push({name: this.$route.name + "OneAds" ,params: {ads_id: ads.id }})
        },

        // Показать фото
        showImage(ads){
            this.imageStore.showImages({images: ads.images,index: 0, allImg: true})
            this.$router.push({name: this.$route.name + "Image"});
        },

        //Метод - Рекламировать или Остановить - рекламу объявления
        async adsActiveToggle(index, ads_id, table_name, ads_control){
            this.query = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            await this.checkInternetStore.checkInternet()


            axios.post('/adsActiveToggle', {
                ads_id, table_name
            })
                .then((response)=>{
                    this.query = false;

                    //Укажем активно или нет
                    ads_control == 'Активно' ? this.ads_arr[index].control = 'Не активно' : this.ads_arr[index].control = 'Активно';

                })
                .catch(errors=>{
                    this.query = false;

                    //Если объявление было удалено
                    if(errors.response.data.error == 'Объявление было удалено')this.ads_arr.splice(index, 1);

                    //Если объявление было отправлено в архив
                    if(errors.response.data.error == 'Объявление находиться в архиве')this.ads_arr[index].control = 'В архиве'

                    Toast.fire({
                        text: errors.response.data.error
                    })

                })
        },

        //Метод добавить в избранное
       async addLikeToggle(index, ads){

            this.query = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
           await this.checkInternetStore.checkInternet()

            //Для быстрого реагирования добавим без проверки и далее в проверке уже подтвердим
            this.ads_arr[index].userLike = !this.ads_arr[index].userLike;

            axios.post('/like',{
                author_id: this.authStore.user.id,
                table_name: ads.table_name,
                ads_id: ads.id
            })
                .then(response=>{
                    this.query = false;
                    //Если метод вызван когда запрос на мои избранные то удалим данное избранно из моих
                    this.getMyLikeAds ? this.ads_arr.splice(index, 1) : '';

                })
                .catch(errors=>{
                    this.query = false;

                    //Toggle своиства избранного, меняем данные у данного объявления без перезагрузки
                    this.ads_arr[index].userLike = !this.ads_arr[index].userLike;

                    //Если объявление уже стало не активно уведомим что не активно
                    Toast.fire({
                        icon: 'error',
                        title: errors.response.data.error
                    })

                })
        },

        //Метод - Отправить в архив -  на 7 дней . Далее автоматом будет удалено
        async deleteAds(index, ads_id, table_name){
            Swal.fire({
                title: this.$t('adsPreviewComponentSendToArchive'),
                showCancelButton: true,
                confirmButtonText: this.$t('adsPreviewComponentYes'),
                cancelButtonText: this.$t('adsPreviewComponentNo')
            })
                .then(async (result) => {
                    if (result.isConfirmed) {
                        this.query = true;

                        //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
                        await this.checkInternetStore.checkInternet()

                        axios.delete('/deleteAds', {params:{
                                ads_id, table_name,
                            }})
                            .then((response)=>{
                                this.query = false;

                                Toast.fire({
                                    title: this.$t('adsPreviewComponentRemoved')
                                })

                                //Уберем из массива данное объявление
                                this.ads_arr.splice(index, 1);

                            })
                            .catch((errors)=>{
                                this.query = false;
                                Toast.fire({
                                    title: this.$t('adsPreviewComponentError')
                                })
                            })
                    }
                })
        },

        // Высчитать до кокого числа зармещено объявление
        addDaysToCurrentDate(date, days) {
            if (date) {
                const newDate = new Date(date);
                newDate.setDate(newDate.getDate() + days);
                return this.$filters.transformDateRuNotWatch(newDate); // Здесь используйте ваш фильтр для форматирования даты
            }
        },

    }

}
</script>

<style scoped>

/* Стиль для - виртуального скролла */
.scroller {
    width: 100%;
    height: 100%;
}


.ads__preview{
    user-select: none;
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


/* Стили для иконок */
.icon__crown, .icon__diamond, .icon__triangle{
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 0 5px;
    border-radius: 50%;
    width: 21px;
    height: 21px;
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

</style>

