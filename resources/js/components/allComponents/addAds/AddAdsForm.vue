<template>
    <!-- КОМПОНЕНТ - ДОБАВЛЕНИЕ ОБЪЯВЛЕНИЙ -->

    <!-- Обвертка - Компонента-->
    <div class="addAds__wrapper">

        <!-- Сам блок -->
        <div class="addAds__block">

            <!-- Header  - Заголовок -->
            <div class="addAds__header">

                <!-- Кнопка Назад / Заголовок старницы - что сдать / Закрыть форму или сохранить -->
                <div class="row g-0 align-center text-white" style="background: #19b07b">

                    <!-- Кнопка Назад -->
                    <div class="col-auto">
                        <!-- Кнопка назад -->
                        <v-btn icon dark variant="text" class="mx-2" @click="$router.back()">
                            <v-icon size="large">mdi-arrow-left</v-icon>
                        </v-btn>
                    </div>

                    <!-- Заголовок старницы - что сдать  -->
                    <div class="col text-body-1">

                        <span v-if="langStore.lang != 'kz'">
                            <span>{{ form.addOrUpdate == 'add' ? $t('addAdsAdd') : $t('addAdsEdit') }}</span>
                        </span>

                        <span v-if="$route.params.table_name == 'Kvartira'">{{ $t('addAdsApartment') }}</span>
                        <span v-if="$route.params.table_name == 'Obshejitie'">{{ $t('addAdsHostel') }}</span>
                        <span v-if="$route.params.table_name == 'Dom'">{{ $t('addAdsHouse') }}</span>
                        <span v-if="$route.params.table_name == 'Ofis'">{{ $t('addAdsOffice') }}</span>
                        <span v-if="$route.params.table_name == 'Zdanie'">{{ $t('addAdsBuilding') }}</span>
                        <span v-if="$route.params.table_name == 'Magazin'">{{ $t('addAdsShop') }}</span>
                        <span v-if="$route.params.table_name == 'Prombaza'">{{ $t('addAdsBase') }}</span>
                        <span v-if="$route.params.table_name == 'Prochaya'">{{ $t('addAdsOther') }}</span>
                        <span v-if="$route.params.table_name == 'Business'">{{ $t('addAdsBusiness') }}</span>

                        <span v-if="langStore.lang == 'kz'">
                            <span>{{ form.addOrUpdate == 'add' ? $t('addAdsAdd') : $t('addAdsEdit') }}</span>
                        </span>

                    </div>

                    <!-- Закрыть форму - или сохранить объявление -->
                    <div class="col-auto px-4">

                        <!-- Звкрыть Форму -->
                        <v-btn icon dark variant="text"
                               v-if="form.addOrUpdate == 'add'"
                               @click="$router.go(-$route.params.step)"
                        >
                            <v-icon>mdi-close</v-icon>
                        </v-btn>

                        <!-- Сохранить -->
                        <v-btn v-if="form.addOrUpdate == 'update' && $route.params.step != 6"
                               @click="formValidate(true)"
                               variant="text" role="button" class="text-caption" :disabled="query"
                        >
                            <v-progress-circular v-if="query" size="x-small" indeterminate color="white"></v-progress-circular>
                            {{ $t('addAdsSave') }}
                        </v-btn>
                    </div>

                </div>

                <!-- Пагинация сверху -->
                <div class="d-flex gap-2 w-100 pt-1">
                    <div class="col rounded-2" :class="{'bg-blue-darken-2' : $route.params.step == 1, 'bg-blue-grey-lighten-3' : $route.params.step != 1}" style="height: 3px"></div>
                    <div class="col rounded-2" :class="{'bg-blue-darken-2' : $route.params.step == 2, 'bg-blue-grey-lighten-3' : $route.params.step != 2}" style="height: 3px"></div>
                    <div class="col rounded-2" :class="{'bg-blue-darken-2' : $route.params.step == 3, 'bg-blue-grey-lighten-3' : $route.params.step != 3}" style="height: 3px"></div>
                    <div class="col rounded-2" :class="{'bg-blue-darken-2' : $route.params.step == 4, 'bg-blue-grey-lighten-3' : $route.params.step != 4}" style="height: 3px"></div>
                    <div class="col rounded-2" :class="{'bg-blue-darken-2' : $route.params.step == 5, 'bg-blue-grey-lighten-3' : $route.params.step != 5}" style="height: 3px"></div>
                    <div class="col rounded-2" :class="{'bg-blue-darken-2' : $route.params.step == 6, 'bg-blue-grey-lighten-3' : $route.params.step != 6}" style="height: 3px"></div>
                    <div class="col rounded-2" :class="{'bg-blue-darken-2' : $route.params.step == 8, 'bg-blue-grey-lighten-3' : $route.params.step != 8}" style="height: 3px"></div>
                </div>

            </div>

            <!-- Body Модального Окна - С формой добавления объявления -->
            <validation-observer as="div" class="addAds__body" id="addAds__body" ref="form" v-slot="{ handleSubmit }">

                <!-- Уведомление что в localstorage есть начатое объявление - продолжить или подать новое -->
                <div v-if="!showForm" class="text-center">

                    <!-- Заголовок -->
                    <h2 class="py-3 pt-5 fw-bold">{{ $t('addAdsDoYouHaveAnUnfinishedAd') }}</h2>

                    <!-- Кнопки -->
                    <div class="d-flex gap-2 align-center justify-center py-2">

                        <!-- Подать новое -->
                        <v-btn @click="openModal(false)" variant="outlined" size="x-large" color="green-darken-2" class=" text-body-1" style="width: 160px">
                            {{ $t('addAdsSubmitANewOne') }}
                        </v-btn>

                        <!-- Продолжить заполнять старое -->
                        <v-btn @click="openModal(true)" variant="outlined" size="x-large" color="blue-darken-2" class="text-body-1" style="width:160px">
                            {{ $t('addAdsContinue') }}
                        </v-btn>

                    </div>

                </div>

                <!-- Форма подачи объявления -->
                <form v-if="showForm" @submit="handleSubmit($event, addOrUpdateAds)" class="form addAds__form">

                    <!-- Верхний блок основная информации  -->
                    <div v-if="$route.params.step == 1" class="addAds__form-block">

                        <!-- Тип сделки - Сдам - продам  -->
                        <div class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsDealType') }}</div>

                            <!-- Поле с кнопками -->
                            <validation-provider rules="required" v-model="form.tip_sdelki" name="tip_sdelki" v-slot="{ errors }">

                                <div class="form__input">
                                    <div @click="form.tip_sdelki = 'sdam'" class="form__item flex-fill" :class="{'item__active': form.tip_sdelki == 'sdam'}" style="width: 150px"> <v-icon color="green" v-if="form.tip_sdelki == 'sdam'">mdi-check-circle</v-icon>{{ $t('addAdsSdam') }} </div>
                                    <div @click="form.tip_sdelki = 'prodam'" class="form__item flex-fill" :class="{'item__active': form.tip_sdelki == 'prodam'}" style="width: 150px"> <v-icon color="green" v-if="form.tip_sdelki == 'prodam'">mdi-check-circle</v-icon>{{ $t('addAdsSell') }} </div>
                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="tip_sdelki"></has-error>
                                </div>

                            </validation-provider>

                        </div>

                        <!-- Сфера деятельности - Прочая недвижимость -->
                        <div v-if="['Business','Prochaya'].includes($route.params.table_name)" class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsFieldOfActivity') }}</div>

                            <!-- Поле -->
                            <validation-provider rules="required" v-model="form.sfera_deyatelnosti" name="sfera_deyatelnosti" v-slot="{ errors, field }">

                                <!-- Сфера деятельности 2 клонки -->
                                <div class="form__input">

                                    <span @click="form.sfera_deyatelnosti = 'proizvodstvo'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'proizvodstvo'}"><v-icon color="green" v-if="form.sfera_deyatelnosti == 'proizvodstvo'">mdi-check-circle</v-icon>{{ $t('addAdsProduction') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'obshepit'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'obshepit'}"><v-icon color="green" v-if="form.sfera_deyatelnosti == 'obshepit'">mdi-check-circle</v-icon>{{ $t('addAdsPublicCatering') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'razvlecheniya'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'razvlecheniya'}"><v-icon color="green" v-if="form.sfera_deyatelnosti == 'razvlecheniya'">mdi-check-circle</v-icon>{{ $t('addAdsEntertainment') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'torgovlya'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'torgovlya'}"><v-icon color="green" v-if="form.sfera_deyatelnosti == 'torgovlya'">mdi-check-circle</v-icon>{{ $t('addAdsTrade') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'uslugi'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'uslugi'}"><v-icon color="green" v-if="form.sfera_deyatelnosti == 'uslugi'">mdi-check-circle</v-icon>{{ $t('addAdsServices') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'selskoe_hozyaistvo'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'selskoe_hozyaistvo'}"><v-icon color="green" v-if="form.sfera_deyatelnosti == 'selskoe_hozyaistvo'">mdi-check-circle</v-icon>{{ $t('addAdsAgriculture') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'drugoe'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'drugoe'}"><v-icon color="green" v-if="form.sfera_deyatelnosti == 'drugoe'">mdi-check-circle</v-icon>{{ $t('addAdsOtherText') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="sfera_deyatelnosti"></has-error>
                                </div>

                            </validation-provider>

                        </div>

                        <!-- Для Офиса - Тип офиса  -->
                        <div v-if="$route.params.table_name == 'Ofis'" class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsOfficeType') }}</div>

                            <!-- Поле -->
                            <div class="form__input">

                                <validation-provider rules="required" v-model="form.tip_ofisa" name="tip_ofisa" v-slot="{ errors}">

                                    <select v-model="form.tip_ofisa"  name="tip_ofisa" class="form-select">
                                        <option value="v_biznes_centre">{{ $t('addAdsInTheBusinessCenter') }}</option>
                                        <option value="v_administrativnom_zdanii">{{ $t('addAdsInTheAdministrativeBuilding') }}</option>
                                        <option value="v_jilom_dome">{{ $t('addAdsInAResidentialBuilding') }}</option>
                                        <option value="v_kotedje">{{ $t('addAdsInTheCottage') }}</option>
                                        <option value="drugoe">{{ $t('addAdsOtherText') }}</option>
                                    </select>

                                    <!-- Вывожу ошибки - veevalidate, vform -->
                                    <div class="text__error">
                                        <span >{{ errors[0] }}</span>
                                        <has-error :form="form" field="tip_ofisa"></has-error>
                                    </div>

                                </validation-provider>

                            </div>
                        </div>

                        <!-- Тип Объекта -->
                        <div v-if="['Dom', 'Magazin','Prombaza'].includes($route.params.table_name)" class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsObjectType') }}</div>

                            <!-- Input -->
                            <validation-provider rules="required" v-model="form.tip_obekta" name="tip_obekta" v-slot="{ errors}">

                                <div class="form__input">

                                    <!-- Дом  -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta = 'dom'" class="form__item" :class="{'item__active' : form.tip_obekta == 'dom'}"><v-icon color="green" v-if="form.tip_obekta == 'dom'">mdi-check-circle</v-icon>{{ $t('addAdsHouseText') }}</span>
                                    <!-- Дача  -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta = 'dacha'" class="form__item" :class="{'item__active' : form.tip_obekta == 'dacha'}"><v-icon color="green" v-if="form.tip_obekta == 'dacha'">mdi-check-circle</v-icon>{{ $t('addAdsCountryHouse') }}</span>
                                    <!-- Коттедж  -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta = 'kotedj'" class="form__item" :class="{'item__active' : form.tip_obekta == 'kotedj'}"><v-icon color="green" v-if="form.tip_obekta == 'kotedj'">mdi-check-circle</v-icon>{{ $t('addAdsCottage') }}</span>
                                    <!-- Времянка  -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta = 'vremyanka'" class="form__item" :class="{'item__active' : form.tip_obekta == 'vremyanka'}"><v-icon color="green" v-if="form.tip_obekta == 'vremyanka'">mdi-check-circle</v-icon>{{ $t('addAdsVremyanka') }}</span>


                                    <!-- Магазин  -->
                                    <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta = 'magazin'" class="form__item" :class="{'item__active':form.tip_obekta == 'magazin'}"><v-icon color="green" v-if="form.tip_obekta == 'magazin'">mdi-check-circle</v-icon>{{ $t('addAdsShopText') }}</span>
                                    <!-- Бутик  -->
                                    <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta = 'butik'" class="form__item" :class="{'item__active':form.tip_obekta == 'butik'}"><v-icon color="green" v-if="form.tip_obekta == 'butik'">mdi-check-circle</v-icon>{{ $t('addAdsBoutique') }}</span>
                                    <!-- Киоск  -->
                                    <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta = 'kiosk'" class="form__item" :class="{'item__active':form.tip_obekta == 'kiosk'}"><v-icon color="green" v-if="form.tip_obekta == 'kiosk'">mdi-check-circle</v-icon>{{ $t('addAdsKiosk') }}</span>
                                    <!-- Контейнер -->
                                    <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta = 'konteiner'" class="form__item" :class="{'item__active':form.tip_obekta == 'konteiner'}"><v-icon color="green" v-if="form.tip_obekta == 'konteiner'">mdi-check-circle</v-icon>{{ $t('addAdsContainer') }}</span>


                                    <!-- Промбаза  -->
                                    <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta = 'prombaza'" class="form__item" :class="{'item__active':form.tip_obekta == 'prombaza'}"><v-icon color="green" v-if="form.tip_obekta == 'prombaza'">mdi-check-circle</v-icon>{{ $t('addAdsIndustrialBase') }}</span>
                                    <!-- Завод  -->
                                    <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta = 'zavod'" class="form__item" :class="{'item__active':form.tip_obekta == 'zavod'}"><v-icon color="green" v-if="form.tip_obekta == 'zavod'">mdi-check-circle</v-icon>{{ $t('addAdsFactory') }}</span>
                                    <!-- Склад бытовой  -->
                                    <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta = 'sklad_bitovoy'" class="form__item" :class="{'item__active':form.tip_obekta == 'sklad_bitovoy'}"><v-icon color="green" v-if="form.tip_obekta == 'sklad_bitovoy'">mdi-check-circle</v-icon>{{ $t('addAdsHouseholdWarehouse') }}</span>
                                    <!-- Склад продовольственный  -->
                                    <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta = 'sklad_prodovolstvenii'" class="form__item" :class="{'item__active':form.tip_obekta == 'sklad_prodovolstvenii'}"><v-icon color="green" v-if="form.tip_obekta == 'sklad_prodovolstvenii'">mdi-check-circle</v-icon>{{ $t('addAdsFoodWarehouse') }}</span>
                                    <!-- Склад химпродукции  -->
                                    <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta = 'sklad_himprodukcii'" class="form__item" :class="{'item__active':form.tip_obekta == 'sklad_himprodukcii'}"><v-icon color="green" v-if="form.tip_obekta == 'sklad_himprodukcii'">mdi-check-circle</v-icon>{{ $t('addAdsWarehouseForChemicalProducts') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="tip_obekta"></has-error>
                                </div>

                            </validation-provider>


                        </div>

                        <!-- Период аренды  -->
                        <div v-if="form.tip_sdelki === 'sdam' && ['Kvartira', 'Obshejitie', 'Dom'].includes($route.params.table_name)" class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsRentalPeriod') }}</div>

                            <!-- Поле с кнопками -->
                            <validation-provider rules="required" v-model="form.period_arendi" name="period_arendi" v-slot="{ errors }">

                                <div class="form__input">
                                    <div @click="form.period_arendi = 'na_dlitelno'" class="form__item flex-fill" :class="{'item__active': form.period_arendi == 'na_dlitelno'}"><v-icon color="green" v-if="form.period_arendi == 'na_dlitelno'">mdi-check-circle</v-icon> {{ $t('addAdsForALongTime') }} </div>
                                    <div @click="form.period_arendi = 'posutochno'" class="form__item flex-fill" :class="{'item__active': form.period_arendi == 'posutochno'}"><v-icon color="green" v-if="form.period_arendi == 'posutochno'">mdi-check-circle</v-icon> {{ $t('addAdsDaily') }} </div>
                                    <div @click="form.period_arendi = 'po_chasam'" class="form__item flex-fill" :class="{'item__active': form.period_arendi == 'po_chasam'}"> <v-icon color="green" v-if="form.period_arendi == 'po_chasam'">mdi-check-circle</v-icon>{{ $t('addAdsByTheHour') }} </div>
                                    <div @click="form.period_arendi = 'na_podselenie'" class="form__item flex-fill" :class="{'item__active': form.period_arendi == 'na_podselenie'}"><v-icon color="green" v-if="form.period_arendi == 'na_podselenie'">mdi-check-circle</v-icon> {{ $t('addAdsForSharing') }} </div>
                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="period_arendi"></has-error>
                                </div>

                            </validation-provider>

                        </div>

                        <!-- Количество комнат  -->
                        <div v-if="['Kvartira','Obshejitie','Dom','Ofis'].includes($route.params.table_name)" class="form-group my-3">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $route.params.table_name == 'Ofis' ? $t('addAdsNumberOfCabinets') : $t('addAdsNumberOfRooms') }}</div>

                            <div class="row g-0">

                                <!-- Кнопки - 1,2,3-->
                                <div class="col-auto d-flex align-items-center gap-1 px-1" style="height: 55px">
                                    <div v-for="n in 8" role="button" @click="form.kolichestvo_komnat = n" class="d-flex rounded-2 align-center justify-center" style="font-size: 1.1em; font-weight: bold; width: 50px; height: 50px; background: #ffffff; border: 1px solid #cecece" :style="{ background: form.kolichestvo_komnat == n ? '#f0f7f0' : '' }">
                                        {{n}}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Действующий бизнес - Да , нет -->
                        <div v-if="['Business','Prochaya','Magazin'].includes($route.params.table_name)" class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">
                                {{ $t('addAdsOperatingBusiness') }}
                            </div>

                            <!-- Поле -->
                            <validation-provider rules="required" v-model="form.deistvuushii_biznes" name="deistvuushii_biznes" v-slot="{ errors }">

                                <div class="form__input">
                                    <span @click="form.deistvuushii_biznes = 'da'" class="form__item" :class="{'item__active':form.deistvuushii_biznes == 'da'}"><v-icon color="green" v-if="form.deistvuushii_biznes == 'da'">mdi-check-circle</v-icon>{{ $t('addAdsYes') }}</span>
                                    <span @click="form.deistvuushii_biznes = 'net'" class="form__item" :class="{'item__active':form.deistvuushii_biznes == 'net'}"><v-icon color="green" v-if="form.deistvuushii_biznes == 'net'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>
                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="deistvuushii_biznes"></has-error>
                                </div>

                            </validation-provider>

                        </div>

                        <!-- Магазин - Местоположение -->
                        <div v-if="['Business','Magazin'].includes($route.params.table_name)" class="form-group py-3">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsLocation') }}</div>

                            <!-- Поле -->
                            <div class="form__input">

                                <validation-provider rules="required" v-model="form.mestopolojenie" name="mestopolojenie" v-slot="{ errors }">
                                    <select v-model="form.mestopolojenie" name="mestopolojenie" class="form-select">
                                        <option value="v_torgovom_centre">{{ $t('addAdsInTheShoppingCenter') }}</option>
                                        <option value="v_administrativnom_zdanii">{{ $t('addAdsInTheAdministrativeBuilding') }}</option>
                                        <option value="na_rinke">{{ $t('addAdsAtTheUniversalMarketFleaMarket') }}</option>
                                        <option value="v_jilom_dome">{{ $t('addAdsInAResidentialBuilding') }}</option>
                                        <option value="otdelno_stoyashee_zdanie">{{ $t('addAdsDetachedBuilding') }}</option>
                                        <option value="ostanovochniy_kompleks">{{ $t('addAdsBusStopComplex') }}</option>
                                        <option value="drugoe">{{ $t('addAdsOtherText') }}</option>
                                    </select>


                                    <!-- Вывожу ошибки - veevalidate, vform -->
                                    <div class="text__error">
                                        <span >{{ errors[0] }}</span>
                                        <has-error :form="form" field="mestopolojenie"></has-error>
                                    </div>
                                </validation-provider>


                            </div>

                        </div>


                        <!-- Этаж - Этажность -->
                        <div class="form-group row g-0 gap-3" v-if="['Kvartira','Obshejitie','Dom','Ofis','Zdanie','Magazin'].includes($route.params.table_name)">

                            <!-- Этаж -->
                            <div class="col" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Ofis'|| $route.params.table_name == 'Magazin'">
                                <validation-provider rules="required|integer|min_value:1|max_value:999" v-model="form.etag" name="etag" class="col" v-slot="{ errors }">

                                    <v-text-field
                                        v-model="form.etag"
                                        name="etag" type="text" :label="$t('addAdsFloor')"
                                        inputmode="numeric"
                                        variant="outlined" color="blue"
                                        :error-messages="form.errors.has('etag') ? form.errors.get('etag'):'' || errors[0]"
                                        @input="form.errors.clear('etag')"
                                    ></v-text-field>

                                </validation-provider>
                            </div>

                            <!-- Этажность -->
                            <div class="col">
                                <validation-provider rules="required|integer|min_value:1|max_value:999" v-model="form.etagnost" name="etagnost" class="col" v-slot="{ errors }">

                                    <!-- Input -->
                                    <v-text-field
                                        v-model="form.etagnost"
                                        name="etagnost" type="text" :label="$t('addAdsNumberOfFloorsOfTheHouse')"
                                        inputmode="numeric"
                                        variant="outlined" color="blue"
                                        :error-messages="form.errors.has('etagnost') ? form.errors.get('etagnost'):'' || errors[0]"
                                        @input="form.errors.clear('etagnost')"
                                    ></v-text-field>

                                </validation-provider>
                            </div>

                        </div>

                        <!-- Высота потолков-->
                        <div class="form-group" v-if="['Ofis','Zdanie'].includes($route.params.table_name)">

                            <!-- Поле -->
                            <div class="form__input">

                                <validation-provider rules="required|max:5" v-model="form.visota_potolkov" name="visota_potolkov" v-slot="{ errors }">

                                    <!-- Input -->
                                    <v-text-field
                                        v-model="form.visota_potolkov"
                                        name="visota_potolkov" type="text" :label="$t('addAdsCeilingHeightM')"
                                        inputmode="numeric" v-mask="'#.##'"
                                        variant="outlined" color="blue"
                                        :error-messages="form.errors.has('visota_potolkov') ? form.errors.get('visota_potolkov'):'' || errors[0]"
                                        @input="form.errors.clear('visota_potolkov')"
                                    ></v-text-field>

                                </validation-provider>

                            </div>
                        </div>

                        <!-- Площадь - Общая, кухня -->
                        <div class="form-group row g-0 gap-3">

                            <!-- Общая площадь -->
                            <div class="col">
                                <validation-provider rules="required|integer|min_value:1|max_value:99999" v-model="form.ploshad_obshaya" name="ploshad_obshaya" v-slot="{ errors }">

                                    <!-- Input -->
                                    <v-text-field
                                        v-model="form.ploshad_obshaya"
                                        name="ploshad_obshaya" type="text" :label="$t('addAdsTotalArea')"
                                        inputmode="numeric"
                                        variant="outlined" color="blue"
                                        :error-messages="form.errors.has('ploshad_obshaya') ? form.errors.get('ploshad_obshaya'):'' || errors[0]"
                                        @input="form.errors.clear('ploshad_obshaya')"
                                    ></v-text-field>

                                </validation-provider>
                            </div>

                            <!-- Площадь кухни -->
                            <div class="col">
                                <validation-provider v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom'" rules="numeric|min_value:1|max_value:999" v-model="form.ploshad_kuhni" name="ploshad_kuhni" v-slot="{ errors }">

                                    <!-- Input -->
                                    <v-text-field
                                        v-model="form.ploshad_kuhni"
                                        name="ploshad_kuhni" type="text" :label="$t('addAdsKitchenArea')"
                                        inputmode="numeric"
                                        variant="outlined" color="blue"
                                        :error-messages="form.errors.has('ploshad_kuhni') ? form.errors.get('ploshad_kuhni'):'' || errors[0]"
                                        @input="form.errors.clear('ploshad_kuhni')"
                                    ></v-text-field>

                                </validation-provider>
                            </div>


                        </div>

                        <!-- Площадь участка -->
                        <div class="form-group" v-if="['Dom','Zdanie','Prombaza','Prochaya'].includes($route.params.table_name)">

                            <!-- Поле -->
                            <validation-provider rules="integer|min_value:1|max_value:99999" v-model="form.ploshad_uchastka" name="ploshad_uchastka" v-slot="{ errors }">

                                <!-- Input -->
                                <v-text-field
                                    v-model="form.ploshad_uchastka"
                                    name="ploshad_uchastka" type="text" :label="$t('addAdsPlotAreaAcres')"
                                    inputmode="numeric"
                                    variant="outlined" color="blue"
                                    :error-messages="form.errors.has('ploshad_uchastka') ? form.errors.get('ploshad_uchastka'):'' || errors[0]"
                                    @input="form.errors.clear('ploshad_uchastka')"
                                ></v-text-field>

                            </validation-provider>


                        </div>

                        <!-- Тип строения -->
                        <div class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">
                                {{ $t('addAdsTypeOfBuilding') }}
                            </div>

                            <!-- Поле -->
                            <validation-provider rules="required" v-model="form.tip_stroeniya" name="tip_stroeniya" v-slot="{ errors, valid, invalid, touched}">

                                <div class="form__input">
                                    <span @click="form.tip_stroeniya = 'panel'" class="form__item"  :class="{ 'item__active' : form.tip_stroeniya == 'panel' }"><v-icon color="green" v-if="form.tip_stroeniya == 'panel'">mdi-check-circle</v-icon> {{ $t('addAdsPanel') }} </span>
                                    <span @click="form.tip_stroeniya = 'kirpich'" class="form__item"  :class="{ 'item__active' : form.tip_stroeniya == 'kirpich' }"><v-icon color="green" v-if="form.tip_stroeniya == 'kirpich'">mdi-check-circle</v-icon> {{ $t('addAdsBrick') }} </span>
                                    <span @click="form.tip_stroeniya = 'derevo'" class="form__item"  :class="{ 'item__active' : form.tip_stroeniya == 'derevo' }"><v-icon color="green" v-if="form.tip_stroeniya == 'derevo'">mdi-check-circle</v-icon> {{ $t('addAdsTree') }} </span>
                                    <span @click="form.tip_stroeniya = 'drugoe'" class="form__item"  :class="{ 'item__active' : form.tip_stroeniya == 'drugoe' }"><v-icon color="green" v-if="form.tip_stroeniya == 'drugoe'">mdi-check-circle</v-icon> {{ $t('addAdsOtherText') }} </span>
                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="tip_stroeniya"></has-error>
                                </div>

                            </validation-provider>

                        </div>

                        <!-- Год постройки -->
                        <div class="form-group">

                            <!-- Поле -->
                            <validation-provider rules="required|integer|min_value:1|max_value:99999" v-model="form.god_postroiki" name="god_postroiki" v-slot="{ errors }">

                                <!-- Input -->
                                <v-text-field
                                    v-model="form.god_postroiki"
                                    name="god_postroiki" type="text" :label="$t('addAdsYearOfConstruction')"
                                    inputmode="numeric"
                                    variant="outlined" color="blue"
                                    :error-messages="form.errors.has('god_postroiki') ? form.errors.get('god_postroiki'):'' || errors[0]"
                                    @input="form.errors.clear('god_postroiki')"
                                ></v-text-field>

                            </validation-provider>


                        </div>


                    </div>

                    <!-- О квартире - О общежитии и тд - много данных -->
                    <div v-if="$route.params.step == 2" class="addAds__form-block">

                        <!-- Состояние - Евро ремонт, Хорошее, среднее, требует ремонта, черновая отделка -->
                        <div class="form-group">

                            <div class="form__title">
                                {{ $t('addAdsCondition') }}
                            </div>


                            <validation-provider rules="required" v-model="form.sostoyanie" name="sostoyanie" v-slot="{ errors }">

                                <div class="form__input">

                                    <!-- Евро ремонт -->
                                    <span @click="form.sostoyanie = 'evro_remont'" class="form__item" :class="{'item__active':form.sostoyanie == 'evro_remont'}"><v-icon color="green" v-if="form.sostoyanie == 'evro_remont'">mdi-check-circle</v-icon>{{ $t('addAdsEuroRepair') }}</span>

                                    <!-- Хорошее -->
                                    <span @click="form.sostoyanie = 'horoshee'" class="form__item" :class="{'item__active':form.sostoyanie == 'horoshee'}"><v-icon color="green" v-if="form.sostoyanie == 'horoshee'">mdi-check-circle</v-icon>{{ $t('addAdsGood') }}</span>

                                    <!-- Среднее -->
                                    <span @click="form.sostoyanie = 'srednee'" class="form__item" :class="{'item__active':form.sostoyanie == 'srednee'}"><v-icon color="green" v-if="form.sostoyanie == 'srednee'">mdi-check-circle</v-icon>{{ $t('addAdsAverage') }}</span>

                                    <!-- Требует ремонта -->
                                    <span @click="form.sostoyanie = 'trebuet_remonta'" class="form__item" :class="{'item__active':form.sostoyanie == 'trebuet_remonta'}"><v-icon color="green" v-if="form.sostoyanie == 'trebuet_remonta'">mdi-check-circle</v-icon>{{ $t('addAdsRequiresRepair') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="sostoyanie"></has-error>
                                </div>

                            </validation-provider>
                        </div>

                        <!-- Мебель - Полностью, Частично, Нет -->
                        <div class="form-group" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Ofis'|| $route.params.table_name == 'Magazin'">

                            <div class="form__title">
                                {{ $t('addAdsFurniture') }}
                            </div>


                            <validation-provider rules="required" v-model="form.mebel" name="mebel" v-slot="{ errors, valid, invalid, touched}">

                                <div class="form__input">

                                    <!-- Полностью-->
                                    <span @click="form.mebel = 'polnostu'" class="form__item" :class="{'item__active':form.mebel == 'polnostu'}"><v-icon color="green" v-if="form.mebel == 'polnostu'">mdi-check-circle</v-icon>{{ $t('addAdsCompletely') }}</span>

                                    <!-- Частично -->
                                    <span @click="form.mebel = 'chastichno'" class="form__item" :class="{'item__active':form.mebel == 'chastichno'}"><v-icon color="green" v-if="form.mebel == 'chastichno'">mdi-check-circle</v-icon>{{ $t('addAdsPartly') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.mebel = 'net'" class="form__item" :class="{'item__active':form.mebel == 'net'}"><v-icon color="green" v-if="form.mebel == 'net'">mdi-check-circle</v-icon>{{ $t('addAdsUnfurnished') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="mebel"></has-error>
                                </div>

                            </validation-provider>


                        </div>

                        <!-- Душ - В комнате, В секции, нет -->
                        <div class="form-group" v-if="$route.params.table_name == 'Obshejitie'" >

                            <div class="form__title">
                                {{ $t('addAdsShower') }}
                            </div>

                            <validation-provider rules="required" v-model="form.dush" name="dush" v-slot="{ errors, valid, invalid, touched}">

                                <div class="form__input">

                                    <!-- В комнате -->
                                    <span @click="form.dush = 'v_komnate'" class="form__item" :class="{'item__active':form.dush == 'v_komnate'}"><v-icon color="green" v-if="form.dush == 'v_komnate'">mdi-check-circle</v-icon>{{ $t('addAdsInTheRoom') }}</span>

                                    <!-- В секции -->
                                    <span @click="form.dush = 'v_sekcii'" class="form__item" :class="{'item__active':form.dush == 'v_sekcii'}"><v-icon color="green" v-if="form.dush == 'v_sekcii'">mdi-check-circle</v-icon>{{ $t('addAdsInTheSection') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.dush = 'net'" class="form__item" :class="{'item__active':form.dush == 'net'}"><v-icon color="green" v-if="form.dush == 'net'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="dush"></has-error>
                                </div>

                            </validation-provider>

                        </div>

                        <!-- Санузел -->
                        <div  class="form-group" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Ofis' || $route.params.table_name == 'Magazin'">

                            <div class="form__title">
                                {{ $t('addAdsBathroom') }}
                            </div>

                            <validation-provider rules="required" v-model="form.sanuzel" name="sanuzel" v-slot="{ errors }">

                                <div class="form__input">

                                    <!-- Квартира - Магазин | Раздельный -->
                                    <span v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Magazin'" @click="form.sanuzel = 'razdelniy'" class="form__item" :class="{'item__active':form.sanuzel == 'razdelniy'}"><v-icon color="green" v-if="form.sanuzel == 'razdelniy'">mdi-check-circle</v-icon>{{ $t('addAdsSeparate') }}</span>
                                    <!-- Совмещенный -->
                                    <span v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Magazin'" @click="form.sanuzel = 'sovmesheniy'" class="form__item" :class="{'item__active':form.sanuzel == 'sovmesheniy'}"><v-icon color="green" v-if="form.sanuzel == 'sovmesheniy'">mdi-check-circle</v-icon>{{ $t('addAdsCombined') }}</span>
                                    <!-- 2 с/у и более -->
                                    <span v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Magazin'" @click="form.sanuzel = '2ibolee'" class="form__item" :class="{'item__active':form.sanuzel == '2ibolee'}"><v-icon color="green" v-if="form.sanuzel == '2ibolee'">mdi-check-circle</v-icon>{{ $t('addAds2OrMore') }}</span>


                                    <!-- Общежитие- В комнате -->
                                    <span v-if="$route.params.table_name == 'Obshejitie'" @click="form.sanuzel = 'v_komnate'" class="form__item" :class="{'item__active':form.sanuzel == 'v_komnate'}"><v-icon color="green" v-if="form.sanuzel == 'v_komnate'">mdi-check-circle</v-icon>{{ $t('addAdsInTheRoom') }}</span>
                                    <!-- В секции -->
                                    <span v-if="$route.params.table_name == 'Obshejitie'" @click="form.sanuzel = 'v_sekcii'" class="form__item" :class="{'item__active':form.sanuzel == 'v_sekcii'}"><v-icon color="green" v-if="form.sanuzel == 'v_sekcii'">mdi-check-circle</v-icon>{{ $t('addAdsInTheSection') }}</span>


                                    <!-- Дом / В доме -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.sanuzel = 'v_dome'" class="form__item" :class="{'item__active':form.sanuzel == 'v_dome'}"><v-icon color="green" v-if="form.sanuzel == 'v_dome'">mdi-check-circle</v-icon>{{ $t('addAdsInAHouse') }}</span>
                                    <!-- 2 с/у и более -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.sanuzel = '2ibolee'" class="form__item" :class="{'item__active':form.sanuzel == '2ibolee'}"><v-icon color="green" v-if="form.sanuzel == '2ibolee'">mdi-check-circle</v-icon>{{ $t('addAds2OrMore') }}</span>
                                    <!-- Во дворе -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.sanuzel = 'vo_dvore'" class="form__item" :class="{'item__active':form.sanuzel == 'vo_dvore'}"><v-icon color="green" v-if="form.sanuzel == 'vo_dvore'">mdi-check-circle</v-icon>{{ $t('addAdsInTheCourtyard') }}</span>


                                    <!-- Офис | Есть -->
                                    <span v-if="$route.params.table_name == 'Ofis'" @click="form.sanuzel = 'est'" class="form__item" :class="{'item__active':form.sanuzel == 'est'}"><v-icon color="green" v-if="form.sanuzel == 'est'">mdi-check-circle</v-icon>{{ $t('addAdsThereAre') }}</span>
                                    <!-- 2 с/у и более -->
                                    <span v-if="$route.params.table_name == 'Ofis'" @click="form.sanuzel = '2ibolee'" class="form__item" :class="{'item__active':form.sanuzel == '2ibolee'}"><v-icon color="green" v-if="form.sanuzel == '2ibolee'">mdi-check-circle</v-icon>{{ $t('addAds2OrMore') }}</span>


                                    <!-- Для всех  -->
                                    <span @click="form.sanuzel = 'net'" class="form__item" :class="{'item__active':form.sanuzel == 'net'}"><v-icon color="green" v-if="form.sanuzel == 'net'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="sanuzel"></has-error>
                                </div>


                            </validation-provider>


                        </div>

                        <!-- Отдельный вход - есть, нет -->
                        <div class="form-group" v-if="$route.params.table_name == 'Ofis' || $route.params.table_name == 'Magazin'">

                            <div class="form__title">
                                {{ $t('addAdsSeparateEntrance') }}
                            </div>

                            <validation-provider rules="required" v-model="form.otdelniy_vhod" name="otdelniy_vhod" v-slot="{ errors, valid, invalid, touched}">

                                <div class="form__input">

                                    <!-- Есть -->
                                    <span @click="form.otdelniy_vhod = 'est'" class="form__item" :class="{'item__active':form.otdelniy_vhod == 'est'}"><v-icon color="green" v-if="form.otdelniy_vhod == 'est'">mdi-check-circle</v-icon>{{ $t('addAdsThereAre') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.otdelniy_vhod = 'net'" class="form__item" :class="{'item__active':form.otdelniy_vhod == 'net'}"><v-icon color="green" v-if="form.otdelniy_vhod == 'net'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>


                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="otdelniy_vhod"></has-error>
                                </div>

                            </validation-provider>

                        </div>

                        <!-- Канализация - Центральная, септик, нет -->
                        <div class="form-group" v-if="$route.params.table_name == 'Dom'">

                            <div class="form__title">
                                {{ $t('addAdsSewageSystem') }}
                            </div>

                            <validation-provider rules="required" v-model="form.kanalizaciya" name="kanalizaciya" v-slot="{ errors, valid, invalid, touched}">

                                <div class="form__input">

                                    <!-- Центральная -->
                                    <span @click="form.kanalizaciya = 'centralnaya'" class="form__item" :class="{'item__active':form.kanalizaciya == 'centralnaya'}"><v-icon color="green" v-if="form.kanalizaciya == 'centralnaya'">mdi-check-circle</v-icon>{{ $t('addAdsCentral') }}</span>

                                    <!-- Септик -->
                                    <span @click="form.kanalizaciya = 'septik'" class="form__item" :class="{'item__active':form.kanalizaciya == 'septik'}"><v-icon color="green" v-if="form.kanalizaciya == 'septik'">mdi-check-circle</v-icon>{{ $t('addAdsSepticTank') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.kanalizaciya = 'net'" class="form__item" :class="{'item__active':form.kanalizaciya == 'net'}"><v-icon color="green" v-if="form.kanalizaciya == 'net'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="kanalizaciya"></has-error>
                                </div>

                            </validation-provider>

                        </div>

                        <!-- Вода - Центральная, скважина, нет -->
                        <div class="form-group" v-if="$route.params.table_name == 'Dom'">

                            <div class="form__title">
                                {{ $t('addAdsWater') }}
                            </div>

                            <validation-provider rules="required" v-model="form.voda" name="voda" v-slot="{ errors, valid, invalid, touched}">

                                <div class="form__input">

                                    <!-- Центральная -->
                                    <span @click="form.voda = 'centralnaya'" class="form__item" :class="{'item__active':form.voda == 'centralnaya'}"><v-icon color="green" v-if="form.voda == 'centralnaya'">mdi-check-circle</v-icon>{{ $t('addAdsCentral') }}</span>

                                    <!-- Скважина -->
                                    <span @click="form.voda = 'skvajina'" class="form__item" :class="{'item__active':form.voda == 'skvajina'}"><v-icon color="green" v-if="form.voda == 'skvajina'">mdi-check-circle</v-icon>{{ $t('addAdsBorehole') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.voda = 'net'" class="form__item" :class="{'item__active':form.voda == 'net'}"><v-icon color="green" v-if="form.voda == 'net'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="voda"></has-error>
                                </div>

                            </validation-provider>

                        </div>

                        <!-- Электричество - Есть, Есть возможность подключения, нет -->
                        <div class="form-group" v-if="$route.params.table_name == 'Dom'">

                            <div class="form__title">
                                {{ $t('addAdsElectricity') }}
                            </div>

                            <validation-provider rules="required" v-model="form.elektrichestvo" name="elektrichestvo" v-slot="{ errors, valid, invalid, touched}">

                                <div class="form__input">

                                    <!-- Есть -->
                                    <span @click="form.elektrichestvo = 'est'" class="form__item" :class="{'item__active':form.elektrichestvo == 'est'}"><v-icon color="green" v-if="form.elektrichestvo == 'est'">mdi-check-circle</v-icon>{{ $t('addAdsThereAre') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.elektrichestvo = 'net'" class="form__item" :class="{'item__active':form.elektrichestvo == 'net'}"><v-icon color="green" v-if="form.elektrichestvo == 'net'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="elektrichestvo"></has-error>
                                </div>

                            </validation-provider>

                        </div>

                        <!-- Отопление - Центральное, На газе, На твердом топливе, На жидком топливе, смешанное, без отопления -->
                        <div class="form-group" v-if="$route.params.table_name == 'Dom'">

                            <div class="form__title">
                                {{ $t('addAdsHeating') }}
                            </div>

                            <validation-provider rules="required" v-model="form.otoplenie" name="otoplenie" v-slot="{ errors, valid, invalid, touched}">

                                <div class="form__input">

                                    <!-- Центральное -->
                                    <span @click="form.otoplenie = 'centralnoe'" class="form__item" :class="{'item__active':form.otoplenie == 'centralnoe'}"><v-icon color="green" v-if="form.otoplenie == 'centralnoe'">mdi-check-circle</v-icon>{{ $t('addAdsCentralText') }}</span>

                                    <!-- На газе -->
                                    <span @click="form.otoplenie = 'na_gaze'" class="form__item" :class="{'item__active':form.otoplenie == 'na_gaze'}"><v-icon color="green" v-if="form.otoplenie == 'na_gaze'">mdi-check-circle</v-icon>{{ $t('addAdsOnGas') }}</span>

                                    <!-- На твердом топливе -->
                                    <span @click="form.otoplenie = 'na_tverdom_toplive'" class="form__item" :class="{'item__active':form.otoplenie == 'na_tverdom_toplive'}"><v-icon color="green" v-if="form.otoplenie == 'na_tverdom_toplive'">mdi-check-circle</v-icon>{{ $t('addAdsOnSolidFuel') }}</span>

                                    <!-- На жидком топливе -->
                                    <span @click="form.otoplenie = 'na_jitkom_toplive'" class="form__item" :class="{'item__active':form.otoplenie == 'na_jitkom_toplive'}"><v-icon color="green" v-if="form.otoplenie == 'na_jitkom_toplive'">mdi-check-circle</v-icon>{{ $t('addAdsOnLiquidFuel') }}</span>

                                    <!-- Смешанное -->
                                    <span @click="form.otoplenie = 'smeshannoe'" class="form__item" :class="{'item__active':form.otoplenie == 'smeshannoe'}"><v-icon color="green" v-if="form.otoplenie == 'smeshannoe'">mdi-check-circle</v-icon>{{ $t('addAdsMixed') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.otoplenie = 'net'" class="form__item" :class="{'item__active':form.otoplenie == 'net'}"><v-icon color="green" v-if="form.otoplenie == 'net'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="otoplenie"></has-error>
                                </div>

                            </validation-provider>

                        </div>

                        <!-- Газ - Магистральный, Автономный, Нет -->
                        <div class="form-group" v-if="$route.params.table_name == 'Dom'">

                            <div class="form__title">
                                {{ $t('addAdsGas') }}
                            </div>

                            <validation-provider rules="required" v-model="form.gaz" name="gaz" v-slot="{ errors, valid, invalid, touched}">

                                <div class="form__input">

                                    <!-- Магистральный -->
                                    <span @click="form.gaz = 'magistralniy'" class="form__item" :class="{'item__active':form.gaz == 'magistralniy'}"><v-icon color="green" v-if="form.gaz == 'magistralniy'">mdi-check-circle</v-icon>{{ $t('addAdsTrunk') }}</span>

                                    <!-- Автономный -->
                                    <span @click="form.gaz = 'avtonomniy'" class="form__item" :class="{'item__active':form.gaz == 'avtonomniy'}"><v-icon color="green" v-if="form.gaz == 'avtonomniy'">mdi-check-circle</v-icon>{{ $t('addAdsAutonomous') }}</span>

                                    <!--Нет -->
                                    <span @click="form.gaz = 'net'" class="form__item" :class="{'item__active':form.gaz == 'net'}"><v-icon color="green" v-if="form.gaz == 'net'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="gaz"></has-error>
                                </div>

                            </validation-provider>

                        </div>

                        <!-- Балкон - Балкон, Лоджия, балкон и лоджия, нет -->
                        <div class="form-group" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie'">

                            <div class="form__title">
                                {{ $t('addAdsBalcony') }}
                            </div>

                            <validation-provider rules="required" v-model="form.balkon" name="balkon" v-slot="{ errors, valid, invalid, touched}">

                                <div class="form__input">

                                    <!-- Балкон -->
                                    <span @click="form.balkon = 'balkon'" class="form__item" :class="{'item__active':form.balkon == 'balkon'}"><v-icon color="green" v-if="form.balkon == 'balkon'">mdi-check-circle</v-icon>{{ $t('addAdsBalcony') }}</span>

                                    <!-- Лоджия -->
                                    <span @click="form.balkon = 'lodjiya'" class="form__item" :class="{'item__active':form.balkon == 'lodjiya'}"><v-icon color="green" v-if="form.balkon == 'lodjiya'">mdi-check-circle</v-icon>{{ $t('addAdsLoggia') }}</span>

                                    <!-- Балкон и Лоджия -->
                                    <span @click="form.balkon = '2_balkona_i_bolee'" class="form__item" :class="{'item__active':form.balkon == '2_balkona_i_bolee'}"><v-icon color="green" v-if="form.balkon == '2_balkona_i_bolee'">mdi-check-circle</v-icon>{{ $t('addAds2BalconiesOrMore') }}</span>

                                    <!-- Несколько балконов и лоджий -->
                                    <span @click="form.balkon = 'net'" class="form__item" :class="{'item__active':form.balkon == 'net'}"><v-icon color="green" v-if="form.balkon == 'net'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="balkon"></has-error>
                                </div>

                            </validation-provider>

                        </div>


                        <!-- Для промбазы -->
                        <!-- Площадь производственных помещений -->
                        <div v-if="$route.params.table_name == 'Prombaza'" class="form-group">

                            <!-- Поле -->
                            <validation-provider rules="integer|min_value:1|max_value:9999999"  v-model="form.ploshad_proizvodstvennih_pomesheniy" name="ploshad_proizvodstvennih_pomesheniy" v-slot="{ errors }">

                                <!-- Input -->
                                <v-text-field
                                    v-model="form.ploshad_proizvodstvennih_pomesheniy" :label="$t('addAdsAreaOfIndustrialPremises')"
                                    name="ploshad_proizvodstvennih_pomesheniy" type="text"
                                    inputmode="numeric"
                                    variant="outlined" color="blue"
                                    :error-messages="form.errors.has('ploshad_proizvodstvennih_pomesheniy') ? form.errors.get('ploshad_proizvodstvennih_pomesheniy'):'' || errors[0]"
                                    @input="form.errors.clear('ploshad_proizvodstvennih_pomesheniy')"
                                ></v-text-field>

                            </validation-provider>

                        </div>
                        <!-- Высота производственных помещений -->
                        <div v-if="$route.params.table_name == 'Prombaza'" class="form-group">

                            <!-- Поле -->
                            <validation-provider rules="integer|min_value:1|max_value:100000" v-model="form.visota_proizvodstvennih_pomesheniy" name="visota_proizvodstvennih_pomesheniy" v-slot="{ errors }">

                                <!-- Input -->
                                <v-text-field
                                    v-model="form.visota_proizvodstvennih_pomesheniy" :label="$t('addAdsHeightOfProductionPremises')"
                                    name="visota_proizvodstvennih_pomesheniy" type="text"
                                    inputmode="numeric"
                                    variant="outlined" color="blue"
                                    :error-messages="form.errors.has('visota_proizvodstvennih_pomesheniy') ? form.errors.get('visota_proizvodstvennih_pomesheniy'):'' || errors[0]"
                                    @input="form.errors.clear('visota_proizvodstvennih_pomesheniy')"
                                ></v-text-field>

                            </validation-provider>

                        </div>
                        <!-- Площадь складских помещений -->
                        <div v-if="$route.params.table_name == 'Prombaza'" class="form-group">

                            <!-- Поле -->
                            <validation-provider rules="integer|min_value:1|max_value:100000" v-model="form.ploshad_skladskih_pomesheniy" name="ploshad_skladskih_pomesheniy" v-slot="{ errors }">

                                <!-- Input -->
                                <v-text-field
                                    v-model="form.ploshad_skladskih_pomesheniy" :label="$t('addAdsWarehouseArea')"
                                    name="ploshad_skladskih_pomesheniy" type="text"
                                    inputmode="numeric"
                                    variant="outlined" color="blue"
                                    :error-messages="form.errors.has('ploshad_skladskih_pomesheniy') ? form.errors.get('ploshad_skladskih_pomesheniy'):'' || errors[0]"
                                    @input="form.errors.clear('ploshad_skladskih_pomesheniy')"
                                ></v-text-field>

                            </validation-provider>

                        </div>
                        <!-- Высота складских помещений -->
                        <div v-if="$route.params.table_name == 'Prombaza'" class="form-group">

                            <!-- Поле -->
                            <validation-provider rules="integer|min_value:1|max_value:100000" v-model="form.visota_skladskih_pomesheniy" name="visota_skladskih_pomesheniy" v-slot="{ errors }">

                                <!-- Input -->
                                <v-text-field
                                    v-model="form.visota_skladskih_pomesheniy" :label="$t('addAdsWarehouseHeight')"
                                    name="visota_skladskih_pomesheniy" type="text"
                                    inputmode="numeric"
                                    variant="outlined" color="blue"
                                    :error-messages="form.errors.has('visota_skladskih_pomesheniy') ? form.errors.get('visota_skladskih_pomesheniy'):'' || errors[0]"
                                    @input="form.errors.clear('visota_skladskih_pomesheniy')"
                                ></v-text-field>

                            </validation-provider>

                        </div>
                        <!-- Площадь офисных помещений -->
                        <div v-if="$route.params.table_name == 'Prombaza'" class="form-group">

                            <!-- Поле -->
                            <validation-provider rules="integer|min_value:1|max_value:100000" v-model="form.ploshad_ofisnih_pomesheniy" name="ploshad_ofisnih_pomesheniy" v-slot="{ errors }">

                                <!-- Input -->
                                <v-text-field
                                    v-model="form.ploshad_ofisnih_pomesheniy" :label="$t('addAdsAreaOfOfficePremises')"
                                    name="ploshad_ofisnih_pomesheniy" type="text"
                                    inputmode="numeric"
                                    variant="outlined" color="blue"
                                    :error-messages="form.errors.has('ploshad_ofisnih_pomesheniy') ? form.errors.get('ploshad_ofisnih_pomesheniy'):'' || errors[0]"
                                    @input="form.errors.clear('ploshad_ofisnih_pomesheniy')"
                                ></v-text-field>

                            </validation-provider>

                        </div>

                    </div>

                    <!-- Мебель и техника -- Разное -->
                    <div v-if="$route.params.step == 3" class="addAds__form-block">

                        <!-- Мебель и техника -->
                        <div v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Ofis' || $route.params.table_name == 'Magazin'" class="form-group">

                            <div class="form__title">{{ $t('addAdsFurnitureAndAppliances') }}</div>

                            <!-- Мебель - Для квартиры - Общежития - Дом  -->
                            <div class="form__input" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom'">
                                <span @click="checkArr('mebel_arr', 'holodilnik')" class="form__item" :class="{'item__active':form.mebel_arr.includes('holodilnik')}"><v-icon color="green" v-if="form.mebel_arr.includes('holodilnik')">mdi-check-circle</v-icon>{{ $t('addAdsFridge')}}</span>
                                <span @click="checkArr('mebel_arr', 'stiralnaya_mashinka')" class="form__item" :class="{'item__active':form.mebel_arr.includes('stiralnaya_mashinka')}"><v-icon color="green" v-if="form.mebel_arr.includes('stiralnaya_mashinka')">mdi-check-circle</v-icon>{{ $t('addAdsWasher')}}</span>
                                <span @click="checkArr('mebel_arr', 'televizor')" class="form__item" :class="{'item__active':form.mebel_arr.includes('televizor')}"><v-icon color="green" v-if="form.mebel_arr.includes('televizor')">mdi-check-circle</v-icon>{{ $t('addAdsTV')}}</span>
                                <span @click="checkArr('mebel_arr', 'divan')" class="form__item" :class="{'item__active':form.mebel_arr.includes('divan')}"><v-icon color="green" v-if="form.mebel_arr.includes('divan')">mdi-check-circle</v-icon>{{ $t('addAdsSofa')}}</span>
                                <span @click="checkArr('mebel_arr', 'krovat')" class="form__item" :class="{'item__active':form.mebel_arr.includes('krovat')}"><v-icon color="green" v-if="form.mebel_arr.includes('krovat')">mdi-check-circle</v-icon>{{ $t('addAdsBed')}}</span>
                                <span @click="checkArr('mebel_arr', 'kuhonniy_garnitur')" class="form__item" :class="{'item__active':form.mebel_arr.includes('kuhonniy_garnitur')}"><v-icon color="green" v-if="form.mebel_arr.includes('kuhonniy_garnitur')">mdi-check-circle</v-icon>{{ $t('addAdsKitchenSet')}}</span>
                                <span @click="checkArr('mebel_arr', 'kondicioner')" class="form__item" :class="{'item__active':form.mebel_arr.includes('kondicioner')}"><v-icon color="green" v-if="form.mebel_arr.includes('kondicioner')">mdi-check-circle</v-icon>{{ $t('addAdsConditioner')}}</span>
                                <span @click="checkArr('mebel_arr', 'mikrovolnovaya_pech')" class="form__item" :class="{'item__active':form.mebel_arr.includes('mikrovolnovaya_pech')}"><v-icon color="green" v-if="form.mebel_arr.includes('mikrovolnovaya_pech')">mdi-check-circle</v-icon>{{ $t('addAdsAMicrowave')}}</span>
                                <span @click="checkArr('mebel_arr', 'vsya_bitovaya_tehnika')" class="form__item" :class="{'item__active':form.mebel_arr.includes('vsya_bitovaya_tehnika')}"><v-icon color="green" v-if="form.mebel_arr.includes('vsya_bitovaya_tehnika')">mdi-check-circle</v-icon>{{ $t('addAdsAllHouseholdAppliances')}}</span>
                            </div>

                            <div class="form__input" v-if="$route.params.table_name == 'Ofis' || $route.params.table_name == 'Magazin'">
                                <span @click="checkArr('mebel_arr', 'kondicioner')" class="form__item" :class="{'item__active':form.mebel_arr.includes('kondicioner')}"><v-icon color="green" v-if="form.mebel_arr.includes('kondicioner')">mdi-check-circle</v-icon>{{ $t('addAdsConditioner')}}</span>
                            </div>

                        </div>


                        <!-- Разное -->
                        <div v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom'|| $route.params.table_name == 'Ofis' || $route.params.table_name == 'Magazin' || $route.params.table_name == 'Prombaza'" class="form-group">

                            <div class="form__title">{{ $t('addAdsVarious') }}</div>

                            <!-- Разное -  Для квартиры - Общежития - Дом  -->
                            <div class="form__input" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie'">
                                <span @click="checkArr('raznoe', 'kuhnya_studiya')" class="form__item" :class="{'item__active':form.raznoe.includes('kuhnya_studiya')}"><v-icon color="green" v-if="form.raznoe.includes('kuhnya_studiya')">mdi-check-circle</v-icon>{{ $t('addAdsStudioKitchen')}}</span>
                                <span @click="checkArr('raznoe', 'komnati_izolirovanni')" class="form__item" :class="{'item__active':form.raznoe.includes('komnati_izolirovanni')}"><v-icon color="green" v-if="form.raznoe.includes('komnati_izolirovanni')">mdi-check-circle</v-icon>{{ $t('addAdsTheRoomsAreIsolated')}}</span>
                                <span @click="checkArr('raznoe', 'kabelnoe_tv')"  class="form__item" :class="{'item__active':form.raznoe.includes('kabelnoe_tv')}"><v-icon color="green" v-if="form.raznoe.includes('kabelnoe_tv')">mdi-check-circle</v-icon>{{ $t('addAdsCableTV')}}</span>
                                <span @click="checkArr('raznoe', 'kladovka')" class="form__item" :class="{'item__active':form.raznoe.includes('kladovka')}"><v-icon color="green" v-if="form.raznoe.includes('kladovka')">mdi-check-circle</v-icon>{{ $t('addAdsStorage')}}</span>
                                <span @click="checkArr('raznoe', 'besplatniy_wifi')"  class="form__item" :class="{'item__active':form.raznoe.includes('besplatniy_wifi')}"><v-icon color="green" v-if="form.raznoe.includes('besplatniy_wifi')">mdi-check-circle</v-icon>{{ $t('addAdsFreeWI-FI') }}</span>
                            </div>

                            <!-- Разное - Дом-->
                            <div class="form__input" v-if="$route.params.table_name == 'Dom'">
                                <span @click="checkArr('raznoe', 'sad')" class="form__item" :class="{'item__active':form.raznoe.includes('sad')}"><v-icon color="green" v-if="form.raznoe.includes('sad')">mdi-check-circle</v-icon>{{ $t('addAdsGarden')}}</span>
                                <span @click="checkArr('raznoe', 'banya')" class="form__item" :class="{'item__active':form.raznoe.includes('banya')}"><v-icon color="green" v-if="form.raznoe.includes('banya')">mdi-check-circle</v-icon>{{ $t('addAdsBathhouse')}}</span>
                                <span @click="checkArr('raznoe', 'bassein')" class="form__item" :class="{'item__active':form.raznoe.includes('bassein')}"><v-icon color="green" v-if="form.raznoe.includes('bassein')">mdi-check-circle</v-icon>{{ $t('addAdsSwimmingPool')}}</span>
                                <span @click="checkArr('raznoe', 'sauna')" class="form__item" :class="{'item__active':form.raznoe.includes('sauna')}"><v-icon color="green" v-if="form.raznoe.includes('sauna')">mdi-check-circle</v-icon>{{ $t('addAdsSauna')}}</span>
                                <span @click="checkArr('raznoe', 'jakuzi')" class="form__item" :class="{'item__active':form.raznoe.includes('jakuzi')}"><v-icon color="green" v-if="form.raznoe.includes('jakuzi')">mdi-check-circle</v-icon>{{ $t('addAdsJacuzzi')}}</span>
                                <span @click="checkArr('raznoe', 'kuhnya_studiya')" class="form__item" :class="{'item__active':form.raznoe.includes('kuhnya_studiya')}"><v-icon color="green" v-if="form.raznoe.includes('kuhnya_studiya')">mdi-check-circle</v-icon>{{ $t('addAdsStudioKitchen')}}</span>
                                <span @click="checkArr('raznoe', 'komnati_izolirovani')" class="form__item" :class="{'item__active':form.raznoe.includes('komnati_izolirovani')}"><v-icon color="green" v-if="form.raznoe.includes('komnati_izolirovani')">mdi-check-circle</v-icon>{{ $t('addAdsTheRoomsAreIsolated')}}</span>
                                <span @click="checkArr('raznoe', 'kabelnoe_tv')" class="form__item" :class="{'item__active':form.raznoe.includes('kabelnoe_tv')}"><v-icon color="green" v-if="form.raznoe.includes('kabelnoe_tv')">mdi-check-circle</v-icon>{{ $t('addAdsCableTV')}}</span>
                                <span @click="checkArr('raznoe', 'hozpostroiki')" class="form__item" :class="{'item__active':form.raznoe.includes('hozpostroiki')}"><v-icon color="green" v-if="form.raznoe.includes('hozpostroiki')">mdi-check-circle</v-icon>{{ $t('addAdsOutbuildings')}}</span>
                                <span @click="checkArr('raznoe', '3_fazi')" class="form__item" :class="{'item__active':form.raznoe.includes('3_fazi')}"><v-icon color="green" v-if="form.raznoe.includes('3_fazi')">mdi-check-circle</v-icon>{{ $t('addAds3Phases')}}</span>
                            </div>

                            <!-- Разное - Магазин -->
                            <div class="form__input" v-if="$route.params.table_name == 'Magazin'">
                                <span @click="checkArr('raznoe', 's_tovarom')"  class="form__item" :class="{'item__active':form.raznoe.includes('s_tovarom')}"><v-icon color="green" v-if="form.raznoe.includes('s_tovarom')">mdi-check-circle</v-icon>{{ $t('addAdsWithTheProduct')}}</span>
                                <span @click="checkArr('raznoe', 's_torgovim_oborudovaniem')"  class="form__item" :class="{'item__active':form.raznoe.includes('s_torgovim_oborudovaniem')}"><v-icon color="green" v-if="form.raznoe.includes('s_torgovim_oborudovaniem')">mdi-check-circle</v-icon>{{ $t('addAdsWithCommercialEquipment')}}</span>
                            </div>

                            <!-- Разное - Промбаза -->
                            <div class="form__input" v-if="$route.params.table_name == 'Prombaza'">
                                <span @click="checkArr('raznoe', 'jd_tupik')" class="form__item" :class="{'item__active':form.raznoe.includes('jd_tupik')}"><v-icon color="green" v-if="form.raznoe.includes('jd_tupik')">mdi-check-circle</v-icon>{{ $t('addAdsRailwayDeadEnd') }}</span>
                                <span @click="checkArr('raznoe', 'avtovesi')"  class="form__item" :class="{'item__active':form.raznoe.includes('avtovesi')}"><v-icon color="green" v-if="form.raznoe.includes('avtovesi')">mdi-check-circle</v-icon>{{ $t('addAdsCarWeights')}}</span>
                                <span @click="checkArr('raznoe', 'holodilnie_kameri')"  class="form__item" :class="{'item__active':form.raznoe.includes('holodilnie_kameri')}"><v-icon color="green" v-if="form.raznoe.includes('holodilnie_kameri')">mdi-check-circle</v-icon>{{ $t('addAdsColdStorageRooms')}}</span>
                                <span @click="checkArr('raznoe', 'kran_balka')" class="form__item" :class="{'item__active':form.raznoe.includes('kran_balka')}"><v-icon color="green" v-if="form.raznoe.includes('kran_balka')">mdi-check-circle</v-icon>{{ $t('addAdsCraneBeam')}}</span>
                                <span @click="checkArr('raznoe', 'pandus')" class="form__item" :class="{'item__active':form.raznoe.includes('pandus')}"><v-icon color="green" v-if="form.raznoe.includes('pandus')">mdi-check-circle</v-icon>{{ $t('addAdsRamp') }}</span>
                                <span @click="checkArr('raznoe', 'svoya_podstanciya')" class="form__item" :class="{'item__active':form.raznoe.includes('svoya_podstanciya')}"><v-icon color="green" v-if="form.raznoe.includes('svoya_podstanciya')">mdi-check-circle</v-icon>{{ $t('addAdsOwnSubstation')}}</span>
                            </div>

                        </div>


                        <!-- Расположение - промбаза, прочее -->
                        <div v-if="$route.params.table_name == 'Business' || $route.params.table_name == 'Prombaza' || $route.params.table_name == 'Prochaya'" class="form-group">

                            <div class="form__title">{{ $t('addAdsLocation') }}</div>

                            <div class="form__input">
                                <span @click="checkArr('raspolojenie', 'v_gorode')" class="form__item" :class="{'item__active':form.raspolojenie.includes('v_gorode')}"><v-icon color="green" v-if="form.raspolojenie.includes('v_gorode')">mdi-check-circle</v-icon>{{ $t('addAdsInTheCity') }}</span>
                                <span @click="checkArr('raspolojenie', 'v_prigorode')" class="form__item" :class="{'item__active':form.raspolojenie.includes('v_prigorode')}"><v-icon color="green" v-if="form.raspolojenie.includes('v_prigorode')">mdi-check-circle</v-icon>{{ $t('addAdsInTheSuburbs')}}</span>
                                <span @click="checkArr('raspolojenie', 'vdol_trasi')" class="form__item" :class="{'item__active':form.raspolojenie.includes('vdol_trasi')}"><v-icon color="green" v-if="form.raspolojenie.includes('vdol_trasi')">mdi-check-circle</v-icon>{{ $t('addAdsAlongTheHighway') }}</span>
                            </div>
                        </div>

                        <!-- Коммуникации - промбаза -->
                        <div v-if="$route.params.table_name == 'Business' || $route.params.table_name == 'Prombaza' || $route.params.table_name == 'Prochaya'" class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsCommunications') }}</div>

                            <div class="form__input">
                                <span @click="checkArr('kommunikacii', 'svet')" class="form__item" :class="{'item__active':form.kommunikacii.includes('svet')}"><v-icon color="green" v-if="form.kommunikacii.includes('svet')">mdi-check-circle</v-icon>{{ $t('addAdsLight') }}</span>
                                <span @click="checkArr('kommunikacii', 'gaz')" class="form__item" :class="{'item__active':form.kommunikacii.includes('gaz')}"><v-icon color="green" v-if="form.kommunikacii.includes('gaz')">mdi-check-circle</v-icon>{{ $t('addAdsGas') }}</span>
                                <span @click="checkArr('kommunikacii', 'internet')"  class="form__item" :class="{'item__active':form.kommunikacii.includes('internet')}"><v-icon color="green" v-if="form.kommunikacii.includes('internet')">mdi-check-circle</v-icon>{{ $t('addAdsTheInternet') }}</span>
                                <span @click="checkArr('kommunikacii', 'otoplenie')"  class="form__item" :class="{'item__active':form.kommunikacii.includes('otoplenie')}"><v-icon color="green" v-if="form.kommunikacii.includes('otoplenie')">mdi-check-circle</v-icon>{{ $t('addAdsHeating') }}</span>
                                <span @click="checkArr('kommunikacii', 'voda')"  class="form__item" :class="{'item__active':form.kommunikacii.includes('voda')}"><v-icon color="green" v-if="form.kommunikacii.includes('voda')">mdi-check-circle</v-icon>{{ $t('addAdsWater') }}</span>
                                <span @click="checkArr('kommunikacii', 'telefon')" class="form__item"  :class="{'item__active':form.kommunikacii.includes('telefon')}"><v-icon color="green" v-if="form.kommunikacii.includes('telefon')">mdi-check-circle</v-icon>{{ $t('addAdsTelephone') }}</span>
                                <span @click="checkArr('kommunikacii', 'kanalizaciya')" class="form__item" :class="{'item__active':form.kommunikacii.includes('kanalizaciya')}"><v-icon color="green" v-if="form.kommunikacii.includes('kanalizaciya')">mdi-check-circle</v-icon>{{ $t('addAdsSewageSystem') }}</span>
                            </div>

                        </div>

                        <!-- Безопасность -->
                        <div v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Ofis' || $route.params.table_name == 'Zdanie' || $route.params.table_name == 'Magazin'" class="form-group">

                            <div class="form__title">{{ $t('addAdsSecurity') }}</div>

                            <div class="form__input">
                                <span @click="checkArr('bezopasnost', 'domofon')" class="form__item" :class="{'item__active':form.bezopasnost.includes('domofon')}"><v-icon color="green" v-if="form.bezopasnost.includes('domofon')">mdi-check-circle</v-icon>{{ $t('addAdsIntercom') }}</span>
                                <span @click="checkArr('bezopasnost', 'videodomofon')" class="form__item" :class="{'item__active':form.bezopasnost.includes('videodomofon')}"><v-icon color="green" v-if="form.bezopasnost.includes('videodomofon')">mdi-check-circle</v-icon>{{ $t('addAdsVideoIntercom') }}</span>
                                <span @click="checkArr('bezopasnost', 'signalizaciya')" class="form__item" :class="{'item__active':form.bezopasnost.includes('signalizaciya')}"><v-icon color="green" v-if="form.bezopasnost.includes('signalizaciya')">mdi-check-circle</v-icon>{{ $t('addAdsAlarmSystem') }}</span>
                                <span @click="checkArr('bezopasnost', 'reshotki_na_oknah')" class="form__item" :class="{'item__active':form.bezopasnost.includes('reshotki_na_oknah')}"><v-icon color="green" v-if="form.bezopasnost.includes('reshotki_na_oknah')">mdi-check-circle</v-icon>{{ $t('addAdsBarsOnTheWindows') }}</span>
                                <span @click="checkArr('bezopasnost', 'ohrana')" class="form__item" :class="{'item__active':form.bezopasnost.includes('ohrana')}"><v-icon color="green" v-if="form.bezopasnost.includes('ohrana')">mdi-check-circle</v-icon>{{ $t('addAdsSecurity2') }}</span>
                                <span @click="checkArr('bezopasnost', 'videonabludenie')" class="form__item" :class="{'item__active':form.bezopasnost.includes('videonabludenie')}"><v-icon color="green" v-if="form.bezopasnost.includes('videonabludenie')">mdi-check-circle</v-icon>{{ $t('addAdsVideoSurveillance') }}</span>
                                <span @click="checkArr('bezopasnost', 'kodoviy_zamok')" class="form__item" :class="{'item__active':form.bezopasnost.includes('kodoviy_zamok')}"><v-icon color="green" v-if="form.bezopasnost.includes('kodoviy_zamok')">mdi-check-circle</v-icon>{{ $t('addAdsCombinationLock') }}</span>
                                <span @click="checkArr('bezopasnost', 'konserj')" class="form__item" :class="{'item__active':form.bezopasnost.includes('konserj')}"><v-icon color="green" v-if="form.bezopasnost.includes('konserj')">mdi-check-circle</v-icon>{{ $t('addAdsConcierge') }}</span>
                            </div>

                        </div>

                    </div>

                    <!--Текст описание - Textarea -->
                    <div v-if="$route.params.step == 4" class="form-group  addAds__form-block">

                        <!-- Заголовок -->
                        <h2 class="text-center py-2 pb-4 fw-bold">{{ langStore.lang == 'ru' ? 'Описание и цена' : ''}}{{ langStore.lang == 'kz' ? 'Сипаттама және баға' : ''}}</h2>

                        <!-- Цена в месяц - За кв.м, за все -->
                        <div v-if="form.tip_sdelki == 'sdam' && form.tip_sdelki == 'sdam' && ['Business','Ofis','Zdanie','Magazin','Prombaza','Prochaya'].includes($route.params.table_name)" class="py-3">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsPricePerMonth') }}</div>

                            <!-- Поле -->
                            <validation-provider rules="required" v-model="form.cena_tip" name="cena_tip" v-slot="{ errors }">

                                <div class="form__input">
                                    <span @click="form.cena_tip = 'za_vse'" class="form__item" :class="{'item__active':form.cena_tip == 'za_vse'}"><v-icon color="green" v-if="form.cena_tip == 'za_vse'">mdi-check-circle</v-icon>{{ $t('addAdsForEverything') }}</span>
                                    <span @click="form.cena_tip = 'za_kvm'" class="form__item" :class="{'item__active':form.cena_tip == 'za_kvm'}"><v-icon color="green" v-if="form.cena_tip == 'za_kvm'">mdi-check-circle</v-icon>{{ $t('addAdsPerSqM') }}</span>
                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="cena_tip"></has-error>
                                </div>

                            </validation-provider>


                        </div>

                        <!-- Цена -->
                        <div class="py-3">

                            <validation-provider rules="required|min:3|max:11" v-model="form.cena" name="cena" v-slot="{ errors }" >

                                <v-text-field
                                    v-model="form.cena"
                                    id="cena_field"
                                    name="cena" type="text" :label="cena"
                                    inputmode="numeric"
                                    variant="outlined" color="blue"
                                    v-number="number"

                                    :error-messages="form.errors.has('cena') ? form.errors.get('cena'):'' || errors[0]"
                                    @focus="form.errors.clear('cena')"
                                ></v-text-field>

                            </validation-provider>

                        </div>

                        <!-- ПОЛЕ - Заголовок -->
                        <div class="my-3">

                            <!-- Input -->
                            <validation-provider rules="required|min:1|max:40" v-model="form.zagolovok" name="zagolovok" v-slot="{ errors }">

                                <v-text-field
                                    v-model="form.zagolovok"
                                    name="zagolovok" :label="$t('addAdsTitle')"
                                    type="text"
                                    counter maxlength="40"
                                    variant="outlined" color="blue"
                                    :error-messages="form.errors.has('zagolovok') ? form.errors.get('zagolovok'):'' || errors[0]"
                                    @input="form.errors.clear('zagolovok')"
                                ></v-text-field>

                            </validation-provider>

                        </div>

                        <!-- Поле -->
                        <validation-provider rules="required|min:1|max:2000" v-model="form.text_obyavleniya" name="text_obyavleniya" v-slot="{ errors }">

                            <v-textarea v-model="form.text_obyavleniya"
                                        name="text_obyavleniya" :label="$t('addAdsEnterADescription')"
                                        variant="outlined" color="blue"
                                        auto-grow rows="3" max-rows="8"
                                        counter maxlength="2000"

                                        :error-messages="form.errors.has('text_obyavleniya') ? form.errors.get('text_obyavleniya'):'' || errors[0]"
                                        @input="form.errors.clear('text_obyavleniya')"
                            ></v-textarea>


                        </validation-provider>


                    </div>

                    <!-- Загрузка Фото -->
                    <div v-if="$route.params.step == 5" class="form-group addAds__form-block">

                        <!-- Поле для выбора картинок с валидацией на клиенте -->
                        <validation-provider rules="image" ref="provider" name="images" v-slot="{errors, valid, validate}">
                            <input type="file" name="images[]"  id="images" accept="image/*" @change="uploadImg" class="d-none" multiple>
                            <label v-if="imageAndPreviewImage.length < 10" for="images" class="v-btn v-btn--block v-theme--light text-blue v-btn--density-default v-btn--size-x-large v-btn--variant-outlined fw-bold my-3" role="button">
                                <i class="bi bi-camera-fill"></i>
                                {{ $t('addAdsAddAPhotoMax10') }}
                            </label>
                            <h4 v-else class="text-center my-3">{{ $t('addAdsAddAPhoto') }}</h4>
                        </validation-provider>

                        <!-- Draggable - Отобразим превью фото перед загрузкой на сервер -->
                        <div id="draggable">
                            <draggable
                                class="d-flex flex-wrap justify-center gap-2"
                                :list="imageAndPreviewImage"
                                @end="draggableEnd"
                                item-key="index"
                                style="max-width: 100%"
                            >
                                <template #item="{ element, index }">
                                    <div class="position-relative">
                                        <v-btn
                                            size="x-small"
                                            icon
                                            @click="deleteImage(index)"
                                            style="position: absolute; top: 5px; right: 5px"
                                        >
                                            <v-icon>mdi-delete</v-icon>
                                        </v-btn>

                                        <img
                                            :src="element.previewImg"
                                            @click="$router.push({ name: $route.name + 'Image' }), imageStore.showImages({ images: imageAndPreviewImage, index, addAdsImg: true })"
                                            width="100"
                                            height="100"
                                            role="button"
                                        />
                                    </div>
                                </template>
                            </draggable>
                        </div>

                    </div>

                    <!-- Адрес объекта - Улица, номер дома -->
                    <div v-if="$route.params.step == 6" class="form-group addAds__form-block">

                        <h2 class="text-center py-2 fw-bold">{{ $t('addAdsAddressOfTheObject') }}</h2>

                        <!-- Выбор локации - Область, город, район -->
                        <div class="form__title">{{ $t('addAdsChooseACity') }}</div>
                        <div class="d-flex pb-3">

                            <!-- Вывод выбранной локации - При клике открыть окно с локациями - получим области по умолчанию  -->
                            <v-btn @click="$router.push({ name: $route.name + 'Location',  params: {oblast: 'null', gorod: 'null', locationStep:1 } })"
                                   class="flex-fill text-body-2"
                                   :class="{'rounded-e-0' : KZLocationStore.location != ''}"
                                   color="grey-lighten-3" size="x-large" rounded="0"
                            >

                                <!-- Вывод выбранной локации -->
                                <div v-if="KZLocationStore.location.oblast != null">
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
                                <div v-else> {{ $t('addAdsAllKazakhstan') }} </div>

                                <v-icon end>mdi-arrow-right</v-icon>

                            </v-btn>
                            <!-- Кнопка сбросить локацию -->
                            <v-btn v-if="KZLocationStore.location != ''"
                                   color="red" size="x-large" :rounded="0"
                                   @click="KZLocationStore.removeLocation()">
                                <v-icon>mdi-close</v-icon>
                            </v-btn>

                        </div>

                        <!-- Проверка Области и города-->
                        <validation-provider rules="required" v-model="form.oblast" name="oblast" v-slot="{ errors }">
                            <!-- Вывожу ошибки - veevalidate, vform -->
                            <div class="text__error">
                                <span >{{ errors[0] }}</span>
                                <has-error :form="form" field="oblast"></has-error>
                            </div>
                        </validation-provider>
                        <validation-provider rules="required" v-model="form.gorod" name="gorod" v-slot="{ errors }">
                            <!-- Вывожу ошибки - veevalidate, vform -->
                            <div class="text__error">
                                <span >{{ errors[0] }}</span>
                                <has-error :form="form" field="gorod"></has-error>
                            </div>
                        </validation-provider>

                        <!-- Поле - Улица номер дома -->
                        <div class="form__input row g-0 mt-4">

                            <!-- Улица -->
                            <div class="col">
                                <validation-provider rules="required|min:1|max:50" v-model="form.ulica" name="ulica" v-slot="{ errors }">

                                    <!-- Input -->
                                    <v-text-field
                                        v-model="form.ulica" type="text"
                                        name="ulica" :label="$t('addAdsStreetName')"
                                        variant="outlined" color="blue"
                                        :error-messages="form.errors.has('ulica') ? form.errors.get('ulica'):'' || errors[0]"
                                        @input="form.errors.clear('ulica')"
                                    ></v-text-field>

                                </validation-provider>
                            </div>

                            <!-- Номер дома -->
                            <div class="col-auto mx-2" style="width: 100%; max-width: 80px">
                                <validation-provider rules="required|min:1|max:10" v-model="form.nomer_doma" name="nomer_doma" v-slot="{ errors }">

                                    <!-- Input -->
                                    <v-text-field
                                        v-model="form.nomer_doma" type="text"
                                        name="nomer_doma" :label="$t('addAdsHouseText')"
                                        variant="outlined" color="blue"
                                        :error-messages="form.errors.has('nomer_doma') ? form.errors.get('nomer_doma'):'' || errors[0]"
                                        @input="form.errors.clear('nomer_doma')"
                                    ></v-text-field>

                                </validation-provider>
                            </div>

                        </div>

                    </div>

                    <!-- Yandex - Карта -->
                    <div v-if="$route.params.step == 7" class="form-group addAds__form-block p-0">

                        <!-- Карта -->
                        <YandexMap v-if="showMap"
                                   :settings="settings"
                                   :coordinates="[form.lat, form.lon]"
                                   :controls="[]"
                                   :detailed-controls="detailedControls"
                                   :zoom="17"
                                   @click="getCoordinate"
                        >
                            <YandexMarker :coordinates="[form.lat, form.lon]" :marker-id="123"></YandexMarker>
                        </YandexMap>

                    </div>

                    <!-- Контактные данные -->
                    <div v-if="$route.params.step == 8" class="addAds__form-block">

                        <!-- Контактные данные -->
                        <h2 class="text-center py-2 pb-3 fw-bold">
                            {{ $t('addAdsContactDetails') }}
                        </h2>

                        <!-- Имя -->
                        <div class="form-group">

                            <!-- Поле -->
                            <validation-provider rules="required|min:1|max:30" v-model="form.name" name="name"  v-slot="{ errors }">

                                <!-- Input -->
                                <v-text-field
                                    v-model="form.name"
                                    name="name" type="text" :label="$t('addAdsName')"
                                    maxlength='30'
                                    variant="outlined" color="blue"
                                    :error-messages="form.errors.has('name') ? form.errors.get('name'):'' || errors[0]"
                                    @input="form.errors.clear('name')"
                                ></v-text-field>

                            </validation-provider>

                        </div>

                        <!-- Телефон  1-->
                        <div class="form-group">

                            <!-- Поле -->
                            <validation-provider rules="required" v-model="form.tel" name="tel"  v-slot="{ errors }">


                            <vue-tel-input v-model="form.tel" style="height: 56px" ref="tel"
                                           :inputOptions="{ placeholder: $t('addAdsYourPhoneNumber'), showDialCode: true}"
                                           :validCharactersOnly="true"
                                           inputmode="numeric"
                                           :error-messages="form.errors.has('tel') ? form.errors.get('tel'):'' || errors[0]"
                                           @input="form.errors.clear('tel')"
                            ></vue-tel-input>

                            </validation-provider>

                            <div class="whatsapp-checkbox">
                                <input type="checkbox" v-model="form.whatsapp_tel" :disabled="!form.tel" :true-value="1" :false-value="0" id="whatsapp1">
                                <label for="whatsapp1">WhatsApp</label>
                            </div>

                        </div>

                        <!-- Телефон 2 -->
                        <div class="form-group">

                            <vue-tel-input v-model="form.tel_2"  style="height: 56px"
                                           :inputOptions="{ placeholder: $t('addAdsYourPhoneNumber'), showDialCode: true}"
                                           :validCharactersOnly="true"
                            ></vue-tel-input>

                            <div class="whatsapp-checkbox">
                                <input type="checkbox" v-model="form.whatsapp_tel_2" :disabled="!form.tel_2" :true-value="1" :false-value="0" id="whatsapp2">
                                <label for="whatsapp2">WhatsApp</label>
                            </div>

                        </div>

                    </div>

                    <!-- КНОПКИ - ПОДАТЬ ОБЪЯВЛЕНИЕ или ДАЛЕЕ -->
                    <div class="p-2 addAds__form-button">

                        <!-- Вывод ошибки - Если форма не прошла валидацию-->
                        <span v-if="form_valid != ''" class="text-red">
                            <v-icon icon="mdi-information"></v-icon>
                            {{ $t('addAdsError') }} {{ form_valid }}
                        </span>

                        <!-- Кнопка далее - для перехода к следующему разделу формы -->
                        <v-btn v-if="$route.params.step < 7" @click="formValidate(false)"
                               size="x-large"
                               style="background: rgb(16, 163, 127);"
                               block
                               class="text-body-1 text-white"
                        >
                            {{ $t('addAdsFurther') }}
                        </v-btn>

                        <!-- Кнопка Все верно - для перехода далее на яндекс карте -->
                        <div v-if="$route.params.step == 7">
                            <div class="form__title">{{ $t('addAdsCheckTheLocation') }}</div>

                            <!-- Адрес объекта -->
                            <div class="m-2 d-flex">
                                <i class="bi bi-geo-alt text-grey"></i>
                                <div class="ml-3">
                                    <span>{{ form.ulica }}, {{form.nomer_doma}}</span>
                                    <div class="text-grey">{{KZLocationStore.gorod}}</div>
                                </div>
                            </div>

                            <v-btn @click="formValidate(false)"
                                   size="x-large"
                                   style="background: rgb(16, 163, 127);"
                                   block
                                   class="mb-3 text-body-1 text-white"
                            >
                                <span>{{ $t('addAdsThatsRight') }}</span>
                            </v-btn>
                        </div>

                        <!-- Кнопка отправить форму -->
                        <div v-if="$route.params.step == 8">

                            <!-- Пользовательское соглашение текст -->
                            <div class="text-caption text-grey text-center px-1">
                                {{ $t('addAdsRulesText1') }}
                                <router-link tag="a" to="/polzovatelskoeSoglashenie" target="_blank" class="text-blue">
                                    {{ $t('addAdsRulesText2') }}
                                </router-link>
                                <router-link tag="a" to="/rules" target="_blank" class="text-blue">
                                    {{ $t('addAdsRulesText3') }}
                                </router-link>
                            </div>

                            <!-- Кнопка отправить форму -->
                            <div class="form-group text-center">
                                <v-btn @click="formValidate(true)"
                                       size="x-large"
                                       dark block
                                       style="background: rgb(16, 163, 127);"
                                       :disabled="query"
                                       id="submit_form_btn"
                                       class="text-body-1 text-white"
                                >
                                    <v-progress-circular v-if="query" size="x-small" indeterminate color="white"></v-progress-circular>
                                    <span v-if="form.addOrUpdate == 'add'"> {{ $t('addAdsPlaceAnAd') }} </span>
                                    <span v-else> {{ $t('addAdsSaveChanges') }} </span>
                                </v-btn>
                            </div>

                        </div>

                    </div>

                </form>

            </validation-observer>

        </div>

    </div>

    <!-- Вывожу дочерние компоненты - Локация, Фото -->
    <router-view></router-view>

</template>


<script>
//Импортирую Store - Наше общее состояние
import {useAuthStore} from "../../../stores/auth";
import {useImagesStore} from "../../../stores/images";
import {useKZLocationStore} from "../../../stores/KZLocation";
import { useLangStore } from "../../../stores/lang";

// VueDraggable Пакет для перемещения картинок в массиве (по экрану)
import draggable from 'vuedraggable'

//Импортирую Компонент - Валидация laravel VFORM
import Form from 'vform'
import { HasError } from 'vform/src/components/bootstrap5'

//Импортирую - Дерективы для формата цены
import { directive } from '@coders-tm/vue-number-format'

// Компонент для номера телефона
import { VueTelInput } from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';

// Компонент яндекс карта
import { YandexMap, YandexMarker, loadYmap  } from 'vue-yandex-maps'

export default {
    name: "addAdsForm",

    directives: {
        number: directive, //Для преобразования цены в поле с 9999 на  9 999
    },

    components: {
        HasError, // VForm - Валидация
        YandexMap, YandexMarker,// Карта яндекс
        draggable, // Для перемещения фото
        VueTelInput // Для номера телефона
    },

    data() {
        return {

            //Подключаю Store - Наше общее состояние
            authStore: useAuthStore(),
            imageStore: useImagesStore(),
            KZLocationStore: useKZLocationStore(),
            langStore: useLangStore(),

            query: false,

            showForm: true, //Показать форму или Кнопики, продолжить подать новое объявление

            //поля формы
            form: new Form({
                addOrUpdate: 'add', //Добавить новое или редактировать старое

                id: '', //id объявления
                author_id: '',

                table_name: '',
                zagolovok: '',
                tip_sdelki: 'sdam',
                tip_obekta: '',
                period_arendi: 'na_dlitelno',
                kolichestvo_komnat: 1,

                deistvuushii_biznes: 'da',
                sfera_deyatelnosti: 'proizvodstvo',
                cena: '',
                cena_tip: 'za_vse',
                mestopolojenie: '', //магазин
                tip_stroeniya: 'panel',
                god_postroiki: '',
                tip_ofisa: '',
                visota_potolkov: 2.5,
                etag: 1,
                etagnost: 5,
                ploshad_obshaya: 32,
                ploshad_kuhni: 6,
                ploshad_uchastka: '',

                //Местоположение
                oblast: '',
                gorod: '',
                raion: '',
                lat: '',
                lon:'',
                ulica: '',

                nomer_doma: '',

                //Фото загружаем новые - и имена старых фото если они есть
                images: [],
                old_images: [],

                //Характеристики
                sostoyanie: '',
                dush: '',
                sanuzel: '',
                otdelniy_vhod: '',
                kanalizaciya: '',
                voda: '',
                elektrichestvo: '',
                otoplenie: '',
                gaz: '',
                balkon: '',
                mebel: '',

                //Промбаза
                ploshad_proizvodstvennih_pomesheniy: '',
                visota_proizvodstvennih_pomesheniy: '',
                ploshad_skladskih_pomesheniy: '',
                visota_skladskih_pomesheniy: '',
                ploshad_ofisnih_pomesheniy: '',

                //Массивы
                mebel_arr: [],
                raznoe: [],
                raspolojenie: [],
                kommunikacii: [],
                bezopasnost: [],


                text_obyavleniya: '',

                //Контактные данные автора
                name: '',
                tel: '',
                whatsapp_tel: 0,
                tel_2: '',
                whatsapp_tel_2: 0,

            }),
            imageAndPreviewImage: [],
            form_valid: '', // Для вывода ошибки сверху кнопки

            //Следим за изменением адреса - чтоб лишний раз не запрашивать координаты
            //При переходе на карту запишется первый адрес - при возврате запишется 2-й, и если они не совпадают то запросим новые координаты
            address: {
                count: 0,
                adr1: {
                    oblast: '',
                    gorod: '',
                    raion: '',
                    ulica: '',
                    nomer_doma: ''
                },
                adr2: {
                    oblast: '',
                    gorod: '',
                    raion: '',
                    ulica: '',
                    nomer_doma: ''
                },
            },

            //Компонент - Для форматирования вводимых данных в поле цена
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

            // Настройки компонента  для Яндекс карты по умолчанию
            showMap: false,
            settings :{
                apiKey: '8740b571-75d9-47f0-a5c4-582b1feaf201',
                lang: 'ru_RU',
                coordorder: 'latlong',
                enterprise: false,
                version: '2.1'
            },
            //Экземпляр карты для доступа к геокодеру
            ymaps: '',
            //Поведение при увеличении и уменьшении карты
            detailedControls :{ zoomControl: { position: { right: 0, top: 0 } } },
        }
    },

    watch:{

        // Получим ГеоКоординаты расположение // Перейдем на покупку рекламы
        async '$route' (to, from) {

            // Прокрутим до верху блок формы
            document.getElementById('addAds__body').scrollTop = 0;


            //Получим ГеоКоординаты расположение
            if(from.params.step == 6 && to.params.step == 7){

                if (this.address.count == 0){
                    this.address.adr1 = {
                        oblast: this.form.oblast,
                        gorod: this.form.gorod,
                        raion: this.form.raion,
                        ulica: this.form.ulica,
                        nomer_doma: this.form.nomer_doma
                    }

                    //Изменим null на '' так как при редактирование заносился null и проверка была не точна
                    if(this.address.adr2.raion == null){
                        this.address.adr2.raion = '';
                    }
                }
                if (this.address.count == 1){
                    this.address.adr2 = {
                        oblast: this.form.oblast,
                        gorod: this.form.gorod,
                        raion: this.form.raion,
                        ulica: this.form.ulica,
                        nomer_doma: this.form.nomer_doma
                    }

                }
                this.address.count == 0 ? this.address.count = 1:  this.address.count = 0;


                //Преобразуем объект в строку json и сравним отличаются ли 2 адреса друг от друга или нет
                if(JSON.stringify(this.address.adr1) !== JSON.stringify(this.address.adr2)){

                    this.ymaps.geocode('Казахстан,' + this.form.oblast +','+this.form.gorod+','+this.form.ulica+','+this.form.nomer_doma).then((res)=>{
                        //Запишем в форму координаты
                        this.form.lat = res.geoObjects.get(0).geometry._coordinates[0];
                        this.form.lon = res.geoObjects.get(0).geometry._coordinates[1];
                        this.showMap = true;
                    })
                }else{
                    this.showMap = true;
                }
            }

        },

        // Отслеживаем изменение выбранной локации
        async 'KZLocationStore.location'(){
            this.form.oblast = this.KZLocationStore.location.oblast,
                this.form.gorod = this.KZLocationStore.location.gorod,
                this.form.raion = this.KZLocationStore.location.raion,
                this.form.lat = this.KZLocationStore.location.lat,
                this.form.lon = this.KZLocationStore.location.lon
        }
    },

    computed:{

        // Поле цена
        cena(){

            if(this.form.tip_sdelki == 'prodam'){
                if(this.langStore.lang == 'ru')return 'Цена'
                if(this.langStore.lang == 'kz')return 'Баға'
            }
            if(this.form.period_arendi == 'na_dlitelno' || this.form.period_arendi == 'na_poselenie'){
                if(this.langStore.lang == 'ru')return 'Цена / В месяц'
                if(this.langStore.lang == 'kz')return 'Баға / айына'
            }
            if(this.form.period_arendi == 'posutochno'){
                if(this.langStore.lang == 'ru')return 'Цена / В сутки'
                if(this.langStore.lang == 'kz')return 'Баға / тәулігіне'
            }
            if(this.form.period_arendi == 'po_chasam'){
                if(this.langStore.lang == 'ru')return 'Цена / В час'
                if(this.langStore.lang == 'kz')return 'Баға / сағатына'
            }

            // Значение по умолчанию, если ни одно условие не сработало
            return this.langStore.lang == 'kz' ? 'Баға' : 'Цена';

        },

    },

    methods: {

        // Открыть Окно с Формой
        async openModal(addLocaleStorageAds = false){

            //Если добавляем новое объявление
            if(this.$route.params.id == 'null') {
                this.form.author_id = this.authStore.user.id;
                this.form.table_name = this.$route.params.table_name;
                this.form.name = this.authStore.user.name;
                this.form.tel = this.authStore.user.tel;
                this.form.tel_2 = this.authStore.user.tel_2;

                // Показать форму
                this.showForm = true;

                // Если выбрано добавить объявление из localeStorage - если нет то просто откроется форма для добавления нового
                if (addLocaleStorageAds) {
                    let newAdsData = JSON.parse(localStorage.getItem("newAdsData=" + this.$route.params.table_name))
                    this.form.fill(newAdsData);

                    this.form.author_id = this.authStore.user.id;
                    this.form.name = this.authStore.user.name;
                    this.form.tel = this.authStore.user.tel;
                    this.form.tel_2 = this.authStore.user.tel_2;

                    //Занесем локацию
                    this.KZLocationStore.addLocation({
                        oblast: this.form.oblast,
                        gorod: this.form.gorod,
                        raion: this.form.raion,
                        lat: this.form.lat,
                        lon: this.form.lon,
                    })

                    if(this.$route.params.step != 1){
                        this.$router.replace({name: this.$route.name, params: {id: 'null', step:1}});
                    }
                }
                else{
                    if(this.$route.params.step != 1){
                        this.$router.replace({name: this.$route.name, params: {id: 'null', step:1}});
                    }
                    // Cбросим локацию
                    this.KZLocationStore.removeLocation()
                }
            }

            // Если редактируем старое объявление
            if(this.$route.params.id != 'null'){

                axios.get('/getOneAds', {
                    params:{
                        ads_id: this.$route.params.id,
                        table_name: this.$route.params.table_name
                    }
                })
                    .then((response)=>{

                        //Заполним форму значениями
                        this.form.fill(response.data);

                        //Отправим локацию в компонент локации
                        this.KZLocationStore.addLocation({
                            oblast: this.form.oblast ,
                            gorod: this.form.gorod,
                            raion: this.form.raion,
                            lat: this.form.lat,
                            lon: this.form.lon,
                        })

                        //Добавим Фото
                        if(this.form.images.length > 0){
                            this.form.images.forEach(img=>{
                                let obj = {};
                                obj.previewImg = '/img/adsImg/' + img
                                this.imageAndPreviewImage.push(obj)
                            })
                        }

                        // Укажем что редактируем объявление
                        this.form.addOrUpdate = 'update';

                        //Занесем наш адрес чтоб в дальнейшем отследить, и если адрес изменится запросим новые координаты
                        this.address = {
                            count: 0,
                            adr1 : {
                                oblast: this.form.oblast,
                                gorod: this.form.gorod,
                                raion: this.form.raion,
                                ulica: this.form.ulica,
                                nomer_doma: this.form.nomer_doma
                            },
                            adr2 : {
                                oblast: this.form.oblast,
                                gorod: this.form.gorod,
                                raion: this.form.raion,
                                ulica: this.form.ulica,
                                nomer_doma: this.form.nomer_doma
                            }
                        };

                    })
                    .catch((errors)=>{
                        Swal.fire({text: errors.response.error,})
                    })
            }
        },

        //Метод Добавить или Редактировать объявление
        async addOrUpdateAds(){

            this.query = true;

            //Проверка рекаптчи если есть соединение идем далее
            this.$recaptchaLoaded()
                .then(()=>{

                    //Если связь есть, то получим токен нашей капчи
                    this.$recaptcha('login')
                        .then((token)=>{

                            //Добавим токен рекапчи в запрос
                            this.form.recaptcha_token = token;

                            //Укажем роль пользователя
                            this.form.role = this.authStore.user.role;

                            //Приведу данные цена в формат вместо 80 000 на 80000 например
                            this.form.cena = String(this.form.cena).replace(/\D/g, "");

                            //Добавим фото в массив из промежуточного массива
                            this.form.images = [];
                            this.form.old_images = [];
                            this.imageAndPreviewImage.forEach((elem)=>{

                                //Если это новое загружаемое фото
                                if(elem.image != undefined){
                                    this.form.images.push(elem.image);
                                    this.form.old_images.push(null)
                                }
                                else{
                                    //Это мы заносим имена старых фото
                                    this.form.old_images.push(elem.previewImg.substring(12))
                                }
                            })

                            //Отправим все на сервер, Я добавил пакет object Form для трансформации запроса чтоб можно было отправить фото как обычный запрос
                            this.form.post('/addOrUpdateAds', {
                                transformRequest: [function (data, headers) {
                                    return objectToFormData(data)
                                }]
                            })
                                .then(async (response) => {
                                    this.query = false;
                                    let ads = response.data.ads;
                                    let step = Number(this.$route.params.step)-1;

                                    if(step > 0){
                                        // Выполняем операцию перехода по истории маршрутов
                                        await this.$router.go(-step);

                                        // Ждем некоторое время перед заменой маршрута
                                        setTimeout(() => {
                                            // После завершения операции перехода по истории маршрутов выполняем замену маршрута
                                            this.$router.replace({ name: 'userAdsBueAds', params: { author_id: ads.author_id, ads_id: ads.id } });
                                        }, 200); // Увеличиваем задержку до 200 миллисекунд
                                    }else{
                                        this.$router.replace({ name: 'userAdsBueAds', params: { author_id: ads.author_id, ads_id: ads.id } });
                                    }

                                })
                                .catch((error)=>{ //Если на сервере ошибка
                                    this.query = false;

                                    Toast.fire({
                                        icon: 'error',
                                        title: this.$t('addAdsSendingErrorCheckTheForm'),
                                        text: error.response.data.error,
                                    })
                                })

                        })
                        .catch(()=>{ //Если токен капчи не получен
                            this.query = false;
                            Swal.fire({
                                icon: 'question',
                                title: this.$t('addAdsNotInternet'),
                            });
                        })
                })
                .catch(()=>{ //Если нет связи с капчей, то скорее всего интернет оборвался
                    this.query = false;
                    Swal.fire({
                        icon: 'question',
                        title: this.$t('addAdsNotInternet'),
                    });
                })
        },

        // Проверка валидности ФОРМЫ - для перехода на следующий шаг.
        async formValidate(save = false){
            this.$refs.form.validate().then( async response =>{
                if(response.valid){

                    this.form_valid = '';

                    //Переход на следующий шаг - Если не сохраняем объявление после редактирования
                    if(!save){
                        this.$router.push({name: this.$route.name, params: {step: Number(this.$route.params.step) +1}})
                    }
                    else{
                        //Tel 2
                        this.form.tel_2 != null && this.form.tel_2.length < 3 ? this.form.tel_2 = '' : '';
                        this.addOrUpdateAds();
                    }
                }else{

                    // Если не валидна - Добавим в нашу переменную смс об ошибке
                    this.form_valid = Object.values(response.errors)[0];
                    setTimeout(()=>{
                        this.form_valid = '';
                    },2000)
                }

            })
        },

        //Получить координаты при перемещении маркера по карте
        async getCoordinate(e) {
            this.form.lat = e.get('coords')[0];
            this.form.lon = e.get('coords')[1];
            this.coordinates = e.get('coords');
        },

        //Метод - Загружаем Фото - Максимум 10 - если более то кнопка загрузки будет d-none
        async uploadImg(img){

            //Поставим заглушку на загрузку только 10 файлов
            for(let image of img.target.files ){

                //Провожу валидацию каждого фото на клиенте veevalidate
                const {valid} = await this.$refs.provider.validate(image);

                //Если валидно идем далее иначе - Уведомление не фото
                if(valid){

                    let images = {};
                    images.image = image; //Занесу Сам файл фото, который отправиться на сервер
                    images.previewImg = URL.createObjectURL(image); // Ссылка для отображения preview

                    //Готовый объект фото и превью занесу в масив если там не более 10 фото иначе остановлю цикл
                    this.imageAndPreviewImage.length < 10 ? this.imageAndPreviewImage.push(images) : '';

                } else{
                    // Если фото не валидно сообщим об этом
                    Toast.fire({
                        icon: 'error',
                        title: this.$t('addAdsNotAPhoto')
                    });
                }
            }

        },

        // Метод Удалить одно фото с массива по index
        deleteImage(index){
            this.imageAndPreviewImage.splice( index , 1 );
        },

        // При прикращении перетаскивания фото в druggable сделаем вертуальный клик, так как на фото преходилось кликать 2 раз
        draggableEnd(event){
            document.getElementById('draggable').click();
        },

        // Проверить наличие элемента в массиве
        checkArr(field, data){
            if (this.form[field].includes(data)) {
                const index = this.form[field].indexOf(data);
                this.form[field].splice(index, 1);
            } else {
                // Добавить элемент, если его нет в массиве
                this.form[field].push(data);
            }
        }

    },

    async mounted(){

        let app = this;
        document.querySelector(':root').classList.add('PATCH_modal'); // Отменим прокрутку под компонентом

        // Если у пользователя более 10 объявлений
        if(this.$route.params.id == 'null' && this.authStore.user.count_ads >= 10 && this.authStore.user.role == 'user'){
            this.$router.back();
        }

        // Если у нас в хранилеще есть начатое объявление то покажем блок уведомления
        if(this.$route.params.id == 'null' && localStorage.getItem("newAdsData=" + this.$route.params.table_name) != undefined){
            this.showForm = false;
        }else{
            this.openModal();
        }

        //Инициализируем Яндекс карту - для доступа к геокодеру.
        await loadYmap(app.settings);
        app.ymaps = ymaps;

    },

    beforeRouteLeave(to, from, next) {

        //Добавим объявление в LocaleStorage
        if(this.$route.params.id == 'null'){
            localStorage.setItem('newAdsData=' + this.$route.params.table_name, JSON.stringify(this.form));
        }

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
.ymaps-2-1-79-map-copyrights-promo{
    display: none!important;
}

/* Стили для макрера - наше фото квартиры */
.ymaps-2-1-79-image{
    border-radius: 100%;
    box-shadow: 0 0 0 2px white, 0 0 0 4px #044dff;
    background-size: cover;
}

</style>

<style scoped>
.addAds__wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #ffffff;
}

.addAds__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
}

.addAds__header {
    width: 100%;
    height: auto;
}

.addAds__body {
    flex-grow: 1; /* Растянем этот блок на всю оставшуюся высоту экрана */
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.addAds__form{
    flex-grow: 1;
    width: 100%;
    max-width: 700px;
    margin: auto;
    display: flex;
    flex-direction: column;
}
.addAds__form-block{
    width: 100%;
    height: auto;
    flex-grow: 1;
}
.addAds__form-button{
    margin: 20px 0;
}

.form__title{
    margin-bottom: 5px;
    font-size: 1.1em;
    font-weight: 600;
    line-height: 1.5rem;
    color: #1a1c1e;
    font-family: 'Open-Sans', sans-serif;
}

.form__input{
    display: flex;
    flex-wrap: wrap;
}

.form__item{
    flex-grow: 1;
    width: 150px;
    background: #f2f3f5;
    cursor: pointer;
    padding: 13px 15px;
    margin: 2px 5px;
    border-radius: 5px;
    color: rgb(51, 51, 51);
    font-size: 16px;
    white-space: nowrap;
    overflow: hidden;
}

.item__active{
    background: #f0f7f0
}

.whatsapp-checkbox {
    display: flex;
    align-items: center;
    gap: 0.5rem; /* отступ между чекбоксом и текстом */
    margin-top: 1rem;
}

.whatsapp-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}


/*Добавим стили для полей vuetify - textarea и для label*/
::v-deep(.v-textarea--auto-grow .v-field__input) {
    overflow-y: auto!important;
}
/*Сделать текст label жирным */
::v-deep(.v-label.v-field-label) {
    font-size: 0.8em;
}

</style>
<!--2300-->
