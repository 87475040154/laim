<template>
    <!-- КОМПОНЕНТ - ВЫБОР ЛОКАЦИИ -->

    <!-- Backdrop -->
    <transition name="location__animation-backdrop">

        <!-- Backdrop -->
        <div v-if="locationAnimation" class="location__backdrop" :class="locationWrapperClass" @click="$router.back()"></div>

    </transition>

    <!-- Обвертка - Компонента -->
    <transition name="location__animation-wrapper">

        <!-- Обвертка - Компонента -->
        <div v-if="locationAnimation" class="location__wrapper">

            <!-- Сам блок -->
            <div class="location__block">

                <!-- Header - Заголовок -->
                <div class="location__header">

                    <!-- Кнопка Закрыть окно -->
                    <v-btn icon variant="text" class="mx-2" dark @click="$router.back()">
                        <v-icon>mdi-arrow-left</v-icon>
                    </v-btn>

                    <!-- Иконка и текст местоположение -->
                    <span v-if="$route.params.oblast == 'null' " class="text-body-1">{{ $t('KZLocationLocation') }}</span>
                    <span v-else-if="$route.params.oblast != 'null' && $route.params.gorod == 'null'" class="text-body-1">
                        {{ $t('KZLocationSelectACity') }}
                    </span>
                    <span v-else class="text-body-1">
                        {{ $t('KZLocationSelectDistrict') }}
                    </span>

                    <v-spacer></v-spacer>

                </div>

                <!-- Body - Блок с локациями вывод -->
                <div class="location__body p-2">

                    <!-- Поле поиск По стране, городу, району -->
                    <div>
                        <v-text-field
                            v-model="poisk" name="name"
                            :label="$t('KZLocationSearchByCityArea')"
                            type="text" maxlength="50"
                            prepend-inner-icon="mdi-magnify"
                            variant="outlined" color="blue-darken-2"
                            clearable clear-icon="mdi-close-circle"
                            @click:clear="poisk = '', filteredResults = []"
                            @input="filterLocations"
                        ></v-text-field>

                        <div v-if="filteredResults.length" class="py-2" v-for="(item, index) in filteredResults" :key="index">
                            <div>
                                <div role="button" @click="item.type === 'gorod' ? showRaion(item.oblast, item.gorod): addSelectedLocation({oblast: item.oblast, gorod: item.gorod, raion:item.raion})"
                                     class="d-flex align-items-center justify-content-between w-100"
                                >
                                    <div class="flex-grow-1 me-2">
                                        {{ item.type === 'gorod' ? KZLocationStore.translateLocation({gorod: item.gorod}).gorod : KZLocationStore.translateLocation({raion: item.raion}).raion }} <!-- Показываем название района или города -->
                                        <div class="text-grey">
                                        <span v-if="item.type === 'gorod'">
                                            {{ KZLocationStore.translateLocation({oblast: item.oblast}).oblast }}
                                        </span>
                                            <span v-else-if="item.type === 'raion'">
                                            {{ KZLocationStore.translateLocation({gorod: item.gorod}).gorod }}
                                        </span>
                                        </div>
                                    </div>
                                    <!-- Проверяем, есть ли районы у текущего города -->
                                    <v-icon v-if="item.type === 'gorod' && locations.oblasty[item.oblast]?.goroda[item.gorod]?.raiony?.length" class="mdi mdi-chevron-right"></v-icon>
                                </div>

                                <v-divider v-if="index < filteredResults.length - 1" class="my-2" />
                            </div>
                        </div>

                    </div>


                    <!-- Выводим Популярные Города -->
                    <div v-if="$route.params.oblast == 'null' && poisk == ''">

                        <div class="pb-3 text-muted">{{ $t('KZLocationPopularCities') }}</div>

                        <div v-for="(oblast, oblastKey) in locations.oblasty" :key="oblastKey" class="text-body-1">

                            <div v-for="(city, cityKey) in oblast.goroda" :key="cityKey">

                                <div class="py-2" v-if="popular_cities.includes(cityKey)">
                                    <div role="button" @click="showRaion(oblastKey, cityKey)" class="d-flex align-items-center justify-content-between w-100">
                                    <span class="flex-grow-1 me-2">
                                      {{ KZLocationStore.translateLocation({ gorod: cityKey }).gorod }}
                                    </span>

                                        <!-- Проверяем, есть ли районы у текущего города -->
                                        <v-icon v-if="city.raiony && city.raiony.length" class="mdi mdi-chevron-right"></v-icon>
                                    </div>
                                </div>

                                <v-divider class="m-0" v-if="popular_cities.includes(cityKey)"></v-divider>

                            </div>

                        </div>

                    </div>

                    <!-- Выводим 17 областей Казахстана -->
                    <div v-if="$route.params.oblast == 'null' && poisk == ''">
                        <div class="py-3 text-muted">{{ $t('KZLocationAllRegionsOfKazakhstan') }}</div>
                        <div v-for="(value, key ) in locations.oblasty" :key="key" class="text-body-1">

                            <div class="py-2" v-if="!['Астана', 'Алматы','Шымкент'].includes(key)">
                                <div role="button"
                                     @click="showGoroda(key)"
                                     class="d-flex align-items-center justify-content-between w-100">
                                    <span class="flex-grow-1 me-2">{{ KZLocationStore.translateLocation({oblast: key}).oblast }}</span>
                                    <v-icon>mdi-chevron-right</v-icon>
                                </div>
                            </div>

                            <v-divider class="m-0" v-if="!['Астана', 'Алматы','Шымкент'].includes(key)"></v-divider>

                        </div>
                    </div>

                    <!-- Выводим Города -->
                    <div v-if="$route.params.oblast != 'null' && $route.params.gorod == 'null' && poisk == ''"
                         v-for="(gorodData, gorod) in locations.oblasty[$route.params.oblast].goroda"
                         :key="gorod" class="text-body-1"
                    >

                        <div class="py-2">
                            <div role="button" @click="showRaion($route.params.oblast, gorod)" class="d-flex align-items-center justify-content-between w-100">
                                <span class="flex-grow-1 me-2">{{ KZLocationStore.translateLocation({gorod: gorod}).gorod }}</span>
                                <v-icon v-if="locations.oblasty[$route.params.oblast]?.goroda[gorod]?.raiony?.length">mdi-chevron-right</v-icon>
                            </div>
                        </div>

                        <v-divider class="m-0"></v-divider>

                    </div>

                    <!-- Выводим Районы -->
                    <div v-if="$route.params.gorod != 'null' && poisk == ''">
                        <div v-for="(raion, index) in locations.oblasty[$route.params.oblast].goroda[$route.params.gorod].raiony" :key="index" class="text-body-1">
                            <div class="py-2">
                                <div role="button" @click="addSelectedLocation({oblast: $route.params.oblast, gorod: $route.params.gorod, raion: raion })">
                                    <span>{{ KZLocationStore.translateLocation({raion: raion}).raion }}</span>
                                </div>
                            </div>
                            <v-divider class="m-0"></v-divider>
                        </div>

                    </div>


                </div>

                <!-- Footer - Кнопка не указывать район -->
                <div v-if="$route.params.gorod != 'null' && poisk == ''" class="location__footer">
                    <!-- Кнопка - Не указывать район -->
                    <v-btn variant="outlined" color="blue" class="w-100 font-weight-bold"
                           @click="addSelectedLocation({oblast: $route.params.oblast, gorod: $route.params.gorod, raion: null })"
                    >
                        {{ $t('KZLocationDoNotSpecifyArea') }}
                    </v-btn>
                </div>

            </div>

        </div>

    </transition>

</template>

<script>

//Импортирую Store - Наше общее состояние
import {useKZLocationStore} from "../../stores/KZLocation";

