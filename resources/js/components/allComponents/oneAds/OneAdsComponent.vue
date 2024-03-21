<template>
    <!-- КОМПОНЕНТ - ВЫВОД 1-го ОБЪЯВЛЕНИЯ -->

    <!-- Обвертка - Компонента-->
    <div class="oneAds__wrapper"  @click="$router.back()">

        <!-- Сам блок -->
        <div class="oneAds__block" @click.stop>

            <!-- Header -->
            <div class="oneAds__header d-flex align-center">

                <!-- Кнопка назад -->
                <v-btn icon dark @click="$router.back()">
                    <v-icon size="large">mdi-arrow-left</v-icon>
                </v-btn>

                <!-- Дата публикации объявления -->
                <span>{{ $filters.transformDateRu(ads.created_at) }}</span>

            </div>

            <!-- Body - Полное описание 1-го объявления - кнопка пожаловаться -->
            <div class="oneAds__body">

                <!-- Блок - Полное описание 1-го объявления -->
                <article>

                    <!-- Слайдер Фото - У слайдера высота зависит от высоты фото - height=300px-->
                    <swiper @swiper="oneAdsSwiper" role="button" v-if="ads && ads.images" :modules="modules" :slides-per-view="1" :space-between="0"
                            :keyboard="true" :pagination="{type: 'fraction'}"
                    >

                        <!-- Вывод самого слайда - то-есть 1-го фото -->
                        <swiper-slide v-for="(img, i) in ads.images" :key="i" class="px-lg-5 position-relative">
                            <img @click="showImage(ads, i)" :src="'/img/adsImg/' + img " style="width: 100%; height: 350px; object-fit: cover; object-position: center; border-radius: 3px 3px 0 0; box-shadow: 0 0 5px #e3e3e3; z-index: 0">

                            <!-- Кнопки перетаскивания -->
                            <div class="swiper-button-next" @click="slidePrev()"></div>
                            <div class="swiper-button-prev" @click="slideNext()"></div>
                        </swiper-slide>

                    </swiper>


                    <!-- Блок - Скачать фото - Поделиться ссылкой, Поставить лайк, к-во просмотров -->
                    <div class="d-flex justify-end gap-2 px-lg-5 p-2">

                        <!-- Кнопка - Написать - для компьютера -->
                        <div class="ads_header-btn d-none d-lg-block"
                             v-if="$route.query.tel == undefined"
                             @click="authStore.check ? showBottomOffCanvas('Написать'): $router.push({ name: $route.name + 'Auth' })">
                            <v-icon>mdi-message-processing</v-icon>
                        </div>

                        <!-- Кнопка -  Позвонить - для компьютера -->
                        <div class="ads_header-btn d-none d-lg-block" @click="showBottomOffCanvas('Позвонить')">
                            <v-icon>mdi-phone</v-icon>
                        </div>

                        <!-- Кнопка - Скачать все фото -->
                        <div class="ads_header-btn"
                             v-if="ads && ads.images && ads.images.length > 0"
                             @click="showBottomOffCanvas('Скачать или поделиться фото')"
                        >
                            <v-icon>mdi-cloud-download-outline</v-icon>
                        </div>

                        <!-- Кнопка -  Поделиться объявлением-->
                        <div class="ads_header-btn" @click="showBottomOffCanvas('Поделиться объявлением')">
                            <v-icon>mdi-share-variant-outline</v-icon>
                        </div>

                        <!-- Количество просмотров -->
                        <div class="ads_header-btn">
                            {{ ads.view }}
                            <v-icon>mdi-eye-outline</v-icon>
                        </div>

                        <!-- Кнопка лайк -->
                        <div class="ads_header-btn" @click="authStore.check ? addLikeToggle(): $router.push({name: $route.name + 'Auth'})">
                            {{ ads.countLike }}
                            <v-icon :class="{'text-red': ads.userLike}">mdi-heart</v-icon>
                        </div>

                        <!-- Выбор языка -->
                        <div class="ads_header-btn">
                            <span @click="$router.push({name: $route.name + 'Lang'})">{{ updateDateLocale.lang == 'kz' ? 'Қаз': '' }}</span>
                            <span @click="$router.push({name: $route.name + 'Lang'})">{{ updateDateLocale.lang == 'ru' ? 'Рус': '' }}</span>
                            <span @click="$router.push({name: $route.name + 'Lang'})">{{ updateDateLocale.lang == 'en' ? 'Eng': '' }}</span>
                        </div>

                    </div>

                    <!-- Верхний блок - Заголовок / Цена / Адрес -->
                    <div class="px-2 py-2">

                        <!-- Цена аренды -->
                        <div class="pb-2 d-flex align-center text-body-1 mt-2">

                            <!-- Цена -->
                            <span class="title">
                                    {{ $filters.format_number(ads.cena) }} &#8376;
                                    /
                                    <span v-if="ads.period_arendi == 'На длительно' || ads.period_arendi == 'На подселение'" >{{ $t('oneAdsMonth') }}</span>
                                    <span v-if="ads.period_arendi == 'Посуточно'">{{ $t('oneAdsDaily') }}</span>
                                    <span v-if="ads.period_arendi == 'По часам'">{{ $t('oneAdsHour') }}</span>

                                     <span v-if="ads.cena_tip != undefined">
                                         <span v-if="ads.cena_tip == 'За все'">{{ $t('oneAdsPerMonth') }}</span>
                                         <span v-if="ads.cena_tip == 'За кв.м'">{{ $t('oneAdsPerMonthPerSq.M') }}</span>
                                     </span>
                                </span>

                            <!-- Срочно торг -->
                            <div v-if="ads.srochno_torg" class="bg-yellow-darken-2 rounded-sm text-caption px-1 mx-2">
                                {{ $t('oneAdsUrgentBargaining') }}
                            </div>

                        </div>

                        <!-- Заголовок -->
                        <div class="mb-5" style="font-size: 18px; line-height: 24px;">
                            {{ads.zagolovok}}
                        </div>

                        <!-- Яндекс карта - Показать расположение объекта -->
                        <div  class="map__container">
                            <YandexMap v-if="showMap"
                                       :settings="settings"
                                       :coordinates="[ads.lat, ads.lon]"
                                       :controls="[]"
                                       :detailed-controls="detailedControls"
                                       :zoom="16"
                                       :behaviors="[]"
                            >
                                <YandexMarker :coordinates="[ads.lat, ads.lon]"
                                              :marker-id="123"
                                              :options="options"
                                ></YandexMarker>
                            </YandexMap>

                            <!-- Кнопка открыть карту на весь экран -->
                            <div style="position: absolute; top: 0; left: 0; right:0; bottom:0" role="button"
                                 @click="$router.push({name: $route.name + 'Map', query: {lat: ads.lat, lon:ads.lon, image: ads.images[0]} })"
                            >
                            </div>

                        </div>

                        <!-- Адрес объекта -->
                        <div class="mt-3 d-flex">
                            <i class="bi bi-geo-alt text-grey text-body-1"></i>
                            <div class="ml-3">
                                <span v-if="ads.table_name!='Snimu'"  style="text-transform: capitalize">{{ ads.ulica }}, {{ads.nomer_doma}}</span>
                                <div class="text-grey">
                                    {{ KZLocationStore.translateLocation({gorod: ads.gorod}).gorod }}

                                    <span v-if="ads.raion != undefined && ads.raion != ''">,
                                        {{ KZLocationStore.translateLocation({raion: ads.raion}).raion }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Кто автор - Хозяин - Риелтор - В архиве -->
                        <v-btn variant="flat" color="grey-lighten-4" size="large" class="w-100 text-body-1 my-3 mt-4">
                            <span v-if="ads.control == 'Активно'">
                                <span v-if="ads.ownerOrRealtor == 'Хозяин'">{{ $t('oneAdsOwner') }}</span>
                                <span v-if="ads.ownerOrRealtor == 'Специалист'">{{ $t('oneAdsSpecialist') }}</span>
                                <span v-if="ads.ownerOrRealtor == 'Можно от специалиста'">{{ $t('oneAdsCanBeFromASpecialist') }}</span>
                            </span>
                            <span v-else>
                                <span v-if="updateDateLocale.lang == 'ru'">{{ads.control}}</span>
                                <span v-if="updateDateLocale.lang == 'kz'">
                                        <span v-if="ads.control == 'В архиве'">Мұрағатта</span>
                                        <span v-else-if="ads.control == 'Поступили жалобы'">Шағымдар түсті</span>
                                        <span v-else>{{ads.control}}</span>
                                    </span>
                                <span v-if="updateDateLocale.lang == 'en'">
                                        <span v-if="ads.control == 'В архиве'">In the archive</span>
                                        <span v-else-if="ads.control == 'Поступили жалобы'">Complaints have been received</span>
                                        <span v-else>{{ads.control}}</span>
                                    </span>
                            </span>
                        </v-btn>
                    </div>

                    <!-- Вывожу Основное описание объявления -->
                    <div class="px-2">

                        <!-- Заголовок - О квартире - О общежитии-  -->
                        <div class="mb-2 title">

                            <span v-if="ads.tip_obekta=='Квартира'"> {{ $t('oneAdsAboutTheApartment') }} </span>
                            <span v-if="ads.tip_obekta=='Общежитие'"> {{ $t('oneAdsAboutTheHostel') }} </span>
                            <span v-if="ads.tip_obekta=='Дом'"> {{ $t('oneAdsAboutTheHouse') }} </span>
                            <span v-if="ads.tip_obekta=='Дача'"> {{ $t('oneAdsAboutTheCountryHouse') }} </span>
                            <span v-if="ads.tip_obekta=='Коттедж'"> {{ $t('oneAdsAboutTheCottage') }} </span>
                            <span v-if="ads.tip_obekta=='Офис'"> {{ $t('oneAdsAboutTheOffice') }} </span>
                            <span v-if="ads.tip_obekta=='Здание'"> {{ $t('oneAdsAboutTheBuilding') }} </span>
                            <span v-if="ads.tip_obekta=='Магазин'"> {{ $t('oneAdsAboutTheShop') }} </span>
                            <span v-if="ads.tip_obekta=='Бутик'"> {{ $t('oneAdsAboutTheBoutique') }} </span>
                            <span v-if="ads.tip_obekta=='Киоск'"> {{ $t('oneAdsAboutTheKiosk') }} </span>
                            <span v-if="ads.tip_obekta=='Контейнер'"> {{ $t('oneAdsAboutTheContainer') }} </span>
                            <span v-if="ads.tip_obekta=='Промбаза'"> {{ $t('oneAdsAboutTheBase') }} </span>
                            <span v-if="ads.tip_obekta=='Завод'"> {{ $t('oneAdsAboutTheFactory') }} </span>
                            <span v-if="ads.tip_obekta=='Склад бытовой'"> {{ $t('oneAdsAboutTheHouseholdWarehouse') }} </span>
                            <span v-if="ads.tip_obekta=='Склад продавольственный'"> {{ $t('oneAdsAboutTheFoodWarehouse') }} </span>
                            <span v-if="ads.tip_obekta=='Склад химпродукции'"> {{ $t('oneAdsAboutTheWarehouseOfChemicalProducts') }} </span>
                            <span v-if="ads.tip_obekta=='Прочая'"> {{ $t('oneAdsAboutTheOther') }} </span>
                            <span v-if="ads.tip_obekta=='Бизнес'"> {{ $t('oneAdsAboutTheBusiness') }} </span>

                        </div>

                        <!-- Описание -->
                        <div class="pl-2">

                            <!-- К-во Комнат  -->
                            <div class="btn__item" v-if="ads.kolichestvo_komnat != undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsCountRooms') }}</div>
                                <div>{{ ads.kolichestvo_komnat }}</div>
                            </div>


                            <!-- Этаж - Этажность -->
                            <div class="btn__item" v-if="ads.etag != undefined||ads.etagnost!=undefined" >
                                <div v-if="ads.etag!=undefined" class="btn__item-cat">{{ $t('oneAdsFloor') }} </div>
                                <div v-if="ads.etag==undefined && ads.etagnost!=undefined " class="btn__item-cat">{{ $t('oneAdsNumberOfFloors') }} </div>
                                <div>
                                    <span v-if="ads.etag!=undefined">{{ ads.etag }}</span>
                                    <span v-if="ads.etag!=undefined" class="px-1">{{ $t('oneAdsFrom') }} </span>
                                    <span v-if="ads.etagnost!=undefined">{{ ads.etagnost }}</span>
                                </div>
                            </div>

                            <!-- Площадь - Общая, кухня -->
                            <div class="btn__item">
                                <div class="btn__item-cat">{{ $t('oneAdsTotalArea') }} </div>
                                <div>
                                    {{ads.ploshad_obshaya}}
                                    <span v-if="ads.ploshad_kuhni==undefined">м<sup>2</sup></span>
                                    <span v-if="ads.ploshad_kuhni!=undefined">/ {{ads.ploshad_kuhni}} м<sup>2</sup> </span>
                                </div>
                            </div>

                            <!-- Действующий бизнес -->
                            <div class="btn__item" v-if="ads.deistvuushii_bisnes != undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsOperatingBusiness') }}</div>
                                <div>{{ads.deistvuushii_bisnes == 'Да' ? $t('oneAdsYes') : $t('oneAdsNo') }}</div>
                            </div>

                            <!-- Для Магазин - Местоположение -->
                            <div class="btn__item" v-if="ads.mestopolojenie!=undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsLocationText') }}</div>
                                <div v-if="updateDateLocale.lang == 'ru'">{{ads.mestopolojenie}}</div>
                                <div v-if="updateDateLocale.lang == 'kz'">
                                    {{ads.mestopolojenie == 'В торговом-центре' ? 'Сауда орталығында' :''}}
                                    {{ads.mestopolojenie == 'В административном здании' ? 'Әкімшілік ғимаратта' :''}}
                                    {{ads.mestopolojenie == 'На универсальном рынке (Барахолке)' ? 'Әмбебап нарықта (Барахолка)' :''}}
                                    {{ads.mestopolojenie == 'В жилом доме' ? 'Тұрғын үйде' :''}}
                                    {{ads.mestopolojenie == 'Отдельно стоящее здание' ? 'Жеке ғимарат' :''}}
                                    {{ads.mestopolojenie == 'Остановочный комплекс' ? 'Аялдама кешені' :''}}
                                    {{ads.mestopolojenie == 'Другое' ? 'Басқа' :''}}
                                </div>
                                <div v-if="updateDateLocale.lang == 'en'">
                                    {{ads.mestopolojenie == 'В торговом-центре' ? 'In the shopping center' :''}}
                                    {{ads.mestopolojenie == 'В административном здании' ? 'In the administrative building' :''}}
                                    {{ads.mestopolojenie == 'На универсальном рынке (Барахолке)' ? 'At the universal market (Flea Market)' :''}}
                                    {{ads.mestopolojenie == 'В жилом доме' ? 'In a residential building' :''}}
                                    {{ads.mestopolojenie == 'Отдельно стоящее здание' ? 'Detached building' :''}}
                                    {{ads.mestopolojenie == 'Остановочный комплекс' ? 'Bus stop complex' :''}}
                                    {{ads.mestopolojenie == 'Другое' ? 'Other' :''}}
                                </div>
                            </div>

                            <!-- Для Офиса - Тип офиса -->
                            <div class="btn__item" v-if="ads.tip_ofisa!=undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsLocation') }}:</div>
                                <div v-if="updateDateLocale.lang == 'ru'">{{ads.tip_ofisa}}</div>
                                <div v-if="updateDateLocale.lang == 'kz'">
                                    {{ads.tip_ofisa == 'В бизнес-центре' ? 'Бизнес орталығында' :''}}
                                    {{ads.tip_ofisa == 'В административном здании' ? 'Әкімшілік ғимаратта' :''}}
                                    {{ads.tip_ofisa == 'В жилом доме' ? 'Тұрғын үйде' :''}}
                                    {{ads.tip_ofisa == 'В коттедже' ? 'Коттеджде' :''}}
                                    {{ads.tip_ofisa == 'Другое' ? 'Басқа' :''}}
                                </div>
                                <div v-if="updateDateLocale.lang == 'en'">
                                    {{ads.tip_ofisa == 'В бизнес-центре' ? 'In the business center' :''}}
                                    {{ads.tip_ofisa == 'В административном здании' ? 'In the administrative building' :''}}
                                    {{ads.tip_ofisa == 'В жилом доме' ? 'In a residential building' :''}}
                                    {{ads.tip_ofisa == 'В коттедже' ? 'In the cottage' :''}}
                                    {{ads.tip_ofisa == 'Другое' ? 'Other' :''}}
                                </div>
                            </div>

                            <!-- Для Прочая - Сфера деятельности -->
                            <div class="btn__item" v-if="ads.sfera_deyatelnosti!=undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsFieldOfActivity') }}</div>
                                <div v-if="updateDateLocale.lang == 'ru'">{{ads.sfera_deyatelnosti}}</div>
                                <div v-if="updateDateLocale.lang == 'kz'">
                                    {{ads.sfera_deyatelnosti == 'Производство' ? 'Өндіріс' :''}}
                                    {{ads.sfera_deyatelnosti == 'Общепит' ? 'Тамақтану' :''}}
                                    {{ads.sfera_deyatelnosti == 'Развлечения' ? 'Ойын-сауық' :''}}
                                    {{ads.sfera_deyatelnosti == 'Торговля' ? 'Сауда' :''}}
                                    {{ads.sfera_deyatelnosti == 'Услуги' ? 'Қызметтер' :''}}
                                    {{ads.sfera_deyatelnosti == 'Сельское хозяйство' ? 'Ауыл шаруашылығы' :''}}
                                    {{ads.sfera_deyatelnosti == 'Другое' ? 'Басқа' :''}}

                                </div>
                                <div v-if="updateDateLocale.lang == 'en'">
                                    {{ads.sfera_deyatelnosti == 'Производство' ? 'Production' :''}}
                                    {{ads.sfera_deyatelnosti == 'Общепит' ? 'Catering' :''}}
                                    {{ads.sfera_deyatelnosti == 'Развлечения' ? 'Entertainments' :''}}
                                    {{ads.sfera_deyatelnosti == 'Торговля' ? 'Trading' :''}}
                                    {{ads.sfera_deyatelnosti == 'Услуги' ? 'Services' :''}}
                                    {{ads.sfera_deyatelnosti == 'Сельское хозяйство' ? 'Agricultural industry' :''}}
                                    {{ads.sfera_deyatelnosti == 'Другое' ? 'Other' :''}}
                                </div>
                            </div>

                            <!-- Состояние -->
                            <div class="btn__item" v-if="ads.sostoyanie!=undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsState') }}</div>
                                <div v-if="updateDateLocale.lang == 'ru'">{{ads.sostoyanie}}</div>
                                <div v-if="updateDateLocale.lang == 'kz'">
                                    {{ads.sostoyanie == 'Евро ремонт' ? 'Еуро жөндеу' :''}}
                                    {{ads.sostoyanie == 'Хорошее' ? 'Жақсы' :''}}
                                    {{ads.sostoyanie == 'Среднее' ? 'Орташа' :''}}
                                    {{ads.sostoyanie == 'Требует ремонта' ? 'Жөндеуді қажет етеді' :''}}
                                </div>
                                <div v-if="updateDateLocale.lang == 'en'">
                                    {{ads.sostoyanie == 'Евро ремонт' ? 'Euro repair' :''}}
                                    {{ads.sostoyanie == 'Хорошее' ? 'Good' :''}}
                                    {{ads.sostoyanie == 'Среднее' ? 'Average' :''}}
                                    {{ads.sostoyanie == 'Требует ремонта' ? 'Requires repair' :''}}
                                </div>
                            </div>

                            <!-- Мебель -->
                            <div class="btn__item" v-if="ads.mebel != undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsFurniture') }}</div>

                                <div v-if="ads.mebel == 'Нет'">{{ $t('oneAdsNo') }}</div>

                                <div v-if="ads.mebel != 'Нет' && updateDateLocale.lang == 'ru'">{{ads.mebel}}</div>
                                <div v-if="ads.mebel != 'Нет' && updateDateLocale.lang == 'kz'">
                                    {{ads.mebel == 'Полностью' ? 'Толық' :''}}
                                    {{ads.mebel == 'Частично' ? 'Ішінара' :''}}
                                </div>
                                <div v-if="ads.mebel != 'Нет' && updateDateLocale.lang == 'en'">
                                    {{ads.mebel == 'Полностью' ? 'Completely' :''}}
                                    {{ads.mebel == 'Частично' ? 'Partly' :''}}
                                </div>
                            </div>

                            <!-- Площадь - Участка соток -->
                            <div class="btn__item" v-if="ads.ploshad_uchastka!=undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsPlotAcres') }}</div>
                                <div>{{ ads.ploshad_uchastka }}</div>
                            </div>

                            <!-- Для Промбазы -->
                            <!-- Площадь - Площадь производственных помещений -->
                            <div class="btn__item" v-if="ads.ploshad_proizvodstvennih_pomesheniy!=undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsAreaOfIndustrialPremises') }}</div>
                                <div>{{ads.ploshad_proizvodstvennih_pomesheniy}} м<sup>2</sup></div>
                            </div>
                            <!-- Высота - Высота производственных помещений -->
                            <div class="btn__item" v-if="ads.visota_proizvodstvennih_pomesheniy!=undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsHeightOfProductionPremises') }}</div>
                                <div>{{ads.visota_proizvodstvennih_pomesheniy}} м</div>
                            </div>
                            <!-- Площадь - Площадь складских помещений -->
                            <div class="btn__item" v-if="ads.ploshad_skladskih_pomesheniy!=undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsWarehouseArea') }}</div>
                                <div>{{ads.ploshad_skladskih_pomesheniy}} м<sup>2</sup></div>
                            </div>
                            <!-- Высота - Высота складских помещений -->
                            <div class="btn__item" v-if="ads.visota_skladskih_pomesheniy!=undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsWarehouseHeight') }}</div>
                                <div>{{ads.visota_skladskih_pomesheniy}} м</div>
                            </div>
                            <!-- Площадь - Площадь офисных помещений -->
                            <div class="btn__item" v-if="ads.ploshad_ofisnih_pomesheniy!=undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsAreaOfOfficePremises') }}</div>
                                <div>{{ads.ploshad_ofisnih_pomesheniy}} м<sup>2</sup></div>
                            </div>

                            <!-- Высота потолков -->
                            <div class="btn__item" v-if="ads.visota_potolkov!=undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsCeilingHeight') }}</div>
                                <div>{{ads.visota_potolkov}}м</div>
                            </div>

                            <!-- Отдельный вход -->
                            <div class="btn__item" v-if="ads.otdelniy_vhod!=undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsSeparateEntrance') }}</div>

                                <div v-if="ads.otdelniy_vhod == 'Нет'" >{{ $t('oneAdsNo') }}</div>
                                <div v-if="ads.otdelniy_vhod != 'Нет'" >{{ $t('oneAdsThereIs') }}</div>
                            </div>


                            <!-- Душ -->
                            <div class="btn__item" v-if="ads.dush != undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsShower') }}</div>

                                <div v-if="ads.dush == 'Нет'">{{ $t('oneAdsNo') }}</div>

                                <div v-if="ads.dush != 'Нет'" >
                                    <span v-if="updateDateLocale.lang == 'ru'">{{ads.dush}}</span>
                                    <span v-if="updateDateLocale.lang == 'kz'">
                                        {{ads.dush == 'В комнате' ? 'Бөлмеде' :''}}
                                        {{ads.dush == 'В секции' ? 'Секцияда' :''}}
                                    </span>
                                    <span v-if="updateDateLocale.lang == 'en'">
                                        {{ads.dush == 'В комнате' ? 'In the room' :''}}
                                        {{ads.dush == 'В секции' ? 'In the section' :''}}
                                    </span>
                                </div>
                            </div>

                            <!-- Санузел -->
                            <div class="btn__item" v-if="ads.sanuzel != undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsBathroom') }}</div>

                                <div v-if="ads.sanuzel == 'Нет'">{{ $t('oneAdsNo') }}</div>

                                <div v-if="ads.sanuzel != 'Нет'">
                                    <span v-if="updateDateLocale.lang == 'ru'">{{ads.sanuzel}}</span>
                                    <span v-if="updateDateLocale.lang == 'kz'">
                                        {{ads.sanuzel == 'Раздельный' ? 'Бөлек' :''}}
                                        {{ads.sanuzel == 'Совмещенный' ? 'Біріктірілген' :''}}
                                        {{ads.sanuzel == '2 с/у и более' ? '2 с/y және одан көп' :''}}
                                        {{ads.sanuzel == 'В комнате' ? 'Бөлмеде' :''}}
                                        {{ads.sanuzel == 'В секции' ? 'Секцияда' :''}}
                                        {{ads.sanuzel == 'В доме' ? 'Үйде' :''}}
                                        {{ads.sanuzel == 'Во дворе' ? 'Аулада' :''}}
                                        {{ads.sanuzel == 'Есть' ? 'Бар' :''}}
                                    </span>
                                    <span v-if="updateDateLocale.lang == 'en'">
                                        {{ads.sanuzel == 'Раздельный' ? 'Separate' :''}}
                                        {{ads.sanuzel == 'Совмещенный' ? 'Combined' :''}}
                                        {{ads.sanuzel == '2 с/у и более' ? '2 or more' :''}}
                                        {{ads.sanuzel == 'В комнате' ? 'In the room' :''}}
                                        {{ads.sanuzel == 'В секции' ? 'In the section' :''}}
                                        {{ads.sanuzel == 'В доме' ? 'In a house' :''}}
                                        {{ads.sanuzel == 'Во дворе' ? 'In the courtyard' :''}}
                                        {{ads.sanuzel == 'Есть' ? 'There are' :''}}
                                    </span>
                                </div>
                            </div>

                            <!-- Канализация -->
                            <div class="btn__item" v-if="ads.kanalizaciya != undefined">

                                <div class="btn__item-cat">{{ $t('oneAdsSewers') }}</div>
                                <div v-if="ads.kanalizaciya == 'Нет'">{{ $t('oneAdsNo') }}</div>

                                <div v-if="ads.kanalizaciya != 'Нет'">
                                    <span v-if="updateDateLocale.lang == 'ru'">{{ads.kanalizaciya}}</span>
                                    <span v-if="updateDateLocale.lang == 'kz'">
                                        {{ads.kanalizaciya == 'Центральная' ? 'Орталық' :''}}
                                        {{ads.kanalizaciya == 'Септик' ? 'Септик' :''}}
                                    </span>
                                    <span v-if="updateDateLocale.lang == 'en'">
                                        {{ads.kanalizaciya == 'Центральная' ? 'Central' :''}}
                                        {{ads.kanalizaciya == 'Септик' ? 'Septic tank' :''}}
                                    </span>
                                </div>

                            </div>

                            <!-- Вода -->
                            <div class="btn__item" v-if="ads.voda != undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsWater') }}</div>

                                <div v-if="ads.voda == 'Нет'">{{ $t('oneAdsNo') }}</div>

                                <div v-if="ads.voda != 'Нет'">
                                    <span v-if="updateDateLocale.lang == 'ru'">{{ads.voda}}</span>
                                    <span v-if="updateDateLocale.lang == 'kz'">
                                        {{ads.voda == 'Центральная' ? 'Орталық' :''}}
                                        {{ads.voda == 'Скважина' ? 'Ұңғыма' :''}}
                                    </span>
                                    <span v-if="updateDateLocale.lang == 'en'">
                                        {{ads.voda == 'Центральная' ? 'Central' :''}}
                                        {{ads.voda == 'Скважина' ? 'Borehole' :''}}
                                    </span>
                                </div>

                            </div>

                            <!-- Электричество -->
                            <div class="btn__item" v-if="ads.elektrichestvo != undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsElectricity') }}</div>

                                <div v-if="ads.elektrichestvo == 'Нет'">{{ $t('oneAdsNo') }}</div>

                                <div v-if="ads.elektrichestvo != 'Нет'">
                                    <span v-if="updateDateLocale.lang == 'ru'">{{ads.elektrichestvo}}</span>
                                    <span v-if="updateDateLocale.lang == 'kz'">
                                        {{ads.elektrichestvo == 'Есть' ? 'Бар' :''}}
                                    </span>
                                    <span v-if="updateDateLocale.lang == 'en'">
                                        {{ads.elektrichestvo == 'Есть' ? 'There are' :''}}
                                    </span>
                                </div>

                            </div>

                            <!-- Отопление -->
                            <div class="btn__item" v-if="ads.otoplenie != undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsHeating') }}</div>

                                <div v-if="ads.otoplenie == 'Нет'">{{ $t('oneAdsNo') }}</div>

                                <div v-if="ads.otoplenie != 'Нет'">
                                    <span v-if="updateDateLocale.lang == 'ru'">{{ads.otoplenie}}</span>
                                    <span v-if="updateDateLocale.lang == 'kz'">
                                        {{ads.otoplenie == 'Центральное' ? 'Орталық' :''}}
                                        {{ads.otoplenie == 'На газе' ? 'Газбен' :''}}
                                        {{ads.otoplenie == 'На твердом топливе' ? 'Қатты отынмен' :''}}
                                        {{ads.otoplenie == 'На жидком топливе' ? 'Сұйық отынмен' :''}}
                                        {{ads.otoplenie == 'Смешанное' ? 'Аралас' :''}}
                                    </span>
                                    <span v-if="updateDateLocale.lang == 'en'">
                                        {{ads.otoplenie == 'Центральное' ? 'Central' :''}}
                                        {{ads.otoplenie == 'На газе' ? 'On gas' :''}}
                                        {{ads.otoplenie == 'На твердом топливе' ? 'On solid fuel' :''}}
                                        {{ads.otoplenie == 'На жидком топливе' ? 'On liquid fuel' :''}}
                                        {{ads.otoplenie == 'Смешанное' ? 'Mixed' :''}}
                                    </span>
                                </div>

                            </div>

                            <!-- Газ -->
                            <div class="btn__item" v-if="ads.gaz != undefined">
                                <div class="btn__item-cat">{{ $t('oneAdsGas') }}</div>

                                <div v-if="ads.gaz == 'Нет'">{{ $t('oneAdsNo') }}</div>

                                <div v-if="ads.gaz != 'Нет'">
                                    <span v-if="updateDateLocale.lang == 'ru'">{{ads.gaz}}</span>
                                    <span v-if="updateDateLocale.lang == 'kz'">
                                        {{ads.gaz == 'Магистральный' ? 'Магистральдық' :''}}
                                        {{ads.gaz == 'Автономный' ? 'Автономды' :''}}
                                    </span>
                                    <span v-if="updateDateLocale.lang == 'en'">
                                        {{ads.gaz == 'Магистральный' ? 'Trunk' :''}}
                                        {{ads.gaz == 'Автономный' ? 'Autonomous' :''}}
                                    </span>
                                </div>

                            </div>

                            <!-- Балкон -->
                            <div class="btn__item" v-if="ads.balkon != undefined">
                                <div v-if="ads.balkon == 'Нет'"  class="btn__item-cat">{{ $t('oneAdsBalcony') }}</div>
                                <div v-if="ads.balkon == 'Нет'">{{ $t('oneAdsNo') }}</div>

                                <div v-if="ads.balkon != 'Нет' && updateDateLocale.lang == 'ru'" class="btn__item-cat">{{ads.balkon}}</div>
                                <div v-if="ads.balkon != 'Нет' && updateDateLocale.lang == 'kz'" class="btn__item-cat">
                                    {{ads.balkon == 'Балкон' ? 'Балкон' :''}}
                                    {{ads.balkon == 'Лоджия' ? 'Лоджия' :''}}
                                    {{ads.balkon == '2 балкона и более' ? '2 балкон және одан да көп' :''}}
                                </div>
                                <div v-if="ads.balkon != 'Нет' && updateDateLocale.lang == 'en'" class="btn__item-cat">
                                    {{ads.balkon == 'Балкон' ? 'Balcony' :''}}
                                    {{ads.balkon == 'Лоджия' ? 'Loggia' :''}}
                                    {{ads.balkon == '2 балкона и более' ? '2 balconies or more' :''}}
                                </div>
                                <div v-if="ads.balkon != 'Нет'">{{ $t('oneAdsThereIs') }}</div>
                            </div>

                            <!-- Тип строения -->
                            <div class="btn__item" v-if="ads.tip_stroeniya!=undefined && ads.table_name != 'Snimu'">
                                <div class="btn__item-cat">{{ $t('oneAdsBuildingType') }}</div>
                                <div v-if="updateDateLocale.lang == 'ru'">{{ads.tip_stroeniya}}</div>
                                <div v-if="updateDateLocale.lang == 'kz'">
                                    {{ads.tip_stroeniya == 'Панель' ? 'Панель' :''}}
                                    {{ads.tip_stroeniya == 'Кирпич' ? 'Кірпіш' :''}}
                                    {{ads.tip_stroeniya == 'Дерево' ? 'Ағаш' :''}}
                                    {{ads.tip_stroeniya == 'Другое' ? 'Басқа' :''}}
                                </div>
                                <div v-if="updateDateLocale.lang == 'en'">
                                    {{ads.tip_stroeniya == 'Панель' ? 'Panel' :''}}
                                    {{ads.tip_stroeniya == 'Кирпич' ? 'Brick' :''}}
                                    {{ads.tip_stroeniya == 'Дерево' ? 'Tree' :''}}
                                    {{ads.tip_stroeniya == 'Другое' ? 'Other' :''}}
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- Описание массивы -->
                    <div class="p-2" style="text-align: justify">

                        <!-- Мебель и техника -->
                        <div v-if="ads.mebel_arr != undefined && ads.mebel_arr.length != 0" class="my-4">
                            <div class="title mb-2">{{ $t('oneAdsFurnitureAndAppliances') }}</div>

                            <div class="p-2" style="font-size: 17px" v-if="updateDateLocale.lang == 'ru'">{{ads.mebel_arr.join(", ")}}</div>
                            <div class="p-2" style="font-size: 17px" v-if="updateDateLocale.lang == 'kz'">
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
                            <div class="p-2" style="font-size: 17px" v-if="updateDateLocale.lang == 'en'">
                                     <span v-for="mebel in ads.mebel_arr">
                                        {{ mebel == 'Холодильник' ? 'Fridge, ' : ''}}
                                        {{ mebel == 'Стиральная машина' ? 'Washer, ' : ''}}
                                        {{ mebel == 'Телевизор' ? 'TV, ' : ''}}
                                        {{ mebel == 'Диван' ? 'Sofa, ' : ''}}
                                        {{ mebel == 'Кровать' ? 'Bed, ' : ''}}
                                        {{ mebel == 'Кухонный гарнитур' ? 'Kitchen set, ' : ''}}
                                        {{ mebel == 'Кондиционер' ? 'Conditioner, ' : ''}}
                                        {{ mebel == 'Микроволновая печь' ? 'A microwave, ' : ''}}
                                        {{ mebel == 'Вся бытовая техника' ? 'All household appliances, ' : ''}}
                                    </span>
                            </div>
                        </div>

                        <!-- Разное-->
                        <div v-if="ads.raznoe != undefined && ads.raznoe.length != 0" class="my-4">
                            <div class="title mb-2">{{ $t('oneAdsMiscellaneous') }}</div>

                            <div class="p-2" style="font-size: 17px" v-if="updateDateLocale.lang == 'ru'">{{ads.raznoe.join(", ")}}</div>
                            <div class="p-2" style="font-size: 17px" v-if="updateDateLocale.lang == 'kz'">
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
                            <div class="p-2" style="font-size: 17px" v-if="updateDateLocale.lang == 'en'">
                                     <span v-for="raznoe in ads.raznoe">
                                        {{ raznoe == 'Кухня-студия' ? 'Studio Kitchen, ' : ''}}
                                        {{ raznoe == 'Комнаты изолированны' ? 'The rooms are isolated, ' : ''}}
                                        {{ raznoe == 'Кабельное TV' ? 'Cable TV, ' : ''}}
                                        {{ raznoe == 'Кладовка' ? 'Storage, ' : ''}}
                                        {{ raznoe == 'Бесплатный WI-FI' ? 'Free WI-FI, ' : ''}}
                                        {{ raznoe == 'Сад' ? 'Garden, ' : ''}}
                                        {{ raznoe == 'Баня' ? 'Bathhouse, ' : ''}}
                                        {{ raznoe == 'Бассейн' ? 'Swimming pool, ' : ''}}
                                        {{ raznoe == 'Сауна' ? 'Sauna, ' : ''}}
                                        {{ raznoe == 'Джакузи' ? 'Jacuzzi, ' : ''}}
                                        {{ raznoe == 'Хозпостройки' ? 'Outbuildings, ' : ''}}
                                        {{ raznoe == '3 Фазы' ? '3 Phases, ' : ''}}
                                        {{ raznoe == 'С товаром' ? 'With the product, ' : ''}}
                                        {{ raznoe == 'С торговым оборудованием' ? 'With commercial equipment, ' : ''}}
                                        {{ raznoe == 'Ж/д тупик' ? 'Railway dead end, ' : ''}}
                                        {{ raznoe == 'Автовесы' ? 'Car weights, ' : ''}}
                                        {{ raznoe == 'Холодильные камеры' ? 'Cold storage rooms, ' : ''}}
                                        {{ raznoe == 'Кран-балка' ? 'Crane beam, ' : ''}}
                                        {{ raznoe == 'Пандус' ? 'Ramp, ' : ''}}
                                        {{ raznoe == 'Своя подстанция' ? 'Own substation, ' : ''}}
                                    </span>
                            </div>
                        </div>

                        <!-- Расположение -->
                        <div v-if="ads.raspolojenie != undefined && ads.raspolojenie.length != 0" class="my-4">
                            <div class="title mb-2">{{ $t('oneAdsLocation') }}</div>

                            <div class="p-2" style="font-size: 17px" v-if="updateDateLocale.lang == 'ru'">{{ads.raspolojenie.join(", ")}}</div>
                            <div class="p-2" style="font-size: 17px" v-if="updateDateLocale.lang == 'kz'">
                                    <span v-for="raspolojenie in ads.raspolojenie">
                                        {{ raspolojenie == 'В городе' ? 'Қалада, ' : ''}}
                                        {{ raspolojenie == 'В пригороде' ? 'Қала маңында, ' : ''}}
                                        {{ raspolojenie == 'Вдоль трассы' ? 'Жол бойында, ' : ''}}
                                    </span>
                            </div>
                            <div class="p-2" style="font-size: 17px" v-if="updateDateLocale.lang == 'en'">
                                    <span v-for="raspolojenie in ads.raspolojenie">
                                        {{ raspolojenie == 'В городе' ? 'In the city, ' : ''}}
                                        {{ raspolojenie == 'В пригороде' ? 'In the suburbs, ' : ''}}
                                        {{ raspolojenie == 'Вдоль трассы' ? 'Along the highway, ' : ''}}
                                    </span>
                            </div>
                        </div>

                        <!-- Коммуникации -->
                        <div v-if="ads.kommunikacii != undefined && ads.kommunikacii != '' && ads.table_name != 'Snimu'" class="my-4">
                            <div class="title mb-2">{{ $t('oneAdsCommunications') }}</div>

                            <div class="p-2" style="font-size: 17px" v-if="updateDateLocale.lang == 'ru'">{{ads.kommunikacii.join(", ")}}</div>
                            <div class="p-2" style="font-size: 17px" v-if="updateDateLocale.lang == 'kz'">
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
                            <div class="p-2" style="font-size: 17px" v-if="updateDateLocale.lang == 'en'">
                                     <span v-for="kommunikacii in ads.kommunikacii">
                                        {{ kommunikacii == 'Свет' ? 'Light, ' : ''}}
                                        {{ kommunikacii == 'Газ' ? 'Gas, ' : ''}}
                                        {{ kommunikacii == 'Интернет' ? 'The Internet, ' : ''}}
                                        {{ kommunikacii == 'Отопление' ? 'Heating, ' : ''}}
                                        {{ kommunikacii == 'Вода' ? 'Water, ' : ''}}
                                        {{ kommunikacii == 'Телефон' ? 'Telephone, ' : ''}}
                                        {{ kommunikacii == 'Канализация' ? 'Sewage system, ' : ''}}
                                    </span>
                            </div>
                        </div>

                        <!-- Безопасность-->
                        <div v-if="ads.bezopasnost != undefined && ads.bezopasnost != '' && ads.table_name != 'Snimu'" class="my-4">
                            <div class="title mb-2">{{ $t('oneAdsSafety') }}</div>

                            <div class="p-2" style="font-size: 17px" v-if="updateDateLocale.lang == 'ru'">{{ads.bezopasnost.join(", ")}}</div>
                            <div class="p-2" style="font-size: 17px" v-if="updateDateLocale.lang == 'kz'">
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
                            <div class="p-2" style="font-size: 17px" v-if="updateDateLocale.lang == 'en'">
                                     <span v-for="bezopasnost in ads.bezopasnost">
                                        {{ bezopasnost == 'Домофон' ? 'Intercom, ' : ''}}
                                        {{ bezopasnost == 'Видеодомофон' ? 'Video intercom, ' : ''}}
                                        {{ bezopasnost == 'Сигнализация' ? 'Alarm system, ' : ''}}
                                        {{ bezopasnost == 'Решетки на окнах' ? 'Bars on the windows, ' : ''}}
                                        {{ bezopasnost == 'Охрана' ? 'Security, ' : ''}}
                                        {{ bezopasnost == 'Видеонаблюдение' ? 'Video surveillance, ' : ''}}
                                        {{ bezopasnost == 'Кодовый замок' ? 'Combination lock, ' : ''}}
                                        {{ bezopasnost == 'Консьерж' ? 'Concierge, ' : ''}}
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

                </article>

                <!-- Ссылка - Все объявления автора / Пожаловаться на объявление -->
                <div v-if="$route.query.tel == undefined">

                    <!-- Все объявления автора-->
                    <v-btn :disabled="$route.name != 'allAdsOneAds'"  @click="$router.push({name: 'userAds', params: { author_id: ads.author_id }})" color="blue-lighten-1" variant="text"  style="text-transform: capitalize">
                        <i class="bi bi-person-workspace"></i>
                        {{ $t('oneAdsAds') }} <strong class="pl-1">{{ads.name}}</strong>
                    </v-btn>

                    <!-- Блок - Отправить жалобу на объявление -->
                    <v-btn dark saiz="small" variant="text" color="red-lighten-1"
                           @click="authStore.check ? showBottomOffCanvas('Пожаловаться') : $router.push({name: $route.name + 'Auth'})"
                           style="text-transform: capitalize; color: #fa5555"
                    >
                        <i class="bi bi-exclamation-octagon"></i>
                        {{ $t('oneAdsComplain') }}
                    </v-btn>

                </div>

            </div>

            <!-- footer -  Позвонить автору - Написать автору  -->
            <div class="oneAds__footer">

                <!-- Кнопки  - позвонить / написать -->
                <v-btn dark variant="flat" color="blue-darken-2" size="large" class="text-body-1" style="width: 100%; max-width: 170px"
                       v-if="$route.query.tel == undefined"
                       @click="authStore.check ? showBottomOffCanvas('Написать'): $router.push({name: $route.name + 'Auth'})"
                >
                    {{ $t('oneAdsWrite') }}
                </v-btn>
                <v-btn dark variant="flat" size="large" class="text-body-1 text-white" style="width: 100%; max-width: 170px; background: #10a37f"
                       @click="showBottomOffCanvas('Позвонить')"
                >
                    {{ $t('oneAdsCall') }}
                </v-btn>

            </div>

        </div>

    </div>

    <!-- Вывожу внутринние стрницы - Фото и тд.-->
    <router-view></router-view>

</template>

<script>

// Слайдер фото Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination, Mousewheel, Keyboard } from 'swiper';
import 'swiper/scss';
import 'swiper/scss/pagination';
import 'swiper/scss/mousewheel';
import 'swiper/scss/keyboard';


//Импортирую Store - Общее состояние
import { useAuthStore } from "../../../stores/auth";
import { useCheckInternetStore } from "../../../stores/checkInternet";
import {useImagesStore} from "../../../stores/images";
import {useAdsStore} from "../../../stores/ads";
import {useUpdateDateLocaleStore} from "../../../stores/updateDateLocale";
import { useKZLocationStore } from "../../../stores/KZLocation";


//Для обрезания строки
import TextClamp from 'vue3-text-clamp';


//Компонент - Yandex map - Карта Яндекс, показать расположение
import { YandexMap, YandexMarker } from 'vue-yandex-maps'

export default {
    name: "ShowOneAds",

    components: {
        Swiper, SwiperSlide, //Слайдер для фото
        TextClamp, //Свернуть-развернуть текст полностью
        YandexMap, YandexMarker, //Карта яндекс
    },

    data(){
        return{

            //Подключаю Store - Общее состояние
            authStore: useAuthStore(),
            checkInternetStore: useCheckInternetStore(),
            imageStore: useImagesStore(),
            adsStore: useAdsStore(),
            updateDateLocale: useUpdateDateLocaleStore(),
            KZLocationStore: useKZLocationStore(),

            query: false,

            ads: '', //Сюда занесем данные 1-го объявления

            //Яндекс карта настройки по умолчанию
            showMap: false,
            settings :{
                apiKey: '8740b571-75d9-47f0-a5c4-582b1feaf201',
                lang: 'ru_RU',
                coordorder: 'latlong',
                enterprise: false,
                version: '2.1'
            },
            detailedControls :{ zoomControl: { position: { right: 0, top: 0 } } },
            //Изменяем иконку
            options: {
                iconLayout: 'default#image',
                iconImageHref: '', //Путь к фото
                iconImageSize: [25, 26],
                iconImageOffset: [-20, -10],
            },

            //swiper Модули для слайдера фото
            modules: [Pagination, Mousewheel, Keyboard],
            swiper: ''
        }
    },

    computed: {
        tipObekta(){
            if(this.ads.tip_obekta == 'Прочая'){
                return this.ads.zagolovok;
            }else{
                return this.ads.tip_obekta;
            }
        },
    },

    methods: {

        //Метод - Получить 1-но объявление с БД
       async getOneAds(table_name, ads_id){

            //Сначало занесем из localStorage
            if(localStorage.getItem('oneAds') != undefined){
                this.ads = JSON.parse(localStorage.getItem('oneAds'));

                //Фото значка расположения на карте
                if(this.ads.lat != undefined){
                    if(this.ads.images.length == 0){
                        this.options.iconImageHref = '/img/siteImg/allImg/apartmens.jpg';
                    }else{
                        this.options.iconImageHref = '/img/adsImg/' + this.ads.images[0];
                    }
                    this.showMap = true;
                }
            }

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
           this.checkInternetStore.checkInternet()

            axios.get('/getOneAds', {
                params:{
                    user_id: this.authStore.check ? this.authStore.user.id : 0,
                    role: this.authStore.check ? this.authStore.user.role : 'user',
                    ads_id: ads_id,
                    table_name: table_name,
                }
            })
                .then((response)=>{
                    this.ads  = response.data;

                    //Занесем В localeStorage чтоб обновить данные в компоненте AdsPreviewComponent
                    localStorage.setItem('oneAds', JSON.stringify(response.data))

                    //Фото значка расположения на карте
                    if(this.ads.images.length == 0){
                        this.options.iconImageHref = '/img/siteImg/allImg/apartmens.jpg';
                    }else{
                        this.options.iconImageHref = '/img/adsImg/' + this.ads.images[0];
                    }

                    this.showMap = true;

                    //Добавим статистику просмотра
                    this.addAdsStatistic();

                })
                .catch((errors)=>{
                    Swal.fire({text: errors.response.data.error})
                    this.$router.back();
                })
        },

        // Метод показать фото
        showImage(ads, i){

            this.imageStore.showImages({images: ads.images,index: i, allImg: true});
            this.$router.push({name: this.$route.name + "Image"})
        },

        //Открыть нижний OffCanvas
        showBottomOffCanvas(type){

            if(type == 'Позвонить')this.addAdsViewTelStatistic()
            this.$router.push({name: this.$route.name + "BottomOffCanvas", params:{type: type }, query:{tel: this.$route.query.tel}})
        },

        //После просмотра объявления добавим статистику
        addAdsStatistic(){
           //Если объявление в архиве не будем добавлять статистику
            if(this.ads.control == 'В архиве')return

            axios.post('/addAdsStatistic', {
                user_id: this.authStore.check ? this.authStore.user.id : 0,
                ads_id: this.ads.id,
                table_name: this.ads.table_name,
            })
                .then((response)=>{
                    this.ads.view += 1;
                })
        },

        //После просмотра телефона добавим статистику
        async addAdsViewTelStatistic(){

            //Если объявление в архиве не будем добавлять статистику
           if(this.ads.control == 'В архиве')return

            axios.post('/addAdsViewTelStatistic', {
                user_id: this.authStore.check ? this.authStore.user.id : 0,
                ads_id: this.ads.id,
                table_name: this.ads.table_name,
            })
                .then((response)=>{
                })
        },

        //Метод - Toggle поставить - убрать Лайк
        async addLikeToggle(){

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            this.checkInternetStore.checkInternet()
            if(!this.checkInternetStore.online)return;

            //Добавим лайк если будет ошибка то уберем
            this.ads.userLike = !this.ads.userLike;
            this.ads.userLike ? this.ads.countLike +=1: this.ads.countLike -=1;

            axios.post('/like',{
                author_id: this.authStore.user.id,
                table_name: this.ads.table_name,
                ads_id: this.ads.id

            })
                .catch(errors=>{

                    this.ads.userLike = !this.ads.userLike;
                    this.ads.userLike? this.ads.countLike +=1: this.ads.countLike -=1;

                    //Если объявление не активно уведомим что не активно
                    Toast.fire({
                        icon: 'error',
                        title: errors.response.data.error
                    })
                })
        },


        // Слайдер
        //Инициализируем Слайдер 1
        oneAdsSwiper(swiper){
            this.swiper = swiper;
        },
        slideNext(){
           this.swiper.slideNext();
        },
        slidePrev(){
            this.swiper.slidePrev();
        },

    },


    mounted(){
        let app = this;

        document.querySelector(':root').classList.add('PATCH_modal');
        app.getOneAds( app.$route.params.table_name, app.$route.params.ads_id );

    },

    beforeRouteLeave(to, from, next) {
        if(to.name == 'allAds')document.querySelector(':root').classList.remove('PATCH_modal'); //Отменим прокрутку под модальным окном
        next();
    }


}
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

/* Стили для макрера - наше фото квартиры */
.ymaps-2-1-79-image{
    border-radius: 100%;
    box-shadow: 0 0 0 2px white, 0 0 0 4px #2196F3;
    background-size: cover;
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

.oneAds__body{
    padding-bottom: 100px;
}

.oneAds__footer{
    position: fixed;
    bottom: 5px;
    left:0;
    right:0;
    top: auto;

    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}


/*Стили Для карты*/
.map__container{
    position: relative;
    height: 100px;
    width: 95%;
    margin: auto;
    border: 1px solid rgba(28,24,25,.05);
    border-radius: 12px;
    overflow: hidden;
}

/* Стили для слайдера */
::v-deep(.swiper-pagination-fraction){
    width: auto;
    color: #fff;
    background-color: rgba(28,24,25,.7);
    border-radius: 3px;
    padding: 0 4px;
    right: 7px;
    left: auto;
    bottom: 12px;
    font-size: 13px;
    line-height: 20px;
}

/*Для заголовков*/
.title{
    font-size: 20px;
    font-family: "Open Sans",sans-serif;;
    font-weight: 600;
    color: #1c1819;
    line-height: 28px;
}

/* Стиль - для кнопок - О квартире */
.btn__item{
    font-size: 17px;
    display: flex;
    justify-content: space-between;
    max-width: 500px;
}

.btn__item > div {
    flex: 1;
    line-height: 28px;
}

.btn__item-cat{
    color: #8d8d8d;
}


/*Для кнопок - скачать, поделиться */
.ads_header-btn{
    color: rgb(93, 111, 106);
    padding: 7px 12px;
    background: #F5F5F5;
    border-radius: 10px;
    cursor: pointer;
}

.ads_header-btn:hover{
    background: rgba(229, 229, 229, 0.91);
}

/* Для Слайдера кнопки переключения */
.swiper-button-next, .swiper-button-prev{
    position: absolute;
    top:0;
    width: 40px;
    height: 100%;
    cursor: pointer;
}

.swiper-button-next{
    left: 0;
}
.swiper-button-prev{
    right: 0;
}


/* Для экрана - Более 992px */
@media screen and  (min-width: 992px) {

    .oneAds__block{
        margin: 25px auto;
        border-radius: 20px 20px 10px 10px;
    }

    .oneAds__body{
        padding-bottom: 50px;
    }

    .oneAds__footer{
        position: relative;
    }

    /* Стили для слайдера Фракции */
    ::v-deep(.swiper-pagination-fraction){
        right: 53px;
    }


}

</style>
