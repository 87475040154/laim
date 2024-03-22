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

                        <!-- Для Бизнеса - Тип сделки - Сдам - продам  -->
                        <div v-if="$route.params.table_name == 'Business'" class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsDealType') }}</div>

                            <!-- Поле с кнопками -->
                            <validation-provider rules="required" v-model="form.tip_sdelki" name="tip_sdelki" v-slot="{ errors }">

                                <div class="form__input">
                                    <div @click="form.tip_sdelki = 'Сдам'" class="form__item flex-fill" :class="{'item__active': form.tip_sdelki == 'Сдам'}" style="width: 150px"> <v-icon color="green" v-if="form.tip_sdelki == 'Сдам'">mdi-check-circle</v-icon>{{ $t('addAdsSdam') }} </div>
                                    <div @click="form.tip_sdelki = 'Продам'" class="form__item flex-fill" :class="{'item__active': form.tip_sdelki == 'Продам'}" style="width: 150px"> <v-icon color="green" v-if="form.tip_sdelki == 'Продам'">mdi-check-circle</v-icon>{{ $t('addAdsSell') }} </div>
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

                                    <span @click="form.sfera_deyatelnosti = 'Производство'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'Производство'}"><v-icon color="green" v-if="form.sfera_deyatelnosti == 'Производство'">mdi-check-circle</v-icon>{{ $t('addAdsProduction') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'Общепит'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'Общепит'}"><v-icon color="green" v-if="form.sfera_deyatelnosti == 'Общепит'">mdi-check-circle</v-icon>{{ $t('addAdsPublicCatering') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'Развлечения'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'Развлечения'}"><v-icon color="green" v-if="form.sfera_deyatelnosti == 'Развлечения'">mdi-check-circle</v-icon>{{ $t('addAdsEntertainment') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'Торговля'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'Торговля'}"><v-icon color="green" v-if="form.sfera_deyatelnosti == 'Торговля'">mdi-check-circle</v-icon>{{ $t('addAdsTrade') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'Услуги'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'Услуги'}"><v-icon color="green" v-if="form.sfera_deyatelnosti == 'Услуги'">mdi-check-circle</v-icon>{{ $t('addAdsServices') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'Сельское хозяйство'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'Сельское хозяйство'}"><v-icon color="green" v-if="form.sfera_deyatelnosti == 'Сельское хозяйство'">mdi-check-circle</v-icon>{{ $t('addAdsAgriculture') }}</span>
                                    <span @click="form.sfera_deyatelnosti = 'Другое'" class="form__item" :class="{'item__active': form.sfera_deyatelnosti == 'Другое'}"><v-icon color="green" v-if="form.sfera_deyatelnosti == 'Другое'">mdi-check-circle</v-icon>{{ $t('addAdsOtherText') }}</span>

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
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta = 'Дом'" class="form__item" :class="{'item__active' : form.tip_obekta == 'Дом'}"><v-icon color="green" v-if="form.tip_obekta == 'Дом'">mdi-check-circle</v-icon>{{ $t('addAdsHouseText') }}</span>
                                    <!-- Дача  -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta = 'Дача'" class="form__item" :class="{'item__active' : form.tip_obekta == 'Дача'}"><v-icon color="green" v-if="form.tip_obekta == 'Дача'">mdi-check-circle</v-icon>{{ $t('addAdsCountryHouse') }}</span>
                                    <!-- Коттедж  -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta = 'Коттедж'" class="form__item" :class="{'item__active' : form.tip_obekta == 'Коттедж'}"><v-icon color="green" v-if="form.tip_obekta == 'Коттедж'">mdi-check-circle</v-icon>{{ $t('addAdsCottage') }}</span>
                                    <!-- Времянка  -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.tip_obekta = 'Времянка'" class="form__item" :class="{'item__active' : form.tip_obekta == 'Времянка'}"><v-icon color="green" v-if="form.tip_obekta == 'Времянка'">mdi-check-circle</v-icon>{{ $t('addAdsVremyanka') }}</span>


                                    <!-- Магазин  -->
                                    <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta = 'Магазин'" class="form__item" :class="{'item__active':form.tip_obekta == 'Магазин'}"><v-icon color="green" v-if="form.tip_obekta == 'Магазин'">mdi-check-circle</v-icon>{{ $t('addAdsShopText') }}</span>
                                    <!-- Бутик  -->
                                    <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta = 'Бутик'" class="form__item" :class="{'item__active':form.tip_obekta == 'Бутик'}"><v-icon color="green" v-if="form.tip_obekta == 'Бутик'">mdi-check-circle</v-icon>{{ $t('addAdsBoutique') }}</span>
                                    <!-- Киоск  -->
                                    <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta = 'Киоск'" class="form__item" :class="{'item__active':form.tip_obekta == 'Киоск'}"><v-icon color="green" v-if="form.tip_obekta == 'Киоск'">mdi-check-circle</v-icon>{{ $t('addAdsKiosk') }}</span>
                                    <!-- Контейнер -->
                                    <span v-if="$route.params.table_name == 'Magazin'" @click="form.tip_obekta = 'Контейнер'" class="form__item" :class="{'item__active':form.tip_obekta == 'Контейнер'}"><v-icon color="green" v-if="form.tip_obekta == 'Контейнер'">mdi-check-circle</v-icon>{{ $t('addAdsContainer') }}</span>


                                    <!-- Промбаза  -->
                                    <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta = 'Промбаза'" class="form__item" :class="{'item__active':form.tip_obekta == 'Промбаза'}"><v-icon color="green" v-if="form.tip_obekta == 'Промбаза'">mdi-check-circle</v-icon>{{ $t('addAdsIndustrialBase') }}</span>
                                    <!-- Завод  -->
                                    <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta = 'Завод'" class="form__item" :class="{'item__active':form.tip_obekta == 'Завод'}"><v-icon color="green" v-if="form.tip_obekta == 'Завод'">mdi-check-circle</v-icon>{{ $t('addAdsFactory') }}</span>
                                    <!-- Склад бытовой  -->
                                    <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta = 'Склад бытовой'" class="form__item" :class="{'item__active':form.tip_obekta == 'Склад бытовой'}"><v-icon color="green" v-if="form.tip_obekta == 'Склад бытовой'">mdi-check-circle</v-icon>{{ $t('addAdsHouseholdWarehouse') }}</span>
                                    <!-- Склад продовольственный  -->
                                    <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta = 'Склад продовольственный'" class="form__item" :class="{'item__active':form.tip_obekta == 'Склад продовольственный'}"><v-icon color="green" v-if="form.tip_obekta == 'Склад продовольственный'">mdi-check-circle</v-icon>{{ $t('addAdsFoodWarehouse') }}</span>
                                    <!-- Склад химпродукции  -->
                                    <span v-if="$route.params.table_name == 'Prombaza'" @click="form.tip_obekta = 'Склад химпродукции'" class="form__item" :class="{'item__active':form.tip_obekta == 'Склад химпродукции'}"><v-icon color="green" v-if="form.tip_obekta == 'Склад химпродукции'">mdi-check-circle</v-icon>{{ $t('addAdsWarehouseForChemicalProducts') }}</span>


                                    <!-- Сниму -->
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Квартира'" class="form__item" :class="{'item__active':form.tip_obekta == 'Квартира'}"><v-icon color="green" v-if="form.tip_obekta == 'Квартира'">mdi-check-circle</v-icon>{{ $t('addAdsApartmentText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Общежитие'" class="form__item" :class="{'item__active':form.tip_obekta == 'Общежитие'}"><v-icon color="green" v-if="form.tip_obekta == 'Общежитие'">mdi-check-circle</v-icon>{{ $t('addAdsHostelText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Дом'" class="form__item" :class="{'item__active':form.tip_obekta == 'Дом'}"><v-icon color="green" v-if="form.tip_obekta == 'Дом'">mdi-check-circle</v-icon>{{ $t('addAdsHouseText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Офис'" class="form__item" :class="{'item__active':form.tip_obekta == 'Офис'}"><v-icon color="green" v-if="form.tip_obekta == 'Офис'">mdi-check-circle</v-icon>{{ $t('addAdsOfficeText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Здание'" class="form__item" :class="{'item__active':form.tip_obekta == 'Здание'}"><v-icon color="green" v-if="form.tip_obekta == 'Здание'">mdi-check-circle</v-icon>{{ $t('addAdsBuildingText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Магазин'" class="form__item" :class="{'item__active':form.tip_obekta == 'Магазин'}"><v-icon color="green" v-if="form.tip_obekta == 'Магазин'">mdi-check-circle</v-icon>{{ $t('addAdsShopText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Промбаза'" class="form__item" :class="{'item__active':form.tip_obekta == 'Промбаза'}"><v-icon color="green" v-if="form.tip_obekta == 'Промбаза'">mdi-check-circle</v-icon>{{ $t('addAdsBaseText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Прочая'" class="form__item" :class="{'item__active':form.tip_obekta == 'Прочая'}"><v-icon color="green" v-if="form.tip_obekta == 'Прочая'">mdi-check-circle</v-icon>{{ $t('addAdsOtherText2') }}</span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.tip_obekta = 'Бизнес'" class="form__item" :class="{'item__active':form.tip_obekta == 'Бизнес'}"><v-icon color="green" v-if="form.tip_obekta == 'Бизнес'">mdi-check-circle</v-icon>{{ $t('addAdsBusinessText2') }}</span>

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
                                    <div @click="form.period_arendi = 'На длительно'" class="form__item flex-fill" :class="{'item__active': form.period_arendi == 'На длительно'}"><v-icon color="green" v-if="form.period_arendi == 'На длительно'">mdi-check-circle</v-icon> {{ $t('addAdsForALongTime') }} </div>
                                    <div @click="form.period_arendi = 'Посуточно'" class="form__item flex-fill" :class="{'item__active': form.period_arendi == 'Посуточно'}"><v-icon color="green" v-if="form.period_arendi == 'Посуточно'">mdi-check-circle</v-icon> {{ $t('addAdsDaily') }} </div>
                                    <div @click="form.period_arendi = 'По часам'" class="form__item flex-fill" :class="{'item__active': form.period_arendi == 'По часам'}"> <v-icon color="green" v-if="form.period_arendi == 'По часам'">mdi-check-circle</v-icon>{{ $t('addAdsByTheHour') }} </div>
                                    <div @click="form.period_arendi = 'На подселение'" class="form__item flex-fill" :class="{'item__active': form.period_arendi == 'На подселение'}"><v-icon color="green" v-if="form.period_arendi == 'На подселение'">mdi-check-circle</v-icon> {{ $t('addAdsForSharing') }} </div>
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
                                <div class="col-auto d-flex align-items-center gap-1 px-1" style="height: 55px">
                                    <div v-for="n in 8" role="button" @click="form.kolichestvo_komnat = n" class="d-flex rounded-2 align-center justify-center" style="font-size: 1.1em; font-weight: bold; width: 50px; height: 50px; background: #ffffff; border: 1px solid #cecece" :style="{ background: form.kolichestvo_komnat == n ? '#f0f7f0' : '' }">
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
                                    <span @click="form.deistvuushii_bisnes = 'Да'" class="form__item" :class="{'item__active':form.deistvuushii_bisnes == 'Да'}"><v-icon color="green" v-if="form.deistvuushii_bisnes == 'Да'">mdi-check-circle</v-icon>{{ $t('addAdsYes') }}</span>
                                    <span @click="form.deistvuushii_bisnes = 'Нет'" class="form__item" :class="{'item__active':form.deistvuushii_bisnes == 'Нет'}"><v-icon color="green" v-if="form.deistvuushii_bisnes == 'Нет'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>
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
                                    <span @click="form.tip_stroeniya = 'Панель'" class="form__item"  :class="{ 'item__active' : form.tip_stroeniya == 'Панель' }"><v-icon color="green" v-if="form.tip_stroeniya == 'Панель'">mdi-check-circle</v-icon> {{ $t('addAdsPanel') }} </span>
                                    <span @click="form.tip_stroeniya = 'Кирпич'" class="form__item"  :class="{ 'item__active' : form.tip_stroeniya == 'Кирпич' }"><v-icon color="green" v-if="form.tip_stroeniya == 'Кирпич'">mdi-check-circle</v-icon> {{ $t('addAdsBrick') }} </span>
                                    <span @click="form.tip_stroeniya = 'Дерево'" class="form__item"  :class="{ 'item__active' : form.tip_stroeniya == 'Дерево' }"><v-icon color="green" v-if="form.tip_stroeniya == 'Дерево'">mdi-check-circle</v-icon> {{ $t('addAdsTree') }} </span>
                                    <span @click="form.tip_stroeniya = 'Другое'" class="form__item"  :class="{ 'item__active' : form.tip_stroeniya == 'Другое' }"><v-icon color="green" v-if="form.tip_stroeniya == 'Другое'">mdi-check-circle</v-icon> {{ $t('addAdsOtherText') }} </span>
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
                                    <span @click="form.sostoyanie = 'Евро ремонт'" class="form__item" :class="{'item__active':form.sostoyanie == 'Евро ремонт'}"><v-icon color="green" v-if="form.sostoyanie == 'Евро ремонт'">mdi-check-circle</v-icon>{{ $t('addAdsEuroRepair') }}</span>

                                    <!-- Хорошее -->
                                    <span @click="form.sostoyanie = 'Хорошее'" class="form__item" :class="{'item__active':form.sostoyanie == 'Хорошее'}"><v-icon color="green" v-if="form.sostoyanie == 'Хорошее'">mdi-check-circle</v-icon>{{ $t('addAdsGood') }}</span>

                                    <!-- Среднее -->
                                    <span @click="form.sostoyanie = 'Среднее'" class="form__item" :class="{'item__active':form.sostoyanie == 'Среднее'}"><v-icon color="green" v-if="form.sostoyanie == 'Среднее'">mdi-check-circle</v-icon>{{ $t('addAdsAverage') }}</span>

                                    <!-- Требует ремонта -->
                                    <span @click="form.sostoyanie = 'Требует ремонта'" class="form__item" :class="{'item__active':form.sostoyanie == 'Требует ремонта'}"><v-icon color="green" v-if="form.sostoyanie == 'Требует ремонта'">mdi-check-circle</v-icon>{{ $t('addAdsRequiresRepair') }}</span>

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
                                    <span @click="form.mebel = 'Полностью'" class="form__item" :class="{'item__active':form.mebel == 'Полностью'}"><v-icon color="green" v-if="form.mebel == 'Полностью'">mdi-check-circle</v-icon>{{ $t('addAdsCompletely') }}</span>

                                    <!-- Частично -->
                                    <span @click="form.mebel = 'Частично'" class="form__item" :class="{'item__active':form.mebel == 'Частично'}"><v-icon color="green" v-if="form.mebel == 'Частично'">mdi-check-circle</v-icon>{{ $t('addAdsPartly') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.mebel = 'Нет'" class="form__item" :class="{'item__active':form.mebel == 'Нет'}"><v-icon color="green" v-if="form.mebel == 'Нет'">mdi-check-circle</v-icon>{{ $t('addAdsUnfurnished') }}</span>

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
                                    <span @click="form.dush = 'В комнате'" class="form__item" :class="{'item__active':form.dush == 'В комнате'}"><v-icon color="green" v-if="form.dush == 'В комнате'">mdi-check-circle</v-icon>{{ $t('addAdsInTheRoom') }}</span>

                                    <!-- В секции -->
                                    <span @click="form.dush = 'В секции'" class="form__item" :class="{'item__active':form.dush == 'В секции'}"><v-icon color="green" v-if="form.dush == 'В секции'">mdi-check-circle</v-icon>{{ $t('addAdsInTheSection') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.dush = 'Нет'" class="form__item" :class="{'item__active':form.dush == 'Нет'}"><v-icon color="green" v-if="form.dush == 'Нет'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

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
                                    <span v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Magazin'" @click="form.sanuzel = 'Раздельный'" class="form__item" :class="{'item__active':form.sanuzel == 'Раздельный'}"><v-icon color="green" v-if="form.sanuzel == 'Раздельный'">mdi-check-circle</v-icon>{{ $t('addAdsSeparate') }}</span>
                                    <!-- Совмещенный -->
                                    <span v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Magazin'" @click="form.sanuzel = 'Совмещенный'" class="form__item" :class="{'item__active':form.sanuzel == 'Совмещенный'}"><v-icon color="green" v-if="form.sanuzel == 'Совмещенный'">mdi-check-circle</v-icon>{{ $t('addAdsCombined') }}</span>
                                    <!-- 2 с/у и более -->
                                    <span v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Magazin'" @click="form.sanuzel = '2 с/у и более'" class="form__item" :class="{'item__active':form.sanuzel == '2 с/у и более'}"><v-icon color="green" v-if="form.sanuzel == '2 с/у и более'">mdi-check-circle</v-icon>{{ $t('addAds2OrMore') }}</span>


                                    <!-- Общежитие- В комнате -->
                                    <span v-if="$route.params.table_name == 'Obshejitie'" @click="form.sanuzel = 'В комнате'" class="form__item" :class="{'item__active':form.sanuzel == 'В комнате'}"><v-icon color="green" v-if="form.sanuzel == 'В комнате'">mdi-check-circle</v-icon>{{ $t('addAdsInTheRoom') }}</span>
                                    <!-- В секции -->
                                    <span v-if="$route.params.table_name == 'Obshejitie'" @click="form.sanuzel = 'В секции'" class="form__item" :class="{'item__active':form.sanuzel == 'В секции'}"><v-icon color="green" v-if="form.sanuzel == 'В секции'">mdi-check-circle</v-icon>{{ $t('addAdsInTheSection') }}</span>


                                    <!-- Дом / В доме -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.sanuzel = 'В доме'" class="form__item" :class="{'item__active':form.sanuzel == 'В доме'}"><v-icon color="green" v-if="form.sanuzel == 'В доме'">mdi-check-circle</v-icon>{{ $t('addAdsInAHouse') }}</span>
                                    <!-- 2 с/у и более -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.sanuzel = '2 с/у и более'" class="form__item" :class="{'item__active':form.sanuzel == '2 с/у и более'}"><v-icon color="green" v-if="form.sanuzel == '2 с/у и более'">mdi-check-circle</v-icon>{{ $t('addAds2OrMore') }}</span>
                                    <!-- Во дворе -->
                                    <span v-if="$route.params.table_name == 'Dom'" @click="form.sanuzel = 'Во дворе'" class="form__item" :class="{'item__active':form.sanuzel == 'Во дворе'}"><v-icon color="green" v-if="form.sanuzel == 'Во дворе'">mdi-check-circle</v-icon>{{ $t('addAdsInTheCourtyard') }}</span>


                                    <!-- Офис | Есть -->
                                    <span v-if="$route.params.table_name == 'Ofis'" @click="form.sanuzel = 'Есть'" class="form__item" :class="{'item__active':form.sanuzel == 'Есть'}"><v-icon color="green" v-if="form.sanuzel == 'Есть'">mdi-check-circle</v-icon>{{ $t('addAdsThereAre') }}</span>
                                    <!-- 2 с/у и более -->
                                    <span v-if="$route.params.table_name == 'Ofis'" @click="form.sanuzel = '2 с/у и более'" class="form__item" :class="{'item__active':form.sanuzel == '2 с/у и более'}"><v-icon color="green" v-if="form.sanuzel == '2 с/у и более'">mdi-check-circle</v-icon>{{ $t('addAds2OrMore') }}</span>


                                    <!-- Для всех  -->
                                    <span @click="form.sanuzel = 'Нет'" class="form__item" :class="{'item__active':form.sanuzel == 'Нет'}"><v-icon color="green" v-if="form.sanuzel == 'Нет'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

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
                                    <span @click="form.otdelniy_vhod = 'Есть'" class="form__item" :class="{'item__active':form.otdelniy_vhod == 'Есть'}"><v-icon color="green" v-if="form.otdelniy_vhod == 'Есть'">mdi-check-circle</v-icon>{{ $t('addAdsThereAre') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.otdelniy_vhod = 'Нет'" class="form__item" :class="{'item__active':form.otdelniy_vhod == 'Нет'}"><v-icon color="green" v-if="form.otdelniy_vhod == 'Нет'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>


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
                                    <span @click="form.kanalizaciya = 'Центральная'" class="form__item" :class="{'item__active':form.kanalizaciya == 'Центральная'}"><v-icon color="green" v-if="form.kanalizaciya == 'Центральная'">mdi-check-circle</v-icon>{{ $t('addAdsCentral') }}</span>

                                    <!-- Септик -->
                                    <span @click="form.kanalizaciya = 'Септик'" class="form__item" :class="{'item__active':form.kanalizaciya == 'Септик'}"><v-icon color="green" v-if="form.kanalizaciya == 'Септик'">mdi-check-circle</v-icon>{{ $t('addAdsSepticTank') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.kanalizaciya = 'Нет'" class="form__item" :class="{'item__active':form.kanalizaciya == 'Нет'}"><v-icon color="green" v-if="form.kanalizaciya == 'Нет'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

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
                                    <span @click="form.voda = 'Центральная'" class="form__item" :class="{'item__active':form.voda == 'Центральная'}"><v-icon color="green" v-if="form.voda == 'Центральная'">mdi-check-circle</v-icon>{{ $t('addAdsCentral') }}</span>

                                    <!-- Скважина -->
                                    <span @click="form.voda = 'Скважина'" class="form__item" :class="{'item__active':form.voda == 'Скважина'}"><v-icon color="green" v-if="form.voda == 'Скважина'">mdi-check-circle</v-icon>{{ $t('addAdsBorehole') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.voda = 'Нет'" class="form__item" :class="{'item__active':form.voda == 'Нет'}"><v-icon color="green" v-if="form.voda == 'Нет'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

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
                                    <span @click="form.elektrichestvo = 'Есть'" class="form__item" :class="{'item__active':form.elektrichestvo == 'Есть'}"><v-icon color="green" v-if="form.elektrichestvo == 'Есть'">mdi-check-circle</v-icon>{{ $t('addAdsThereAre') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.elektrichestvo = 'Нет'" class="form__item" :class="{'item__active':form.elektrichestvo == 'Нет'}"><v-icon color="green" v-if="form.elektrichestvo == 'Нет'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

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
                                    <span @click="form.otoplenie = 'Центральное'" class="form__item" :class="{'item__active':form.otoplenie == 'Центральное'}"><v-icon color="green" v-if="form.otoplenie == 'Центральное'">mdi-check-circle</v-icon>{{ $t('addAdsCentralText') }}</span>

                                    <!-- На газе -->
                                    <span @click="form.otoplenie = 'На газе'" class="form__item" :class="{'item__active':form.otoplenie == 'На газе'}"><v-icon color="green" v-if="form.otoplenie == 'На газе'">mdi-check-circle</v-icon>{{ $t('addAdsOnGas') }}</span>

                                    <!-- На твердом топливе -->
                                    <span @click="form.otoplenie = 'На твердом топливе'" class="form__item" :class="{'item__active':form.otoplenie == 'На твердом топливе'}"><v-icon color="green" v-if="form.otoplenie == 'На твердом топливе'">mdi-check-circle</v-icon>{{ $t('addAdsOnSolidFuel') }}</span>

                                    <!-- На жидком топливе -->
                                    <span @click="form.otoplenie = 'На жидком топливе'" class="form__item" :class="{'item__active':form.otoplenie == 'На жидком топливе'}"><v-icon color="green" v-if="form.otoplenie == 'На жидком топливе'">mdi-check-circle</v-icon>{{ $t('addAdsOnLiquidFuel') }}</span>

                                    <!-- Смешанное -->
                                    <span @click="form.otoplenie = 'Смешанное'" class="form__item" :class="{'item__active':form.otoplenie == 'Смешанное'}"><v-icon color="green" v-if="form.otoplenie == 'Смешанное'">mdi-check-circle</v-icon>{{ $t('addAdsMixed') }}</span>

                                    <!-- Нет -->
                                    <span @click="form.otoplenie = 'Нет'" class="form__item" :class="{'item__active':form.otoplenie == 'Нет'}"><v-icon color="green" v-if="form.otoplenie == 'Нет'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

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
                                    <span @click="form.gaz = 'Магистральный'" class="form__item" :class="{'item__active':form.gaz == 'Магистральный'}"><v-icon color="green" v-if="form.gaz == 'Магистральный'">mdi-check-circle</v-icon>{{ $t('addAdsTrunk') }}</span>

                                    <!-- Автономный -->
                                    <span @click="form.gaz = 'Автономный'" class="form__item" :class="{'item__active':form.gaz == 'Автономный'}"><v-icon color="green" v-if="form.gaz == 'Автономный'">mdi-check-circle</v-icon>{{ $t('addAdsAutonomous') }}</span>

                                    <!--Нет -->
                                    <span @click="form.gaz = 'Нет'" class="form__item" :class="{'item__active':form.gaz == 'Нет'}"><v-icon color="green" v-if="form.gaz == 'Нет'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

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
                                    <span @click="form.balkon = 'Балкон'" class="form__item" :class="{'item__active':form.balkon == 'Балкон'}"><v-icon color="green" v-if="form.balkon == 'Балкон'">mdi-check-circle</v-icon>{{ $t('addAdsBalcony') }}</span>

                                    <!-- Лоджия -->
                                    <span @click="form.balkon = 'Лоджия'" class="form__item" :class="{'item__active':form.balkon == 'Лоджия'}"><v-icon color="green" v-if="form.balkon == 'Лоджия'">mdi-check-circle</v-icon>{{ $t('addAdsLoggia') }}</span>

                                    <!-- Балкон и Лоджия -->
                                    <span @click="form.balkon = '2 балкона и более'" class="form__item" :class="{'item__active':form.balkon == '2 балкона и более'}"><v-icon color="green" v-if="form.balkon == '2 балкона и более'">mdi-check-circle</v-icon>{{ $t('addAds2BalconiesOrMore') }}</span>

                                    <!-- Несколько балконов и лоджий -->
                                    <span @click="form.balkon = 'Нет'" class="form__item" :class="{'item__active':form.balkon == 'Нет'}"><v-icon color="green" v-if="form.balkon == 'Нет'">mdi-check-circle</v-icon>{{ $t('addAdsNo') }}</span>

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
                                <span @click="checkArr('mebel_arr', 'Холодильник')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Холодильник')}"><v-icon color="green" v-if="form.mebel_arr.includes('Холодильник')">mdi-check-circle</v-icon>{{ $t('addAdsFridge')}}</span>
                                <span @click="checkArr('mebel_arr', 'Стиральная машина')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Стиральная машина')}"><v-icon color="green" v-if="form.mebel_arr.includes('Стиральная машина')">mdi-check-circle</v-icon>{{ $t('addAdsWasher')}}</span>
                                <span @click="checkArr('mebel_arr', 'Телевизор')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Телевизор')}"><v-icon color="green" v-if="form.mebel_arr.includes('Телевизор')">mdi-check-circle</v-icon>{{ $t('addAdsTV')}}</span>
                                <span @click="checkArr('mebel_arr', 'Диван')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Диван')}"><v-icon color="green" v-if="form.mebel_arr.includes('Диван')">mdi-check-circle</v-icon>{{ $t('addAdsSofa')}}</span>
                                <span @click="checkArr('mebel_arr', 'Кровать')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Кровать')}"><v-icon color="green" v-if="form.mebel_arr.includes('Кровать')">mdi-check-circle</v-icon>{{ $t('addAdsBed')}}</span>
                                <span @click="checkArr('mebel_arr', 'Кухонный гарнитур')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Кухонный гарнитур')}"><v-icon color="green" v-if="form.mebel_arr.includes('Кухонный гарнитур')">mdi-check-circle</v-icon>{{ $t('addAdsKitchenSet')}}</span>
                                <span @click="checkArr('mebel_arr', 'Кондиционер')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Кондиционер')}"><v-icon color="green" v-if="form.mebel_arr.includes('Кондиционер')">mdi-check-circle</v-icon>{{ $t('addAdsConditioner')}}</span>
                                <span @click="checkArr('mebel_arr', 'Микроволновая печь')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Микроволновая печь')}"><v-icon color="green" v-if="form.mebel_arr.includes('Микроволновая печь')">mdi-check-circle</v-icon>{{ $t('addAdsAMicrowave')}}</span>
                                <span @click="checkArr('mebel_arr', 'Вся бытовая техника')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Вся бытовая техника')}"><v-icon color="green" v-if="form.mebel_arr.includes('Вся бытовая техника')">mdi-check-circle</v-icon>{{ $t('addAdsAllHouseholdAppliances')}}</span>
                            </div>

                            <div class="form__input" v-if="$route.params.table_name == 'Ofis' || $route.params.table_name == 'Magazin'">
                                <span @click="checkArr('mebel_arr', 'Кондиционер')" class="form__item" :class="{'item__active':form.mebel_arr.includes('Кондиционер')}"><v-icon color="green" v-if="form.mebel_arr.includes('Кондиционер')">mdi-check-circle</v-icon>{{ $t('addAdsConditioner')}}</span>
                            </div>

                        </div>


                        <!-- Разное -->
                        <div v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom'|| $route.params.table_name == 'Ofis' || $route.params.table_name == 'Magazin' || $route.params.table_name == 'Prombaza'" class="form-group">

                            <div class="form__title">{{ $t('addAdsVarious') }}</div>

                            <!-- Разное -  Для квартиры - Общежития - Дом  -->
                            <div class="form__input" v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie'">
                                <span @click="checkArr('raznoe', 'Кухня-студия')" class="form__item" :class="{'item__active':form.raznoe.includes('Кухня-студия')}"><v-icon color="green" v-if="form.raznoe.includes('Кухня-студия')">mdi-check-circle</v-icon>{{ $t('addAdsStudioKitchen')}}</span>
                                <span @click="checkArr('raznoe', 'Комнаты изолированны')" class="form__item" :class="{'item__active':form.raznoe.includes('Комнаты изолированны')}"><v-icon color="green" v-if="form.raznoe.includes('Комнаты изолированны')">mdi-check-circle</v-icon>{{ $t('addAdsTheRoomsAreIsolated')}}</span>
                                <span @click="checkArr('raznoe', 'Кабельное TV')"  class="form__item" :class="{'item__active':form.raznoe.includes('Кабельное TV')}"><v-icon color="green" v-if="form.raznoe.includes('Кабельное TV')">mdi-check-circle</v-icon>{{ $t('addAdsCableTV')}}</span>
                                <span @click="checkArr('raznoe', 'Кладовка')" class="form__item" :class="{'item__active':form.raznoe.includes('Кладовка')}"><v-icon color="green" v-if="form.raznoe.includes('Кладовка')">mdi-check-circle</v-icon>{{ $t('addAdsStorage')}}</span>
                                <span @click="checkArr('raznoe', 'Бесплатный WI-FI')"  class="form__item" :class="{'item__active':form.raznoe.includes('Бесплатный WI-FI')}"><v-icon color="green" v-if="form.raznoe.includes('Бесплатный WI-FI')">mdi-check-circle</v-icon>{{ $t('addAdsFreeWI-FI') }}</span>
                            </div>

                            <!-- Разное - Дом-->
                            <div class="form__input" v-if="$route.params.table_name == 'Dom'">
                                <span @click="checkArr('raznoe', 'Сад')" class="form__item" :class="{'item__active':form.raznoe.includes('Сад')}"><v-icon color="green" v-if="form.raznoe.includes('Сад')">mdi-check-circle</v-icon>{{ $t('addAdsGarden')}}</span>
                                <span @click="checkArr('raznoe', 'Баня')" class="form__item" :class="{'item__active':form.raznoe.includes('Баня')}"><v-icon color="green" v-if="form.raznoe.includes('Баня')">mdi-check-circle</v-icon>{{ $t('addAdsBathhouse')}}</span>
                                <span @click="checkArr('raznoe', 'Бассейн')" class="form__item" :class="{'item__active':form.raznoe.includes('Бассейн')}"><v-icon color="green" v-if="form.raznoe.includes('Бассейн')">mdi-check-circle</v-icon>{{ $t('addAdsSwimmingPool')}}</span>
                                <span @click="checkArr('raznoe', 'Сауна')" class="form__item" :class="{'item__active':form.raznoe.includes('Сауна')}"><v-icon color="green" v-if="form.raznoe.includes('Сауна')">mdi-check-circle</v-icon>{{ $t('addAdsSauna')}}</span>
                                <span @click="checkArr('raznoe', 'Джакузи')" class="form__item" :class="{'item__active':form.raznoe.includes('Джакузи')}"><v-icon color="green" v-if="form.raznoe.includes('Джакузи')">mdi-check-circle</v-icon>{{ $t('addAdsJacuzzi')}}</span>
                                <span @click="checkArr('raznoe', 'Кухня-студия')" class="form__item" :class="{'item__active':form.raznoe.includes('Кухня-студия')}"><v-icon color="green" v-if="form.raznoe.includes('Кухня-студия')">mdi-check-circle</v-icon>{{ $t('addAdsStudioKitchen')}}</span>
                                <span @click="checkArr('raznoe', 'Комнаты изолированны')" class="form__item" :class="{'item__active':form.raznoe.includes('Комнаты изолированны')}"><v-icon color="green" v-if="form.raznoe.includes('Комнаты изолированны')">mdi-check-circle</v-icon>{{ $t('addAdsTheRoomsAreIsolated')}}</span>
                                <span @click="checkArr('raznoe', 'Кабельное TV')" class="form__item" :class="{'item__active':form.raznoe.includes('Кабельное TV')}"><v-icon color="green" v-if="form.raznoe.includes('Кабельное TV')">mdi-check-circle</v-icon>{{ $t('addAdsCableTV')}}</span>
                                <span @click="checkArr('raznoe', 'Хозпостройки')" class="form__item" :class="{'item__active':form.raznoe.includes('Хозпостройки')}"><v-icon color="green" v-if="form.raznoe.includes('Хозпостройки')">mdi-check-circle</v-icon>{{ $t('addAdsOutbuildings')}}</span>
                                <span @click="checkArr('raznoe', '3 Фазы')" class="form__item" :class="{'item__active':form.raznoe.includes('3 Фазы')}"><v-icon color="green" v-if="form.raznoe.includes('3 Фазы')">mdi-check-circle</v-icon>{{ $t('addAds3Phases')}}</span>
                            </div>

                            <!-- Разное - Магазин -->
                            <div class="form__input" v-if="$route.params.table_name == 'Magazin'">
                                <span @click="checkArr('raznoe', 'С товаром')"  class="form__item" :class="{'item__active':form.raznoe.includes('С товаром')}"><v-icon color="green" v-if="form.raznoe.includes('С товаром')">mdi-check-circle</v-icon>{{ $t('addAdsWithTheProduct')}}</span>
                                <span @click="checkArr('raznoe', 'С торговым оборудованием')"  class="form__item" :class="{'item__active':form.raznoe.includes('С торговым оборудованием')}"><v-icon color="green" v-if="form.raznoe.includes('С торговым оборудованием')">mdi-check-circle</v-icon>{{ $t('addAdsWithCommercialEquipment')}}</span>
                            </div>

                            <!-- Разное - Промбаза -->
                            <div class="form__input" v-if="$route.params.table_name == 'Prombaza'">
                                <span @click="checkArr('raznoe', 'Ж/д тупик')" class="form__item" :class="{'item__active':form.raznoe.includes('Ж/д тупик')}"><v-icon color="green" v-if="form.raznoe.includes('Ж/д тупик')">mdi-check-circle</v-icon>{{ $t('addAdsRailwayDeadEnd') }}</span>
                                <span @click="checkArr('raznoe', 'Автовесы')"  class="form__item" :class="{'item__active':form.raznoe.includes('Автовесы')}"><v-icon color="green" v-if="form.raznoe.includes('Автовесы')">mdi-check-circle</v-icon>{{ $t('addAdsCarWeights')}}</span>
                                <span @click="checkArr('raznoe', 'Холодильные камеры')"  class="form__item" :class="{'item__active':form.raznoe.includes('Холодильные камеры')}"><v-icon color="green" v-if="form.raznoe.includes('Холодильные камеры')">mdi-check-circle</v-icon>{{ $t('addAdsColdStorageRooms')}}</span>
                                <span @click="checkArr('raznoe', 'Кран-балка')" class="form__item" :class="{'item__active':form.raznoe.includes('Кран-балка')}"><v-icon color="green" v-if="form.raznoe.includes('Кран-балка')">mdi-check-circle</v-icon>{{ $t('addAdsCraneBeam')}}</span>
                                <span @click="checkArr('raznoe', 'Пандус')" class="form__item" :class="{'item__active':form.raznoe.includes('Пандус')}"><v-icon color="green" v-if="form.raznoe.includes('Пандус')">mdi-check-circle</v-icon>{{ $t('addAdsRamp') }}</span>
                                <span @click="checkArr('raznoe', 'Своя подстанция')" class="form__item" :class="{'item__active':form.raznoe.includes('Своя подстанция')}"><v-icon color="green" v-if="form.raznoe.includes('Своя подстанция')">mdi-check-circle</v-icon>{{ $t('addAdsOwnSubstation')}}</span>
                            </div>

                        </div>


                        <!-- Расположение - промбаза, прочее -->
                        <div v-if="$route.params.table_name == 'Business' || $route.params.table_name == 'Prombaza' || $route.params.table_name == 'Prochaya'" class="form-group">

                            <div class="form__title">{{ $t('addAdsLocation') }}</div>

                            <div class="form__input">
                                <span @click="checkArr('raspolojenie', 'В городе')" class="form__item" :class="{'item__active':form.raspolojenie.includes('В городе')}"><v-icon color="green" v-if="form.raspolojenie.includes('В городе')">mdi-check-circle</v-icon>{{ $t('addAdsInTheCity') }}</span>
                                <span @click="checkArr('raspolojenie', 'В пригороде')" class="form__item" :class="{'item__active':form.raspolojenie.includes('В пригороде')}"><v-icon color="green" v-if="form.raspolojenie.includes('В пригороде')">mdi-check-circle</v-icon>{{ $t('addAdsInTheSuburbs')}}</span>
                                <span @click="checkArr('raspolojenie', 'Вдоль трассы')" class="form__item" :class="{'item__active':form.raspolojenie.includes('Вдоль трассы')}"><v-icon color="green" v-if="form.raspolojenie.includes('Вдоль трассы')">mdi-check-circle</v-icon>{{ $t('addAdsAlongTheHighway') }}</span>
                            </div>
                        </div>

                        <!-- Коммуникации - промбаза -->
                        <div v-if="$route.params.table_name == 'Business' || $route.params.table_name == 'Prombaza' || $route.params.table_name == 'Prochaya'" class="form-group">

                            <!-- Заголовок -->
                            <div class="form__title">{{ $t('addAdsCommunications') }}</div>

                            <div class="form__input">
                                <span @click="checkArr('kommunikacii', 'Свет')" class="form__item" :class="{'item__active':form.kommunikacii.includes('Свет')}"><v-icon color="green" v-if="form.kommunikacii.includes('Свет')">mdi-check-circle</v-icon>{{ $t('addAdsLight') }}</span>
                                <span @click="checkArr('kommunikacii', 'Газ')" class="form__item" :class="{'item__active':form.kommunikacii.includes('Газ')}"><v-icon color="green" v-if="form.kommunikacii.includes('Газ')">mdi-check-circle</v-icon>{{ $t('addAdsGas') }}</span>
                                <span @click="checkArr('kommunikacii', 'Интернет')"  class="form__item" :class="{'item__active':form.kommunikacii.includes('Интернет')}"><v-icon color="green" v-if="form.kommunikacii.includes('Интернет')">mdi-check-circle</v-icon>{{ $t('addAdsTheInternet') }}</span>
                                <span @click="checkArr('kommunikacii', 'Отопление')"  class="form__item" :class="{'item__active':form.kommunikacii.includes('Отопление')}"><v-icon color="green" v-if="form.kommunikacii.includes('Отопление')">mdi-check-circle</v-icon>{{ $t('addAdsHeating') }}</span>
                                <span @click="checkArr('kommunikacii', 'Вода')"  class="form__item" :class="{'item__active':form.kommunikacii.includes('Вода')}"><v-icon color="green" v-if="form.kommunikacii.includes('Вода')">mdi-check-circle</v-icon>{{ $t('addAdsWater') }}</span>
                                <span @click="checkArr('kommunikacii', 'Телефон')" class="form__item"  :class="{'item__active':form.kommunikacii.includes('Телефон')}"><v-icon color="green" v-if="form.kommunikacii.includes('Телефон')">mdi-check-circle</v-icon>{{ $t('addAdsTelephone') }}</span>
                                <span @click="checkArr('kommunikacii', 'Канализация')" class="form__item" :class="{'item__active':form.kommunikacii.includes('Канализация')}"><v-icon color="green" v-if="form.kommunikacii.includes('Канализация')">mdi-check-circle</v-icon>{{ $t('addAdsSewageSystem') }}</span>
                            </div>

                        </div>

                        <!-- Безопасность -->
                        <div v-if="$route.params.table_name == 'Kvartira' || $route.params.table_name == 'Obshejitie' || $route.params.table_name == 'Dom' || $route.params.table_name == 'Ofis' || $route.params.table_name == 'Zdanie' || $route.params.table_name == 'Magazin' || $route.params.table_name == 'Snimu'" class="form-group">

                            <div class="form__title">{{ $t('addAdsSecurity') }}</div>

                            <div class="form__input">
                                <span @click="checkArr('bezopasnost', 'Домофон')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Домофон')}"><v-icon color="green" v-if="form.bezopasnost.includes('Домофон')">mdi-check-circle</v-icon>{{ $t('addAdsIntercom') }}</span>
                                <span @click="checkArr('bezopasnost', 'Видеодомофон')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Видеодомофон')}"><v-icon color="green" v-if="form.bezopasnost.includes('Видеодомофон')">mdi-check-circle</v-icon>{{ $t('addAdsVideoIntercom') }}</span>
                                <span @click="checkArr('bezopasnost', 'Сигнализация')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Сигнализация')}"><v-icon color="green" v-if="form.bezopasnost.includes('Сигнализация')">mdi-check-circle</v-icon>{{ $t('addAdsAlarmSystem') }}</span>
                                <span @click="checkArr('bezopasnost', 'Решетки на окнах')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Решетки на окнах')}"><v-icon color="green" v-if="form.bezopasnost.includes('Решетки на окнах')">mdi-check-circle</v-icon>{{ $t('addAdsBarsOnTheWindows') }}</span>
                                <span @click="checkArr('bezopasnost', 'Охрана')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Охрана')}"><v-icon color="green" v-if="form.bezopasnost.includes('Охрана')">mdi-check-circle</v-icon>{{ $t('addAdsSecurity2') }}</span>
                                <span @click="checkArr('bezopasnost', 'Видеонаблюдение')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Видеонаблюдение')}"><v-icon color="green" v-if="form.bezopasnost.includes('Видеонаблюдение')">mdi-check-circle</v-icon>{{ $t('addAdsVideoSurveillance') }}</span>
                                <span @click="checkArr('bezopasnost', 'Кодовый замок')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Кодовый замок')}"><v-icon color="green" v-if="form.bezopasnost.includes('Кодовый замок')">mdi-check-circle</v-icon>{{ $t('addAdsCombinationLock') }}</span>
                                <span @click="checkArr('bezopasnost', 'Консьерж')" class="form__item" :class="{'item__active':form.bezopasnost.includes('Консьерж')}"><v-icon color="green" v-if="form.bezopasnost.includes('Консьерж')">mdi-check-circle</v-icon>{{ $t('addAdsConcierge') }}</span>
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
                                    <span @click="form.cena_tip = 'За все'" class="form__item" :class="{'item__active':form.cena_tip == 'За все'}"><v-icon color="green" v-if="form.cena_tip == 'За все'">mdi-check-circle</v-icon>{{ $t('addAdsForEverything') }}</span>
                                    <span @click="form.cena_tip = 'За кв.м'" class="form__item" :class="{'item__active':form.cena_tip == 'За кв.м'}"><v-icon color="green" v-if="form.cena_tip == 'За кв.м'">mdi-check-circle</v-icon>{{ $t('addAdsPerSqM') }}</span>
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

                        <!-- Вы хозяин / Риэлтор -->
                        <div class="form-group">

                            <!-- Input -->
                            <validation-provider rules="required" v-model="form.ownerOrRealtor" name="ownerOrRealtor" v-slot="{ errors }">

                                <div v-if="$route.params.table_name == 'Snimu'" class="alert alert-success py-1 text-body-1">{{ $t('addAdsRealtorText') }}</div>
                                <div class="form__input my-3">
                                    <span v-if="$route.params.table_name != 'Snimu'" @click="form.ownerOrRealtor = 'Хозяин'" class="form__item" :class="{'item__active' : form.ownerOrRealtor == 'Хозяин'}"><v-icon color="green" v-if="form.ownerOrRealtor == 'Хозяин'">mdi-check-circle</v-icon>{{ $t('addAdsIAmTheOwner') }} </span>
                                    <span v-if="$route.params.table_name != 'Snimu'" @click="form.ownerOrRealtor = 'Специалист'" class="form__item" :class="{'item__active' : form.ownerOrRealtor == 'Специалист'}"><v-icon color="green" v-if="form.ownerOrRealtor == 'Специалист'">mdi-check-circle</v-icon>{{ $t('addAdsIAmSpecialist') }} </span>
                                    <span v-if="$route.params.table_name == 'Snimu'" @click="form.ownerOrRealtor = 'Можно от специалиста'" class="form__item" :class="{'item__active' : form.ownerOrRealtor == 'Можно от специалиста'}"><v-icon color="green" v-if="form.ownerOrRealtor == 'Можно от специалиста'">mdi-check-circle</v-icon>{{ $t('addAdsItIsPossibleFromASpecialist') }} </span>
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
                if(this.updateDateLocaleStore.lang == 'kz')return 'Баға / айына'
                if(this.updateDateLocaleStore.lang == 'en')return 'Price / Per month'
            }
            if(this.form.period_arendi == 'Посуточно'){
                if(this.updateDateLocaleStore.lang == 'ru')return 'Цена / В сутки'
                if(this.updateDateLocaleStore.lang == 'kz')return 'Баға / тәулігіне'
                if(this.updateDateLocaleStore.lang == 'en')return 'Price / Per day'
            }
            if(this.form.period_arendi == 'По часам'){
                if(this.updateDateLocaleStore.lang == 'ru')return 'Цена / В час'
                if(this.updateDateLocaleStore.lang == 'kz')return 'Баға / сағатына'
                if(this.updateDateLocaleStore.lang == 'en')return 'Price / Per hour'
            }

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

})

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


/*Добавим стили для полей vuetify - textarea и для label*/
::v-deep(.v-textarea--auto-grow .v-field__input) {
    overflow-y: auto!important;
}
/*Сделать текст label жирным */
::v-deep(.v-label.v-field-label) {
    font-size: 0.8em;
}

</style>
