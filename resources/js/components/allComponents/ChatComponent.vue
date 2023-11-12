<template>
    <!-- ЧАТЫ - СООБЩЕНИЯ  -->

    <!-- Backdrop  -->
    <transition name="chat__animation-backdrop">

        <div v-if="chatAnimation" class="chat__backdrop" @click="$router.back()"></div>

    </transition>

    <!-- Обвертка - Компонента -->
    <transition name="chat__animation-wrapper">

        <!-- Обвертка - Компонента -->
        <div v-if="chatAnimation" class="chat__wrapper">

            <!-- Сам блок -->
            <div class="chat__block">

                <!-- Header -->
                <div class="chat__header">

                    <!-- Заголовок - Чаты -->
                    <div v-if="$route.params.id == 'null'" class="flex-grow-1 d-flex align-center justify-center">
                        <div class="fw-bold" style="font-size: 1.2em"> {{ $t('chatChats') }} </div>
                    </div>

                    <!-- Данные отправителя -->
                    <div v-if="$route.params.id != 'null'" class="flex-grow-1 d-flex justify-start align-center">

                        <!-- Иконка User -->
                        <v-icon size="x-large" style="font-size: 3em" class="mx-1 text-grey-lighten-2">mdi-account-circle</v-icon>

                        <!-- Имя | В сети -->
                        <div role="button" @click=" $router.push({name:'userAds', params: {table_name: 'Kvartira', author_id: user_data.id, page:1}})">

                            <!-- Имя пользователя -->
                            <div>
                                <span class="fw-bold"> {{ user_data.name }} </span>

                                <!-- В сети -->
                                <span class="small px-1" v-if="new Date(user_data.last_active).getTime() > new Date().getTime()-60000">
                            {{ $t('userAdsOnline') }}
                        </span>
                                <span class="small px-1" v-else>
                            {{ $t('userAdsWas')}} {{$filters.transformDateRu(user_data.last_active)}}
                        </span>

                            </div>

                            <!-- Дата регистрации пользователя -->
                            <div  style="font-size: 0.7em">{{ $t('userAdsOnLaimWith') }} {{ $filters.transformDateRu(user_data.created_at)}}</div>

                        </div>

                    </div>

                    <!-- Кнопка назад -->
                    <v-btn icon dark variant="text" @click="$router.back()">
                        <v-icon>mdi-arrow-right</v-icon>
                    </v-btn>

                </div>

                <!-- Body - Вывод превью всех чатов или вывод 1-го чата -->
                <div class="chat__body" id="chat__body">

                    <!-- Gif Load  - Если объявления еще не загрузились -->
                    <div v-if="query" class="d-flex justify-content-center">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                    </div>

                    <!-- Блок превью чатами -->
                    <div v-if="$route.params.id == 'null' && all_chats.length > 0"
                         v-for="(chat, index) of all_chats" class="px-2"
                    >

                        <!-- Чат -->
                        <div class="row g-0 chat__preview-wrapper align-items-center p-2">

                            <!-- Иконка user -->
                            <div class="col-auto"><i class="bi bi-person-circle" style="font-size: 2em"></i></div>

                            <!-- Имя отправителя / Последнее СМС -->
                            <div @click="$router.push('/chat/' + chat.id)" class="col mx-2" role="button">

                                <!-- Имя отпровителя написавшего мне и сколько новых от него смс -->
                                <div>
                                    <v-badge floating v-if="chat.count_new_message != undefined && chat.count_new_message > 0" :content="chat.count_new_message" color="error">
                                        {{ chat.user_data.name}}
                                    </v-badge>
                                    <span v-else>{{ chat.user_data.name}}</span>
                                </div>

                                <!-- Последнее СМС и иконка прочитал или нет -->
                                <div class="d-inline-block text-truncate" style="max-width:150px; font-size: 0.9em; color: #656464">

                                    <!-- Иконка прочитанно или нет последнее смс  -->
                                    <i v-if="chat.sender_id == authStore.user.id" class="bi bi-check2-all" :class="{'text-blue':chat.sender_id == authStore.user.id && chat.open_recipient}"></i>

                                    <!-- Сам Текст последнего смс -->
                                    <small>{{chat.message}}</small>

                                </div>

                            </div>

                            <!-- Дата отправки, Кнопка удалить чат -->
                            <div class="col-auto text-end position-relative">

                                <v-btn icon size="small" @click="deleteChat(chat, index)"><v-icon color="black">mdi-trash-can-outline</v-icon></v-btn>

                                <!-- Дата отправки -->
                                <div class="text-muted py-1" style="font-size: 0.6em">
                                    {{ $filters.transformDateRu(chat.created_at)}}
                                </div>
                            </div>
                        </div >

                    </div>

                    <!-- Блок с 1 чатом  -->
                    <div v-if="$route.params.id != 'null'" class="one__chat position-relative">

                        <!-- Блок с сообщениями - Использую эту конструкцию чтоб показать дату сверху сообщений -->
                        <div v-for="(group, index) in groupedMessages" :key="index">

                            <!-- Дата сверху сообщений - Вчера, сегодня и тд.-->
                            <div class="d-flex justify-center align-center" style="position: sticky; top:3px; z-index: 1">
                                <div class="p-1 bg-white rounded-lg text-black">{{ $filters.transformDateRuNotWatch(group.date) }}</div>
                            </div>

                            <!-- Сообщения -->
                            <div class="messages__block" v-for="message in group.messages" :key="message.id">

                                <!-- Компонент вывод превью объявлений -->
                                <div v-if="message.ads_id != null" v-for="(ads, index) of Array(message)" :key="index" style="font-family: 'Open-Sans'">
                                    <v-card max-width="600" style="box-shadow: none; border: 1px solid #dce1e6; border-radius: 12px"
                                            class="mx-3 my-3 mx-sm-auto"
                                            :class="{
                                                'bg-teal-lighten-4': ads.top_x7 != undefined && ads.top_x7 != null,
                                                'bg-red-lighten-4': ads.top_x30 != undefined && ads.top_x30 != null,
                                            }"
                                    >

                                        <!-- Описание -->
                                        <div class="d-flex p-md-2">

                                            <!-- Фото -->
                                            <div class="position-relative" style="width: 170px; max-height: 150px; overflow: hidden">

                                                <!-- Срочно торг -->
                                                <div v-if="ads.srochno_torg != undefined && ads.srochno_torg" style="position: absolute; top: 5px; left: 5px;" class="bg-yellow-darken-2 rounded-sm text-caption px-1">
                                                    {{ $t('adsPreviewComponentUrgentBargaining') }}
                                                </div>

                                                <img v-if="ads.images[0] != 'no-image'" @click="$router.push('/imageChat'), imageStore.showImages({images: ads.images,index: 0, allImg: true})" class="rounded-sm" :src=" '/img/adsImg/' + ads.images[0] " style="width: 100%; height: 150px; object-fit: cover; object-position: center" alt="Фото недвижимости">
                                                <img v-else src="/public/img/siteImg/allImg/no-image-buildings.png" alt="Нет фото" style="width: 100%; height: 150px;">

                                            </div>

                                            <!--Блок - Описание объявления -->
                                            <div class="col pl-2">

                                                <div @click="$router.push({name: 'oneAds' ,params: {table_name: ads.table_name, ads_id: ads.ads_id == undefined ? ads.id: ads.ads_id }})"
                                                     role="button" class="d-flex align-start flex-column" style="height: 115px"
                                                >

                                                    <!-- Заголовок -->
                                                    <div style="font-size: 1.1em; color: #4b4b4b">
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

                                                <!-- Дата публикации  -->
                                                <div class="d-flex align-center gap-2 position-relative">

                                                    <!-- Дата публикации -->
                                                    <div style="font-size: 0.9em; color: #5d6f6a">
                                                        {{ $filters.transformDateRu(ads.ads_created) }}
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </v-card>
                                </div>

                                <!-- Мое смс -->
                                <div v-if="message.sender_id == authStore.user.id" class="row g-0 justify-content-end my-2">

                                    <div class="col-auto rounded-3" style="max-width: 80%; padding: 3px 7px 3px 7px; background: #d9fdd3;">

                                        <!-- Вывод прикрепленных  фото - Слайдер фото - Swiper  -->
                                        <div v-if="message.ads_id == null && message.images[0] != 'no-image'" class="col-auto my-1 position-relative" style="width: 150px; height: 100px">

                                            <swiper :modules="modules" :mousewheel="true" :slides-per-view="1"
                                                    :space-between="0" :pagination="{type: 'fraction'}">

                                                <!-- Сам слайд Фото -->
                                                <swiper-slide v-for="(img, i) in message.images" :key="i">
                                                    <img @click="$router.push('/imageChat'), imageStore.showImages({images: message.images,index: i, chatImg: true})" class="rounded-t-sm" :src=" '/img/messageImg/' + img " style="width: 100%; height: 90px; object-fit: cover; object-position: center" alt="Фото недвижимости">
                                                </swiper-slide>
                                            </swiper>

                                        </div>

                                        <span class="text-body-1 pr-5" style="white-space: pre-line;">{{ message.message }}</span>

                                        <div class="text-muted text-end" style="font-size: 0.7em">
                                            {{ $filters.transformDateWatch(message.created_at) }}
                                            <i v-if="message.open_recipient" class="bi bi-check2-all text-blue"></i>
                                            <i v-else class="bi bi-check2-all text-dark" ></i>
                                        </div>

                                    </div>

                                </div>

                                <!-- Не мое смс -->
                                <div v-else class="row g-0 my-2">

                                    <div class="col-auto rounded-3 position-relative" style="max-width: 80%; padding: 3px 7px; background: #ffffff;">

                                        <!-- Вывод прикрепленных  фото - Слайдер фото - Swiper  -->
                                        <div v-if="message.ads_id == null && message.images[0] != 'no-image'" class="col-auto my-1 position-relative" style="width: 150px; height: 100px">

                                            <swiper :modules="modules" :mousewheel="true" :slides-per-view="1"
                                                    :space-between="0" :pagination="{type: 'fraction'}">

                                                <!-- Сам слайд Фото -->
                                                <swiper-slide v-for="(img, i) in message.images" :key="i">
                                                    <img @click="$router.push('/imageChat'), imageStore.showImages({images: message.images,index: i, chatImg: true})" class="rounded-t-sm" :src=" '/img/messageImg/' + img " style="width: 100%; height: 90px; object-fit: cover; object-position: center" alt="Фото недвижимости">
                                                </swiper-slide>
                                            </swiper>

                                        </div>

                                        <div class="text-body-1 pr-5" style="white-space: pre-line;">{{message.message}}</div>
                                        <div class="text-muted text-end" style="font-size: 0.7em">
                                            {{ $filters.transformDateWatch(message.created_at) }}
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>


                    </div>

                </div>

                <!-- Footer - Форма отправить смс -->
                <div v-if="$route.params.id != 'null'" class="chat__footer">

                    <!-- Gif Load  - Если объявления еще не загрузились -->
                    <div v-if="sendMessageQuery" class="d-flex justify-content-center">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                    </div>

                    <!-- Форма -->
                    <validation-observer as="div" ref="form" v-slot="{ handleSubmit }">
                        <form @submit="handleSubmit($event, sendMessage)" class="row g-0 align-items-center">

                            <!-- Добавить Фото -->
                            <div v-if="show_upload_image" class="col-12">
                                <!-- Поле для выбора картинок с валидацией на клиенте -->
                                <validation-provider rules="image" ref="provider" name="Фото" v-slot="{errors, valid, validate}">
                                    <input type="file" name="images[]"  id="images" accept="image/*" @change="uploadImg" class="d-none" multiple>
                                    <label v-if="imageAndPreviewImage.length < 10" for="images" role="button" class="v-btn v-btn--block v-theme--light text-blue-darken-2 v-btn--density-default v-btn--size-x-large v-btn--variant-outlined fw-bold">
                                        <i class="bi bi-camera-fill"></i>
                                        {{ $t('chatAddPhotoMax10') }}
                                    </label>
                                </validation-provider>

                                <!-- Draggable - Отобразим превью фото перед загрузкой на сервер -->
                                <div id="druggable">
                                    <draggable class="row g-0 gap-2 p-2 justify-content-center" :list="imageAndPreviewImage" @end="onEnd">
                                <span class="col-auto position-relative" v-for="(img, index) of imageAndPreviewImage" :key="index">
                                    <v-btn size="x-small" icon @click="deleteImage(index)" style="position: absolute; top: 5px; right: 5px">
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                    <img :src="img.previewImg" @click="$router.push('/imageChat'), imageStore.showImages({images: imageAndPreviewImage,index, chatImg:true})"  width="100" height="100" role="button">
                                </span>
                                    </draggable>
                                </div>
                            </div>

                            <!-- Кнопка показать загрузку фото -->
                            <div class="col-auto mb-3" @click="show_upload_image = !show_upload_image" role="button">
                                <i class="bi bi-camera-fill" style="font-size: 2em"></i>
                            </div>

                            <!-- Поле textarea -->
                            <div class="form-group col">

                                <validation-provider rules="max:200" v-model="form.message" name="message" v-slot="{ errors }">

                                    <v-textarea v-model="form.message"
                                                name="message" :label="$t('chatEnterYourMessage')"
                                                variant="outlined" color="blue-darken-2"
                                                auto-grow rows="1" max-rows="3"
                                                counter maxlength="200"

                                                :error-messages="form.errors.has('message') ? form.errors.get('message'):'' || errors[0]"
                                                @input="form.errors.clear('message')"
                                    ></v-textarea>

                                </validation-provider>

                            </div>

                            <!-- Кнопка отправки -->
                            <v-btn type="submit" variant="text" icon :disabled="query" class="col-auto mb-3">
                                <i class="bi bi-send-fill" style="font-size: 2em; color: #10a37f"></i>
                            </v-btn>

                        </form>
                    </validation-observer>

                </div>

            </div>

        </div>

    </transition>

    <!-- Вывожу внутренние страницы -->
    <router-view></router-view>

