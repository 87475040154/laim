import { defineStore } from 'pinia'
import { useUpdateDateLocaleStore } from "./updateDateLocale";

export const useKZLocationStore= defineStore('KZLocation', {

    //Свойства
    state: ()=>({
        LOCATION: '',
    }),

    //Получаем доступ к свойствам
    getters: {
        location: ( state )=> state.LOCATION,
    },

    actions: {

        //Занесем
        addLocation(location){
            this.LOCATION = location;
        },

        // Перевести локации
        translateLocation(location){
            const lang = useUpdateDateLocaleStore().lang;

            let oblast_translate = '';
            let gorod_translate = '';
            let raion_translate = '';

            if(location.oblast != null){
                if(lang == 'ru'){
                    oblast_translate = location.oblast;
                }
                if(lang == 'kz'){
                    const oblastTranslations = {
                        'Астана': 'Астана',
                        'Алматы': 'Алматы',
                        'Шымкент': 'Шымкент',
                        'Абайская обл.': 'Абай обл.',
                        'Акмолинская обл.': 'Ақмола обл.',
                        'Актюбинская обл.': 'Ақтөбе обл.',
                        'Алматинская обл.': 'Алматы обл.',
                        'Атырауская обл.': 'Атырау обл.',
                        'Восточно-Казахстанская обл.': 'Шығыс Қазақстан обл.',
                        'Жамбылская обл.': 'Жамбыл обл.',
                        'Жетысуская обл.': 'Жетісу обл.',
                        'Западно-Казахстанская обл.': 'Батыс Қазақстан обл.',
                        'Карагандинская обл.': 'Қарағанды обл.',
                        'Костанайская обл.': 'Қостанай обл.',
                        'Кызылординская обл.': 'Қызылорда обл.',
                        'Мангистауская обл.': 'Маңғыстау обл.',
                        'Павлодарская обл.': 'Павлодар обл.',
                        'Северо-Казахстанская обл.': 'Солтүстік Қазақстан обл.',
                        'Туркестанская обл.': 'Түркістан обл.',
                        'Улытауская обл.': 'Ұлытау обл.'
                    };
                    oblast_translate = oblastTranslations[location.oblast];
                }
                if(lang == 'en'){
                    const oblastTranslations = {
                        'Астана': 'Astana',
                        'Алматы': 'Almaty',
                        'Шымкент': 'Shymkent',
                        'Абайская обл.': 'Abai region',
                        'Акмолинская обл.': 'Akmola region',
                        'Актюбинская обл.': 'Aktobe region',
                        'Алматинская обл.': 'Almaty region',
                        'Атырауская обл.': 'Atyrau region',
                        'Восточно-Казахстанская обл.': 'East Kazakhstan region',
                        'Жамбылская обл.': 'Zhambyl region',
                        'Жетысуская обл.': 'Jetysui region',
                        'Западно-Казахстанская обл.': 'West Kazakhstan region',
                        'Карагандинская обл.': 'Karaganda region',
                        'Костанайская обл.': 'Kostanay region',
                        'Кызылординская обл.': 'Kyzylorda region',
                        'Мангистауская обл.': 'Mangystau region',
                        'Павлодарская обл.': 'Pavlodar region',
                        'Северо-Казахстанская обл.': 'North Kazakhstan region',
                        'Туркестанская обл.': 'Turkestan region',
                        'Улытауская обл.': 'Ulytau region'
                    };
                    oblast_translate = oblastTranslations[location.oblast];
                }
            }
            if(location.gorod != null){
                if(lang == 'ru'){
                    gorod_translate = location.gorod;
                }
                if(lang == 'kz'){
                    const gorodTranslations = {
                        'Астана': 'Астана',
                        'Алматы': 'Алматы',
                        'Шымкент': 'Шымкент',
                        'Семей': 'Семей',
                        'Аягоз': 'Аягөз',
                        'Курчатов': 'Курчатов',
                        'Урджар': 'Үржар',
                        'Чарск': 'Шар',
                        'Кокшетау': 'Көкшетау',
                        'Косшы': 'Қосшы',
                        'Щучинск': 'Щучинск',
                        'Степногорск': 'Степногорск',
                        'Атбасар': 'Атбасар',
                        'Акколь': 'Ақкөл',
                        'Державинск': 'Державин',
                        'Макинск': 'Макинск',
                        'Степняк': 'Степняк',
                        'Есиль': 'Есіл',
                        'Ерейментау': 'Ерейментау',
                        'Актобе': 'Ақтөбе',
                        'Кандыагаш': 'Қандыағаш',
                        'Алга': 'Алға',
                        'Жем': 'Жем',
                        'Темир': 'Темір',
                        'Хромтау': 'Хромтау',
                        'Шалкар': 'Шалқар',
                        'Эмба': 'Ембі',
                        'Каскелен': 'Қаскелең',
                        'Конаев': 'Қонаев',
                        'Есик': 'Есік',
                        'Талгар': 'Талғар',
                        'Узынагаш': 'Ұзынағаш',
                        'Шелек': 'Шелек',
                        'Атырау': 'Атырау',
                        'Кульсары': 'Құлсары',
                        'Усть-Каменогорск': 'Өскемен',
                        'Риддер': 'Риддер',
                        'Алтай': 'Алтай',
                        'Зайсан': 'Зайсаң',
                        'Серебрянск': 'Серебрянск',
                        'Шемонаиха': 'Шемонаиха',
                        'Тараз': 'Тараз',
                        'Шу': 'Шу',
                        'Жанатас': 'Жаңатас',
                        'Каратау': 'Қаратау',
                        'Кордай': 'Қордай',
                        'Сарыкемер': 'Сарыкемер',
                        'Толе би': 'Төле би',
                        'Талдыкорган': 'Талдықорған',
                        'Жаркент': 'Жаркент',
                        'Сарканд': 'Сарқант',
                        'Текели': 'Текелі',
                        'Уштобе': 'Үштөбе',
                        'Ушарал': 'Ушарал',
                        'Уральск': 'Орал',
                        'Аксай': 'Ақсай',
                        'Зачаганск': 'Зачаганск',
                        'Караганда': 'Қарағанды',
                        'Темиртау': 'Теміртау',
                        'Абай': 'Абай',
                        'Балхаш': 'Балқаш',
                        'Каркаралинск': 'Қарқаралы',
                        'Приозерск': 'Приозер',
                        'Сарань': 'Саран',
                        'Шахтинск': 'Шахтинск',
                        'Костанай': 'Қостанай',
                        'Рудный': 'Рудный',
                        'Аркалык': 'Арқалық',
                        'Аулиеколь': 'Әулиекөл',
                        'Житикара': 'Жітіқара',
                        'Качар': 'Қашар',
                        'Карабалык': 'Қарабалық',
                        'Лисаковск': 'Лисаковск',
                        'Сарыколь': 'Сарыкөл',
                        'Тобыл': 'Тобыл',
                        'Кызылорда': 'Қызылорда',
                        'Байконур': 'Байқоңыр',
                        'Аральск': 'Арал',
                        'Айтеке би': 'Әйтеке би',
                        'Жанакорган': 'Жаңақорған',
                        'Казалинск': 'Қазалы',
                        'Тасбогет': 'Тасбөгет',
                        'Шиели': 'Шиелі',
                        'Актау': 'Ақтау',
                        'Жанаозен': 'Жаңаөзен',
                        'Форт-Шевченко': 'Форт-Шевченко',
                        'Бейнеу': 'Бейнеу',
                        'Мангистау': 'Маңғыстау',
                        'Павлодар': 'Павлодар',
                        'Экибастуз': 'Екібастұз',
                        'Аксу': 'Ақсу',
                        'Петропавловск': 'Петропавл',
                        'Бесколь': 'Бескөл',
                        'Булаево': 'Булаев',
                        'Мамлютка': 'Мамлют',
                        'Сергеевка': 'Сергеев',
                        'Тайынша': 'Тайынша',
                        'Туркестан': 'Түркістан',
                        'Кентау': 'Кентау',
                        'Арыс': 'Арыс',
                        'Жетысай': 'Жетісай',
                        'Карабулак': 'Қарабұлақ',
                        'Ленгер': 'Леңгір',
                        'Сарыагаш': 'Сарыагаш',
                        'Шардара': 'Шардара',
                        'Жезказган': 'Жезқазған',
                        'Каражал': 'Қаражал',
                        'Сатпаев': 'Сәтбаев',
                    };
                    gorod_translate = gorodTranslations[location.gorod];
                }
                if(lang == 'en'){
                    const gorodTranslations = {
                        'Астана': 'Astana',
                        'Алматы': 'Almaty',
                        'Шымкент': 'Shymkent',
                        'Семей': 'Semey',
                        'Аягоз': 'Ayagoz',
                        'Курчатов': 'Kurchatov',
                        'Урджар': 'Urjar',
                        'Чарск': 'Charsk',
                        'Кокшетау': 'Kokshetau',
                        'Косшы': 'Koschi',
                        'Щучинск': 'Shchuchinsk',
                        'Степногорск': 'Stepnogorsk',
                        'Атбасар': 'Atbasar',
                        'Акколь': 'Accol',
                        'Державинск': 'Derzhavinsk',
                        'Макинск': 'Makinsk',
                        'Степняк': 'Stepnyak',
                        'Есиль': 'Esil',
                        'Ерейментау': 'Ereymentau',
                        'Актобе': 'Aktobe',
                        'Кандыагаш': 'Kandyagash',
                        'Алга': 'Alga',
                        'Жем': 'Jem',
                        'Темир': 'Temir',
                        'Хромтау': 'Khromtau',
                        'Шалкар': 'Shalkar',
                        'Эмба': 'Emba',
                        'Каскелен': 'Kaskelen',
                        'Конаев': 'Konaev',
                        'Есик': 'Esik',
                        'Талгар': 'Talgar',
                        'Узынагаш': 'Uzynagash',
                        'Шелек': 'Shelek',
                        'Атырау': 'Atyrau',
                        'Кульсары': 'Kulsary',
                        'Усть-Каменогорск': 'Ust-Kamenogorsk',
                        'Риддер': 'Ridder',
                        'Алтай': 'Altai',
                        'Зайсан': 'Zaisan',
                        'Серебрянск': 'Serebryansk',
                        'Шемонаиха': 'Shemonaiha',
                        'Тараз': 'Taraz',
                        'Шу': 'Shu',
                        'Жанатас': 'Zhanatas',
                        'Каратау': 'Karatau',
                        'Кордай': 'Kordai',
                        'Сарыкемер': 'Sarykemer',
                        'Толе би': 'Tole bi',
                        'Талдыкорган': 'Taldykorgan',
                        'Жаркент': 'Zharkent',
                        'Сарканд': 'Sarkand',
                        'Текели': 'Tekeli',
                        'Уштобе': 'Ushtobe',
                        'Ушарал': 'Usharal',
                        'Уральск': 'Uralsk',
                        'Аксай': 'Aksay',
                        'Зачаганск': 'Zachagansk',
                        'Караганда': 'Karaganda',
                        'Темиртау': 'Temirtau',
                        'Абай': 'Abai',
                        'Балхаш': 'Balkhash',
                        'Каркаралинск': 'Karkaralinsk',
                        'Приозерск': 'Priozersk',
                        'Сарань': 'Saran',
                        'Шахтинск': 'Shakhtinsk',
                        'Костанай': 'Kostanay',
                        'Рудный': 'Rudny',
                        'Аркалык': 'Arkalyk',
                        'Аулиеколь': 'Auliekol',
                        'Житикара': 'Zhitikara',
                        'Качар': 'Kachar',
                        'Карабалык': 'Karabalyk',
                        'Лисаковск': 'Lisakovsk',
                        'Сарыколь': 'Sarykol',
                        'Тобыл': 'Tobyl',
                        'Кызылорда': 'Kyzylorda',
                        'Байконур': 'Baikonur',
                        'Аральск': 'Aralsk',
                        'Айтеке би': 'Aiteke bi',
                        'Жанакорган': 'Zhanakorgan',
                        'Казалинск': 'Kazalinsk',
                        'Тасбогет': 'Tasboget',
                        'Шиели': 'Shieli',
                        'Актау': 'Aktau',
                        'Жанаозен': 'Zhanaozen',
                        'Форт-Шевченко': 'Fort Shevchenko',
                        'Бейнеу': 'Beyneu',
                        'Мангистау': 'Mangystau',
                        'Павлодар': 'Pavlodar',
                        'Экибастуз': 'Ekibastuz',
                        'Аксу': 'Aksu',
                        'Петропавловск': 'Petropavlovsk',
                        'Бесколь': 'Beskol',
                        'Булаево': 'Bulaevo',
                        'Мамлютка': 'Mamlyutka',
                        'Сергеевка': 'Sergeyevka',
                        'Тайынша': 'Tayynsha',
                        'Туркестан': 'Turkestan',
                        'Кентау': 'Kentau',
                        'Арыс': 'Arys',
                        'Жетысай': 'Zhetysay',
                        'Карабулак': 'Karabulak',
                        'Ленгер': 'Lenger',
                        'Сарыагаш': 'Saryagash',
                        'Шардара': 'Shardara',
                        'Жезказган': 'Zhezkazgan',
                        'Каражал': 'Karazhal',
                        'Сатпаев': 'Satpaev'
                    };
                    gorod_translate = gorodTranslations[location.gorod];
                }
            }
            if(location.raion != null){
                if(lang == 'ru'){
                    raion_translate = location.raion;
                }
                if(lang == 'kz'){
                    const raionTranslations = {
                        'Алматинский р-н': 'Алматы ау.',
                        'Есильский р-н': 'Есіл ау.',
                        'Байконурский р-н': 'Байқоңыр ау.',
                        'Сарыаркинский р-н': 'Сарыарқа ау.',
                        'Нуринский р-н': 'Нұра ау.',
                        'Алатауский р-н': 'Алатау ау.',
                        'Алмалинский р-н': 'Алмалы ау.',
                        'Ауэзовский р-н': 'Әуезов ау.',
                        'Бостандыкский р-н': 'Бостандық ау.',
                        'Жетысуский р-н': 'Жетісу ау.',
                        'Медеуский р-н': 'Медеу ау.',
                        'Наурызбайский р-н': 'Наурызбай ау.',
                        'Турксибский р-н': 'Түрксіб ау.',
                        'Абайский р-н': 'Абай ау.',
                        'Аль-Фарабийский р-н': 'Әл-Фараби ау.',
                        'Енбекшинский р-н': 'Еңбекші ау.',
                        'Каратауский р-н': 'Қаратау ау.',
                        'Туранский р-н': 'Тұран ау.',
                        'Казыбек би р-н': 'Қазыбек би ау.',
                        'Алихана Бокейханова р-н': 'Әлихан Бөкейханов ау.'
                    };
                    raion_translate = raionTranslations[location.raion];
                }
                if(lang == 'en'){
                    const raionTranslations = {
                        'Алматинский р-н': 'Almaty district',
                        'Есильский р-н': 'Yesil district',
                        'Байконурский р-н': 'Baikonur district',
                        'Сарыаркинский р-н': 'Saryarka district',
                        'Нуринский р-н': 'Nura district',
                        'Алатауский р-н': 'Alatau district',
                        'Алмалинский р-н': 'Almaly district',
                        'Ауэзовский р-н': 'Auezov district',
                        'Бостандыкский р-н': 'Bostandyk district',
                        'Жетысуский р-н': 'Zhetysu district',
                        'Медеуский р-н': 'Medeu district',
                        'Наурызбайский р-н': 'Nauryzbay district',
                        'Турксибский р-н': 'Turksib district',
                        'Абайский р-н': 'Abay district',
                        'Аль-Фарабийский р-н': 'Al-Farabi district',
                        'Енбекшинский р-н': 'Enbekshi district',
                        'Каратауский р-н': 'Karatau district',
                        'Туранский р-н': 'Turan district',
                        'Казыбек би р-н': 'Kazybek bi district',
                        'Алихана Бокейханова р-н': 'Alikhana Bokeikhanov district'
                    };
                    raion_translate = raionTranslations[location.raion];
                }
            }

            return {oblast: oblast_translate, gorod: gorod_translate, raion: raion_translate}
        },

        //Занесем установшик
        removeLocation(){
            this.LOCATION = '';
        },
    }

} )

