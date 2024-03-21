<template>
    <!-- КОМПОНЕНТ - ФИЛЬТР -->

    <!-- Backdrop -->
    <transition name="filter__animation-backdrop">

        <div v-if="filterAnimation" class="filter__backdrop" @click="$router.back()"></div>

    </transition>

    <!-- Обвертка - Компонента -->
    <transition name="filter__animation-wrapper">

        <!-- Обвертка - Компонента -->
        <div v-if="filterAnimation" class="filter__wrapper">

            <!-- Сам блок -->
            <div class="filter__block" >

                <!-- Header -->
                <div class="filter__header">

                    <!-- Кнопка назад -->
                    <v-btn icon dark variant="text" class="mx-2"  @click="$router.back()">
                        <v-icon>mdi-arrow-left</v-icon>
                    </v-btn>

                    <!-- Заголовок что ищем - Квартиры, Общежития и тд. -->
                    <span class="text-body-1">
                        {{ $t('filter')}}
                    </span>

                    <v-spacer></v-spacer>

                    <!-- Кнопка сбросить ФИЛЬТР -->
                    <v-btn size="x-small" variant="text" class="text-caption" @click="clearFilterFunc()">
                        {{ $t('filterReset') }}
                    </v-btn>

                </div>

                <!-- Форма Фильтра - поля  -->
                <div class="filter__body form">

                    <!-- Выбор локации - Область, город, район -->
                    <div class="form-group mt-3">
                        <div class="form__title">{{ $t('filterChooseCity') }}</div>
                        <div class="d-flex">

                            <!-- Вывод выбранной локации - При клике открыть окно с локациями - получим области по умолчанию  -->
                            <v-btn @click="$router.push({ name: 'filterLocation',  params: { locationId: 'null', stepLocation:1 } })"
                                   class="flex-fill text-body-2"
                                   :class="{'rounded-e-0' : KZLocationStore.location != ''}"
                                   color="blue"
                                   size="x-large"
                                   rounded="0"
                            >

                                <!-- Вывод выбранной локации -->
                                <div v-if="KZLocationStore.location != ''">
                                    <div class="mb-1">
                                        {{ KZLocationStore.translateLocation({oblast: KZLocationStore.location.oblast}).oblast }}
                                    </div>
                                    <span v-if="KZLocationStore.location.gorod != null">
                                        {{ KZLocationStore.translateLocation({gorod: KZLocationStore.location.gorod}).gorod }}
                                    </span>
                                    <span v-if="KZLocationStore.location.raion != null">
                                        , {{ KZLocationStore.translateLocation({raion: KZLocationStore.location.raion}).raion }}
                                    </span>
                                </div>

                                <!-- Весь Казахстан -->
                                <div v-else> {{ $t('filterAllKazakhstan') }} </div>

                                <v-icon end>mdi-arrow-right</v-icon>

                            </v-btn>
                            <!-- Кнопка сбросить локацию -->
                            <v-btn v-if="KZLocationStore.location != ''"
                                   color="red" size="x-large" :rounded="0"
                                   @click="KZLocationStore.removeLocation()">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>

                        </div>
                    </div>

                    <!-- Кто автор - Хозяин , Риелтор -->
                    <div v-if="$route.params.table_name != 'Snimu'" class="form-group">

                        <!-- Заголовок -->
                        <div class="form__title">
                            {{ $t('filterWhoIsAuthor') }}
                        </div>

                        <!-- Поле -->
                        <div class="form__input">

                            <span  @click="form.ownerOrRealtor == 'Хозяин'?form.ownerOrRealtor = '':form.ownerOrRealtor = 'Хозяин'" class="form__item" :class="{'item__active':form.ownerOrRealtor == 'Хозяин'}">{{ $t('filterOwner') }}</span>
                            <span  @click="form.ownerOrRealtor == 'Риелтор'?form.ownerOrRealtor = '':form.ownerOrRealtor = 'Риелтор'" class="form__item" :class="{'item__active':form.ownerOrRealtor == 'Риелтор'}">{{ $t('filterRealtor') }}</span>

                        </div>
                    </div>

                    <!-- Тип сделки  - Сдам продам - для Бизнеса  -->
                    <div class="form-group" v-if="$route.params.table_name == 'Business'">

                        <!-- Заголовок -->
                        <div class="form__title">
                            {{ $t('filterDealType') }}
                        </div>

                        <!-- Поле с кнопками -->
                        <div class="form__input">

                            <span @click="form.tip_sdelki == 'Сдам'?form.tip_sdelki = '':form.tip_sdelki = 'Сдам'" class="form__item" :class="{'item__active':form.tip_sdelki == 'Сдам'}">{{ $t('filterSdam') }}</span>
                            <span @click="form.tip_sdelki == 'Продам'?form.tip_sdelki = '':form.tip_sdelki = 'Продам'" class="form__item" :class="{'item__active':form.tip_sdelki == 'Продам'}">{{ $t('filterSell') }}</span>

                        </div>

                    </div>

                    <!-- Для Офиса - Тип офиса  -->
                    <div class="form-group" v-if="$route.params.table_name == 'Ofis'">

                        <!-- Заголовок -->
                        <div class="form__title">
                            {{ $t('filterOfficeType') }}
                        </div>

                        <!-- Поле -->
                        <div class="form__input">
                            <select v-model="form.tip_ofisa" name="tip_ofisa" class="form-select">
                                <option value="">{{ $t('filterAny') }}</option>
                                <option value="В бизнес-центре">{{ $t('filterInTheBusinessCenter') }}</option>
                                <option value="В административном здании">{{ $t('filterInTheAdministrativeBuilding') }}</option>
                                <option value="В жилом доме">{{ $t('filterInAResidentialBuilding') }}</option>
                                <option value="В коттедже">{{ $t('filterInTheCottage') }}</option>
                                <option value="Другое">{{ $t('filterOtherText') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Для Прочая недвижимость - Сфера деятельности -->
                    <div class="form-group" v-if="$route.params.table_name == 'Business' || $route.params.table_name == 'Prochaya'">

                        <!-- Заголовок -->
                        <div class="form__title">
                            {{ $t('filterFieldOfActivity') }}
                        </div>

                        <!-- Поле -->
                        <div class="form__input d-flex justify-content-center">

                            <!-- Сфера деятельности 1 колонка -->
                            <div class="col" style="max-width: 200px">

                                <!-- Производство -->
                                <span class="form-check form-switch">
                                <input v-model="form.sfera_deyatelnosti" value="Производство" class="form-check-input" type="radio" name="sferaDeyatelnosti" id="sferaDeyatelnostiProizvodstvo" >
                                <label class="form-check-label" role="button" for="sferaDeyatelnostiProizvodstvo">{{ $t('filterProduction') }}</label>
                            </span>

                                <!-- Общепит -->
                                <span class="form-check form-switch">
                                <input v-model="form.sfera_deyatelnosti" value="Общепит" class="form-check-input" type="radio" name="sferaDeyatelnosti" id="sferaDeyatelnostiObshepit" >
                                <label class="form-check-label" role="button" for="sferaDeyatelnostiObshepit">{{ $t('filterPublicCatering') }}</label>
                            </span>

                                <!-- Развлечения -->
                                <span class="form-check form-switch">
                                <input v-model="form.sfera_deyatelnosti" value="Развлечения" class="form-check-input" type="radio" name="sferaDeyatelnosti" id="sferaDeyatelnostiRazvlecheniya" >
                                <label class="form-check-label" role="button" for="sferaDeyatelnostiRazvlecheniya">
                                    {{ $t('filterEntertainment') }}
                                </label>
                            </span>

                                <!-- Другое -->
                                <span class="form-check form-switch">
                                <input v-model="form.sfera_deyatelnosti" value="Другое" class="form-check-input" type="radio" name="sferaDeyatelnosti" id="sferaDeyatelnostiDrugoe" >
                                <label class="form-check-label" role="button" for="sferaDeyatelnostiDrugoe">
                                    {{ $t('filterOtherText') }}
                                </label>
                            </span>

                            </div>

                            <!-- Сфера деятельности 2 колонка -->
                            <div class="col" style="max-width: 200px">

                                <!-- Торговля -->
                                <span class="form-check form-switch">
                                <input v-model="form.sfera_deyatelnosti" value="Торговля" class="form-check-input" type="radio" name="sferaDeyatelnosti" id="sferaDeyatelnostiTorgovlya" >
                                <label class="form-check-label" role="button" for="sferaDeyatelnostiTorgovlya">
                                    {{ $t('filterTrade') }}
                                </label>
                            </span>

                                <!-- Услуги -->
                                <span class="form-check form-switch">
                                <input v-model="form.sfera_deyatelnosti" value="Услуги" class="form-check-input" type="radio" name="sferaDeyatelnosti" id="sferaDeyatelnostiUslugi" >
                                <label class="form-check-label" role="button" for="sferaDeyatelnostiUslugi">
                                    {{ $t('filterServices') }}
                                </label>
                            </span>

                                <!-- Сельское хозяйство -->
                                <span class="form-check form-switch">
                                <input v-model="form.sfera_deyatelnosti" value="Сельское хозяйство" class="form-check-input" type="radio" name="sferaDeyatelnosti" id="sferaDeyatelnostiSelskoeHozyaistvo" >
                                <label class="form-check-label" role="button" for="sferaDeyatelnostiSelskoeHozyaistvo">
                                    {{ $t('filterAgriculture') }}
                                </label>
                            </span>

                                <!-- Сельское хозяйство -->
                                <span class="form-check form-switch">
                                <input v-model="form.sfera_deyatelnosti" value="Все" class="form-check-input" type="radio" name="sferaDeyatelnosti" id="sferaDeyatelnostiVse" >
                                <label class="form-check-label" role="button" for="sferaDeyatelnostiVse">
                                    {{ $t('filterAllOptions') }}
                                </label>
                            </span>

                            </div>

                        </div>

                    </div>

                    <!-- Тип Объекта -->
                    <div class="form-group" v-if=" $route.params.table_name == 'Dom' || $route.params.table_name == 'Magazin' || $route.params.table_name == 'Prombaza' || $route.params.table_name == 'Snimu' ">

                        <!-- Заголовок -->
                        <div class="form__title">
                            {{ $t('filterObjectType') }}
                        </div>

                        <!-- Input -->
                        <div class="form__input">

                            <!-- Дом  -->
                            <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta == 'Дом'?form.tip_obekta = '':form.tip_obekta = 'Дом'" class="form__item" :class="{'item__active':form.tip_obekta == 'Дом'}">{{ $t('filterHouseText') }}</span>
                            <!-- Дача  -->
                            <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta == 'Дача'?form.tip_obekta = '':form.tip_obekta = 'Дача'" class="form__item" :class="{'item__active':form.tip_obekta == 'Дача'}">{{ $t('filterCountryHouse') }}</span>
                            <!-- Коттедж  -->
                            <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta == 'Коттедж'?form.tip_obekta = '':form.tip_obekta = 'Коттедж'" class="form__item" :class="{'item__active':form.tip_obekta == 'Коттедж'}">{{ $t('filterCottage') }}</span>
                            <!-- Времянка  -->
                            <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta == 'Времянка'?form.tip_obekta = '':form.tip_obekta = 'Времянка'" class="form__item" :class="{'item__active':form.tip_obekta == 'Времянка'}">{{ $t('filterVremyanka') }}</span>


                            <!-- Магазин  -->
                            <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta == 'Магазин'?form.tip_obekta = '':form.tip_obekta = 'Магазин'" class="form__item" :class="{'item__active':form.tip_obekta == 'Магазин'}">{{ $t('filterShopText') }}</span>
                            <!-- Бутик  -->
                            <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta == 'Бутик'?form.tip_obekta = '':form.tip_obekta = 'Бутик'" class="form__item" :class="{'item__active':form.tip_obekta == 'Бутик'}">{{ $t('filterBoutique') }}</span>
                            <!-- Киоск  -->
                            <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta == 'Киоск'?form.tip_obekta = '':form.tip_obekta = 'Киоск'" class="form__item" :class="{'item__active':form.tip_obekta == 'Киоск'}">{{ $t('filterKiosk') }}</span>
                            <!-- Контейнер -->
                            <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta == 'Контейнер'?form.tip_obekta = '':form.tip_obekta = 'Контейнер'" class="form__item" :class="{'item__active':form.tip_obekta == 'Контейнер'}">{{ $t('filterContainer') }}</span>


                            <!-- Промбаза  -->
                            <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta == 'Промбаза'?form.tip_obekta = '':form.tip_obekta = 'Промбаза'" class="form__item" :class="{'item__active':form.tip_obekta == 'Промбаза'}">{{ $t('filterIndustrialBase') }}</span>
                            <!-- Завод  -->
                            <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta == 'Завод'?form.tip_obekta = '':form.tip_obekta = 'Завод'" class="form__item" :class="{'item__active':form.tip_obekta == 'Завод'}">{{ $t('filterFactory') }}</span>
                            <!-- Склад бытовой  -->
                            <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta == 'Склад бытовой'?form.tip_obekta = '':form.tip_obekta = 'Склад бытовой'" class="form__item" :class="{'item__active':form.tip_obekta == 'Склад бытовой'}">{{ $t('filterHouseholdWarehouse') }}</span>
                            <!-- Склад продовольственный  -->
                            <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta == 'Склад продовольственный'?form.tip_obekta = '':form.tip_obekta = 'Склад продовольственный'" class="form__item" :class="{'item__active':form.tip_obekta == 'Склад продовольственный'}">{{ $t('filterFoodWarehouse') }}</span>
                            <!-- Склад химпродукции  -->
                            <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta == 'Склад химпродукции'?form.tip_obekta = '':form.tip_obekta = 'Склад химпродукции'" class="form__item" :class="{'item__active':form.tip_obekta == 'Склад химпродукции'}">{{ $t('filterWarehouseForChemicalProducts') }}</span>


                            <!-- Сниму  -->
                            <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta == 'Квартира'?form.tip_obekta = '':form.tip_obekta = 'Квартира'" class="form__item" :class="{'item__active':form.tip_obekta == 'Квартира'}">{{ $t('addAdsApartment') }}</span>
                            <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta == 'Общежитие'?form.tip_obekta = '':form.tip_obekta = 'Общежитие'" class="form__item" :class="{'item__active':form.tip_obekta == 'Общежитие'}">{{ $t('addAdsHostel') }}</span>
                            <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta == 'Дом'?form.tip_obekta = '':form.tip_obekta = 'Дом'" class="form__item" :class="{'item__active':form.tip_obekta == 'Дом'}">{{ $t('addAdsHouse') }}</span>
                            <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta == 'Офис'?form.tip_obekta = '':form.tip_obekta = 'Офис'" class="form__item" :class="{'item__active':form.tip_obekta == 'Офис'}">{{ $t('addAdsOffice') }}</span>
                            <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta == 'Здание'?form.tip_obekta = '':form.tip_obekta = 'Здание'" class="form__item" :class="{'item__active':form.tip_obekta == 'Здание'}">{{ $t('addAdsBuilding') }}</span>
                            <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta == 'Магазин'?form.tip_obekta = '':form.tip_obekta = 'Магазин'" class="form__item" :class="{'item__active':form.tip_obekta == 'Магазин'}">{{ $t('addAdsShop') }}</span>
                            <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta == 'Промбаза'?form.tip_obekta = '':form.tip_obekta = 'Промбаза'" class="form__item" :class="{'item__active':form.tip_obekta == 'Промбаза'}">{{ $t('addAdsBase') }}</span>
                            <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta == 'Прочая'?form.tip_obekta = '':form.tip_obekta = 'Прочая'" class="form__item" :class="{'item__active':form.tip_obekta == 'Прочая'}">{{ $t('addAdsOther') }}</span>
                            <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta == 'Бизнес'?form.tip_obekta = '':form.tip_obekta = 'Бизнес'" class="form__item" :class="{'item__active':form.tip_obekta == 'Бизнес'}">{{ $t('addAdsBusiness') }}</span>
                        </div>

                    </div>

                    <!-- Действующий бизнес - Да , нет -->
                    <div class="form-group" v-if="$route.params.table_name == 'Business' || $route.params.table_name == 'Prochaya' || $route.params.table_name == 'Magazin'">

                        <!-- Заголовок -->
                        <div class="form__title">
                            {{ $t('filterOperatingBusiness') }}
                        </div>

                        <!-- Поле -->
                        <div class="form__input">

                            <span @click="form.deistvuushii_bisnes == 'Да'?form.deistvuushii_bisnes = '':form.deistvuushii_bisnes = 'Да'" class="form__item" :class="{'item__active':form.deistvuushii_bisnes == 'Да'}">{{ $t('filterYes') }}</span>
                            <span @click="form.deistvuushii_bisnes == 'Нет'?form.deistvuushii_bisnes = '':form.deistvuushii_bisnes = 'Нет'" class="form__item" :class="{'item__active':form.deistvuushii_bisnes == 'Нет'}">{{ $t('filterNo') }}</span>

                        </div>
                    </div>

                    <!-- Для Магазин - Местоположение -->
                    <div class="form-group" v-if="$route.params.table_name == 'Business' || $route.params.table_name == 'Magazin'">

                        <!-- Заголовок -->
                        <div class="form__title">
                            {{ $t('filterLocation') }}
                        </div>

                        <!-- Поле -->
                        <div class="form__input">

                            <select v-model="form.mestopolojenie" name="mestopolojenie" class="form-select">
                                <option value="">{{ $t('filterAnyText') }}</option>
                                <option value="В торговом-центре">{{ $t('filterInTheMall') }}</option>
                                <option value="В административном здании">{{ $t('filterInTheAdministrativeBuilding') }}</option>
                                <option value="На универсальном рынке (Барахолке)">{{ $t('filterAtTheUniversalMarketFleaMarket') }}</option>
                                <option value="В жилом доме">{{ $t('filterInAResidentialBuilding') }}</option>
                                <option value="Отдельно стоящее здание">{{ $t('filterDetachedBuilding') }}</option>
                                <option value="Остановочный комплекс">{{ $t('filterStopComplex') }}</option>
                                <option value="Другое">{{ $t('filterOtherText') }}</option>
                            </select>

                        </div>

                    </div>

                    <!-- Период аренды  -->
                    <div class="form-group" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Snimu'">

                        <!-- Заголовок -->
                        <div class="form__title">
                            {{ $t('filterRentalPeriod') }}
                        </div>

                        <!-- Поле с кнопками -->
                        <div class="form__input">

                            <span @click="form.period_arendi == 'На длительно'?form.period_arendi = '':form.period_arendi = 'На длительно'" class="form__item" :class="{'item__active':form.period_arendi == 'На длительно'}">{{ $t('filterForALongTime') }}</span>
                            <span @click="form.period_arendi == 'Посуточно'?form.period_arendi = '':form.period_arendi = 'Посуточно'" class="form__item" :class="{'item__active':form.period_arendi == 'Посуточно'}">{{ $t('filterDaily') }}</span>
                            <span @click="form.period_arendi == 'По часам'?form.period_arendi = '':form.period_arendi = 'По часам'" class="form__item" :class="{'item__active':form.period_arendi == 'По часам'}">{{ $t('filterByTheHour') }}</span>
                            <span @click="form.period_arendi == 'На подселение'?form.period_arendi = '':form.period_arendi = 'На подселение'" class="form__item" :class="{'item__active':form.period_arendi == 'На подселение'}">{{ $t('filterForSharing') }}</span>

                        </div>

                    </div>

                    <!-- Комнат - ОТ - До  -->
                    <div class="form-group d-flex gap-3" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Ofis' || $route.params.table_name == 'Snimu'">

                        <!-- Комнат от -->
                        <v-text-field
                            v-model="form.kolichestvo_komnat_ot"
                            name="kolichestvo_komnat_ot" :label="$route.params.table_name !=='Ofisa'?$t('filterRoomsFrom'):$t('filterCabinetsFrom')"
                            type="text" maxlength="15"
                            inputmode="numeric"
                            variant="outlined" color="blue"
                        ></v-text-field>

                        <!-- Комнат до -->
                        <v-text-field
                            v-model="form.kolichestvo_komnat_do"
                            name="kolichestvo_komnat_do" :label="$route.params.table_name !=='Ofisa'?$t('filterRoomsUpTo'):$t('filterCabinetsUpTo')"
                            type="text" maxlength="15"
                            inputmode="numeric"
                            variant="outlined" color="blue"
                        ></v-text-field>

                    </div>

                    <!-- Цена в месяц - За кв.м, за все -->
                    <div v-if="$route.params.table_name == 'Business' || $route.params.table_name == 'Ofis' || $route.params.table_name == 'Zdanie' || $route.params.table_name == 'Magazin'|| $route.params.table_name == 'Prombaza' || $route.params.table_name == 'Prochaya'" class="form-group">

                        <!-- Заголовок -->
                        <div class="form__title">
                            {{ $t('filterPricePerMonth') }}
                        </div>

                        <!-- Поле -->
                        <div class="form__input">

                            <span @click="form.cena_tip == 'За все'?form.cena_tip = '':form.cena_tip = 'За все'" class="form__item" :class="{'item__active':form.cena_tip == 'За все'}">{{ $t('filterForAll') }}</span>
                            <span @click="form.cena_tip == 'За кв.м'?form.cena_tip = '':form.cena_tip = 'За кв.м'" class="form__item" :class="{'item__active':form.cena_tip == 'За кв.м'}">{{ $t('filterPerSqM') }}</span>

                        </div>
                    </div>

                    <!-- Цена - ОТ - До  -->
                    <div class="form-group d-flex gap-3" >

                        <!-- Цена от -->
                        <v-text-field
                            v-model.lazy="form.cena_ot"
                            name="cena_ot" :label="$t('filterPriceFrom')"
                            type="text"
                            inputmode="numeric" v-number="number"
                            variant="outlined" color="blue"
                        ></v-text-field>

                        <!-- Цена до -->
                        <v-text-field
                            v-model.lazy="form.cena_do"
                            name="cena_do" :label="$t('filterPriceUpTo')"
                            type="text"
                            inputmode="numeric" v-number="number"
                            variant="outlined" color="blue"
                        ></v-text-field>

                    </div>

                    <!-- Этаж - От - Этаж до -->
                    <div class="form-group d-flex gap-3" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Ofis' || $route.params.table_name == 'Magazin'">

                        <!-- Этаж От -->
                        <v-text-field
                            v-model="form.etag_ot"
                            name="etag_ot" :label="$t('filterFloorFrom')"
                            type="text"
                            inputmode="numeric"
                            variant="outlined" color="blue"
                        ></v-text-field>

                        <!-- Этаж До -->
                        <v-text-field
                            v-model="form.etag_do"
                            name="etag_do" :label="$t('filterFloorUpTo')"
                            type="text"
                            inputmode="numeric"
                            variant="outlined" color="blue"
                        ></v-text-field>

                    </div>

                    <!-- Этажность - От - Этажность до -->
                    <div class="form-group d-flex gap-3" v-if="$route.params.table_name == 'Dom' || $route.params.table_name == 'Zdanie'">

                        <!-- Этажность От -->
                        <v-text-field
                            v-model="form.etagnost_ot"
                            name="etagnost_ot" :label="$t('filterFloorsFrom')"
                            type="text"
                            inputmode="numeric"
                            variant="outlined" color="blue"
                        ></v-text-field>

                        <!-- Этажность До -->
                        <v-text-field
                            v-model="form.etagnost_do"
                            name="etagnost_do" :label="$t('filterFloorsUpTo')"
                            type="text"
                            inputmode="numeric"
                            variant="outlined" color="blue"
                        ></v-text-field>

                    </div>

                    <!-- Состояние - Хорошее, среднее, требует ремонта -->
                    <div class="form-group">

                        <div class="form__title">
                            {{ $t('filterState') }}
                        </div>

                        <div class="form__input">

                            <!-- Евро ремонт -->
                            <span @click="form.sostoyanie == 'Евро ремонт'?form.sostoyanie = '':form.sostoyanie = 'Евро ремонт'" class="form__item" :class="{'item__active':form.sostoyanie == 'Евро ремонт'}">{{ $t('filterEuroRepair') }}</span>

                            <!-- Хорошее -->
                            <span @click="form.sostoyanie == 'Хорошее'?form.sostoyanie = '':form.sostoyanie = 'Хорошее'" class="form__item" :class="{'item__active':form.sostoyanie == 'Хорошее'}">{{ $t('filterGood') }}</span>

                            <!-- Среднее -->
                            <span @click="form.sostoyanie == 'Среднее'?form.sostoyanie = '':form.sostoyanie = 'Среднее'" class="form__item" :class="{'item__active':form.sostoyanie == 'Среднее'}">{{ $t('filterAverage') }}</span>

                            <!-- Требует ремонта -->
                            <span @click="form.sostoyanie == 'Требует ремонта'?form.sostoyanie = '':form.sostoyanie = 'Требует ремонта'" class="form__item" :class="{'item__active':form.sostoyanie == 'Требует ремонта'}">{{ $t('filterRequiresRepair') }}</span>

                        </div>
                    </div>

                    <!-- Мебель - Полностью, Частично, Нет -->
                    <div v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Ofis'|| $route.params.table_name == 'Magazin' || $route.params.table_name == 'Snimu'" class="form-group">

                        <div class="form__title">
                            {{ $t('filterFurniture') }}
                        </div>

                        <div class="form__input">

                            <!-- Полностью-->
                            <span @click="form.mebel == 'Полностью'?form.mebel = '':form.mebel = 'Полностью'" class="form__item" :class="{'item__active':form.mebel == 'Полностью'}">{{ $t('filterFully') }}</span>

                            <!-- Частично -->
                            <span @click="form.mebel == 'Частично'?form.mebel = '':form.mebel = 'Частично'" class="form__item" :class="{'item__active':form.mebel == 'Частично'}">{{ $t('filterPartially') }}</span>

                            <!-- Без мебели -->
                            <span @click="form.mebel == 'Нет'?form.mebel = '':form.mebel = 'Нет'" class="form__item" :class="{'item__active':form.mebel == 'Нет'}">{{ $t('filterWithoutFurniture') }}</span>

                        </div>
                    </div>

                    <!-- Для общежития - Душ -->
                    <div v-if="$route.params.table_name == 'Obshejitie'" class="form-group">

                        <div class="form__title">
                            {{ $t('filterShower') }}
                        </div>

                        <div class="form__input">

                            <!-- В комнате -->
                            <span @click="form.dush == 'В комнате'?form.dush = '':form.dush = 'В комнате'" class="form__item" :class="{'item__active':form.dush == 'В комнате'}">{{  $t('filterInTheRoom') }}</span>

                            <!-- В секции -->
                            <span @click="form.dush == 'В секции'?form.dush = '':form.dush = 'В секции'" class="form__item" :class="{'item__active':form.dush == 'В секции'}">{{  $t('filterInSection') }}</span>

                            <!-- Нет -->
                            <span @click="form.dush == 'Нет'?form.dush = '':form.dush = 'Нет'" class="form__item" :class="{'item__active':form.dush == 'Нет'}">{{  $t('filterNo') }}</span>

                        </div>

                    </div>

                    <!-- Санузел -->
                    <div  class="form-group" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Ofis' || $route.params.table_name == 'Magazin'">

                        <div class="form__title">
                            {{ $t('filterBathroom') }}
                        </div>

                        <div class="form__input">

                            <!-- Квартира - Магазин | Раздельный -->
                            <span v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Magazin'" @click="form.sanuzel == 'Раздельный' ? form.sanuzel = '' : form.sanuzel = 'Раздельный'" class="form__item" :class="{'item__active':form.sanuzel == 'Раздельный'}">{{ $t('filterSeparated') }}</span>
                            <!-- Совмещенный -->
                            <span v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Magazin'" @click="form.sanuzel == 'Совмещенный' ? form.sanuzel = '' : form.sanuzel = 'Совмещенный'" class="form__item" :class="{'item__active':form.sanuzel == 'Совмещенный'}">{{ $t('filterCombined') }}</span>
                            <!-- 2 с/у и более -->
                            <span v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Magazin'" @click="form.sanuzel == '2 с/у и более' ? form.sanuzel = '' : form.sanuzel = '2 с/у и более'" class="form__item" :class="{'item__active':form.sanuzel == '2 с/у и более'}">{{ $t('filter2SAnAndMore') }}</span>


                            <!-- Общежитие- В комнате -->
                            <span v-if="$route.params.table_name == 'Obshejitie'" @click="form.sanuzel == 'В комнате' ? form.sanuzel = '' : form.sanuzel = 'В комнате'" class="form__item" :class="{'item__active':form.sanuzel == 'В комнате'}">{{ $t('filterInTheRoom') }}</span>
                            <!-- В секции -->
                            <span v-if="$route.params.table_name == 'Obshejitie'" @click="form.sanuzel == 'В секции' ? form.sanuzel = '' : form.sanuzel = 'В секции'" class="form__item" :class="{'item__active':form.sanuzel == 'В секции'}">{{ $t('filterInSection') }}</span>


                            <!-- Дом / В доме -->
                            <span v-if="$route.params.table_name == 'Dom'" @click="form.sanuzel == 'В доме' ? form.sanuzel = '' : form.sanuzel = 'В доме'" class="form__item" :class="{'item__active':form.sanuzel == 'В доме'}">{{ $t('filterInTheHouse') }}</span>
                            <!-- 2 с/у и более -->
                            <span v-if="$route.params.table_name == 'Dom'" @click="form.sanuzel == '2 с/у и более' ? form.sanuzel = '' : form.sanuzel = '2 с/у и более'" class="form__item" :class="{'item__active':form.sanuzel == '2 с/у и более'}">{{ $t('filter2SAnAndMore') }}</span>
                            <!-- Во дворе -->
                            <span v-if="$route.params.table_name == 'Dom'" @click="form.sanuzel == 'Во дворе' ? form.sanuzel = '' : form.sanuzel = 'Во дворе'" class="form__item" :class="{'item__active':form.sanuzel == 'Во дворе'}">{{ $t('filterInTheCourtyard') }}</span>


                            <!-- Офис | Есть -->
                            <span v-if="$route.params.table_name == 'Ofis'" @click="form.sanuzel == 'Есть' ? form.sanuzel = '' : form.sanuzel = 'Есть'" class="form__item" :class="{'item__active':form.sanuzel == 'Есть'}">{{ $t('filterThereIs') }}</span>
                            <!-- 2 с/у и более -->
                            <span v-if="$route.params.table_name == 'Ofis'" @click="form.sanuzel == '2 с/у и более' ? form.sanuzel = '' : form.sanuzel = '2 с/у и более'" class="form__item" :class="{'item__active':form.sanuzel == '2 с/у и более'}">{{ $t('filter2SAnAndMore') }}</span>


                            <!-- Для всех  -->
                            <span @click="form.sanuzel == 'Нет' ? form.sanuzel = '' : form.sanuzel = 'Нет'" class="form__item" :class="{'item__active':form.sanuzel == 'Нет'}">{{ $t('filterNo') }}</span>


                        </div>

                    </div>

                    <!-- Высота потолков От - до -->
                    <div class="form-group d-flex gap-3" v-if="$route.params.table_name == 'Ofis' || $route.params.table_name == 'Zdanie'" >

                        <!-- Высота потолков От  -->
                        <v-text-field
                            v-model="form.visota_potolkov_ot"
                            name="visota_potolkov_ot" :label="$t('filterCeilingHeightFrom')"
                            type="text"
                            inputmode="numeric"
                            variant="outlined" color="blue"
                        ></v-text-field>

                        <!-- Высота потолков До  -->
                        <v-text-field
                            v-model="form.visota_potolkov_do"
                            name="visota_potolkov_do" :label="$t('filterCeilingHeightUpTo')"
                            type="text"
                            inputmode="numeric"
                            variant="outlined" color="blue"
                        ></v-text-field>

                    </div>

                    <!-- Площадь - От - До -->
                    <div class="form-group d-flex gap-3">

                        <!-- Площадь от м2  -->
                        <v-text-field
                            v-model="form.ploshad_obshaya_ot"
                            name="ploshad_obshaya_ot" :label="$t('filterAreaFromM2')"
                            type="text"
                            inputmode="numeric"
                            variant="outlined" color="blue"
                        ></v-text-field>

                        <!-- Площадь до м2  -->
                        <v-text-field
                            v-model="form.ploshad_obshaya_do"
                            name="ploshad_obshaya_do" :label="$t('filterAreaUpToM2')"
                            type="text"
                            inputmode="numeric"
                            variant="outlined" color="blue"
                        ></v-text-field>

                    </div>

                    <!-- Площадь участка - От - До -->
                    <div class="form-group d-flex gap-3" v-if="$route.params.table_name == 'Dom' || $route.params.table_name == 'Zdanie'|| $route.params.table_name == 'Prombaza'|| $route.params.table_name == 'Prochaya'">

                        <!-- Площадь участка - От  -->
                        <v-text-field
                            v-model="form.ploshad_uchastka_ot"
                            name="ploshad_uchastka_ot" :label="$t('filterPlotAreaAcresFrom')"
                            type="text"
                            inputmode="numeric"
                            variant="outlined" color="blue"
                        ></v-text-field>

                        <!-- Площадь участка - До  -->
                        <v-text-field
                            v-model="form.ploshad_uchastka_do"
                            name="ploshad_uchastka_do" :label="$t('filterPlotAreaAcresUpTo')"
                            type="text"
                            inputmode="numeric"
                            variant="outlined" color="blue"
                        ></v-text-field>

                    </div>

                    <!-- Тип строения -->
                    <div class="form-group" v-if="$route.params.table_name != 'Snimu' && $route.params.table_name != 'goryachie'">

                        <div class="form__title">
                            {{ $t('filterBuildingType') }}
                        </div>

                        <!-- Поле -->
                        <div class="form__input">

                            <span @click="form.tip_stroeniya == 'Панель'?form.tip_stroeniya = '':form.tip_stroeniya = 'Панель'" class="form__item" :class="{'item__active':form.tip_stroeniya == 'Панель'}">{{ $t('filterPanel') }}</span>
                            <span @click="form.tip_stroeniya == 'Кирпич'?form.tip_stroeniya = '':form.tip_stroeniya = 'Кирпич'" class="form__item" :class="{'item__active':form.tip_stroeniya == 'Кирпич'}">{{ $t('filterBrick') }}</span>
                            <span @click="form.tip_stroeniya == 'Дерево'?form.tip_stroeniya = '':form.tip_stroeniya = 'Дерево'" class="form__item" :class="{'item__active':form.tip_stroeniya == 'Дерево'}">{{ $t('filterTree') }}</span>
                            <span @click="form.tip_stroeniya == 'Другое'?form.tip_stroeniya = '':form.tip_stroeniya = 'Другое'" class="form__item" :class="{'item__active':form.tip_stroeniya == 'Другое'}">{{ $t('filterOtherText') }}</span>

                        </div>

                    </div>

                    <!-- Для дома - Канализация - Центральная, Септик, Нет -->
                    <div v-if="$route.params.table_name == 'Dom'" class="form-group">

                        <div class="form__title">
                            {{ $t('filterSewerage') }}
                        </div>

                        <div class="form__input">

                            <!-- Центральная -->
                            <span @click="form.kanalizaciya == 'Центральная'?form.kanalizaciya = '':form.kanalizaciya = 'Центральная'" class="form__item" :class="{'item__active':form.kanalizaciya == 'Центральная'}">{{ $t('filterCentral') }}</span>
                            <!-- Септик -->
                            <span @click="form.kanalizaciya == 'Септик'?form.kanalizaciya = '':form.kanalizaciya = 'Септик'" class="form__item" :class="{'item__active':form.kanalizaciya == 'Септик'}">{{ $t('filterSepticTank') }}</span>
                            <!-- Нет -->
                            <span @click="form.kanalizaciya == 'Нет'?form.kanalizaciya = '':form.kanalizaciya = 'Нет'" class="form__item" :class="{'item__active':form.kanalizaciya == 'Нет'}">{{ $t('filterNo') }}</span>

                        </div>
                    </div>

                    <!-- Для дома - Питьевая вода - Центральная, скважина, нет -->
                    <div v-if="$route.params.table_name == 'Dom'" class="form-group">

                        <div class="form__title">
                            {{ $t('filterDrinkingWater') }}
                        </div>

                        <div class="form__input">

                            <!-- Центральная -->
                            <span @click="form.voda == 'Центральная'?form.voda = '':form.voda = 'Центральная'" class="form__item" :class="{'item__active':form.voda == 'Центральная'}">{{ $t('filterCentral') }}</span>

                            <!-- Скважина -->
                            <span @click="form.voda == 'Скважина'?form.voda = '':form.voda = 'Скважина'" class="form__item" :class="{'item__active':form.voda == 'Скважина'}">{{ $t('filterWell') }}</span>

                            <!-- Нет -->
                            <span @click="form.voda == 'Нет'?form.voda = '':form.voda = 'Нет'" class="form__item" :class="{'item__active':form.voda == 'Нет'}">{{ $t('filterNo') }}</span>

                        </div>
                    </div>

                    <!-- Для дома - Отопление - Центральное, На газе, На твердом топливе, На жидком топливе, смешанное, без отопления -->
                    <div v-if="$route.params.table_name == 'Dom'" class="form-group">

                        <div class="form__title">
                            {{ $t('filterHeating') }}
                        </div>

                        <div class="form__input">

                            <!-- Центральное -->
                            <span @click="form.otoplenie == 'Центральное'?form.otoplenie = '':form.otoplenie = 'Центральное'" class="form__item" :class="{'item__active':form.otoplenie == 'Центральное'}">{{ $t('filterCentralText') }}</span>

                            <!-- На газе -->
                            <span @click="form.otoplenie == 'На газе'?form.otoplenie = '':form.otoplenie = 'На газе'" class="form__item" :class="{'item__active':form.otoplenie == 'На газе'}">{{ $t('filterOnGas') }}</span>

                            <!-- На твердом топливе -->
                            <span @click="form.otoplenie == 'На твердом топливе'?form.otoplenie = '':form.otoplenie = 'На твердом топливе'" class="form__item" :class="{'item__active':form.otoplenie == 'На твердом топливе'}">{{ $t('filterSolidFuel') }}</span>

                            <!-- На жидком топливе -->
                            <span @click="form.otoplenie == 'На жидком топливе'?form.otoplenie = '':form.otoplenie = 'На жидком топливе'" class="form__item" :class="{'item__active':form.otoplenie == 'На жидком топливе'}">{{ $t('filterLiquidFuel') }}</span>

                            <!-- Смешанное -->
                            <span @click="form.otoplenie == 'Смешанное'?form.otoplenie = '':form.otoplenie = 'Смешанное'" class="form__item" :class="{'item__active':form.otoplenie == 'Смешанное'}">{{ $t('filterMixed') }}</span>

                            <!-- Без отопления -->
                            <span @click="form.otoplenie == 'Без отопления'?form.otoplenie = '':form.otoplenie = 'Без отопления'" class="form__item" :class="{'item__active':form.otoplenie == 'Без отопления'}">{{ $t('filterWithoutHeating') }}</span>

                        </div>
                    </div>

                    <!-- Поиск объявлений по номеру телефона -->
                    <div class="form-group mt-2">

                        <div class="form__title">
                            {{ $t('filterSearchByPhoneNumber') }}
                        </div>

                        <vue-tel-input v-model="form.tel"  style="height: 56px"
                                       :inputOptions="{showDialCode: true}"
                                       :validCharactersOnly="true"
                        ></vue-tel-input>

                    </div>

                    <!-- Показать архивные -->
                    <div class="form-group">

                        <div class="form__title">
                            {{ $t('filterArchivedAnnouncements') }}
                        </div>

                        <div class="form__input">

                            <!-- Показать архивные объявления -->
                            <span @click="form.arhiv == 'Показать' ? form.arhiv = '':form.arhiv = 'Показать'" class="form__item" :class="{'item__active':form.arhiv == 'Показать'}">{{ $t('filterShowArchivedAds') }}</span>

                        </div>
                    </div>

                </div>

                <!-- Footer блока  - Кнопка показать результаты -->
                <div class="filter__footer">

                    <!-- Кнопка найти -->
                    <v-btn @click="search"
                           size="x-large"
                           block
                           class="text-body-1 text-white"
                           style="background: #10a37f"
                    >
                        {{ $t('filterShowResults') }}
                    </v-btn>

                </div>

            </div>

        </div>

    </transition>

    <!-- Внутренние страницы - Локация -->
    <router-view></router-view>

</template>

<script>

//Компонент - Валидация Формы - laravel VFORM
import Form from 'vform'

//Компонент для формата номера телефона
import { VueTelInput } from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';

//Директива для формата цены из "80000" в "80 000"
import { directive } from '@coders-tm/vue-number-format'

//Компонент - OffCanvas с локациями Казахстана
import { useKZLocationStore } from "../../stores/KZLocation";
import {useUpdateDateLocaleStore} from "../../stores/updateDateLocale";
import { useFilterStore } from "../../stores/Filter";

export default {

    name: "AdsFilter",

    //Для трансформации цены
    directives: {
        number: directive,
    },

    components: {
        VueTelInput
    },

    data(){
        return {
            KZLocationStore: useKZLocationStore(),
            updateDateLocaleStore: useUpdateDateLocaleStore(),
            filterStore: useFilterStore(),


            filterAnimation: false,
            form_name: '', //Что искать - Квартиры, Общежития и тд.

            //Форма для поиска
            form: new Form({

                ownerOrRealtor: '',
                tip_sdelki: '',
                tip_obekta: '',
                period_arendi: '',
                kolichestvo_komnat_ot: '',
                kolichestvo_komnat_do: '',

                deistvuushii_bisnes: '',
                cena_tip: '',
                cena_ot: '',
                cena_do: '',

                sfera_deyatelnosti: '', //прочая
                mestopolojenie: '', //магазин
                tip_ofisa: '', //офис

                etag_ot: '',
                etag_do: '',
                etagnost_ot: '',
                etagnost_do: '',

                visota_potolkov_ot: '',
                visota_potolkov_do: '',

                ploshad_obshaya_ot: '',
                ploshad_obshaya_do: '',
                ploshad_uchastka_ot: '',
                ploshad_uchastka_do: '',

                tip_stroeniya: '',

                sostoyanie: '',
                mebel: '',
                dush: '',
                sanuzel: '',

                kanalizaciya: '',
                voda: '',
                otoplenie: '',

                //Местоположение
                oblast: null,
                gorod: null,
                raion: null,
                lat: null,
                lon: null,

                //Показать архивные объявления
                arhiv: '',
                tel: '', //Поиск по номеру телефона
            }),

            //Для форматирования данных в поле цена c 80000 на 80 000 для дерективы number
            number: {
                "decimal": ".",
                "separator": " ",
                "prefix": "",
                "suffix": "",
                "precision": "",
                "nullValue": "",
                "masked": false,
                "reverseFill": false,
                "minimumFractionDigits": ""
            },
        }
    },

    methods:{

        //Применить Фильтр
        search(){
            //Преобразуем формат цены с например 80 000 на  80000
            this.form.cena_ot = this.form.cena_ot.replace(/\D/g, "");
            this.form.cena_do = this.form.cena_do.replace(/\D/g, "");

            //Преобразуем номер телефона
            this.form.tel.length < 3 ? this.form.tel = '': '';

            if(this.KZLocationStore.location != ''){
                this.form.oblast = this.KZLocationStore.location.oblast;
                this.form.gorod = this.KZLocationStore.location.gorod;
                this.form.raion = this.KZLocationStore.location.raion;
                this.form.lat = this.KZLocationStore.location.lat;
                this.form.lon = this.KZLocationStore.location.lon;
            }
            else{
                this.form.oblast = null;
                this.form.gorod = null;
                this.form.raion = null;
                this.form.lat = null;
                this.form.lon = null;
            }

            localStorage.setItem('filter=' + this.$route.params.table_name, JSON.stringify(this.form));
            this.filterStore.makeFilter(); //Сообщим в Store что применен фильтр
            //Вернуться назад
            this.$router.back();
        },

        //Метод открыть фильтр
        showFilter(){

            //Если в localstorage есть фильтр занесем его
            if(localStorage.getItem ("filter=" + this.$route.params.table_name ) != undefined) {
                let filter = JSON.parse(localStorage.getItem ("filter=" + this.$route.params.table_name))
                this.form.fill(filter);

                if(filter.oblast != null){
                    this.KZLocationStore.addLocation({
                        oblast: filter.oblast,
                        gorod: filter.gorod,
                        raion: filter.raion,
                        lat: filter.lat,
                        lon: filter.lon,
                    })
                }
            }
            else{
                this.KZLocationStore.removeLocation();
            }

        },

        //Метод сбросить фильтр
        clearFilterFunc(){

            //Отчистим форму
            this.form.reset();
            this.form.clear();

            //Проверим есть ли примененный Фильтр в Locale Storage - То оставим Локацию
            if(localStorage.getItem ("filter=" + this.$route.params.table_name) != undefined){

                let filter = JSON.parse(localStorage.getItem ("filter=" + this.$route.params.table_name ));

                //Оставим некоторый фильтр
                if(filter.oblast != null){

                    this.form.oblast = filter.oblast;
                    this.form.gorod = filter.gorod;
                    this.form.raion = filter.raion;
                    this.form.lat = filter.lat;
                    this.form.lon = filter.lon;

                    this.search();
                }
                else{
                    localStorage.removeItem('filter=' + this.$route.params.table_name);

                    this.filterStore.makeFilter(); //Сообщим в Store что применен фильтр

                    this.$router.back();
                }

            }else{
                this.$router.back();
            }
        },

    },

    mounted(){
        let app = this;

        this.filterAnimation = true;
        document.querySelector(':root').classList.add('PATCH_modal');
        app.showFilter();

    },

    beforeRouteLeave(to, from, next){
        this.filterAnimation = false;
        document.querySelector(':root').classList.remove('PATCH_modal');

        setTimeout(() => {
            next(); // Вызываем next() после завершения setTimeout - Для завершения анимации
        }, 350);
    }

}
</script>

<style>

/* Анимация для плавного появления -- Backdrop */
.filter__animation-backdrop-enter-active,
.filter__animation-backdrop-leave-active{
    transition: opacity 0.3s ease;
}

.filter__animation-backdrop-enter-from,
.filter__animation-backdrop-leave-to {
    opacity: 0;
}

.filter__animation-backdrop-enter-to,
.filter__animation-backdrop-leave-from {
    opacity: 1;
}

/* Анимация для плавного появления -- Wrapper */
.filter__animation-wrapper-enter-active,
.filter__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}

.filter__animation-wrapper-enter-from {
    transform: translateX(-50%); /* Начальное положение при анимации скрытия */
}

.filter__animation-wrapper-leave-to {
    transform: translateX(-100%); /* Начальное положение при анимации скрытия */
}

.filter__animation-wrapper-enter-to,
.filter__animation-wrapper-leave-from {
    transform: translateY(0);/* Конечное положение при анимации появления */
}

</style>

<style scoped>

/* Backdrop */
.filter__backdrop{
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: 0;
    background: rgba(0,0,0,0.8);
}

.filter__wrapper{
    position: fixed;
    top: 0;
    bottom:0;
    right: auto;
    left: 0;
    width: 100%;
    max-width: 450px;
    background: #ffffff;
}

.filter__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
}

.filter__header{
    display: flex;
    align-items: center;
    background: var(--app-header-bg-color);
    color: #ffffff;
}

.filter__body{
    flex-grow: 1;
    overflow-y: scroll;

}

.filter__footer{
    padding: 5px 20px;
}

/* Заголовок для label input */
.form__title{
    margin-bottom: 5px;
    font-size: 1.1em;
    font-weight: 600;
    line-height: 1.5rem;
    color: #1a1c1e;
}

/* Поле */
.form__input{
    display: flex;
    flex-wrap: wrap;
}

/* Кнопка */
.form__item{
    flex-grow: 1;
    width: 150px;
    background: #f2f3f5;
    cursor: pointer;
    padding: 15px 20px;
    margin: 2px 5px;
    border-radius: 10px;
    color: rgb(51, 51, 51);
    font-size: 16px;
    overflow: hidden;
}

/* Выбранная кнопка */
.item__active{
    background: #2d81e1;
    color: #ffffff;
}

</style>
