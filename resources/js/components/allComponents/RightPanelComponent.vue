<template>
    <div v-if="ads_arr.data != undefined && ads_arr.data.length > 0 " class="right-panel__wrapper d-none d-md-block">

        <!-- Заголовок -->
        <div class=" d-flex gap-1 pt-2 px-3 text-grey">

            <!-- Иконка -->
            <div class="mx-3 d-flex justify-center align-center rounded-circle"
                 style="width: 21px; height: 21px; background: #FF0000"
            >
                <v-icon icon="mdi-fire" size="x-small" color="white"></v-icon>
            </div>

            <!-- Текст -->
            <h5>Горячие предложения</h5>

        </div>

        <!-- Компонент вывод превью объявлений -->
        <div v-for="(ads, index) of ads_arr.data" :key="index">
            <v-card max-width="600" style="background: #ededed; box-shadow: none; border-radius: 6px"
                    class="mx-3 my-3 ads__preview"
            >

                <!--  Описание объявления -->
                <div class="d-flex p-3">

                    <!-- Фото -->
                    <div class="position-relative" role="button" style="width: 70px; max-height: 65px; overflow: hidden">
                        <img v-if="ads.images[0] != 'no-image'" @click="$router.push({name: 'image', params: {table_name: ads.table_name, page: $route.params.page}}), imageStore.showImages({images: ads.images,index: 0, allImg: true})" class="ads__preview-img rounded-2" :src=" '/img/adsImg/' + ads.images[0] " style="width: 100%; height: 65px; object-fit: cover; object-position: center" alt="Фото недвижимости">
                        <img v-else src="/public/img/siteImg/allImg/no-image-buildings.png" alt="Нет фото" style="width: 100%; height: 65px" class="ads__preview-img">
                    </div>

                    <!--Блок - Описание объявления -->
                    <div class="col pl-2">

                        <!--Блок - Описание объявления -->
                        <div @click="showOneAds(ads, index)" role="button" class="d-flex align-start flex-column" style="height: 65px">

                            <!-- Заголовок -->
                            <div class="d-inline-block text-truncate" style="max-width:220px; font-size: 1.1em; color: #4b4b4b; text-align: start">
                                {{ads.zagolovok}}
                            </div>

                            <!-- Цена аренды -->
                            <div class="my-auto fw-bold" style="font-size: 1.1em; line-height: 16px">
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

                    </div>

                </div>

            </v-card>
        </div>

        <!-- Блок с пагинацией  - покажется если найденно более 30 объявлений -->
        <div class="mt-3" >

            <!-- Экран более - 768 px-->
            <Bootstrap5Pagination :align="'center'" :limit="3" :data="ads_arr"
                                  @pagination-change-page="(page)=> getAds(page)"
            ></Bootstrap5Pagination>

        </div>

    </div>
</template>

<script>
import { useImagesStore } from "../../stores/images";
import { useCheckInternetStore } from "../../stores/checkInternet";
import {useUpdateDateLocaleStore} from "../../stores/updateDateLocale";

//Пакет для пагинации Laravel + Vue
import { Bootstrap5Pagination } from 'laravel-vue-pagination';

export default {
    name: "RightPanelComponent",

    props: {
        update: Boolean,
        // другие пропсы
    },

    components: {
        Bootstrap5Pagination,
    },


    data(){
        return{
            imageStore: useImagesStore(),
            checkInternetStore: useCheckInternetStore(),
            updateDateLocale: useUpdateDateLocaleStore(),

            query: false,
            ads_arr: {}, //Полученные объявления с БД
        }
    },

    watch: {
        update(newVal, oldVal) {
            // Вызываем вашу функцию при изменении пропса update
            if (newVal !== oldVal) {
                this.getAds( this.$route.params.page);
            }
        },
    },

    methods: {

        //Метод - Получить объявления выбранной категории
        async getAds( page = 1 ) {

            this.query = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            await this.checkInternetStore.checkInternet();

            //Обнулим данные
            this.ads_arr = {};

            //Получаю объявления
            axios.get('getGoryachieRightPanel', {
                params: {
                    page: page,
                    table_name: this.$route.params.table_name,
                }

            })
                .then(response => {
                    this.query = false;
                    this.ads_arr = response.data.ads;
                })
                .catch((errors) => {
                    this.query = false;
                })


        },

        //Открыть 1-но объявление
        async showOneAds(ads,index){
            this.query = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            await this.checkInternetStore.checkInternet();

            localStorage.setItem('oneAdsIndex', index)
            localStorage.setItem('oneAds', JSON.stringify(ads))
            this.$router.push({name: 'oneAds' ,params: {table_name: ads.table_name, ads_id: ads.ads_id == undefined ? ads.id: ads.ads_id }})
        },


    },

    mounted(){
        this.getAds();
    },

    }
</script>

<style scoped>
.right-panel__wrapper{
    background: #ffffff;
    width: 100%;
    max-width: 380px;
    height: 100%;
    margin: 16px 0;
    border-radius: 8px;
    text-align: center;
    padding: 5px
}
</style>