export default {
    name: 'KZLocation',

    data(){
        return {

            //Подключаю Store - Наше общее состояние
            KZLocationStore: useKZLocationStore(),
            locationAnimation: false,

            popular_cities:['Астана', 'Алматы', 'Шымкент', 'Актобе', 'Караганда', 'Павлодар', 'Усть-Каменогорск', 'Семей', 'Атырау', 'Костанай'],
            locations: {
                "oblasty": {
                    "Алматы":{
                        "goroda" : {
                            "Алматы": {
                                "raiony": ["Алатауский р-н", "Алмалинский р-н", "Ауэзовский р-н", "Бостандыкский р-н", "Жетысуский р-н", "Медеуский р-н", "Наурызбайский р-н", "Турксибский р-н"],
                            }
                        }
                    },
                    "Астана": {
                        "goroda": {
                            "Астана": {
                                "raiony": ["Алматы р-н", "Есильский р-н", "Нура р-н", "Сарыарка р-н", "р-н Байконур", "Сарайшык р-н"],
                            }
                        }
                    },
                    "Шымкент": {
                        "goroda":{
                            "Шымкент": {
                                "raiony": ["Абайский р-н", "Аль-Фарабийский р-н", "Енбекшинский р-н", "Каратауский р-н", "Туран р-н"],
                            }
                        }
                    },
                    "Абай обл." : {
                        "goroda" : {
                            "Семей": {
                                "raiony": ["мкр Красный Кордон"]
                            },
                            "Акжар": {
                                "raiony": ""
                            },
                            "Аягоз": {
                                "raiony": ""
                            },
                            "Букенчи": {
                                "raiony": ""
                            },
                            "Жангизтобе": {
                                "raiony": ""
                            },
                            "Зенковка": {
                                "raiony": ""
                            },
                            "Кабанбай": {
                                "raiony": ""
                            },
                            "Камышенка": {
                                "raiony": ""
                            },
                            "Караул": {
                                "raiony": ""
                            },
                            "Курчатов": {
                                "raiony": ""
                            },
                            "Маканчи": {
                                "raiony": ""
                            },
                            "Озерки": {
                                "raiony": ""
                            },
                            "Урджар": {
                                "raiony": ""
                            }
                        }
                    },
                    "Акмолинская обл.": {
                        "goroda": {
                            "Кокшетау": { "raiony": [] },
                            "Щучинск": { "raiony": [] },
                            "Азат": { "raiony": [] },
                            "Акбулак": { "raiony": [] },
                            "Аккайын": { "raiony": [] },
                            "Акмечеть": { "raiony": [] },
                            "Акколь": { "raiony": [] },
                            "Акколь (свхз.Кокчетавский)": { "raiony": [] },
                            "Акылбай": { "raiony": [] },
                            "Алексеевка": { "raiony": [] },
                            "Алтынды": { "raiony": [] },
                            "Анар": { "raiony": [] },
                            "Акимовка": { "raiony": [] },
                            "Акмол": { "raiony": [] },
                            "Арайлы": { "raiony": [] },
                            "Арнасай": { "raiony": [] },
                            "Аршалы": { "raiony": [] },
                            "Арыкты": { "raiony": [] },
                            "Астраханка": { "raiony": [] },
                            "Атамекен": { "raiony": [] },
                            "Атбасар": { "raiony": [] },
                            "Бозайгыр": { "raiony": [] },
                            "Балкашино": { "raiony": [] },
                            "Бабатай": { "raiony": [] },
                            "Бестобе": { "raiony": [] },
                            "Бектау": { "raiony": [] },
                            "Бирлестик": { "raiony": [] },
                            "Бирсуат": { "raiony": [] },
                            "Борисовка": { "raiony": [] },
                            "Булаксай": { "raiony": [] },
                            "Бурабай (Боровое)": { "raiony": [] },
                            "Гранитный": { "raiony": [] },
                            "Воздвиженка": { "raiony": [] },
                            "Веденовка": { "raiony": [] },
                            "Волгодоновка": { "raiony": [] },
                            "Державинск": { "raiony": [] },
                            "Дамса": { "raiony": [] },
                            "Домбыралы": { "raiony": [] },
                            "Егиндыколь": { "raiony": [] },
                            "Еленовка": { "raiony": [] },
                            "Еликти": { "raiony": [] },
                            "Енбек": { "raiony": [] },
                            "Ерейментау": { "raiony": [] },
                            "Есиль": { "raiony": [] },
                            "Ески Калкутан": { "raiony": [] },
                            "Жаксы": { "raiony": [] },
                            "Жалтыр": { "raiony": [] },
                            "Жалтырколь": { "raiony": [] },
                            "Жангызкудук": { "raiony": [] },
                            "Жаркайын": { "raiony": [] },
                            "Жарлыколь": { "raiony": [] },
                            "Жибек Жолы": { "raiony": [] },
                            "Жолымбет": { "raiony": [] },
                            "Жукей": { "raiony": [] },
                            "Заводской": { "raiony": [] },
                            "Зеленый бор": { "raiony": [] },
                            "Зеренда": { "raiony": [] },
                            "Златополье": { "raiony": [] },
                            "Ивановка": { "raiony": [] },
                            "Ижевское": { "raiony": [] },
                            "Кабанбай Батыра": { "raiony": [] },
                            "Кажымукан": { "raiony": [] },
                            "Каменка": { "raiony": [] },
                            "Карагай": { "raiony": [] },
                            "Каражар": { "raiony": [] },
                            "Караоткель": { "raiony": [] },
                            "Карасай": { "raiony": [] },
                            "Карашилик": { "raiony": [] },
                            "Карловка": { "raiony": [] },
                            "Катарколь": { "raiony": [] },
                            "Киима": { "raiony": [] },
                            "Кенесары": { "raiony": [] },
                            "Ключевое": { "raiony": [] },
                            "Койгельды": { "raiony": [] },
                            "Константиновка": { "raiony": [] },
                            "Конысбай": { "raiony": [] },
                            "Коргалжын": { "raiony": [] },
                            "Косшы": { "raiony": [] },
                            "Костомар": { "raiony": [] },
                            "Коянды": { "raiony": [] },
                            "Красный Кордон": { "raiony": [] },
                            "Красный яр": { "raiony": [] },
                            "Куйгенжар": { "raiony": [] },
                            "Кызылсуат": { "raiony": [] },
                            "Кызылжулдуз": { "raiony": [] },
                            "Кызылжар": { "raiony": [] },
                            "Николаевка": { "raiony": [] },
                            "Никольское": { "raiony": [] },
                            "Новорыбинка": { "raiony": [] },
                            "Новоишимка": { "raiony": [] },
                            "Новокубанка": { "raiony": [] },
                            "Новочеркасское": { "raiony": [] },
                            "Нурлы": { "raiony": [] },
                            "Макинка": { "raiony": [] },
                            "Макинск": { "raiony": [] },
                            "Максимовка": { "raiony": [] },
                            "Малотимофеевка": { "raiony": [] },
                            "Маншук": { "raiony": [] },
                            "Мариновка": { "raiony": [] },
                            "Михайловка": { "raiony": [] },
                            "Научный": { "raiony": [] },
                            "Новомарковка": { "raiony": [] },
                            "Озен": { "raiony": [] },
                            "Окжетпес": { "raiony": [] },
                            "Ольгинка": { "raiony": [] },
                            "Оразак": { "raiony": [] },
                            "Оркен": { "raiony": [] },
                            "Отемис": { "raiony": [] },
                            "Отрадное": { "raiony": [] },
                            "Первомайка": { "raiony": [] },
                            "Петровка": { "raiony": [] },
                            "Пригородное": { "raiony": [] },
                            "Приречное": { "raiony": [] },
                            "Разъезд 96 (с.им. Ыбырая Алтынсарина)": { "raiony": [] },
                            "Раевка": { "raiony": [] },
                            "Рахымжана Кошкарбаева": { "raiony": [] },
                            "Родники": { "raiony": [] },
                            "Сабынды": { "raiony": [] },
                            "Самарка": { "raiony": [] },
                            "село Сарыколь": { "raiony": [] },
                            "Сарыоба": { "raiony": [] },
                            "Садовое": { "raiony": [] },
                            "Сандыктау": { "raiony": [] },
                            "Сарыбулак": { "raiony": [] },
                            "Семеновка": { "raiony": [] },
                            "Сосновка": { "raiony": [] },
                            "Софиевка": { "raiony": [] },
                            "Станционный": { "raiony": [] },
                            "Степногорск": { "raiony": [] },
                            "Степное": { "raiony": [] },
                            "Степняк": { "raiony": [] },
                            "Тайтобе": { "raiony": [] },
                            "Талкара": { "raiony": [] },
                            "Талапкер": { "raiony": [] },
                            "Тастак": { "raiony": [] },
                            "Тасты": { "raiony": [] },
                            "Тельмана": { "raiony": [] },
                            "Тонкерис": { "raiony": [] },
                            "Троицкое": { "raiony": [] },
                            "ст Тонкерис": { "raiony": [] },
                            "Узунколь": { "raiony": [] },
                            "Уленты": { "raiony": [] },
                            "Ульги": { "raiony": [] },
                            "Урумкай": { "raiony": [] },
                            "Успеноюрьевка": { "raiony": [] },
                            "Фарфоровое": { "raiony": [] },
                            "Чаглинка": { "raiony": [] },
                            "Чайкино": { "raiony": [] },
                            "Шагалы": { "raiony": [] },
                            "Шалкар": { "raiony": [] },
                            "Шортанды": { "raiony": [] },
                            "Шубар": { "raiony": [] },
                            "Щучинский": { "raiony": [] },
                            "Шантобе": { "raiony": [] },
                            "Ыбырай Алтынсарин": { "raiony": [] },
                        }
                    },
                    "Актюбинская обл.": {
                        "goroda": {
                            "Актобе": { "raiony": ["жилой массив Бауырластар", "мкр. 41-й разъезд", "мкр. Авиатор", "мкр. Алтын орда", "мкр Акжар", "мкр Авиагородок", "мкр Гормолзавод", "жилой массив Акжар-2", "жилой массив Бауырластар-5", "жилой массив Думан", "жилой массив Жанаконыс", "жилой массив Жанаконыс-2", "жилой массив Жанаконыс-4", "жилой массив Жилянка", "жилой массив Заречный-1", "жилой массив Заречный-2", "жилой массив Заречный-3", "жилой массив Заречный-4", "жилой массив Кирпичный", "жилой массив Карайлы", "жилой массив Кызылжар", "жилой массив Рауан", "жилой массив Юго-Запад-1", "жилой массив Ясное", "мкр Москва", "мкр Болашак", "мкр Жилгородок", "мкр Курмыш", "мкр Сельмаш", "Новый город", "Нур Актобе", "Сазда-1", "Сазда-2", "Сазда-3", "Сазда-4", "СК Акжар-Плюс", "СК Сарбаз", "Старый город", "мкр Шанхай", "мкр 5", "мкр 6", "мкр 8", "мкр 11", "мкр 12", "квартал Авиатор-2", "Курашасай"] },
                            "Бауырластар 2": { "raiony": [] },
                            "Акжар": { "raiony": [] },
                            "Аккемер": { "raiony": [] },
                            "Акшат": { "raiony": [] },
                            "Алга": { "raiony": [] },
                            "Алия": { "raiony": [] },
                            "Ащылысай": { "raiony": [] },
                            "Бадамша": { "raiony": [] },
                            "Байторысай": { "raiony": [] },
                            "Беккул баба": { "raiony": [] },
                            "Бестамак": { "raiony": [] },
                            "Заречный": { "raiony": [] },
                            "Иргиз": { "raiony": [] },
                            "Каргалинское (Жилянка)": { "raiony": [] },
                            "Кандыагаш": { "raiony": [] },
                            "Каракудук": { "raiony": [] },
                            "Карауылкельды": { "raiony": [] },
                            "Кенкияк": { "raiony": [] },
                            "Кенбидаик": { "raiony": [] },
                            "Хобда": { "raiony": [] },
                            "Комсомольское": { "raiony": [] },
                            "Кос-Истек": { "raiony": [] },
                            "Курашасай": { "raiony": [] },
                            "Кызылжак": { "raiony": [] },
                            "Маржанбулак": { "raiony": [] },
                            "Мартук": { "raiony": [] },
                            "Нижний Курайлы": { "raiony": [] },
                            "Новый": { "raiony": [] },
                            "Нурбулак": { "raiony": [] },
                            "Петропавловка": { "raiony": [] },
                            "Преображеновка": { "raiony": [] },
                            "пос Кенеса Нокина": { "raiony": [] },
                            "Пригородный": { "raiony": [] },
                            "Садовое": { "raiony": [] },
                            "Сазда": { "raiony": [] },
                            "Сарыжар": { "raiony": [] },
                            "Тамды": { "raiony": [] },
                            "Темир": { "raiony": [] },
                            "Уил": { "raiony": [] },
                            "Ушкудык": { "raiony": [] },
                            "Хромтау": { "raiony": [] },
                            "Шалкар": { "raiony": [] },
                            "с. Шамши Калдаякова": { "raiony": [] },
                            "Шубаркудук": { "raiony": [] },
                            "Шилисай": { "raiony": [] },
                            "Эмба": { "raiony": [] },
                            "Ясное": { "raiony": [] }
                        }
                    },
                    "Алматинская обл.": {
                        "goroda": {
                            "Каскелен": { "raiony": [] },
                            "Конаев (Капчагай)": { "raiony": [] },
                            "Талгар": { "raiony": [] },
                            "Есик": { "raiony": [] },
                            "Абай": { "raiony": [] },
                            "Ават": { "raiony": [] },
                            "Айганым": { "raiony": [] },
                            "Айдарлы": { "raiony": [] },
                            "Аймен": { "raiony": [] },
                            "Айнабулак": { "raiony": [] },
                            "Айтей": { "raiony": [] },
                            "Азат": { "raiony": [] },
                            "Акбеит": { "raiony": [] },
                            "Акдала": { "raiony": [] },
                            "Акбулак": { "raiony": [] },
                            "Акжар": { "raiony": [] },
                            "Аккайнар": { "raiony": [] },
                            "Аксай": { "raiony": [] },
                            "Актас": { "raiony": [] },
                            "Актогай (Дендрарий)": { "raiony": [] },
                            "Актоган": { "raiony": [] },
                            "Аксенгир": { "raiony": [] },
                            "ст Аксенгир": { "raiony": [] },
                            "Акбастау": { "raiony": [] },
                            "Акши": { "raiony": [] },
                            "Акши (Енбекшиказахский район)": { "raiony": [] },
                            "Акын Сара": { "raiony": [] },
                            "Алатау": { "raiony": ["п. Арман"] },
                            "Алга": { "raiony": [] },
                            "Алдаберген": { "raiony": [] },
                            "Али": { "raiony": [] },
                            "Алма-Арасан": { "raiony": [] },
                            "Алмалы": { "raiony": [] },
                            "Алмалык": { "raiony": [] },
                            "Алмалыбак (КИЗ)": { "raiony": [] },
                            "Амангельды": { "raiony": [] },
                            "Арай": { "raiony": [] },
                            "Арман": { "raiony": [] },
                            "Арна": { "raiony": [] },
                            "Асы-Сага": { "raiony": [] },
                            "Ащибулак": { "raiony": ["Туймебая"] },
                            "Ащысай": { "raiony": [] },
                            "Баганашыл": { "raiony": [] },
                            "Базаркельды": { "raiony": [] },
                            "Байбулак": { "raiony": [] },
                            "Байдибек би": { "raiony": [] },
                            "Байсеит": { "raiony": [] },
                            "Байсерке": { "raiony": [] },
                            "Байтерек (Новоалексеевка)": { "raiony": [] },
                            "Баканас": { "raiony": [] },
                            "Балтабай": { "raiony": [] },
                            "Бастобе": { "raiony": [] },
                            "Батан": { "raiony": [] },
                            "Бекболат": { "raiony": [] },
                            "Бельбулак (Мичурино)": { "raiony": [] },
                            "Береке": { "raiony": [] },
                            "Бирлик": { "raiony": [] },
                            "Бесагаш": { "raiony": [] },
                            "Бескайнар (Горный Садовод)": { "raiony": [] },
                            "Болек": { "raiony": [] },
                            "Боралдай (Бурундай)": { "raiony": ["мкр Водник-1", "мкр Водник-2", "мкр Водник-3"] },
                            "Булакты": { "raiony": [] },
                            "Гульдала": { "raiony": [] },
                            "Головацкий": { "raiony": [] },
                            "Верхняя Каменка": { "raiony": [] },
                            "Даулет": { "raiony": [] },
                            "Дегерес": { "raiony": [] },
                            "Долан": { "raiony": [] },
                            "Достык": { "raiony": [] },
                            "Еркин": { "raiony": [] },
                            "Ельтай": { "raiony": [] },
                            "Енбекшиарал": { "raiony": [] },
                            "Екпинды": { "raiony": [] },
                            "Еламан": { "raiony": [] },
                            "Енбек": { "raiony": [] },
                            "Енбекши": { "raiony": [] },
                            "Жаугашты": { "raiony": [] },
                            "Жалкамыс": { "raiony": [] },
                            "Жанаарна": { "raiony": [] },
                            "Жанакурылыс": { "raiony": [] },
                            "Жалпаксай": { "raiony": [] },
                            "Жана Куат": { "raiony": [] },
                            "Жана-талап": { "raiony": [] },
                            "Жамбыл": { "raiony": [] },
                            "Жаналык": { "raiony": [] },
                            "Жаналык (Талгарский район)": { "raiony": [] },
                            "Жамбыл (Жамбылский район)": { "raiony": [] },
                            "Жанатурмыс": { "raiony": [] },
                            "Жанадаур": { "raiony": [] },
                            "Жанашар": { "raiony": [] },
                            "Жандосов": { "raiony": [] },
                            "Жапек батыр": { "raiony": [] },
                            "Жармухамбет": { "raiony": [] },
                            "Желторангы": { "raiony": [] },
                            "Жетысу": { "raiony": [] },
                            "Жибек Жолы": { "raiony": [] },
                            "Жидели": { "raiony": [] },
                            "Жомарт": { "raiony": [] },
                            "Жунжурек": { "raiony": [] },
                            "Жыланды": { "raiony": [] },
                            "Заречное": { "raiony": [] },
                            "им. Балгабека Кыдырбекулы": { "raiony": [] },
                            "Исаево": { "raiony": [] },
                            "Иргели": { "raiony": [] },
                            "ИЯФ": { "raiony": [] },
                            "Казатком": { "raiony": [] },
                            "КазЦИК": { "raiony": [] },
                            "Кайнар": { "raiony": [] },
                            "Кайназар (Енбекшиказахский район)": { "raiony": [] },
                            "Кайназар (Жамбылский район)": { "raiony": [] },
                            "Кайрат": { "raiony": [] },
                            "Казахстан": { "raiony": [] },
                            "Казыбек бека": { "raiony": [] },
                            "Карабулак (п.Ключи)": { "raiony": [] },
                            "Карагайлы (Чапаево)": { "raiony": [] },
                            "Каракастек": { "raiony": [] },
                            "Каракемер": { "raiony": [] },
                            "Каратобе": { "raiony": [] },
                            "Караозек": { "raiony": [] },
                            "Караой": { "raiony": [] },
                            "Карасай": { "raiony": [] },
                            "Карасу": { "raiony": [] },
                            "Карасу-2": { "raiony": [] },
                            "Каратал": { "raiony": [] },
                            "Каратурук": { "raiony": [] },
                            "Каратоган": { "raiony": [] },
                            "Каргалы (п. Фабричный)": { "raiony": [] },
                            "Касымбек": { "raiony": [] },
                            "Кеген": { "raiony": [] },
                            "Кемертоган": { "raiony": [] },
                            "Кендала": { "raiony": [] },
                            "Киши Байсерке": { "raiony": [] },
                            "Коргалы": { "raiony": [] },
                            "Койлык": { "raiony": [] },
                            "Коишыбек": { "raiony": [] },
                            "Кокдала": { "raiony": [] },
                            "Кокжар": { "raiony": [] },
                            "Коккайнар": { "raiony": [] },
                            "Кок-Лай-Сай": { "raiony": [] },
                            "Коктал": { "raiony": [] },
                            "Кокозек": { "raiony": [] },
                            "Коксай (Путь Ильича)": { "raiony": [] },
                            "Коксу": { "raiony": [] },
                            "село Коксу": { "raiony": [] },
                            "Коктобе": { "raiony": [] },
                            "Коктоган (Чапаев)": { "raiony": [] },
                            "Коктерек": { "raiony": [] },
                            "Кокшокы": { "raiony": [] },
                            "Кольащы": { "raiony": [] },
                            "Кольды": { "raiony": [] },
                            "Комсомол": { "raiony": [] },
                            "Коныр": { "raiony": [] },
                            "Корам": { "raiony": [] },
                            "Космос": { "raiony": [] },
                            "Косозен": { "raiony": [] },
                            "Коянкоз": { "raiony": [] },
                            "Котырбулак": { "raiony": [] },
                            "Коянкус": { "raiony": [] },
                            "Кошмамбет": { "raiony": [] },
                            "Куйган": { "raiony": [] },
                            "Кумарал": { "raiony": [] },
                            "Кумтоган (Мирное)": { "raiony": [] },
                            "Куш": { "raiony": [] },
                            "Кызыл Борик Сай": { "raiony": [] },
                            "Кызылжар": { "raiony": [] },
                            "Кызыл ту-4": { "raiony": [] },
                            "Кызыл Кайрат": { "raiony": [] },
                            "Кызыласкер": { "raiony": [] },
                            "Кызылсок": { "raiony": [] },
                            "Кызылту": { "raiony": [] },
                            "Кыргауылды": { "raiony": [] },
                            "Кырбалтабай": { "raiony": [] },
                            "Лепси": { "raiony": [] },
                            "М. Туймебаев": { "raiony": [] },
                            "Малыбай": { "raiony": [] },
                            "Мерей (Селекция)": { "raiony": [] },
                            "Междуреченск": { "raiony": [] },
                            "Мынбаево": { "raiony": [] },
                            "Нарынкол": { "raiony": [] },
                            "Наурыз": { "raiony": [] },
                            "Нияз": { "raiony": [] },
                            "Нура": { "raiony": [] },
                            "Нургиса Тлендиев": { "raiony": [] },
                            "Нурлы": { "raiony": [] },
                            "Орикти": { "raiony": [] },
                            "Орман": { "raiony": [] },
                            "Остемир": { "raiony": [] },
                            "Отеген батыр": { "raiony": [] },
                            "Отенай": { "raiony": [] },
                            "Панфилово (Табаксовхоз)": { "raiony": [] },
                            "Пригородное": { "raiony": [] },
                            "Райымбек": { "raiony": [] },
                            "Рахат (Покровка)": { "raiony": [] },
                            "Рахат (Енбекшиказахский район)": { "raiony": [] },
                            "Рахат (поселок Жалпаксай)": { "raiony": [] },
                            "Рыскулово": { "raiony": [] },
                            "Саймасай": { "raiony": [] },
                            "Самсы": { "raiony": [] },
                            "Саты": { "raiony": [] },
                            "Сауыншы": { "raiony": [] },
                            "Сулусай": { "raiony": [] },
                            "Сункар": { "raiony": [] },
                            "Сарыбастау": { "raiony": [] },
                            "Сарыбулак": { "raiony": [] },
                            "СТ Ветеран": { "raiony": [] },
                            "СТ Монтажник": { "raiony": [] },
                            "Талгарский р-н": { "raiony": [] },
                            "Талдыбулак": { "raiony": [] },
                            "Талдыбулак (Енбекшиказахский район)": { "raiony": [] },
                            "Таргап": { "raiony": [] },
                            "Таукаратурык": { "raiony": [] },
                            "Таутургень": { "raiony": [] },
                            "Ташкенсаз": { "raiony": [] },
                            "Теректы": { "raiony": [] },
                            "Теренкара": { "raiony": [] },
                            "Тескенсу": { "raiony": [] },
                            "Токжайлау": { "raiony": [] },
                            "Толкын": { "raiony": [] },
                            "Толе би": { "raiony": [] },
                            "Тонкерис": { "raiony": [] },
                            "Туганбай": { "raiony": [] },
                            "Туздыбастау (Калинино)": { "raiony": [] },
                            "Тузусай": { "raiony": [] },
                            "Турар": { "raiony": [] },
                            "Турарские дачи": { "raiony": [] },
                            "Турген": { "raiony": [] },
                            "Узынагаш": { "raiony": ["п. Шиен"] },
                            "Улькен": { "raiony": [] },
                            "Улькен Аксу": { "raiony": [] },
                            "Унгуртас": { "raiony": [] },
                            "Улан": { "raiony": [] },
                            "Ульгили": { "raiony": [] },
                            "Умбетали": { "raiony": [] },
                            "Ушбулак": { "raiony": [] },
                            "Шамалган": { "raiony": [] },
                            "Ушжарма": { "raiony": [] },
                            "Уштерек": { "raiony": [] },
                            "Хоргос": { "raiony": [] },
                            "пос. Хусаина Бижанова": { "raiony": [] },
                            "Чапаево": { "raiony": [] },
                            "Чимбулак": { "raiony": [] },
                            "Чунджа": { "raiony": [] },
                            "Ынтымак": { "raiony": [] },
                            "Шалкар": { "raiony": [] },
                            "Ынтымак (Жамбылский район)": { "raiony": [] },
                            "Шарын": { "raiony": [] },
                            "Шелек": { "raiony": [] },
                            "Шелекемир": { "raiony": [] },
                            "Шенгельды": { "raiony": [] },
                            "Шолакай": { "raiony": [] },
                            "Шубар": { "raiony": [] },
                            "Шубартубек": { "raiony": [] },
                            "Шымбулак": { "raiony": [] },
                            "71-й Разъезд": { "raiony": [] }
                        }
                    },
                    "Атырауская обл.": {
                        "goroda": {
                            "Атырау": { "raiony": ["мкр Авангард-2", "мкр Авангард-3", "мкр Авангард-4", "мкр Ак депо", "мкр Ак Шагала", "мкр Акжайык", "мкр Аксай", "мкр Аксай-2", "мкр Алмагуль", "мкр Ардагер", "мкр Атамура", "мкр Атырау", "мкр Атырау-2", "мкр Байтак", "мкр Байтак-2", "мкр Байтак-3", "мкр Балауса", "мкр Береке", "мкр Бирлик", "мкр Бирлик-2", "мкр Геолог", "мкр Жастар", "мкр Жеруйык", "мкр Жети Казына", "мкр Жилгородок", "мкр Жулдыз", "мкр Жулдыз-2", "мкр Жулдыз-3", "мкр Жумыскер", "мкр Зарослый", "мкр Кайнар", "мкр Кара депо", "мкр Каспий", "мкр Кокарна", "мкр Коктем", "мкр Курсай", "мкр Лесхоз", "мкр Лесхоз-2", "мкр Мирас", "мкр Мирный", "мкр Мунайшы", "мкр Мясокомбинат", "мкр Новокирпичное", "мкр Нурсая", "мкр Нурсая-3", "мкр Оркен", "мкр Оркен-2", "мкр Орлеу", "мкр Привокзальный-1", "мкр Привокзальный-3", "мкр Привокзальный-3А", "мкр Привокзальный-5", "мкр Ракуша", "мкр Рембаза", "мкр Садовод", "мкр Самал", "мкр Самал-2", "мкр Сарыкамыс", "мкр Сарыкамыс-2", "мкр Сая", "мкр СМП 136", "мкр СМП 163", "мкр Тендык", "мкр Толкын", "мкр Томарлы", "мкр Томарлы-2", "мкр Химпоселок", "мкр Сары Арка", "мкр Центральный", "пгт Балыкши"] },
                            "Кульсары": { "raiony": [] },
                            "Акжар": { "raiony": [] },
                            "Аккайын": { "raiony": [] },
                            "Аккистау": { "raiony": [] },
                            "Аксай": { "raiony": [] },
                            "Алмалы": { "raiony": [] },
                            "Балыкшы": { "raiony": [] },
                            "Бейбарыс": { "raiony": [] },
                            "Береке (Память Ильича)": { "raiony": [] },
                            "Бирлик": { "raiony": [] },
                            "Ганюшкино": { "raiony": [] },
                            "Геолог": { "raiony": [] },
                            "Геолог-2": { "raiony": [] },
                            "Дамба": { "raiony": [] },
                            "Доссор": { "raiony": [] },
                            "Еркинкала": { "raiony": [] },
                            "Есбол (Кулагино)": { "raiony": [] },
                            "Жанаталап": { "raiony": [] },
                            "Жана Каратон": { "raiony": [] },
                            "Жанбай": { "raiony": [] },
                            "Жулдыз": { "raiony": [] },
                            "Жумыскер": { "raiony": [] },
                            "Зарослый": { "raiony": [] },
                            "Зинеден": { "raiony": [] },
                            "Индер": { "raiony": [] },
                            "Исатай": { "raiony": [] },
                            "Кокарна": { "raiony": [] },
                            "Кигач": { "raiony": [] },
                            "Коктагай": { "raiony": [] },
                            "Макат": { "raiony": [] },
                            "Махамбет": { "raiony": [] },
                            "Миялы": { "raiony": [] },
                            "Нарын": { "raiony": [] },
                            "Ортакшыл": { "raiony": [] },
                            "Ракуша": { "raiony": [] },
                            "Сарайчик": { "raiony": [] },
                            "Сарытогай": { "raiony": [] },
                            "Талгайран": { "raiony": [] },
                            "Талдыколь": { "raiony": [] },
                            "Таскала": { "raiony": [] },
                            "Томарлы": { "raiony": [] },
                            "Тущыкудык": { "raiony": [] },
                            "Хамит Ергалиев": { "raiony": [] }
                        }
                    },
                    "Восточно-Казахстанская обл.": {
                        "goroda": {
                            "Усть-Каменогорск": { "raiony": ["Ульбинский", "Ахмирово"] },
                            "Риддер": { "raiony": [] },
                            "Тугыл": { "raiony": [] },
                            "Айыртау": { "raiony": [] },
                            "Аккайнар": { "raiony": [] },
                            "Актогай": { "raiony": [] },
                            "Акши": { "raiony": [] },
                            "Алмасай": { "raiony": [] },
                            "Алтай": { "raiony": [] },
                            "Алтайский": { "raiony": [] },
                            "Асубулак": { "raiony": [] },
                            "Ахмирово": { "raiony": [] },
                            "Ауэзов": { "raiony": [] },
                            "Аксаринский кордон": { "raiony": [] },
                            "Белокаменка": { "raiony": [] },
                            "Белоусовка": { "raiony": [] },
                            "Березовка": { "raiony": [] },
                            "Бескарагай": { "raiony": [] },
                            "Бестерек": { "raiony": [] },
                            "Бобровка": { "raiony": [] },
                            "Бозанбай": { "raiony": [] },
                            "Большая Речка": { "raiony": [] },
                            "Бородулиха": { "raiony": [] },
                            "Быструха": { "raiony": [] },
                            "Верх-Уба": { "raiony": [] },
                            "Верхнеберёзовский": { "raiony": [] },
                            "Веселовка": { "raiony": [] },
                            "Винное": { "raiony": [] },
                            "Волчанка": { "raiony": [] },
                            "Восточный": { "raiony": [] },
                            "Выдриха": { "raiony": [] },
                            "Герасимовка": { "raiony": [] },
                            "Глубокое": { "raiony": [] },
                            "Горная Ульбинка": { "raiony": [] },
                            "Греховка": { "raiony": [] },
                            "Донское": { "raiony": [] },
                            "Жаркын": { "raiony": [] },
                            "Жезкент": { "raiony": [] },
                            "Жиланды": { "raiony": [] },
                            "Зайсан": { "raiony": [] },
                            "Зубовск": { "raiony": [] },
                            "Зевакино": { "raiony": [] },
                            "Зимовье": { "raiony": [] },
                            "Знаменка": { "raiony": [] },
                            "Ивановка": { "raiony": [] },
                            "Калбатау": { "raiony": [] },
                            "Каменный Карьер": { "raiony": [] },
                            "Кандыковка": { "raiony": [] },
                            "Канонерка": { "raiony": [] },
                            "Карабуйрат": { "raiony": [] },
                            "Карагужиха": { "raiony": [] },
                            "Катон-Карагай": { "raiony": [] },
                            "Красина": { "raiony": [] },
                            "им. Касыма Кайсенова": { "raiony": [] },
                            "Кожохово": { "raiony": [] },
                            "Кокпекты": { "raiony": [] },
                            "Коктерек": { "raiony": [] },
                            "Колденен": { "raiony": [] },
                            "Куленовка": { "raiony": [] },
                            "Курчум": { "raiony": [] },
                            "Кызымбет (Алексеевка)": { "raiony": [] },
                            "Лесхоз": { "raiony": [] },
                            "Малоубинка": { "raiony": [] },
                            "Маркаколь": { "raiony": [] },
                            "Мамай Батыра": { "raiony": [] },
                            "Межовка": { "raiony": [] },
                            "Меновное": { "raiony": [] },
                            "Мирный": { "raiony": [] },
                            "Миролюбовка": { "raiony": [] },
                            "Митрофановка": { "raiony": [] },
                            "Новая Бухтарма": { "raiony": [] },
                            "Ново-Ахмирово": { "raiony": [] },
                            "Новобаженово": { "raiony": [] },
                            "Новопокровка": { "raiony": [] },
                            "Ново-Троицкое": { "raiony": [] },
                            "Новая Согра": { "raiony": [] },
                            "Новая Шульба": { "raiony": [] },
                            "Ново-Явленка": { "raiony": [] },
                            "Ново-Ульбинка": { "raiony": [] },
                            "Октябрьский": { "raiony": [] },
                            "Опытное поле": { "raiony": [] },
                            "Перевальное": { "raiony": [] },
                            "Первомайский": { "raiony": [] },
                            "Прапорщиково": { "raiony": [] },
                            "Предгорное": { "raiony": [] },
                            "Преображенка": { "raiony": [] },
                            "Прииртышский": { "raiony": [] },
                            "Прибрежный": { "raiony": [] },
                            "Пригородное": { "raiony": [] },
                            "Приморское": { "raiony": [] },
                            "Приречное": { "raiony": [] },
                            "Прогресс": { "raiony": [] },
                            "Радужное": { "raiony": [] },
                            "Рассыпное": { "raiony": [] },
                            "Рулиха": { "raiony": [] },
                            "Сагыр": { "raiony": [] },
                            "с/о Звездный": { "raiony": [] },
                            "Самарское": { "raiony": [] },
                            "Самсоновка": { "raiony": [] },
                            "Свинчатка": { "raiony": [] },
                            "Саратовка": { "raiony": [] },
                            "Сарыбел": { "raiony": [] },
                            "Секисовка": { "raiony": [] },
                            "Серебрянск": { "raiony": [] },
                            "Солнечная Долина": { "raiony": [] },
                            "Степное": { "raiony": [] },
                            "Таврическое": { "raiony": [] },
                            "Тарханка": { "raiony": [] },
                            "Таргын": { "raiony": [] },
                            "Топиха": { "raiony": [] },
                            "Уварова": { "raiony": [] },
                            "Украинка": { "raiony": [] },
                            "Ульба": { "raiony": [] },
                            "Улкен Нарын": { "raiony": [] },
                            "Ушаново": { "raiony": [] },
                            "Усть-Таловка": { "raiony": [] },
                            "Ушановский": { "raiony": [] },
                            "Черемшанка": { "raiony": [] },
                            "Шакаман": { "raiony": [] },
                            "Шар": { "raiony": [] },
                            "Шемонаиха": { "raiony": [] },
                            "Шульбинск": { "raiony": [] },
                            "Холодный ключ": { "raiony": [] },
                            "Энергетик": { "raiony": [] }
                        }
                    },
                    "Жамбылская обл.": {
                        "goroda": {
                            "Тараз": { "raiony": [] },
                            "Айша-Биби": { "raiony": [] },
                            "Акарал": { "raiony": [] },
                            "Акбулым": { "raiony": [] },
                            "Акыртобе": { "raiony": [] },
                            "Алатау": { "raiony": [] },
                            "Алга": { "raiony": [] },
                            "Андас Батыра": { "raiony": [] },
                            "Аса": { "raiony": [] },
                            "Аспара": { "raiony": [] },
                            "Байтерек": { "raiony": [] },
                            "Байзак": { "raiony": [] },
                            "Бауыржан Момышулы": { "raiony": [] },
                            "Бектобе": { "raiony": [] },
                            "Бесагаш": { "raiony": [] },
                            "Бурыл": { "raiony": [] },
                            "Гродеково": { "raiony": [] },
                            "Жанатас": { "raiony": [] },
                            "Жалпактобе": { "raiony": [] },
                            "Карасу": { "raiony": [] },
                            "Каратау": { "raiony": [] },
                            "Каракемер": { "raiony": [] },
                            "Коктал": { "raiony": [] },
                            "Коктобе": { "raiony": [] },
                            "Кордай": { "raiony": [] },
                            "Костобе": { "raiony": [] },
                            "Кызыл-Кайнар": { "raiony": [] },
                            "Кулан": { "raiony": [] },
                            "Майтобе": { "raiony": [] },
                            "Мерке": { "raiony": [] },
                            "Мойынкум": { "raiony": [] },
                            "Мырзатай": { "raiony": [] },
                            "Орнек": { "raiony": [] },
                            "Отар": { "raiony": [] },
                            "Пригородное": { "raiony": [] },
                            "Сарыкемер": { "raiony": [] },
                            "Саудакент": { "raiony": [] },
                            "Тамды": { "raiony": [] },
                            "Тастобе": { "raiony": [] },
                            "Толе би": { "raiony": [] },
                            "Туймекент": { "raiony": [] },
                            "Турксиб": { "raiony": [] },
                            "Шайкорык": { "raiony": [] },
                            "Шиен": { "raiony": [] },
                            "Шолаккаргалы": { "raiony": [] },
                            "Шу": { "raiony": [] },
                            "Чайкурук": { "raiony": [] },
                            "Чиганак": { "raiony": [] }
                        }
                    },
                    "Жетысу обл.": {
                        "goroda": {
                            "Талдыкорган": {
                                "raiony": ["Каратал", "9-й микрорайон", "военный городок Улан", "мкр Бирлик", "мкр Болашак", "мкр Жана Гарышкер", "мкр военный городок Жулдыз", "мкр Жастар", "мкр Жетысу", "мкр Коктем", "мкр Мушелтой", "мкр Самал", "село Ынтымак"]
                            },
                            "Алмалы": { "raiony": [] },
                            "Акши": { "raiony": [] },
                            "Бактыбая Жолбарысулы": { "raiony": [] },
                            "Алдабергеново": { "raiony": [] },
                            "Бесколь": { "raiony": [] },
                            "Текели": { "raiony": [] },
                            "Жастар-2": { "raiony": [] },
                            "Жаркент": { "raiony": ["Алтыуй"] },
                            "Балпык Би": { "raiony": [] },
                            "Сарканд": { "raiony": [] },
                            "Сарыозек": { "raiony": [] },
                            "Уштобе": { "raiony": [] },
                            "Кабанбай": { "raiony": [] },
                            "Капал": { "raiony": [] },
                            "Карабулак": { "raiony": [] },
                            "Каратальское": { "raiony": [] },
                            "Коктума": { "raiony": [] },
                            "Кызылагаш": { "raiony": [] },
                            "Лепсы": { "raiony": [] },
                            "Матай": { "raiony": [] },
                            "Ушарал": { "raiony": [] },
                            "Шимыр": { "raiony": [] },
                            "Жансугуров": { "raiony": [] }
                        }
                    },
                    "Западно-Казахстанская обл.": {
                        "goroda": {
                            "Уральск": { "raiony": ["мкр 4", "мкр Азаулы", "мкр Айгуль", "мкр Астана", "мкр Жана Орда", "мкр Женис", "мкр Жулдыз", "мкр Зачаганск пгт", "мкр Кунаева", "мкр Кадыра Мырза-Али", "мкр Строитель", "мкр Северо-Восток", "мкр Омега", "мкр Школьник"] },
                            "Аксай": { "raiony": [] },
                            "Абай": { "raiony": [] },
                            "Айтиево": { "raiony": [] },
                            "Акбулак": { "raiony": [] },
                            "Алмазное": { "raiony": [] },
                            "Акпатер": { "raiony": [] },
                            "Алгабас": { "raiony": [] },
                            "Асан": { "raiony": [] },
                            "Аманат": { "raiony": [] },
                            "Барбастау": { "raiony": [] },
                            "Белес": { "raiony": [] },
                            "Бисен": { "raiony": [] },
                            "Большой Чаган": { "raiony": [] },
                            "Булдурта": { "raiony": [] },
                            "Бударино": { "raiony": [] },
                            "Бурлин": { "raiony": [] },
                            "Дарьинск": { "raiony": [] },
                            "Долинное": { "raiony": [] },
                            "Егиндыбулак": { "raiony": [] },
                            "Жайык-2": { "raiony": [] },
                            "Жалпактал": { "raiony": [] },
                            "Жангала": { "raiony": [] },
                            "Жанаталап": { "raiony": [] },
                            "Жанибек": { "raiony": [] },
                            "Жарсуат": { "raiony": [] },
                            "Жалаево": { "raiony": [] },
                            "Жымпиты": { "raiony": [] },
                            "Деркул": { "raiony": ["Сарыарка"] },
                            "Достык": { "raiony": [] },
                            "Егиндыколь": { "raiony": [] },
                            "Зачаганск": { "raiony": ["мкр Арман", "мкр Кен Дала", "мкр Коминтерн", "мкр Сарытау"] },
                            "Зеленое": { "raiony": [] },
                            "Казталовка": { "raiony": [] },
                            "Каратобе": { "raiony": [] },
                            "Кирсаново": { "raiony": [] },
                            "Косколь": { "raiony": [] },
                            "Круглоозерное": { "raiony": [] },
                            "Лбищенское": { "raiony": [] },
                            "Новая жизнь": { "raiony": [] },
                            "Новенький": { "raiony": [] },
                            "Малый Чаган": { "raiony": [] },
                            "Макарово": { "raiony": [] },
                            "Махамбет": { "raiony": [] },
                            "Мастексай": { "raiony": [] },
                            "Мичуринское": { "raiony": [] },
                            "Озёрное": { "raiony": [] },
                            "Переметное": { "raiony": [] },
                            "Погодаево": { "raiony": [] },
                            "Подхоз": { "raiony": [] },
                            "Подстепное": { "raiony": [] },
                            "Пугачево": { "raiony": [] },
                            "Пятимарское": { "raiony": [] },
                            "Рубежинское": { "raiony": [] },
                            "Садовое": { "raiony": [] },
                            "Сайхин": { "raiony": [] },
                            "Тайпак": { "raiony": [] },
                            "Саралжын": { "raiony": [] },
                            "Таскала": { "raiony": [] },
                            "Теректы": { "raiony": [] },
                            "Трекино": { "raiony": [] },
                            "Успенка": { "raiony": [] },
                            "Уялы": { "raiony": [] },
                            "Федоровка": { "raiony": [] },
                            "Ханколь": { "raiony": [] },
                            "Хан Ордасы": { "raiony": [] },
                            "Чапаев": { "raiony": [] },
                            "Чувашинское": { "raiony": [] },
                            "Шынгырлау": { "raiony": [] },
                            "Щапово": { "raiony": [] },
                            "Январцево": { "raiony": [] }
                        }
                    },
                    "Карагандинская обл.": {
                        "goroda": {
                            "Караганда": { "raiony": ["Казыбек би р-н", "Алихана Бокейханова р-н"] },
                            "Балхаш": { "raiony": [] },
                            "Темиртау": { "raiony": [] },
                            "Шахтинск": { "raiony": [] },
                            "Актогай": { "raiony": [] },
                            "Абай": { "raiony": [] },
                            "Агадырь": { "raiony": [] },
                            "Акбастау": { "raiony": [] },
                            "Актас": { "raiony": [] },
                            "п. Актау": { "raiony": [] },
                            "Атасу": { "raiony": [] },
                            "Ахмет": { "raiony": [] },
                            "Байтуган": { "raiony": [] },
                            "Березняки": { "raiony": [] },
                            "Бестамак": { "raiony": [] },
                            "Ботакара": { "raiony": [] },
                            "Вольный": { "raiony": [] },
                            "Габидена Мустафина": { "raiony": [] },
                            "Доскей": { "raiony": [] },
                            "Долинка": { "raiony": [] },
                            "Дубовка": { "raiony": [] },
                            "Жайрем": { "raiony": [] },
                            "Жартас": { "raiony": [] },
                            "Карабас": { "raiony": [] },
                            "Каркаралинск": { "raiony": [] },
                            "Киевка": { "raiony": [] },
                            "Кенгир": { "raiony": [] },
                            "Кобетей": { "raiony": [] },
                            "Коктас": { "raiony": [] },
                            "Кокпекты": { "raiony": [] },
                            "Курминское": { "raiony": [] },
                            "Курылус": { "raiony": [] },
                            "Кушокы": { "raiony": [] },
                            "Кызылжар": { "raiony": [] },
                            "Мынбаев": { "raiony": [] },
                            "Мирное": { "raiony": [] },
                            "Молодежный": { "raiony": [] },
                            "Новодолинск": { "raiony": [] },
                            "Новоузенка": { "raiony": [] },
                            "Осакаровка": { "raiony": [] },
                            "Петровка": { "raiony": [] },
                            "Приозёрск": { "raiony": [] },
                            "Самарка": { "raiony": [] },
                            "Сарань": { "raiony": [] },
                            "Сарыарка": { "raiony": [] },
                            "Сарышаган": { "raiony": [] },
                            "Сокурское": { "raiony": [] },
                            "Сортировка": { "raiony": [] },
                            "Трудовое": { "raiony": [] },
                            "Топар": { "raiony": [] },
                            "Торангалык": { "raiony": [] },
                            "Улытау": { "raiony": [] },
                            "Уштобе": { "raiony": [] },
                            "Центральное": { "raiony": [] },
                            "Шахан": { "raiony": [] },
                            "Шахтерское": { "raiony": [] },
                            "Шашубай": { "raiony": [] },
                            "Шет": { "raiony": [] },
                            "Шешенкара": { "raiony": [] },
                            "Южный": { "raiony": [] }
                        }
                    },
                    "Костанайская обл.": {
                        "goroda": {
                            "Костанай": { "raiony": ["1-й микрорайон","2-й микрорайон","3-й микрорайон","4-й микрорайон","5-й микрорайон","6-й микрорайон","7-й микрорайон","8-й микрорайон","9-й микрорайон", "Микрорайон Аэропорт 1", "Микрорайон Аэропорт 2", "Микрорайон Восточный", "Микрорайон Нурай", "Микрорайон Северный", "Микрорайон Южный", "Юбилейный"] },
                            "Лисаковск": { "raiony": [] },
                            "Рудный": { "raiony": [] },
                            "Абай": { "raiony": [] },
                            "Александровка": { "raiony": [] },
                            "Алтын-Дала": { "raiony": [] },
                            "Алтынсарино": { "raiony": [] },
                            "Аманкарагай": { "raiony": [] },
                            "Антоновка": { "raiony": [] },
                            "Аркалык": { "raiony": [] },
                            "Аулиеколь": { "raiony": [] },
                            "Басбек": { "raiony": [] },
                            "Большая Чураковка": { "raiony": [] },
                            "Береговое": { "raiony": [] },
                            "Боровской": { "raiony": [] },
                            "Владимировка": { "raiony": [] },
                            "Ворошиловка": { "raiony": [] },
                            "Глазуновка": { "raiony": [] },
                            "Горняцкий": { "raiony": [] },
                            "Денисовка": { "raiony": [] },
                            "Дружба": { "raiony": [] },
                            "Жамбыл": { "raiony": [] },
                            "Ждановка": { "raiony": [] },
                            "Житикара": { "raiony": [] },
                            "Заречное": { "raiony": [] },
                            "Затобольск (Тобыл)": { "raiony": [] },
                            "Камысты": { "raiony": [] },
                            "Карабалык": { "raiony": [] },
                            "Караменды": { "raiony": [] },
                            "Карасу": { "raiony": [] },
                            "Качар": { "raiony": [] },
                            "Киевский": { "raiony": [] },
                            "Красный Кордон": { "raiony": [] },
                            "Красный партизан": { "raiony": [] },
                            "Комсомольское": { "raiony": [] },
                            "Константиновка": { "raiony": [] },
                            "Кушмурун": { "raiony": [] },
                            "Майколь": { "raiony": [] },
                            "Майское": { "raiony": [] },
                            "Михайловка": { "raiony": [] },
                            "Мичуринское": { "raiony": [] },
                            "Молокановка": { "raiony": [] },
                            "Московское": { "raiony": [] },
                            "Набережное": { "raiony": [] },
                            "Надеждинка": { "raiony": [] },
                            "Научное": { "raiony": [] },
                            "Новоселовка": { "raiony": [] },
                            "Озерное": { "raiony": [] },
                            "Октябрьский": { "raiony": [] },
                            "Октябрьское": { "raiony": [] },
                            "Перелески": { "raiony": [] },
                            "Пешковка": { "raiony": [] },
                            "Рыспай": { "raiony": [] },
                            "Садовое": { "raiony": [] },
                            "Степановка": { "raiony": [] },
                            "Садчикова": { "raiony": [] },
                            "Сарыколь": { "raiony": [] },
                            "Силантьевка": { "raiony": [] },
                            "Талдыколь": { "raiony": [] },
                            "Тарановское": { "raiony": [] },
                            "Тобол": { "raiony": [] },
                            "Убаган": { "raiony": [] },
                            "Ульяновское": { "raiony": [] },
                            "Узунколь": { "raiony": [] },
                            "Федоровка": { "raiony": [] },
                            "Челгаши": { "raiony": [] },
                            "Щербаково": { "raiony": [] },
                            "Юбилейное": { "raiony": [] }
                        }
                    },
                    "Кызылординская обл.": {
                        "goroda": {
                            "Кызылорда": { "raiony": [] },
                            "Байконур": { "raiony": ["Тюра-там"] },
                            "Аральск": { "raiony": [] },
                            "Акай": { "raiony": [] },
                            "Досан": { "raiony": [] },
                            "Жалагаш": { "raiony": [] },
                            "Жанакорган": { "raiony": [] },
                            "Жосалы": { "raiony": [] },
                            "Казалинск": { "raiony": [] },
                            "Казылжарма": { "raiony": [] },
                            "Караул Тобе": { "raiony": [] },
                            "Мухамбетов": { "raiony": [] },
                            "Талсуат": { "raiony": [] },
                            "Тасбогет": { "raiony": [] },
                            "Теренозек": { "raiony": [] },
                            "Шиели": { "raiony": [] }
                        }
                    },
                    "Мангистауская обл.": {
                        "goroda": {
                            "Актау": { "raiony": ["1-й мкр", "1А мкр", "1Б мкр", "1В мкр", "2-й мкр", "3-й мкр", "3А мкр", "3Б мкр", "4-й мкр", "4А мкр", "5-й мкр", "5А мкр", "6-й мкр", "6А мкр", "7-й мкр", "7А мкр", "8-й мкр", "8А мкр", "9-й мкр", "9А мкр", "10-й мкр", "11-й мкр", "11А мкр","12-й мкр","13-й мкр","14-й мкр","15-й мкр","16-й мкр","17-й мкр","18-й мкр", "18А мкр", "19-й мкр", "19А мкр", "20-й мкр", "20а мкр","21-й мкр","22-й мкр","23-й мкр","24-й мкр","25-й мкр","26-й мкр","27-й мкр","28-й мкр", "28А мкр", "29-й мкр", "29а мкр", "30-й мкр", "31-й мкр", "31А мкр", "31Б мкр", "32-й мкр","32А мкр", "32Б мкр", "32В мкр", "33-й мкр", "34-й мкр", "34А мкр", "35-й мкр", "38-й мкр", "39-й мкр", "40-й мкр", "Акжелкен", "Акжелкен-1", "Оймаш 1", "Оймаш 2", "Оймаш 3", "мкр Самал", "мкр Самал 2", "мкр Шыгыс 1", "мкр Шыгыс 2", "мкр Шыгыс 3", "мкр Приморский"] },
                            "Акшукур": { "raiony": [] },
                            "Атамекен": { "raiony": [] },
                            "Аташ": { "raiony": [] },
                            "Баскудук": { "raiony": [] },
                            "Баутино": { "raiony": [] },
                            "Батыр": { "raiony": [] },
                            "Баянды": { "raiony": [] },
                            "Даулет": { "raiony": [] },
                            "Заозёрный": { "raiony": [] },
                            "Бейнеу": { "raiony": [] },
                            "Жармыш": { "raiony": [] },
                            "Жанаозен": { "raiony": [] },
                            "Жетыбай": { "raiony": [] },
                            "Курык": { "raiony": [] },
                            "Кызылсай": { "raiony": [] },
                            "Мангистау": { "raiony": [] },
                            "Мангышлак": { "raiony": [] },
                            "Кызылтобе": { "raiony": [] },
                            "Кызылтобе 2": { "raiony": [] },
                            "Мунайшы": { "raiony": [] },
                            "Приозёрный": { "raiony": [] },
                            "с. Шапагатова": { "raiony": [] },
                            "Сай-Отес": { "raiony": [] },
                            "Таушик": { "raiony": [] },
                            "Умирзак": { "raiony": [] },
                            "Уштаган": { "raiony": [] },
                            "Форт-Шевченко": { "raiony": [] },
                            "Шетпе": { "raiony": [] }
                        }
                    },
                    "Павлодарская обл.": {
                        "goroda": {
                            "Павлодар": { "raiony": [] },
                            "Экибастуз": { "raiony": [] },
                            "Аксу": { "raiony": [] },
                            "Актогай": { "raiony": [] },
                            "Аккулы": { "raiony": [] },
                            "Алгабас": { "raiony": [] },
                            "Баянаул": { "raiony": [] },
                            "Берёзовка": { "raiony": [] },
                            "Галицкое": { "raiony": [] },
                            "Достык": { "raiony": [] },
                            "Евгеньевка": { "raiony": [] },
                            "Жана кала": { "raiony": [] },
                            "Железинка": { "raiony": [] },
                            "Жетекши": { "raiony": [] },
                            "Зангар": { "raiony": [] },
                            "Заря": { "raiony": [] },
                            "Иртышск": { "raiony": [] },
                            "Калмакан": { "raiony": [] },
                            "Канаш Камзин": { "raiony": [] },
                            "Караоба": { "raiony": [] },
                            "Кеменгер": { "raiony": [] },
                            "Кенжеколь": { "raiony": [] },
                            "Коктобе": { "raiony": [] },
                            "Коянды село": { "raiony": [] },
                            "Ленинский": { "raiony": [] },
                            "Луганск": { "raiony": [] },
                            "Майкайын": { "raiony": [] },
                            "Мичурино": { "raiony": [] },
                            "Мойылды": { "raiony": [] },
                            "Набережное": { "raiony": [] },
                            "Ольгино": { "raiony": [] },
                            "Павлодарское": { "raiony": [] },
                            "Парамоновка": { "raiony": [] },
                            "Пресное": { "raiony": [] },
                            "Прииртышское": { "raiony": [] },
                            "Равнополь": { "raiony": [] },
                            "Рождественка": { "raiony": [] },
                            "Розовка": { "raiony": [] },
                            "Солнечный": { "raiony": [] },
                            "Такыр": { "raiony": [] },
                            "Теренколь": { "raiony": [] },
                            "Торткудук": { "raiony": [] },
                            "Чернорецкое": { "raiony": [] },
                            "Черноярка": { "raiony": [] },
                            "Успенка": { "raiony": [] },
                            "Уштерек": { "raiony": [] },
                            "Шакат": { "raiony": [] },
                            "Шарбакты": { "raiony": [] },
                            "Шидерты": { "raiony": [] },
                            "Ямышево": { "raiony": [] }
                        }
                    },
                    "Северо-Казахстанская обл.": {
                        "goroda": {
                            "Петропавловск": { "raiony": [] },
                            "Антоновка": { "raiony": [] },
                            "Альжан": { "raiony": [] },
                            "Арыкбалык": { "raiony": [] },
                            "Астраханка": { "raiony": [] },
                            "Белое": { "raiony": [] },
                            "Бишкуль": { "raiony": [] },
                            "Берёзовка": { "raiony": [] },
                            "Большая Малышка": { "raiony": [] },
                            "Булаево": { "raiony": [] },
                            "Виноградовка": { "raiony": [] },
                            "Зарослое": { "raiony": [] },
                            "Заря": { "raiony": [] },
                            "Знаменское": { "raiony": [] },
                            "Ивановка": { "raiony": [] },
                            "Имантау": { "raiony": [] },
                            "Каратерек": { "raiony": [] },
                            "Карлуга": { "raiony": [] },
                            "Келлеровка": { "raiony": [] },
                            "Кишкенеколь": { "raiony": [] },
                            "Киялы": { "raiony": [] },
                            "Мамлютка": { "raiony": [] },
                            "Кривоозёрка": { "raiony": [] },
                            "Новоишимское": { "raiony": [] },
                            "Новопавловка": { "raiony": [] },
                            "Петерфельд": { "raiony": [] },
                            "Пресновка": { "raiony": [] },
                            "Рассвет": { "raiony": [] },
                            "Саумалколь": { "raiony": [] },
                            "Полудино": { "raiony": [] },
                            "Прибрежное": { "raiony": [] },
                            "Рублёвка": { "raiony": [] },
                            "Соколовка": { "raiony": [] },
                            "Сергеевка": { "raiony": [] },
                            "Смирново": { "raiony": [] },
                            "Тайынша": { "raiony": [] },
                            "Талшик": { "raiony": [] },
                            "Тельманово": { "raiony": [] },
                            "Тепличное": { "raiony": [] },
                            "Темирязево": { "raiony": [] },
                            "Токуши": { "raiony": [] },
                            "Уваковское": { "raiony": [] },
                            "Чкалова": { "raiony": [] },
                            "Шалкар": { "raiony": [] },
                            "Шаховское": { "raiony": [] },
                            "Явленка": { "raiony": [] },
                            "Якорь": { "raiony": [] }
                        }
                    },
                    "Туркестанская обл.": {
                        "goroda": {
                            "Туркестан": { "raiony": [] },
                            "Тасарык": { "raiony": [] },
                            "Абай": { "raiony": [] },
                            "Акбулак": { "raiony": [] },
                            "Акжол": { "raiony": [] },
                            "Аксукент": { "raiony": [] },
                            "Алтынтобе": { "raiony": [] },
                            "Арысь": { "raiony": [] },
                            "Асиларык": { "raiony": [] },
                            "Асыката": { "raiony": [] },
                            "Атакент": { "raiony": [] },
                            "Бадам": { "raiony": [] },
                            "Балыкты": { "raiony": [] },
                            "Биринши Мамыр": { "raiony": [] },
                            "Боралдай": { "raiony": [] },
                            "Бургулюк": { "raiony": [] },
                            "Дербисек": { "raiony": [] },
                            "Ельтай": { "raiony": [] },
                            "Жабаглы": { "raiony": [] },
                            "Жанабазар": { "raiony": [] },
                            "Жанаталап": { "raiony": [] },
                            "Жаскешу": { "raiony": [] },
                            "Жемисти": { "raiony": [] },
                            "Жетысай": { "raiony": [] },
                            "Жибек Жолы": { "raiony": [] },
                            "Жинишке": { "raiony": [] },
                            "Зертас": { "raiony": [] },
                            "им. Турара Рыскулова": { "raiony": [] },
                            "Кайнар": { "raiony": [] },
                            "Кайнарбулак": { "raiony": [] },
                            "Кызыгурт": { "raiony": [] },
                            "Капланбек": { "raiony": [] },
                            "Карабастау": { "raiony": [] },
                            "Каскасу": { "raiony": [] },
                            "Карабулак": { "raiony": [] },
                            "Карамурт": { "raiony": [] },
                            "Карасу": { "raiony": [] },
                            "Каратас": { "raiony": [] },
                            "Карашык": { "raiony": [] },
                            "Карнак": { "raiony": [] },
                            "Касымбек датка": { "raiony": [] },
                            "Кутарыс": { "raiony": [] },
                            "Кентау": { "raiony": [] },
                            "Кенесарык": { "raiony": [] },
                            "Кожамберды": { "raiony": [] },
                            "Кокибель": { "raiony": [] },
                            "Коксаек": { "raiony": [] },
                            "Кулан": { "raiony": [] },
                            "Ленгер": { "raiony": [] },
                            "Майтобе": { "raiony": [] },
                            "Мартобе": { "raiony": [] },
                            "Машат": { "raiony": [] },
                            "Оразалиев": { "raiony": [] },
                            "Маятас": { "raiony": [] },
                            "Мырзакент": { "raiony": [] },
                            "Састобе": { "raiony": [] },
                            "Сарыагаш": { "raiony": [] },
                            "Таусагыз": { "raiony": [] },
                            "Таскешу": { "raiony": [] },
                            "Темирлановка": { "raiony": [] },
                            "Теке": { "raiony": [] },
                            "Тюлькубас": { "raiony": [] },
                            "Улгили": { "raiony": [] },
                            "Чернак": { "raiony": [] },
                            "Черноводск": { "raiony": [] },
                            "Шакпак баба": { "raiony": [] },
                            "Шолаккорган": { "raiony": [] },
                            "Шарапхана": { "raiony": [] },
                            "Шардара": { "raiony": [] },
                            "Шаульдер": { "raiony": [] },
                            "Шаян": { "raiony": [] },
                            "Шубар": { "raiony": [] },
                            "Шубарсу": { "raiony": [] },
                            "Ынтымак": { "raiony": [] }
                        }
                    },
                    "Улытау обл.": {
                        "goroda": {
                            "Жезказган": {"rayony": []},
                            "Сатпаев": {"rayony": []},
                            "Каражал": {"rayony": []},
                        }
                    }

                }
            },

            poisk: '',
            filteredResults: [],

            scrollPositions: {} // Сохраняем позиции прокрутки для каждого шага
        }
    },

    watch: {
        '$route'(to, from) {
            const locationBody = document.querySelector('.location__body');

            // Если есть сохранённая позиция для текущего шага, восстанавливаем её
            if (locationBody && this.scrollPositions[to.params.locationStep]) {
                // Используем nextTick для корректной работы с DOM - Произойдет когда элементы в монтируются
                this.$nextTick(() => {
                    locationBody.scrollTo({
                        top: this.scrollPositions[to.params.locationStep], // Восстановление позиции для текущего шага
                        behavior: 'auto' // Мгновенная прокрутка
                    });
                });
            } else if (locationBody) {
                locationBody.scrollTo({
                    top: 0 // Если нет сохранённой позиции, прокручиваем к началу
                });
            }
        }
    },

    computed: {

        //Добавим затемнение только если компонент выводится в addAds
        locationWrapperClass() {
            const currentRoute = this.$route.name;
            // Установите классы CSS в зависимости от текущего маршрута
            return {
                'location__wrapper--addAds': currentRoute !== 'filterLocation',
            };
        },
    },

    methods: {

        //Показать список городов области
        showGoroda( oblast ){

            // Сохраняем позицию прокрутки для текущего шага
            const locationBody = document.querySelector('.location__body');
            const currentStep = this.$route.params.locationStep;
            if (locationBody) this.scrollPositions[currentStep] = locationBody.scrollTop;

            this.$router.push({ name: this.$route.name,  params: { oblast: oblast, gorod: 'null' ,locationStep: Number(currentStep)+1} })
        },

        //Показать список районов входящих в город
        showRaion(oblast, gorod) {
            // Проверяем, есть ли районы у города
            if (this.locations.oblasty[oblast]?.goroda[gorod]?.raiony?.length) {

                // Сохраняем позицию прокрутки для текущего шага
                const locationBody = document.querySelector('.location__body');
                const currentStep = this.$route.params.locationStep;
                if (locationBody) this.scrollPositions[currentStep] = locationBody.scrollTop;

                // Переходим на маршрут с новым шагом локации
                this.poisk = '';
                this.filteredResults = [];
                this.$router.push({name: this.$route.name, params: {oblast: oblast, gorod: gorod, locationStep: Number(currentStep) + 1}});
            } else {
                // Если районов нет, добавляем город в локацию
                this.addSelectedLocation({oblast: oblast, gorod: gorod, raion: null});
            }
        },

        //Добавим выбранную локацию в наш State
        addSelectedLocation( location ){
            this.KZLocationStore.addLocation({"oblast" : location.oblast, "gorod" : location.gorod, "raion" : location.raion, lat:54, lon:72})
            this.$router.go(-this.$route.params.locationStep)
        },

        // Метод для фильтрации данных при поиске через поле
        filterLocations() {
            const searchTerm = this.poisk.trim().toLowerCase(); // Убираем пробелы и приводим к нижнему регистру

            if (!searchTerm) {
                this.filteredResults = []; // Очищаем результаты при пустом вводе
                return;
            }

            const results = []; // Используем массив для хранения результатов

            // Рекурсивная функция для поиска по городам и районам
            const searchInObject = (obj, oblast = "") => {
                for (const key in obj) {
                    const value = obj[key];

                    if (key === "goroda") {
                        // Поиск по городам
                        for (const cityName in value) {
                            // Проверяем совпадение с названием города
                            if (cityName.toLowerCase().includes(searchTerm)) {
                                results.push({ // Используем push вместо add
                                    type: 'gorod',
                                    oblast: oblast,
                                    gorod: cityName,
                                    raion: null, // Район не определен для города
                                });
                            }

                            // Проверяем, есть ли районы в городе
                            if (value[cityName].raiony) {
                                // Поиск по районам внутри города
                                value[cityName].raiony.forEach((raion) => {
                                    if (raion.toLowerCase().includes(searchTerm)) {
                                        results.push({
                                            type: 'raion',
                                            oblast: oblast,
                                            gorod: cityName, // Указываем город для района
                                            raion, // Указываем найденный район
                                        });
                                    }
                                });
                            }
                        }
                    }
                    else if (typeof value === "object") {
                        // Рекурсивный вызов для вложенных объектов
                        searchInObject(value, key);
                    }
                }
            };

            // Запуск поиска по объекту locations
            searchInObject(this.locations);

            // Присваиваем результаты
            this.filteredResults = results;
        }


    },

    async mounted(){
        let app = this;

        this.locationAnimation = true;

        document.querySelector(':root').classList.add('PATCH_modal'); //Отменим прокрутку за OffCanvas

    },

    beforeRouteLeave(to, from, next){
        this.locationAnimation = false;

        setTimeout(() => {
            next(); // Вызываем next() после завершения setTimeout - Для завершения анимации
        }, 350);
    }


}
</script>

