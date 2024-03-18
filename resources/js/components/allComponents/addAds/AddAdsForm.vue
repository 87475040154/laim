<template>
    <!-- КОМПОНЕНТ - ДОБАВЛЕНИЕ ОБЪЯВЛЕНИЙ -->

    <!-- Обвертка - Компонента-->
    <div class="addAds__wrapper">

        <!-- Сам блок -->
        <div class="addAds__block">

            <!-- Header  - Заголовок -->
            <div class="addAds__header">

                <!-- Кнопка Назад / Заголовок старницы - что сдать / Закрыть форму или сохранить -->
                <div class="row g-0 align-center text-white" style="background: var(--app-header-bg-color);">

                    <!-- Кнопка Назад -->
                    <div class="col-auto">
                        <!-- Кнопка назад -->
                        <v-btn icon dark variant="text" class="mx-2" @click="$router.back()">
                            <v-icon size="large">mdi-arrow-left</v-icon>
                        </v-btn>
                    </div>

                    <!-- Заголовок старницы - что сдать  -->
                    <div class="col text-body-1">

                            <span v-if="updateDateLocaleStore.lang != 'kz'">
                                <span v-if="$route.params.table_name != 'Snimu'">{{ form.addOrUpdate == 'add' ? $t('addAdsPass') : $t('addAdsEdit') }}</span>
                                <span v-else>{{ $t('addAdsRent') }}</span>
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

                        <span v-if="updateDateLocaleStore.lang == 'kz'">
                                <span v-if="$route.params.table_name != 'Snimu'">{{ form.addOrUpdate == 'add' ? $t('addAdsPass') : $t('addAdsEdit') }}</span>
                                <span v-else>{{ $t('addAdsRent') }}</span>
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
                        <v-btn @click="openModal(false)" variant="flat" size="x-large" class="text-white text-body-1" style="background: #10a37f; width: 160px">
                            {{ $t('addAdsSubmitANewOne') }}
                        </v-btn>

                        <!-- Продолжить заполнять старое -->
                        <v-btn @click="openModal(true)" variant="flat" size="x-large" color="blue-darken-2" class="text-body-1" style="width:160px">
                            {{ $t('addAdsContinue') }}
                        </v-btn>

                    </div>

                </div>

                <!-- Форма подачи объявления -->
                <form v-if="showForm" @submit="handleSubmit($event, addOrUpdateAds)" class="form addAds__form">

                    <!-- Верхний блок основная информации  -->
                    <div v-if="$route.params.step == 1" class="addAds__form-block">


                        <!-- Заголовок -->
                        <div class="form-group mt-3">

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

                        <!-- Для Бизнеса - Тип сделки - Сдам - продам  -->
                        <div v-if="$route.params.table_name == 'Business'" class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsDealType') }}</div>

                            <!-- Поле с кнопками -->
                            <validation-provider rules="required" v-model="form.tip_sdelki" name="tip_sdelki" v-slot="{ errors }">

                                <div class="form__input">
                                    <div @click="form.tip_sdelki = 'Сдам'" class="form__item flex-fill" :class="{'item__active': form.tip_sdelki == 'Сдам'}" style="width: 150px"> {{ $t('addAdsSdam') }} </div>
                                    <div @click="form.tip_sdelki = 'Продам'" class="form__item flex-fill" :class="{'item__active': form.tip_sdelki == 'Продам'}" style="width: 150px"> {{ $t('addAdsSell') }} </div>
                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="tip_sdelki"></has-error>
                                </div>

                            </validation-provider>

                        </div>


                        <!-- Сфера деятельности - Прочая недвижимость -->
                        <div v-if="$route.params.table_name == 'Business' || $route.params.table_name == 'Prochaya'" class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsFieldOfActivity') }}</div>

                            <!-- Поле -->
                            <validation-provider rules="required" v-model="form.sfera_deyatelnosti" name="sfera_deyatelnosti" v-slot="{ errors, field }">

                                <!-- Сфера деятельности 2 клонки -->
                                <div class="form__input">

                                    <span @click="form.sfera_deyatelnosti = 'Производство'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'Производство'}">{{ $t('addAdsProduction') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'Общепит'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'Общепит'}">{{ $t('addAdsPublicCatering') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'Развлечения'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'Развлечения'}">{{ $t('addAdsEntertainment') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'Торговля'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'Торговля'}">{{ $t('addAdsTrade') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'Услуги'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'Услуги'}">{{ $t('addAdsServices') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'Сельское хозяйство'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'Сельское хозяйство'}">{{ $t('addAdsAgriculture') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'Другое'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'Другое'}">{{ $t('addAdsOtherText') }}</span>

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
                                        <option value="В бизнес-центре">{{ $t('addAdsInTheBusinessCenter') }}</option>
                                        <option value="В административном здании">{{ $t('addAdsInTheAdministrativeBuilding') }}</option>
                                        <option value="В жилом доме">{{ $t('addAdsInAResidentialBuilding') }}</option>
                                        <option value="В коттедже">{{ $t('addAdsInTheCottage') }}</option>
                                        <option value="Другое">{{ $t('addAdsOtherText') }}</option>
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
                        <div v-if="$route.params.table_name == 'Dom' || $route.params.table_name == 'Magazin' || $route.params.table_name == 'Prombaza' || $route.params.table_name == 'Snimu' " class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsObjectType') }}</div>

                            <!-- Input -->
                            <validation-provider rules="required" v-model="form.tip_obekta" name="tip_obekta" v-slot="{ errors}">

                                <div class="form__input">

                                    <!-- Дом  -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta = 'Дом'" class="form__item" :class="{'item__active' : form.tip_obekta == 'Дом'}">{{ $t('addAdsHouseText') }}</span>
                                    <!-- Дача  -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta = 'Дача'" class="form__item" :class="{'item__active' : form.tip_obekta == 'Дача'}">{{ $t('addAdsCountryHouse') }}</span>
                                    <!-- Коттедж  -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta = 'Коттедж'" class="form__item" :class="{'item__active' : form.tip_obekta == 'Коттедж'}">{{ $t('addAdsCottage') }}</span>
                                    <!-- Времянка  -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta = 'Времянка'" class="form__item" :class="{'item__active' : form.tip_obekta == 'Времянка'}">{{ $t('addAdsVremyanka') }}</span>


                                    <!-- Магазин  -->
                                    <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta = 'Магазин'" class="form__item" :class="{'item__active':form.tip_obekta == 'Магазин'}">{{ $t('addAdsShopText') }}</span>
                                    <!-- Бутик  -->
                                    <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta = 'Бутик'" class="form__item" :class="{'item__active':form.tip_obekta == 'Бутик'}">{{ $t('addAdsBoutique') }}</span>
                                    <!-- Киоск  -->
                                    <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta = 'Киоск'" class="form__item" :class="{'item__active':form.tip_obekta == 'Киоск'}">{{ $t('addAdsKiosk') }}</span>
                                    <!-- Контейнер -->
                                    <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta = 'Контейнер'" class="form__item" :class="{'item__active':form.tip_obekta == 'Контейнер'}">{{ $t('addAdsContainer') }}</span>


                                    <!-- Промбаза  -->
                                    <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta = 'Промбаза'" class="form__item" :class="{'item__active':form.tip_obekta == 'Промбаза'}">{{ $t('addAdsIndustrialBase') }}</span>
                                    <!-- Завод  -->
                                    <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta = 'Завод'" class="form__item" :class="{'item__active':form.tip_obekta == 'Завод'}">{{ $t('addAdsFactory') }}</span>
                                    <!-- Склад бытовой  -->
                                    <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta = 'Склад бытовой'" class="form__item" :class="{'item__active':form.tip_obekta == 'Склад бытовой'}">{{ $t('addAdsHouseholdWarehouse') }}</span>
                                    <!-- Склад продовольственный  -->
                                    <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta = 'Склад продовольственный'" class="form__item" :class="{'item__active':form.tip_obekta == 'Склад продовольственный'}">{{ $t('addAdsFoodWarehouse') }}</span>
                                    <!-- Склад химпродукции  -->
                                    <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta = 'Склад химпродукции'" class="form__item" :class="{'item__active':form.tip_obekta == 'Склад химпродукции'}">{{ $t('addAdsWarehouseForChemicalProducts') }}</span>


                                    <!-- Сниму -->
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Квартира'" class="form__item" :class="{'item__active':form.tip_obekta == 'Квартира'}">{{ $t('addAdsApartmentText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Общежитие'" class="form__item" :class="{'item__active':form.tip_obekta == 'Общежитие'}">{{ $t('addAdsHostelText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Дом'" class="form__item" :class="{'item__active':form.tip_obekta == 'Дом'}">{{ $t('addAdsHouseText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Офис'" class="form__item" :class="{'item__active':form.tip_obekta == 'Офис'}">{{ $t('addAdsOfficeText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Здание'" class="form__item" :class="{'item__active':form.tip_obekta == 'Здание'}">{{ $t('addAdsBuildingText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Магазин'" class="form__item" :class="{'item__active':form.tip_obekta == 'Магазин'}">{{ $t('addAdsShopText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Промбаза'" class="form__item" :class="{'item__active':form.tip_obekta == 'Промбаза'}">{{ $t('addAdsBaseText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Прочая'" class="form__item" :class="{'item__active':form.tip_obekta == 'Прочая'}">{{ $t('addAdsOtherText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Бизнес'" class="form__item" :class="{'item__active':form.tip_obekta == 'Бизнес'}">{{ $t('addAdsBusinessText2') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="tip_obekta"></has-error>
                                </div>

                            </validation-provider>


                        </div>

                        <!-- Период аренды  -->
                        <div v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Snimu'" class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsRentalPeriod') }}</div>

                            <!-- Поле с кнопками -->
                            <validation-provider rules="required" v-model="form.period_arendi" name="period_arendi" v-slot="{ errors }">

                                <div class="form__input">
                                    <div @click="form.period_arendi = 'На длительно'" class="form__item flex-fill" :class="{'item__active': form.period_arendi == 'На длительно'}"> {{ $t('addAdsForALongTime') }} </div>
                                    <div @click="form.period_arendi = 'Посуточно'" class="form__item flex-fill" :class="{'item__active': form.period_arendi == 'Посуточно'}"> {{ $t('addAdsDaily') }} </div>
                                    <div @click="form.period_arendi = 'По часам'" class="form__item flex-fill" :class="{'item__active': form.period_arendi == 'По часам'}"> {{ $t('addAdsByTheHour') }} </div>
                                    <div @click="form.period_arendi = 'На подселение'" class="form__item flex-fill" :class="{'item__active': form.period_arendi == 'На подселение'}"> {{ $t('addAdsForSharing') }} </div>
                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="period_arendi"></has-error>
                                </div>

                            </validation-provider>

                        </div>

                        <!-- Количество комнат  -->
                        <div v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Ofis' || $route.params.table_name == 'Snimu'" class="form-group my-3">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $route.params.table_name == 'Ofis' ? $t('addAdsNumberOfCabinets') : $t('addAdsNumberOfRooms') }}</div>

                            <div class="row g-0">

                                <!-- Кнопки - 1,2,3-->
                                <div class="col-auto d-flex align-items-center gap-1 px-1 text-blue" style="height: 55px">
                                    <div v-for="n in 8" role="button" @click="form.kolichestvo_komnat = n" class="d-flex rounded-2 align-center justify-center" style="font-size: 1.1em; font-weight: bold; width: 50px; height: 50px; background: #ffffff; border: 1px solid #cecece" :class="{'bg-blue':this.form.kolichestvo_komnat == n}">
                                        {{n}}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Действующий бизнес - Да , нет -->
                        <div v-if="$route.params.table_name == 'Business' || $route.params.table_name == 'Prochaya' || $route.params.table_name == 'Magazin'" class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">
                                {{ $t('addAdsOperatingBusiness') }}
                            </div>

                            <!-- Поле -->
                            <validation-provider rules="required" v-model="form.deistvuushii_bisnes" name="deistvuushii_bisnes" v-slot="{ errors }">

                                <div class="form__input">
                                    <span @click="form.deistvuushii_bisnes = 'Да'" class="form__item" :class="{'item__active':form.deistvuushii_bisnes == 'Да'}">{{ $t('addAdsYes') }}</span>
                                    <span @click="form.deistvuushii_bisnes = 'Нет'" class="form__item" :class="{'item__active':form.deistvuushii_bisnes == 'Нет'}">{{ $t('addAdsNo') }}</span>
                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="deistvuushii_bisnes"></has-error>
                                </div>

                            </validation-provider>

                        </div>

                        <!-- Магазин - Местоположение -->
                        <div v-if="$route.params.table_name == 'Business' || $route.params.table_name == 'Magazin'" class="form-group py-3">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsLocation') }}</div>

                            <!-- Поле -->
                            <div class="form__input">

                                <validation-provider rules="required" v-model="form.mestopolojenie" name="mestopolojenie" v-slot="{ errors }">
                                    <select v-model="form.mestopolojenie" name="mestopolojenie" class="form-select">
                                        <option value="В торговом-центре">{{ $t('addAdsInTheShoppingCenter') }}</option>
                                        <option value="В административном здании">{{ $t('addAdsInTheAdministrativeBuilding') }}</option>
                                        <option value="На универсальном рынке (Барахолке)">{{ $t('addAdsAtTheUniversalMarketFleaMarket') }}</option>
                                        <option value="В жилом доме">{{ $t('addAdsInAResidentialBuilding') }}</option>
                                        <option value="Отдельно стоящее здание">{{ $t('addAdsDetachedBuilding') }}</option>
                                        <option value="Остановочный комплекс">{{ $t('addAdsBusStopComplex') }}</option>
                                        <option value="Другое">{{ $t('addAdsOtherText') }}</option>
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
                        <div class="form-group row g-0 gap-3" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Ofis' || $route.params.table_name == 'Zdanie'|| $route.params.table_name == 'Magazin'">

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
                        <div class="form-group" v-if="$route.params.table_name == 'Ofis' || $route.params.table_name == 'Zdanie'">

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
                                <validation-provider v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom'" rules="integer|max_value:999" v-model="form.ploshad_kuhni" name="ploshad_kuhni" v-slot="{ errors }">

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
                        <div class="form-group" v-if="$route.params.table_name == 'Dom' || $route.params.table_name == 'Zdanie'|| $route.params.table_name == 'Prombaza' || $route.params.table_name == 'Prochaya'">

                            <!-- Поле -->
                            <validation-provider rules="required|integer|min_value:1|max_value:99999" v-model="form.ploshad_uchastka" name="ploshad_uchastka" v-slot="{ errors }">

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
                        <div class="form-group" v-if="$route.params.table_name != 'Snimu'">

                            <!-- Заголовок -->
                            <div class="form__title">
                                {{ $t('addAdsTypeOfBuilding') }}
                            </div>

                            <!-- Поле -->
                            <validation-provider rules="required" v-model="form.tip_stroeniya" name="tip_stroeniya" v-slot="{ errors, valid, invalid, touched}">

                                <div class="form__input">
                                    <span @click="form.tip_stroeniya = 'Панель'" class="form__item"  :class="{ 'item__active' : form.tip_stroeniya == 'Панель' }"> {{ $t('addAdsPanel') }} </span>
                                    <span @click="form.tip_stroeniya = 'Кирпич'" class="form__item"  :class="{ 'item__active' : form.tip_stroeniya == 'Кирпич' }"> {{ $t('addAdsBrick') }} </span>
                                    <span @click="form.tip_stroeniya = 'Дерево'" class="form__item"  :class="{ 'item__active' : form.tip_stroeniya == 'Дерево' }"> {{ $t('addAdsTree') }} </span>
                                    <span @click="form.tip_stroeniya = 'Другое'" class="form__item"  :class="{ 'item__active' : form.tip_stroeniya == 'Другое' }"> {{ $t('addAdsOtherText') }} </span>
                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="tip_stroeniya"></has-error>
                                </div>

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
                                    <span @click="form.sostoyanie = 'Евро ремонт'" class="form__item" :class="{'item__active':form.sostoyanie == 'Евро ремонт'}">{{ $t('addAdsEuroRepair') }}</span>

                                    <!-- Хорошее -->
                                    <span @click="form.sostoyanie = 'Хорошее'" class="form__item" :class="{'item__active':form.sostoyanie == 'Хорошее'}">{{ $t('addAdsGood') }}</span>

                                    <!-- Среднее -->
                                    <span @click="form.sostoyanie = 'Среднее'" class="form__item" :class="{'item__active':form.sostoyanie == 'Среднее'}">{{ $t('addAdsAverage') }}</span>

                                    <!-- Требует ремонта -->
                                    <span @click="form.sostoyanie = 'Требует ремонта'" class="form__item" :class="{'item__active':form.sostoyanie == 'Требует ремонта'}">{{ $t('addAdsRequiresRepair') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="sostoyanie"></has-error>
                                </div>

                            </validation-provider>
                        </div>

                        <!-- Мебель - Полностью, Частично, Нет -->
                        <div class="form-group" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Ofis'|| $route.params.table_name == 'Magazin'|| $route.params.table_name == 'Snimu'">

                            <div class="form__title">
                                {{ $t('addAdsFurniture') }}
                            </div>


                            <validation-provider rules="required" v-model="form.mebel" name="mebel" v-slot="{ errors, valid, invalid, touched}">

                                <div class="form__input">

                                    <!-- Полностью-->
                                    <span @click="form.mebel = 'Полностью'" class="form__item" :class="{'item__active':form.mebel == 'Полностью'}">{{ $t('addAdsCompletely') }}</span>

                                    <!-- Частично -->
                                    <span @click="form.mebel = 'Частично'" class="form__item" :class="{'item__active':form.mebel == 'Частично'}">{{ $t('addAdsPartly') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.mebel = 'Нет'" class="form__item" :class="{'item__active':form.mebel == 'Нет'}">{{ $t('addAdsUnfurnished') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="mebel"></has-error>
                                </div>

                            </validation-provider>


                        </div>

                        <!-- Интернет - Модем, Через TV кабель, проводной, оптика -->
                        <div class="form-group" v-if="$route.params.table_name != 'Snimu'">

                            <div class="form__title">
                                {{ $t('addAdsTheInternet') }}
                            </div>


                            <validation-provider rules="required" v-model="form.internet" name="internet" v-slot="{ errors, valid, invalid, touched}">

                                <div class="form__input">

                                    <!-- Модем -->
                                    <span @click="form.internet = 'Модем'" class="form__item" :class="{'item__active':form.internet == 'Модем'}">{{ $t('addAdsModem') }}</span>

                                    <!-- Через TV кабель -->
                                    <span @click="form.internet = 'Через TV кабель'" class="form__item" :class="{'item__active':form.internet == 'Через TV кабель'}">{{ $t('addAdsViaTVCable') }}</span>

                                    <!-- Проводной -->
                                    <span @click="form.internet = 'Проводной'" class="form__item" :class="{'item__active':form.internet == 'Проводной'}">{{ $t('addAdsWired') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.internet = 'Нет'" class="form__item" :class="{'item__active':form.internet == 'Нет'}">{{ $t('addAdsNo') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="internet"></has-error>
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
                                    <span @click="form.dush = 'В комнате'" class="form__item" :class="{'item__active':form.dush == 'В комнате'}">{{ $t('addAdsInTheRoom') }}</span>

                                    <!-- В секции -->
                                    <span @click="form.dush = 'В секции'" class="form__item" :class="{'item__active':form.dush == 'В секции'}">{{ $t('addAdsInTheSection') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.dush = 'Нет'" class="form__item" :class="{'item__active':form.dush == 'Нет'}">{{ $t('addAdsNo') }}</span>

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
                                    <span v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Magazin'" @click="form.sanuzel = 'Раздельный'" class="form__item" :class="{'item__active':form.sanuzel == 'Раздельный'}">{{ $t('addAdsSeparate') }}</span>
                                    <!-- Совмещенный -->
                                    <span v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Magazin'" @click="form.sanuzel = 'Совмещенный'" class="form__item" :class="{'item__active':form.sanuzel == 'Совмещенный'}">{{ $t('addAdsCombined') }}</span>
                                    <!-- 2 с/у и более -->
                                    <span v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Magazin'" @click="form.sanuzel = '2 с/у и более'" class="form__item" :class="{'item__active':form.sanuzel == '2 с/у и более'}">{{ $t('addAds2OrMore') }}</span>


                                    <!-- Общежитие- В комнате -->
                                    <span v-if="$route.params.table_name == 'Obshejitie'" @click="form.sanuzel = 'В комнате'" class="form__item" :class="{'item__active':form.sanuzel == 'В комнате'}">{{ $t('addAdsInTheRoom') }}</span>
                                    <!-- В секции -->
                                    <span v-if="$route.params.table_name == 'Obshejitie'" @click="form.sanuzel = 'В секции'" class="form__item" :class="{'item__active':form.sanuzel == 'В секции'}">{{ $t('addAdsInTheSection') }}</span>


                                    <!-- Дом / В доме -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.sanuzel = 'В доме'" class="form__item" :class="{'item__active':form.sanuzel == 'В доме'}">{{ $t('addAdsInAHouse') }}</span>
                                    <!-- 2 с/у и более -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.sanuzel = '2 с/у и более'" class="form__item" :class="{'item__active':form.sanuzel == '2 с/у и более'}">{{ $t('addAds2OrMore') }}</span>
                                    <!-- Во дворе -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.sanuzel = 'Во дворе'" class="form__item" :class="{'item__active':form.sanuzel == 'Во дворе'}">{{ $t('addAdsInTheCourtyard') }}</span>


                                    <!-- Офис | Есть -->
                                    <span v-if="$route.params.table_name == 'Ofis'" @click="form.sanuzel = 'Есть'" class="form__item" :class="{'item__active':form.sanuzel == 'Есть'}">{{ $t('addAdsThereAre') }}</span>
                                    <!-- 2 с/у и более -->
                                    <span v-if="$route.params.table_name == 'Ofis'" @click="form.sanuzel = '2 с/у и более'" class="form__item" :class="{'item__active':form.sanuzel == '2 с/у и более'}">{{ $t('addAds2OrMore') }}</span>


                                    <!-- Для всех  -->
                                    <span @click="form.sanuzel = 'Нет'" class="form__item" :class="{'item__active':form.sanuzel == 'Нет'}">{{ $t('addAdsNo') }}</span>

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
                                    <span @click="form.otdelniy_vhod = 'Есть'" class="form__item" :class="{'item__active':form.otdelniy_vhod == 'Есть'}">{{ $t('addAdsThereAre') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.otdelniy_vhod = 'Нет'" class="form__item" :class="{'item__active':form.otdelniy_vhod == 'Нет'}">{{ $t('addAdsNo') }}</span>


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
                                    <span @click="form.kanalizaciya = 'Центральная'" class="form__item" :class="{'item__active':form.kanalizaciya == 'Центральная'}">{{ $t('addAdsCentral') }}</span>

                                    <!-- Септик -->
                                    <span @click="form.kanalizaciya = 'Септик'" class="form__item" :class="{'item__active':form.kanalizaciya == 'Септик'}">{{ $t('addAdsSepticTank') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.kanalizaciya = 'Нет'" class="form__item" :class="{'item__active':form.kanalizaciya == 'Нет'}">{{ $t('addAdsNo') }}</span>

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
                                    <span @click="form.voda = 'Центральная'" class="form__item" :class="{'item__active':form.voda == 'Центральная'}">{{ $t('addAdsCentral') }}</span>

                                    <!-- Скважина -->
                                    <span @click="form.voda = 'Скважина'" class="form__item" :class="{'item__active':form.voda == 'Скважина'}">{{ $t('addAdsBorehole') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.voda = 'Нет'" class="form__item" :class="{'item__active':form.voda == 'Нет'}">{{ $t('addAdsNo') }}</span>

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
                                    <span @click="form.elektrichestvo = 'Есть'" class="form__item" :class="{'item__active':form.elektrichestvo == 'Есть'}">{{ $t('addAdsThereAre') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.elektrichestvo = 'Нет'" class="form__item" :class="{'item__active':form.elektrichestvo == 'Нет'}">{{ $t('addAdsNo') }}</span>

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
                                    <span @click="form.otoplenie = 'Центральное'" class="form__item" :class="{'item__active':form.otoplenie == 'Центральное'}">{{ $t('addAdsCentralText') }}</span>

                                    <!-- На газе -->
                                    <span @click="form.otoplenie = 'На газе'" class="form__item" :class="{'item__active':form.otoplenie == 'На газе'}">{{ $t('addAdsOnGas') }}</span>

                                    <!-- На твердом топливе -->
                                    <span @click="form.otoplenie = 'На твердом топливе'" class="form__item" :class="{'item__active':form.otoplenie == 'На твердом топливе'}">{{ $t('addAdsOnSolidFuel') }}</span>

                                    <!-- На жидком топливе -->
                                    <span @click="form.otoplenie = 'На жидком топливе'" class="form__item" :class="{'item__active':form.otoplenie == 'На жидком топливе'}">{{ $t('addAdsOnLiquidFuel') }}</span>

                                    <!-- Смешанное -->
                                    <span @click="form.otoplenie = 'Смешанное'" class="form__item" :class="{'item__active':form.otoplenie == 'Смешанное'}">{{ $t('addAdsMixed') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.otoplenie = 'Нет'" class="form__item" :class="{'item__active':form.otoplenie == 'Нет'}">{{ $t('addAdsNo') }}</span>

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
                                    <span @click="form.gaz = 'Магистральный'" class="form__item" :class="{'item__active':form.gaz == 'Магистральный'}">{{ $t('addAdsTrunk') }}</span>

                                    <!-- Автономный -->
                                    <span @click="form.gaz = 'Автономный'" class="form__item" :class="{'item__active':form.gaz == 'Автономный'}">{{ $t('addAdsAutonomous') }}</span>

                                    <!--Нет -->
                                    <span @click="form.gaz = 'Нет'" class="form__item" :class="{'item__active':form.gaz == 'Нет'}">{{ $t('addAdsNo') }}</span>

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
                                    <span @click="form.balkon = 'Балкон'" class="form__item" :class="{'item__active':form.balkon == 'Балкон'}">{{ $t('addAdsBalcony') }}</span>

                                    <!-- Лоджия -->
                                    <span @click="form.balkon = 'Лоджия'" class="form__item" :class="{'item__active':form.balkon == 'Лоджия'}">{{ $t('addAdsLoggia') }}</span>

                                    <!-- Балкон и Лоджия -->
                                    <span @click="form.balkon = '2 балкона и более'" class="form__item" :class="{'item__active':form.balkon == '2 балкона и более'}">{{ $t('addAds2BalconiesOrMore') }}</span>

                                    <!-- Несколько балконов и лоджий -->
                                    <span @click="form.balkon = 'Нет', form.balkon_osteklen = 'Нет'" class="form__item" :class="{'item__active':form.balkon == 'Нет'}">{{ $t('addAdsNo') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="balkon"></has-error>
                                </div>

                            </validation-provider>

                        </div>


                        <!-- Парковка - Паркинг, Гараж, Рядом охраняемая стоянка -->
                        <div class="form-group" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Ofis' || $route.params.table_name == 'Magazin'">

                            <div class="form__title">
                                {{ $t('addAdsParkingSpace') }}
                            </div>

                            <validation-provider rules="required" v-model="form.parkovka" name="parkovka" v-slot="{ errors, valid, invalid, touched}">

                                <div class="form__input">

                                    <!-- Паркинг -->
                                    <span @click="form.parkovka = 'Паркинг'" class="form__item" :class="{'item__active':form.parkovka == 'Паркинг'}">{{ $t('addAdsParking') }}</span>

                                    <!-- Гараж -->
                                    <span @click="form.parkovka = 'Гараж'" class="form__item" :class="{'item__active':form.parkovka == 'Гараж'}">{{ $t('addAdsGarage') }}</span>

                                    <!-- Рядом охраняемая стоянка -->
                                    <span @click="form.parkovka = 'Рядом охраняемая стоянка'" class="form__item" :class="{'item__active':form.parkovka == 'Рядом охраняемая стоянка'}">{{ $t('addAdsThereIsAGuardedParkingLotNearby') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="parkovka"></has-error>
                                </div>

                            </validation-provider>

                        </div>

                        <!-- Пол - Линолеум, Паркет, Ламинат, дерево, ковролан, плитка -->
                        <div class="form-group" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Ofis'|| $route.params.table_name == 'Magazin'">

                            <div class="form__title">
                                {{ $t('addAdsFloorText') }}
                            </div>

                            <validation-provider rules="required" v-model="form.pol" name="pol" v-slot="{ errors, valid, invalid, touched}">

                                <div class="form__input">

                                    <!-- Линолеум-->
                                    <span @click="form.pol = 'Линолеум'" class="form__item" :class="{'item__active':form.pol == 'Линолеум'}">{{ $t('addAdsLinoleum') }}</span>

                                    <!-- Паркет -->
                                    <span @click="form.pol = 'Паркет'" class="form__item" :class="{'item__active':form.pol == 'Паркет'}">{{ $t('addAdsParquet') }}</span>

                                    <!-- Ламинат -->
                                    <span @click="form.pol = 'Ламинат'" class="form__item" :class="{'item__active':form.pol == 'Ламинат'}">{{ $t('addAdsLaminateFlooring') }}</span>

                                    <!-- Дерево -->
                                    <span @click="form.pol = 'Дерево'" class="form__item" :class="{'item__active':form.pol == 'Дерево'}">{{ $t('addAdsTree') }}</span>

                                    <!-- Ковролан -->
                                    <span @click="form.pol = 'Ковролан'" class="form__item" :class="{'item__active':form.pol == 'Ковролан'}">{{ $t('addAdsCarpet') }}</span>

                                    <!-- Плитка -->
                                    <span @click="form.pol = 'Плитка'" class="form__item" :class="{'item__active':form.pol == 'Плитка'}">{{ $t('addAdsTile') }}</span>

                                </div>

                                <!-- Вывожу ошибки - veevalidate, vform -->
                                <div class="text__error">
                                    <span >{{ errors[0] }}</span>
                                    <has-error :form="form" field="pol"></has-error>
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

                            <div class="form__title">{{ updateDateLocaleStore.lang == 'ru' ? 'Мебель и техника' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Жиһаз және техника' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Furniture and appliances' : ''}}</div>

                            <!-- Мебель - Для квартиры - Общежития - Дом  -->
                            <div class="form__input" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom'">
                                <span @click="form.mebel_arr.includes('Холодильник') ? form.mebel_arr = form.mebel_arr.filter(function(f) { return f !== 'Холодильник' }) : form.mebel_arr.unshift('Холодильник') " class="form__item" :class="{'item__active':form.mebel_arr.includes('Холодильник')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Холодильник' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Тоңазытқыш' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Fridge' : ''}}</span>
                                <span @click="form.mebel_arr.includes('Стиральная машина') ? form.mebel_arr = form.mebel_arr.filter(function(f) { return f !== 'Стиральная машина' }) : form.mebel_arr.unshift('Стиральная машина')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Стиральная машина')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Стиральная машина' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Кір жуғыш машина' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Washer' : ''}}</span>
                                <span @click="form.mebel_arr.includes('Телевизор') ? form.mebel_arr = form.mebel_arr.filter(function(f) { return f !== 'Телевизор' }) : form.mebel_arr.unshift('Телевизор')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Телевизор')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Телевизор' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Теледидар' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'TV' : ''}}</span>
                                <span @click="form.mebel_arr.includes('Диван') ? form.mebel_arr = form.mebel_arr.filter(function(f) { return f !== 'Диван' }) : form.mebel_arr.push('Диван')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Диван')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Диван' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Диван' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Sofa' : ''}}</span>
                                <span @click="form.mebel_arr.includes('Кровать') ? form.mebel_arr = form.mebel_arr.filter(function(f) { return f !== 'Кровать' }): form.mebel_arr.push('Кровать')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Кровать')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Кровать' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Төсек' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Bed' : ''}}</span>
                                <span @click="form.mebel_arr.includes('Кухонный гарнитур') ? form.mebel_arr = form.mebel_arr.filter(function(f) { return f !== 'Кухонный гарнитур' }) : form.mebel_arr.push('Кухонный гарнитур')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Кухонный гарнитур')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Кухонный гарнитур' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Ас үй жиынтығы' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Kitchen set' : ''}}</span>
                                <span @click="form.mebel_arr.includes('Кондиционер') ? form.mebel_arr = form.mebel_arr.filter(function(f) { return f !== 'Кондиционер' }) : form.mebel_arr.push('Кондиционер')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Кондиционер')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Кондиционер' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Кондиционер' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Conditioner' : ''}}</span>
                                <span @click="form.mebel_arr.includes('Микроволновая печь') ? form.mebel_arr = form.mebel_arr.filter(function(f) { return f !== 'Микроволновая печь' }) : form.mebel_arr.push('Микроволновая печь')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Микроволновая печь')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Микроволновая печь' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Микротолқынды пеш' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'A microwave' : ''}}</span>
                                <span @click="form.mebel_arr.includes('Вся бытовая техника') ? form.mebel_arr = form.mebel_arr.filter(function(f) { return f !== 'Вся бытовая техника' }) : form.mebel_arr.push('Вся бытовая техника')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Вся бытовая техника')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Вся бытовая техника' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Барлық тұрмыстық техника' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'All household appliances' : ''}}</span>
                            </div>

                            <div class="form__input" v-if="$route.params.table_name == 'Ofis' || $route.params.table_name == 'Magazin'">
                                <span @click="form.mebel_arr.includes('Кондиционер') ? form.mebel_arr = form.mebel_arr.filter(function(f) { return f !== 'Кондиционер' }): form.mebel_arr.push('Кондиционер')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Кондиционер')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Кондиционер' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Кондиционер' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Conditioner' : ''}}</span>
                            </div>

                        </div>


                        <!-- Разное -->
                        <div v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom'|| $route.params.table_name == 'Ofis' || $route.params.table_name == 'Magazin' || $route.params.table_name == 'Prombaza'" class="form-group">

                            <div class="form__title">{{ updateDateLocaleStore.lang == 'ru' ? 'Разное' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Әр түрлі' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Various' : ''}}</div>

                            <!-- Разное -  Для квартиры - Общежития - Дом  -->
                            <div class="form__input" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie'">
                                <span @click="form.raznoe.includes('Кухня-студия') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Кухня-студия' }) : form.raznoe.push('Кухня-студия') " class="form__item" :class="{'item__active':form.raznoe.includes('Кухня-студия')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Кухня-студия' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Ас үй студиясы' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Studio Kitchen' : ''}}</span>
                                <span @click="form.raznoe.includes('Комнаты изолированны') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Комнаты изолированны' }) : form.raznoe.push('Комнаты изолированны') " class="form__item" :class="{'item__active':form.raznoe.includes('Комнаты изолированны')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Комнаты изолированны' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Бөлмелер оқшауланған' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'The rooms are isolated' : ''}}</span>
                                <span @click="form.raznoe.includes('Кабельное TV') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Кабельное TV' }) : form.raznoe.push('Кабельное TV') " class="form__item" :class="{'item__active':form.raznoe.includes('Кабельное TV')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Кабельное TV' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Кабельдік теледидар' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Cable TV' : ''}}</span>
                                <span @click="form.raznoe.includes('Кладовка') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Кладовка' }) : form.raznoe.push('Кладовка') " class="form__item" :class="{'item__active':form.raznoe.includes('Кладовка')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Кладовка' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Қойма' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Storage' : ''}}</span>
                                <span @click="form.raznoe.includes('Бесплатный WI-FI') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Бесплатный WI-FI' }) : form.raznoe.push('Бесплатный WI-FI') " class="form__item" :class="{'item__active':form.raznoe.includes('Бесплатный WI-FI')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Бесплатный WI-FI' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Тегін WI-FI' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Free WI-FI' : ''}}</span>
                            </div>

                            <!-- Разное - Дом-->
                            <div class="form__input" v-if="$route.params.table_name == 'Dom'">
                                <span @click="form.raznoe.includes('Сад') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Сад' }) : form.raznoe.push('Сад') " class="form__item" :class="{'item__active':form.raznoe.includes('Сад')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Сад' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Бақша' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Garden' : ''}}</span>
                                <span @click="form.raznoe.includes('Баня') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Баня' }) : form.raznoe.push('Баня') " class="form__item" :class="{'item__active':form.raznoe.includes('Баня')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Баня' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Монша' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Bathhouse' : ''}}</span>
                                <span @click="form.raznoe.includes('Бассейн') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Бассейн' }) : form.raznoe.push('Бассейн') " class="form__item" :class="{'item__active':form.raznoe.includes('Бассейн')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Бассейн' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Бассейн' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Swimming pool' : ''}}</span>
                                <span @click="form.raznoe.includes('Сауна') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Сауна' }) : form.raznoe.push('Сауна') " class="form__item" :class="{'item__active':form.raznoe.includes('Сауна')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Сауна' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Сауна' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Sauna' : ''}}</span>
                                <span @click="form.raznoe.includes('Джакузи') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Джакузи' }) : form.raznoe.push('Джакузи') " class="form__item" :class="{'item__active':form.raznoe.includes('Джакузи')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Джакузи' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Ыстық ванна' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Jacuzzi' : ''}}</span>
                                <span @click="form.raznoe.includes('Кухня-студия') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Кухня-студия' }) : form.raznoe.push('Кухня-студия') " class="form__item" :class="{'item__active':form.raznoe.includes('Кухня-студия')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Кухня-студия' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Ас үй студиясы' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Studio Kitchen' : ''}}</span>
                                <span @click="form.raznoe.includes('Комнаты изолированны') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Комнаты изолированны' }) : form.raznoe.push('Комнаты изолированны') " class="form__item" :class="{'item__active':form.raznoe.includes('Комнаты изолированны')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Комнаты изолированны' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Бөлмелер оқшауланған' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'The rooms are isolated' : ''}}</span>
                                <span @click="form.raznoe.includes('Кабельное TV') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Кабельное TV' }) : form.raznoe.push('Кабельное TV') " class="form__item" :class="{'item__active':form.raznoe.includes('Кабельное TV')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Кабельное TV' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Кабельдік теледидар' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Cable TV' : ''}}</span>
                                <span @click="form.raznoe.includes('Хозпостройки') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Хозпостройки' }) : form.raznoe.push('Хозпостройки') " class="form__item" :class="{'item__active':form.raznoe.includes('Хозпостройки')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Хозпостройки' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Шаруашылық құрылыстары' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Outbuildings' : ''}}</span>
                                <span @click="form.raznoe.includes('3 Фазы') ? form.raznoe = form.raznoe.filter(function(f) { return f !== '3 Фазы' }) : form.raznoe.push('3 Фазы') " class="form__item" :class="{'item__active':form.raznoe.includes('3 Фазы')}">{{ updateDateLocaleStore.lang == 'ru' ? '3 Фазы' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? '3 кезең' : ''}}{{ updateDateLocaleStore.lang == 'en' ? '3 Phases' : ''}}</span>
                            </div>

                            <!-- Разное - Магазин -->
                            <div class="form__input" v-if="$route.params.table_name == 'Magazin'">
                                <span @click="form.raznoe.includes('С товаром') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'С товаром' }) : form.raznoe.push('С товаром') " class="form__item" :class="{'item__active':form.raznoe.includes('С товаром')}">{{ updateDateLocaleStore.lang == 'ru' ? 'С товаром' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Тауармен' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'With the product' : ''}}</span>
                                <span @click="form.raznoe.includes('С торговым оборудованием') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'С торговым оборудованием' }) : form.raznoe.push('С торговым оборудованием') " class="form__item" :class="{'item__active':form.raznoe.includes('С торговым оборудованием')}">{{ updateDateLocaleStore.lang == 'ru' ? 'С торговым оборудованием' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Сауда жабдықтарымен' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'With commercial equipment' : ''}}</span>
                            </div>

                            <!-- Разное - Промбаза -->
                            <div class="form__input" v-if="$route.params.table_name == 'Prombaza'">
                                <span @click="form.raznoe.includes('Ж/д тупик') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Ж/д тупик' }) : form.raznoe.push('Ж/д тупик') " class="form__item" :class="{'item__active':form.raznoe.includes('Ж/д тупик')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Ж/д тупик' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Т/ж тұйық' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Railway dead end' : ''}}</span>
                                <span @click="form.raznoe.includes('Автовесы') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Автовесы' }) : form.raznoe.push('Автовесы') " class="form__item" :class="{'item__active':form.raznoe.includes('Автовесы')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Автовесы' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Жүк таразылары' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Car weights' : ''}}</span>
                                <span @click="form.raznoe.includes('Холодильные камеры') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Холодильные камеры' }) : form.raznoe.push('Холодильные камеры') " class="form__item" :class="{'item__active':form.raznoe.includes('Холодильные камеры')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Холодильные камеры' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Тоңазытқыш камералар' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Cold storage rooms' : ''}}</span>
                                <span @click="form.raznoe.includes('Кран-балка') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Кран-балка' }) : form.raznoe.push('Кран-балка') " class="form__item" :class="{'item__active':form.raznoe.includes('Кран-балка')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Кран-балка' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Кран арқалығы' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Crane beam' : ''}}</span>
                                <span @click="form.raznoe.includes('Пандус') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Пандус' }) : form.raznoe.push('Пандус') " class="form__item" :class="{'item__active':form.raznoe.includes('Пандус')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Пандус' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Пандус' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Ramp' : ''}}</span>
                                <span @click="form.raznoe.includes('Своя подстанция') ? form.raznoe = form.raznoe.filter(function(f) { return f !== 'Своя подстанция' }) : form.raznoe.push('Своя подстанция') " class="form__item" :class="{'item__active':form.raznoe.includes('Своя подстанция')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Своя подстанция' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Өзінің қосалқы станциясы' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Own substation' : ''}}</span>
                            </div>

                        </div>


                        <!-- Расположение - промбаза, прочее -->
                        <div v-if="$route.params.table_name == 'Business' || $route.params.table_name == 'Prombaza' || $route.params.table_name == 'Prochaya'" class="form-gang">

                            <div class="form__title">{{ updateDateLocaleStore.lang == 'ru' ? 'Расположение' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Орналасқан жері' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Location' : ''}}</div>

                            <div class="form__input">
                                <span @click="form.raspolojenie.includes('В городе') ? form.raspolojenie = form.raspolojenie.filter(function(f) { return f !== 'В городе' }): form.raspolojenie.push('В городе')" class="form__item" :class="{'item__active':form.raspolojenie.includes('В городе')}">{{ updateDateLocaleStore.lang == 'ru' ? 'В городе' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Қалада' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'In the city' : ''}}</span>
                                <span @click="form.raspolojenie.includes('В пригороде') ? form.raspolojenie = form.raspolojenie.filter(function(f) { return f !== 'В пригороде' }): form.raspolojenie.push('В пригороде')" class="form__item" :class="{'item__active':form.raspolojenie.includes('В пригороде')}">{{ updateDateLocaleStore.lang == 'ru' ? 'В пригороде' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Қала маңында' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'In the suburbs' : ''}}</span>
                                <span @click="form.raspolojenie.includes('Вдоль трассы') ? form.raspolojenie = form.raspolojenie.filter(function(f) { return f !== 'Вдоль трассы' }): form.raspolojenie.push('Вдоль трассы')" class="form__item" :class="{'item__active':form.raspolojenie.includes('Вдоль трассы')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Вдоль трассы' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Жол бойында' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Along the highway' : ''}}</span>
                            </div>
                        </div>

                        <!-- Коммуникации - промбаза -->
                        <div v-if="$route.params.table_name == 'Business' || $route.params.table_name == 'Prombaza' || $route.params.table_name == 'Prochaya'" class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">{{ updateDateLocaleStore.lang == 'ru' ? 'Коммуникации' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Коммуникациялар' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Communications' : ''}}</div>

                            <div class="form__input">
                                <span @click="form.kommunikacii.includes('Свет') ? form.kommunikacii = form.kommunikacii.filter(function(f) { return f !== 'Свет' }): form.kommunikacii.push('Свет')" class="form__item" :class="{'item__active':form.kommunikacii.includes('Свет')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Свет' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Жарық' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Light' : ''}}</span>
                                <span @click="form.kommunikacii.includes('Газ') ? form.kommunikacii = form.kommunikacii.filter(function(f) { return f !== 'Газ' }): form.kommunikacii.push('Газ')" class="form__item" :class="{'item__active':form.kommunikacii.includes('Газ')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Газ' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Газ' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Gas' : ''}}</span>
                                <span @click="form.kommunikacii.includes('Интернет') ? form.kommunikacii = form.kommunikacii.filter(function(f) { return f !== 'Интернет' }): form.kommunikacii.push('Интернет')" class="form__item" :class="{'item__active':form.kommunikacii.includes('Интернет')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Интернет' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Ғаламтор' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'The Internet' : ''}}</span>
                                <span @click="form.kommunikacii.includes('Отопление') ? form.kommunikacii = form.kommunikacii.filter(function(f) { return f !== 'Отопление' }): form.kommunikacii.push('Отопление')" class="form__item" :class="{'item__active':form.kommunikacii.includes('Отопление')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Отопление' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Жылыту' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Heating' : ''}}</span>
                                <span @click="form.kommunikacii.includes('Вода') ? form.kommunikacii = form.kommunikacii.filter(function(f) { return f !== 'Вода' }): form.kommunikacii.push('Вода')" class="form__item" :class="{'item__active':form.kommunikacii.includes('Вода')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Вода' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Су' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Water' : ''}}</span>
                                <span @click="form.kommunikacii.includes('Телефон') ? form.kommunikacii = form.kommunikacii.filter(function(f) { return f !== 'Телефон' }): form.kommunikacii.push('Телефон')" class="form__item" :class="{'item__active':form.kommunikacii.includes('Телефон')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Телефон' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Телефон' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Telephone' : ''}}</span>
                                <span @click="form.kommunikacii.includes('Канализация') ? form.kommunikacii = form.kommunikacii.filter(function(f) { return f !== 'Канализация' }): form.kommunikacii.push('Канализация')" class="form__item" :class="{'item__active':form.kommunikacii.includes('Канализация')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Канализация' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Кәріз' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Sewage system' : ''}}</span>
                            </div>

                        </div>

                        <!-- Безопасность -->
                        <div v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Ofis' || $route.params.table_name == 'Zdanie' || $route.params.table_name == 'Magazin' || $route.params.table_name == 'Snimu'" class="form-group">

                            <div class="form__title">{{ updateDateLocaleStore.lang == 'ru' ? 'Безопасность' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Қауіпсіздік' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Security' : ''}}</div>

                            <div class="form__input">
                                <span @click="form.bezopasnost.includes('Домофон') ? form.bezopasnost = form.bezopasnost.filter(function(f) { return f !== 'Домофон' }): form.bezopasnost.push('Домофон')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Домофон')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Домофон' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Домофон' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Intercom' : ''}}</span>
                                <span @click="form.bezopasnost.includes('Видеодомофон') ? form.bezopasnost = form.bezopasnost.filter(function(f) { return f !== 'Видеодомофон' }): form.bezopasnost.push('Видеодомофон')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Видеодомофон')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Видеодомофон' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Видеодомофон' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Video intercom' : ''}}</span>
                                <span @click="form.bezopasnost.includes('Сигнализация') ? form.bezopasnost = form.bezopasnost.filter(function(f) { return f !== 'Сигнализация' }): form.bezopasnost.push('Сигнализация')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Сигнализация')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Сигнализация' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Дабыл' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Alarm system' : ''}}</span>
                                <span @click="form.bezopasnost.includes('Решетки на окнах') ? form.bezopasnost = form.bezopasnost.filter(function(f) { return f !== 'Решетки на окнах' }): form.bezopasnost.push('Решетки на окнах')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Решетки на окнах')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Решетки на окнах' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Терезелердегі торлар' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Bars on the windows' : ''}}</span>
                                <span @click="form.bezopasnost.includes('Охрана') ? form.bezopasnost = form.bezopasnost.filter(function(f) { return f !== 'Охрана' }): form.bezopasnost.push('Охрана')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Охрана')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Охрана' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Күзет' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Security' : ''}}</span>
                                <span @click="form.bezopasnost.includes('Видеонаблюдение') ? form.bezopasnost = form.bezopasnost.filter(function(f) { return f !== 'Видеонаблюдение' }): form.bezopasnost.push('Видеонаблюдение')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Видеонаблюдение')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Видеонаблюдение' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Бейнебақылау' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Video surveillance' : ''}}</span>
                                <span @click="form.bezopasnost.includes('Кодовый замок') ? form.bezopasnost = form.bezopasnost.filter(function(f) { return f !== 'Кодовый замок' }): form.bezopasnost.push('Кодовый замок')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Кодовый замок')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Кодовый замок' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Код құлпы' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Combination lock' : ''}}</span>
                                <span @click="form.bezopasnost.includes('Консьерж') ? form.bezopasnost = form.bezopasnost.filter(function(f) { return f !== 'Консьерж' }): form.bezopasnost.push('Консьерж')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Консьерж')}">{{ updateDateLocaleStore.lang == 'ru' ? 'Консьерж' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Консьерж' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Concierge' : ''}}</span>
                            </div>

                        </div>

                    </div>

                    <!--Текст описание - Textarea -->
                    <div v-if="$route.params.step == 4" class="form-group  addAds__form-block">


                        <!-- Заголовок -->
                        <h2 class="text-center py-2 pb-4 fw-bold">{{ updateDateLocaleStore.lang == 'ru' ? 'Описание и цена' : ''}}{{ updateDateLocaleStore.lang == 'kz' ? 'Сипаттама және баға' : ''}}{{ updateDateLocaleStore.lang == 'en' ? 'Description and price' : ''}}</h2>

                        <!-- Цена в месяц - За кв.м, за все -->
                        <div v-if="$route.params.table_name == 'Business' || $route.params.table_name == 'Ofis' || $route.params.table_name == 'Zdanie' || $route.params.table_name == 'Magazin' || $route.params.table_name == 'Prombaza' || $route.params.table_name == 'Prochaya'" class="py-3">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsPricePerMonth') }}</div>

                            <!-- Поле -->
                            <validation-provider rules="required" v-model="form.cena_tip" name="cena_tip" v-slot="{ errors }">

                                <div class="form__input">
                                    <span @click="form.cena_tip = 'За все'" class="form__item" :class="{'item__active':form.cena_tip == 'За все'}">{{ $t('addAdsForEverything') }}</span>
                                    <span @click="form.cena_tip = 'За кв.м'" class="form__item" :class="{'item__active':form.cena_tip == 'За кв.м'}">{{ $t('addAdsPerSqM') }}</span>
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
                            <draggable class="d-flex flex-wrap justify-center gap-2" :list="imageAndPreviewImage" @end="draggableEnd" style="max-width: 100%">
                                <div class="position-relative" v-for="(img, index) of imageAndPreviewImage" :key="index">
                                    <v-btn size="x-small" icon @click="deleteImage(index)" style="position: absolute; top: 5px; right: 5px">
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                    <img :src="img.previewImg" @click="$router.push({name: $route.name + 'Image'}), imageStore.showImages({images: imageAndPreviewImage,index, addAdsImg:true})"  width="100" height="100" role="button">
                                </div>
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
                            <v-btn @click="$router.push({ name: $route.name + 'Location',  params: { locationId: 'null', stepLocation:1 } })"
                                   class="flex-fill text-body-2"
                                   :class="{'rounded-e-0' : KZLocationStore.location != ''}"
                                   color="blue" size="x-large" rounded="0"
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
                        <div v-if="$route.params.table_name != 'Snimu'" class="form__input row g-0 mt-4">

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
                                        name="nomer_doma" :label="$t('addAdsHouse')"
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

                        <!-- Вы хозяин / Риэлтор -->
                        <div class="form-group">

                            <!-- Input -->
                            <validation-provider rules="required" v-model="form.ownerOrRealtor" name="ownerOrRealtor" v-slot="{ errors }">

                                <div v-if="$route.params.table_name == 'Snimu'" class="alert alert-success py-1 text-body-1">{{ $t('addAdsRealtorText') }}</div>
                                <div class="form__input my-3">
                                    <span v-if="$route.params.table_name != 'Snimu'" @click="form.ownerOrRealtor = 'Хозяин'" class="form__item" :class="{'item__active' : form.ownerOrRealtor == 'Хозяин'}">{{ $t('addAdsIAmTheOwner') }} </span>
                                    <span v-if="$route.params.table_name != 'Snimu'" @click="form.ownerOrRealtor = 'Риелтор'" class="form__item" :class="{'item__active' : form.ownerOrRealtor == 'Риелтор'}">{{ $t('addAdsIAmARealtorSpecialist') }} </span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.ownerOrRealtor = 'Через риелтора'" class="form__item" :class="{'item__active' : form.ownerOrRealtor == 'Через риелтора'}">{{ $t('addAdsItIsPossibleFromASpecialist') }} </span>
                                </div>

                                <!-- Вывод ошибок -->
                                <div class="text__error">
                                    <div>{{ errors[0] }}</div>
                                    <has-error :form="form" field="ownerOrRealtor"></has-error>
                                </div>


                            </validation-provider>

                        </div>

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

                        </div>

                        <!-- Телефон 2 -->
                        <div class="form-group">

                            <vue-tel-input v-model="form.tel2"  style="height: 56px"
                                           :inputOptions="{ placeholder: $t('addAdsYourPhoneNumber'), showDialCode: true}"
                                           :validCharactersOnly="true"
                            ></vue-tel-input>

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
                                    <span v-if="$route.params.table_name != 'Snimu'">{{ form.ulica }}, {{form.nomer_doma}}</span>
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
import {useCheckInternetStore} from "../../../stores/checkInternet";
import {useImagesStore} from "../../../stores/images";
import {useKZLocationStore} from "../../../stores/KZLocation";
import { useUpdateDateLocaleStore } from "../../../stores/updateDateLocale";

// VueDraggable Пакет для перемещения картинок в массиве (по экрану)
import { defineComponent } from 'vue'
import { VueDraggableNext } from 'vue-draggable-next'

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

export default defineComponent({
    name: "addAdsForm",

    directives: {
        number: directive, //Для преобразования цены в поле с 9999 на  9 999
    },

    components: {
        HasError, // VForm - Валидация
        YandexMap, YandexMarker,// Карта яндекс
        draggable: VueDraggableNext, // Для перемещения фото
        VueTelInput // Для номера телефона
    },

    data() {
        return {

            //Подключаю Store - Наше общее состояние
            authStore: useAuthStore(),
            checkInternetStore: useCheckInternetStore(),
            imageStore: useImagesStore(),
            KZLocationStore: useKZLocationStore(),
            updateDateLocaleStore: useUpdateDateLocaleStore(),

            query: false,

            showForm: true, //Показать форму или Кнопики, продолжить подать новое объявление

            //поля формы
            form: new Form({
                addOrUpdate: 'add', //Добавить новое или редактировать старое

                id: '', //id объявления
                author_id: '',

                table_name: '',
                zagolovok: '',
                tip_sdelki: '',
                tip_obekta: '',
                period_arendi: 'На длительно',
                kolichestvo_komnat: 1,

                deistvuushii_bisnes: 'Да',
                sfera_deyatelnosti: 'Производство',
                cena: '',
                cena_tip: 'За все',
                mestopolojenie: '', //магазин
                tip_stroeniya: 'Панель',
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
                internet: '',
                dush: '',
                sanuzel: '',
                otdelniy_vhod: '',
                kanalizaciya: '',
                voda: '',
                elektrichestvo: '',
                otoplenie: '',
                gaz: '',
                balkon: '',
                parkovka: '',
                mebel: '',
                pol: '',

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
                ownerOrRealtor: '',
                name: '',
                tel: '',
                tel2: '',

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

                //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
                this.checkInternetStore.checkInternet()


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


                //Если изменился получить новые координаты
                if(this.address.adr1.oblast != this.address.adr2.oblast || this.address.adr1.gorod != this.address.adr2.gorod
                    || this.address.adr1.raion != this.address.adr2.raion || this.address.adr1.ulica != this.address.adr2.ulica || this.address.adr1.nomer_doma != this.address.adr2.nomer_doma){

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

            if(this.form.period_arendi == 'На длительно' || this.form.period_arendi == 'На подселение'){
                if(this.updateDateLocaleStore.lang == 'ru')return 'Цена / В месяц'
                if(this.updateDateLocaleStore.lang == 'kz')return 'Бағасы / айына'
                if(this.updateDateLocaleStore.lang == 'en')return 'Price / Per month'
            }
            if(this.form.period_arendi == 'Посуточно'){
                if(this.updateDateLocaleStore.lang == 'ru')return 'Цена / В сутки'
                if(this.updateDateLocaleStore.lang == 'kz')return 'Бағасы / тәулігіне'
                if(this.updateDateLocaleStore.lang == 'en')return 'Price / Per day'
            }
            if(this.form.period_arendi == 'По часам'){
                if(this.updateDateLocaleStore.lang == 'ru')return 'Цена / В час'
                if(this.updateDateLocaleStore.lang == 'kz')return 'Бағасы / сағатына'
                if(this.updateDateLocaleStore.lang == 'en')return 'Price / Per hour'
            }

        }
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
                this.form.tel2 = this.authStore.user.tel2;

                // Показать форму
                this.showForm = true;

                // Если выбрано добавить объявление из localeStorage - если нет то просто откроется форма для добавления нового
                if (addLocaleStorageAds) {
                    let newAdsData = JSON.parse(localStorage.getItem("newAdsData=" + this.$route.params.table_name))
                    this.form.fill(newAdsData);

                    this.form.author_id = this.authStore.user.id;
                    this.form.name = this.authStore.user.name;
                    this.form.tel = this.authStore.user.tel;
                    this.form.tel2 = this.authStore.user.tel2;

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

                //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
                this.checkInternetStore.checkInternet();

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

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            this.checkInternetStore.checkInternet()

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
                            this.form.tel2 != null && this.form.tel2.length < 3 ? this.form.tel2 = '' : '';
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
           this.checkInternetStore.checkInternet()
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

})

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
    border-radius: 10px;
    color: rgb(51, 51, 51);
    font-size: 16px;
    white-space: nowrap;
}

.item__active{
    background: #2d81e1;
    color: #ffffff;
}

/*Добавим стили для полей vuetify - textarea и для label*/
::v-deep(.v-textarea--auto-grow .v-field__input) {
    overflow-y: auto!important;
}
/*Сделать текст label жирным */
::v-deep(.v-label.v-field-label) {
    font-size: 1em;
    font-weight: 600;
    font-family: 'Open-Sans', sans-serif;
}

</style>
