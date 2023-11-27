import {
    Form as ValidationObserver,
    Field as ValidationProvider,
    ErrorMessage,

    defineRule,
    configure
} from "vee-validate";

import { localize } from '@vee-validate/i18n';
import ru from '@vee-validate/i18n/dist/locale/ru.json';
import en from '@vee-validate/i18n/dist/locale/en.json';
import kz from '../../../node_modules/@vee-validate/i18n/dist/locale/kz.json';
import { setLocale } from '@vee-validate/i18n';


import * as rules from '@vee-validate/rules';


export default {
    install(app) {
        app.component("ValidationObserver", ValidationObserver);
        app.component("ValidationProvider", ValidationProvider);
        app.component("ErrorMessage", ErrorMessage);

        Object.keys(rules).forEach(rule => {
            if(rule != 'default'){
                defineRule(rule, rules[rule]);
            }
        });

        localize({ru, kz,en})
        //Указываем локаль
        configure({
            generateMessage: localize({
                ru:{
                    names: {
                        tip_sdelki: 'Тип сделки',
                        zagolovok: 'Заголовок',
                        sfera_deyatelnosti: 'Сфера деятельности',
                        tip_ofisa: 'Тип офиса',
                        tip_obekta: 'Тип объекта',
                        period_arendi: 'Период аренды',
                        kolichestvo_komnat: 'Количество комнат',
                        deistvuushii_bisnes: 'Действующий бизнес',
                        mestopolojenie: 'Местоположение',
                        cena_tip: 'Цена в месяц',
                        cena: 'Цена',
                        etag: 'Этаж',
                        etagnost: 'Этажность дома',
                        visota_potolkov: 'Высота потолков',
                        ploshad_obshaya: 'Общая площадь',
                        ploshad_kuhni: 'Площадь кухни',
                        ploshad_uchastka: 'Площадь участка',
                        tip_stroeniya: 'Тип строения',
                        sostoyanie: 'Состояние',
                        mebel: 'Мебель',
                        internet: 'Интернет',
                        dush: 'Душ',
                        sanuzel: 'Санузел',
                        otdelniy_vhod: 'Отдельный вход',
                        kanalizaciya: 'Канализация',
                        voda: 'Вода',
                        elektrichestvo: 'Электричество',
                        otoplenie: 'Отопление',
                        gaz: 'Газ',
                        balkon: 'Балкон',
                        balkon_osteklen: 'Балкон остеклен',
                        parkovka: 'Парковка',
                        pol: 'Пол',
                        ploshad_proizvodstvennih_pomesheniy: 'Площадь производственных помещений',
                        visota_proizvodstvennih_pomesheniy: 'Высота производственных помещений',
                        ploshad_skladskih_pomesheniy: 'Площадь складских помещений',
                        visota_skladskih_pomesheniy: 'Высота складских помещений',
                        ploshad_ofisnih_pomesheniy: 'Площадь офисных помещений',
                        text_obyavleniya: 'Текст объявления',
                        images: 'Фото',
                        oblast: 'Область',
                        gorod: 'Город',
                        ulica: 'Улица',
                        nomer_doma: 'Номер дома',
                        ownerOrRealtor: 'Хозяин или риэлтор',
                        name: 'Имя',
                        tel: 'Номер телефона',
                        tel2: 'Номер телефона 2',
                        password: 'Пароль',
                        message: 'Сообщение'

                    }
                },
                kz:{
                    names: {
                        tip_sdelki: 'Мәміле түрі',
                        zagolovok: 'Тақырып',
                        sfera_deyatelnosti: 'Қызмет саласы',
                        tip_ofisa: 'Кеңсе түрі',
                        tip_obekta: 'Нысан түрі',
                        period_arendi: 'Жалдау мерзімі',
                        kolichestvo_komnat: 'Бөлмелер саны',
                        deistvuushii_bisnes: 'Жұмыс істеп тұрған бизнес',
                        mestopolojenie: 'Орналасқан жері',
                        cena_tip: 'Айына баға',
                        cena: 'Бағасы',
                        etag: 'Қабат',
                        etagnost: 'Үйдің қабаты',
                        visota_potolkov: 'Төбелердің биіктігі',
                        ploshad_obshaya: 'Жалпы ауданы',
                        ploshad_kuhni: 'Ас үй ауданы',
                        ploshad_uchastka: 'Учаскенің ауданы',
                        tip_stroeniya: 'Құрылым түрі',
                        sostoyanie: 'Жағдайы',
                        mebel: 'Жиһаз',
                        internet: 'Интернет',
                        dush: 'Душ',
                        sanuzel: 'Жуынатын бөлме',
                        otdelniy_vhod: 'Бөлек кіреберіс',
                        kanalizaciya: 'Кәріз',
                        voda: 'Су',
                        elektrichestvo: 'Электр қуаты',
                        otoplenie: 'Жылыту',
                        gaz: 'Газ',
                        balkon: 'Балкон',
                        balkon_osteklen: 'Балкон әйнектелген',
                        parkovka: 'Тұрақ',
                        pol: 'Еден',
                        ploshad_proizvodstvennih_pomesheniy: 'Өндірістік үй-жайлардың ауданы',
                        visota_proizvodstvennih_pomesheniy: 'Өндірістік үй-жайлардың биіктігі',
                        ploshad_skladskih_pomesheniy: 'Қойма үй-жайларының ауданы',
                        visota_skladskih_pomesheniy: 'Қойма үй-жайларының биіктігі',
                        ploshad_ofisnih_pomesheniy: 'Кеңсе кеңістігінің ауданы',
                        text_obyavleniya: 'Хабарландыру Мәтіні',
                        images: 'Фото',
                        oblast: 'Облыс',
                        gorod: 'Қала',
                        ulica: 'Көше',
                        nomer_doma: 'Үй нөмірі',
                        ownerOrRealtor: 'Меншік иесі немесе риэлтор',
                        name: 'Аты',
                        tel: 'Телефон нөмірі',
                        tel2: 'Телефон нөмірі 2',
                        password: 'Құпия сөз',
                        message: 'Хабар'

                    }
                },
                en:{
                    names: {
                        tip_sdelki: 'Trade type',
                        zagolovok: 'Title',
                        sfera_deyatelnosti: 'Field of activity',
                        tip_ofisa: 'Office type',
                        tip_obekta: 'Object Type',
                        period_arendi: 'Rent period',
                        kolichestvo_komnat: 'Number of rooms',
                        deistvuushii_bisnes: 'Operating business',
                        mestopolojenie: 'Location',
                        cena_tip: 'Price per month',
                        cena: 'Price',
                        etag: 'Floor',
                        etagnost: 'Floors of the house',
                        visota_potolkov: 'Ceiling height',
                        ploshad_obshaya: 'total area',
                        ploshad_kuhni: 'kitchen area',
                        ploshad_uchastka: 'Land area',
                        tip_stroeniya: 'Building type',
                        sostoyanie: 'State',
                        mebel: 'Furniture',
                        internet: 'Internet',
                        dush: 'Shower',
                        sanuzel: 'bathroom',
                        otdelniy_vhod: 'Separate entrance',
                        kanalizaciya: 'Sewerage',
                        voda: 'Water',
                        elektrichestvo: 'Electricity',
                        otoplenie: 'Heating',
                        gaz: 'Gas',
                        balkon: 'Balcony',
                        balkon_osteklen: 'The balcony is glazed',
                        parkovka: 'parking',
                        pol: 'Floor',
                        ploshad_proizvodstvennih_pomesheniy: 'Production area',
                        visota_proizvodstvennih_pomesheniy: 'Height of production premises',
                        ploshad_skladskih_pomesheniy: 'Warehouse area',
                        visota_skladskih_pomesheniy: 'Warehouse height',
                        ploshad_ofisnih_pomesheniy: 'Office area',
                        text_obyavleniya: 'Announcement text',
                        images: 'Photo',
                        oblast: 'Region',
                        gorod: 'City',
                        ulica: 'Street',
                        nomer_doma: 'House number',
                        ownerOrRealtor: 'Owner or Realtor',
                        name: 'Name',
                        tel: 'Phone number',
                        tel2: 'Phone number 2',
                        password: 'Password',
                        message: 'Message'

                    }
                }
            }),
        });
        setLocale('ru');
    },
};