</template>

<script>
// Слайдер фото Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination, Mousewheel } from 'swiper';
import 'swiper/scss';
import 'swiper/scss/pagination';
import 'swiper/scss/mousewheel';


//Валидация laravel VFORM
import Form from 'vform'
import { Button, HasError, AlertError } from 'vform/src/components/bootstrap5'

//Импортирую Store - Общее состояние
import {useAuthStore} from "../../stores/auth";
import {useImagesStore} from "../../stores/images";
import { useCheckInternetStore } from "../../stores/checkInternet";
import { useGetProjectDataStore } from "../../stores/getProjectData";
import {useUpdateDateLocaleStore} from "../../stores/updateDateLocale";

//Пакет для перемещения картинок в массиве (по экрану)
import { defineComponent } from 'vue'
import { VueDraggableNext } from 'vue-draggable-next'


export default defineComponent({
    name: "Index",
    components: {
        Button, HasError, AlertError,
        Swiper, SwiperSlide,
        draggable: VueDraggableNext,
    },

    data(){
        return {
            //Подключаю Store - Наше общее состояние
            authStore: useAuthStore(),
            imageStore: useImagesStore(),
            checkInternetStore: useCheckInternetStore(),
            getProjectDataStore: useGetProjectDataStore(),
            updateDateLocale: useUpdateDateLocaleStore(),

            chatAnimation: false,
            all_chats: [], //Получаю Все чаты с последним сообщением
            all_messages: [], //Получаю один чат со всеми переписками
            user_data: '', //Данные с кем ведется переписка

            //ОТПРАВКА СООБЩЕНИЯ
            query: false,
            sendMessageQuery: false,
            show_upload_image: false,
            form: new Form({
                sender_id: '',
                recipient_id: '',
                message: '',
                images: [],
                recaptcha_token: ''
            }),
            imageAndPreviewImage: [],

            //Подключаем доп модули к слайдеру SWIPER
            modules: [Pagination, Mousewheel],

        }
    },

    watch:{

        '$route' (to, from) {

            if(to.name == 'chat' && from.name == 'chat'){
                //Получить новые чаты
                if(this.$route.params.id == 'null'){
                    this.getAllChats();
                }else{
                    this.getAllMessage(this.$route.params.id)
                }
            }

        },

        //Отслеживаю чаты и если мне кто-то написал запрошу новые чаты - или переписку с этим пользователем
        async "getProjectDataStore.countNewMessage"(){

            //Получить новые чаты
            if(this.$route.params.id == 'null'){
                this.getAllChats();
            }else{
                if(getProjectDataStore.countNewMessage > 0)this.getAllMessage(this.$route.params.id, false)
            }
        }

    },

    computed: {

        //Для отображения даты вверху сообщений -- Сегодня, вчера и тд.
        groupedMessages() {
            const groups = [];
            let currentGroup = null;

            for (const message of this.all_messages) {
                const messageDate = new Date(message.created_at).toDateString();

                if (!currentGroup || currentGroup.date !== messageDate) {
                    currentGroup = { date: messageDate, messages: [] };
                    groups.push(currentGroup);
                }

                currentGroup.messages.push(message);
            }

            return groups;
        }
    },

    methods: {

        //Получить все мои чаты, с последним сообщением
        async getAllChats(){
            this.query = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            await this.checkInternetStore.checkInternet()


            axios.get('/getAllChats?user_id=' + this.authStore.user.id)
                .then(response=>{
                    this.query = false;
                    this.all_chats = response.data;
                })
                .catch(errors=>{
                    this.query = false;
                })
        },

        //Получить все сообщения 1-го чата
        async getAllMessage(chat_id, one_upload = true, send_message = false){
            this.query = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            await this.checkInternetStore.checkInternet()

            //Если это переход в другой чат то сразу отчистим иначе нет так как идет перезагрузка при отравки смс
            if(!send_message){
                this.all_messages = [];
                this.user_data = '';
            }


            //Получим все СМС 1-го чата
            axios.get('/getAllMessages', {params : {
                    chat_id: chat_id,
                    sender_id: this.authStore.user.id,
                }})
                .then(response=>{
                    this.query = false;

                    //Занашу в массив всю переписку и данны с кем переписка
                    this.all_messages = response.data.messages;
                    this.user_data = response.data.recipient_data;

                    //Прокрутить чат до последнего смс при первом открытии чата, так как есть рекурсивныйвызов этого же метода и если не проверить то постоянно будет кидать на верх
                    if(one_upload){
                        setTimeout(() => {
                            let chatBody = document.getElementById('chat__body');
                            chatBody.scrollTop = chatBody.scrollHeight
                        }, 0)
                    }

                    //Отнимем просмотренные смс в Store
                    if(chat.count_new_message != undefined && chat.count_new_message != 0){
                        this.getProjectDataStore.updateCountNewMessage(chat.count_new_message);
                    }

                })
                .catch(errors=>{
                    this.query = false;
                })
        },

        //Метод отправить сообщения
        async sendMessage(){

            this.form.sender_id = this.authStore.user.id;
            this.form.recipient_id = this.user_data.id;

            //Добавим фото в массив из промежуточного массива
            this.form.images = [];
            this.imageAndPreviewImage.forEach((elem)=>{
                this.form.images.push(elem.image);
            })


            //Проверка если нет фото и нет текста то ошибка
            if(this.form.images.length == 0 && this.form.message == '' || this.form.images.length == 0 && this.form.message == undefined){
                this.$refs.form.setFieldError( 'message', this.$t('chatEnterYourMessage'));
                return;
            }

            this.sendMessageQuery = true;

            //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
            await this.checkInternetStore.checkInternet()


            this.form.post('/sendMessage', {
                transformRequest: [function (data, headers) {
                    return objectToFormData(data)
                }]
            })
                .then(response=>{
                    //Отчистим форму
                    this.form.message = '';
                    this.form.images = [];
                    this.imageAndPreviewImage = [];
                    this.show_upload_image = false;
                    this.imageStore.deleteAll();
                    this.sendMessageQuery = false;

                    //отчистим форму
                    setTimeout(()=>{
                        this.$refs.form.resetForm();
                    },0)

                    //Получим новые смс
                    this.getAllMessage(this.$route.params.id, true,true);
                })
                .catch(errors=>{
                    Toast.fire({
                        icon: 'error',
                        title: this.$t('chatSendError')
                    })
                    this.sendMessageQuery = false;
                })
        },

        //Удалить чат
        async deleteChat(chat, index){
            Swal.fire({
                title: this.$t('chatDeleteChat1'),
                text: this.$t('chatDeleteChat2'),
                showCancelButton: true,
                confirmButtonText: this.$t('chatDelete'),
                cancelButtonText: this.$t('chatNo')
            }).then(async (result) => {
                if (result.isConfirmed) {

                    this.query = true;

                    //Проверка наличие интернета - Если нет то выведем alert в AppComponent.vue
                    await this.checkInternetStore.checkInternet()

                    axios.delete('/deleteChat', {params : {
                            sender_id: this.authStore.user.id,
                            recipient_id: chat.sender_id == this.authStore.user.id ? chat.recipient_id : chat.sender_id

                        }})
                        .then(response=>{
                            Toast.fire({
                                icon: 'success',
                                title: this.$t('chatChatRemoved')
                            })

                            //Удалим элемент с массива
                            this.all_chats.splice(index, 1);
                            this.query = false;
                        }).catch(()=>{
                        this.query = false;
                    })
                }
            })

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
                    images.previewImg = URL.createObjectURL(image);

                    //Готовый объект фото и превью занесу в масив если там не более 10 фото иначе остановлю цикл
                    this.imageAndPreviewImage.length < 10 ? this.imageAndPreviewImage.push(images) : '';

                }
                else{
                    //Если фото не валидно сообщим об этом
                    Toast.fire({
                        icon: 'error',
                        title: 'Не фото'
                    });
                }
            }

        },

        //Метод Удалить одно фото с массива по index
        deleteImage(index){
            this.imageAndPreviewImage.splice( index , 1 );
        },

        //При прикращении перетаскивания
        onEnd(event){
            document.getElementById('druggable').click();
        },

    },

    mounted(){
        const app = this;

        this.chatAnimation = true;
        document.querySelector(':root').classList.add('PATCH_modal'); //Отменим прокрутку за OffCanvas

        //Получить чаты или Сообщения 1-го чата
        if(this.$route.params.id == 'null'){
            this.getAllChats();
        }
        else{
            this.getAllMessage(this.$route.params.id);
        }
    },
    beforeRouteLeave(to, from, next){
        this.chatAnimation = false;
        document.querySelector(':root').classList.remove('PATCH_modal');

        setTimeout(() => {
            next(); // Вызываем next() после завершения setTimeout - Для завершения анимации
        }, 350);
    }

})