<style>

/* Анимация для плавного появления -- Backdrop */
.location__animation-backdrop-enter-active,
.location__animation-backdrop-leave-active{
    transition: opacity 0.3s ease;
}

.location__animation-backdrop-enter-from,
.location__animation-backdrop-leave-to {
    opacity: 0;
}

.location__animation-backdrop-enter-to,
.location__animation-backdrop-leave-from {
    opacity: 1;
}

/* Анимация для плавного появления -- Wrapper */
.location__animation-wrapper-enter-active,
.location__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}


.location__animation-wrapper-enter-from{
    transform: translateX(-50%); /* Начальное положение при анимации скрытия */
}

.location__animation-wrapper-leave-to {
    transform: translateX(-100%); /* Начальное положение при анимации скрытия */
}

.location__animation-wrapper-enter-to,
.location__animation-wrapper-leave-from {
    transform: translateY(0);/* Конечное положение при анимации появления */
}

</style>

<style scoped>

/* Backdrop */
.location__backdrop{
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: 0;
}

/* Этот класс добавиться если мы будем на страницы добавления а не Filter иначе там двойное затемнение фона */
.location__wrapper--addAds{
    background: rgba(0,0,0,0.5);
}

.location__wrapper{
    position: fixed;
    top: 0;
    bottom:0;
    right: auto;
    left: 0;
    width: 100%;
    max-width: 450px;
    background: #ffffff;
}

.location__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
}

.location__header{
    display: flex;
    align-items: center;
    background: #19b07b;
    color: #ffffff;
}

.location__body{
    flex-grow: 1;
    overflow-y: scroll;
}

.location__footer{
    padding: 5px 20px;
}

</style>
<!--1705-->