</script>

<style>
/* Анимация для плавного появления -- Backdrop */
.chat__animation-backdrop-enter-active,
.chat__animation-backdrop-leave-active{
    transition: opacity 0.3s ease;
}

.chat__animation-backdrop-enter-from,
.chat__animation-backdrop-leave-to {
    opacity: 0;
}

.chat__animation-backdrop-enter-to,
.chat__animation-backdrop-leave-from {
    opacity: 1;
}

/* Анимация для плавного появления -- wrapper */
.chat__animation-wrapper-enter-active,
.chat__animation-wrapper-leave-active{
    transition: transform 0.3s ease;
}

.chat__animation-wrapper-enter-from{
    transform: translateX(50%); /* Начальное положение при анимации скрытия */
}

.chat__animation-wrapper-leave-to {
    transform: translateX(100%); /* Начальное положение при анимации скрытия */
}

.chat__animation-wrapper-enter-to,
.chat__animation-wrapper-leave-from {
    transform: translateY(0);/* Конечное положение при анимации появления */
}
</style>


<style scoped>

/* Backdrop */
.chat__backdrop{
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: 0;
    background: rgba(0,0,0,0.5);
}

.chat__wrapper{
    position: fixed;
    top: 0;
    bottom:0;
    right: 0;
    left: auto;
    width: 100%;
    max-width: 450px;
    background: #ffffff;
}

.chat__block{
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
    background: url("/img/siteImg/allImg/chat-bg.png");
}

.chat__header{
    width: 100%;
    height: 44px;
    display: flex;
    align-items: center;
    background: var(--app-header-bg-color);
    color: #ffffff;
    padding: 5px;
}

.chat__body{
    flex-grow: 1;
    width: 100%;
    overflow-y: auto;
    padding: 5px 10px;
}

.chat__preview-wrapper{
    border-radius: 5px;
    background: #fffcfc;
    margin-top: 5px;
    border: 1px solid #d8d8d8;
}

.chat__footer{
    padding: 5px;
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
    font-size: .7em;
}
</style>
