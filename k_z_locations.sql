-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Сен 10 2023 г., 14:42
-- Версия сервера: 8.0.24
-- Версия PHP: 8.0.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `laim_2_kz`
--

-- --------------------------------------------------------

--
-- Структура таблицы `k_z_locations`
--

CREATE TABLE `k_z_locations` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_id` int DEFAULT NULL,
  `oblast` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oblast_kz` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `oblast_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gorod` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gorod_kz` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gorod_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `raion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `raion_kz` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `raion_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` double NOT NULL,
  `lon` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `k_z_locations`
--

INSERT INTO `k_z_locations` (`id`, `parent_id`, `oblast`, `oblast_kz`, `oblast_en`, `gorod`, `gorod_kz`, `gorod_en`, `raion`, `raion_kz`, `raion_en`, `lat`, `lon`) VALUES
(1, NULL, 'Астана', 'Астана', 'Astana', NULL, 'NULL', NULL, NULL, NULL, NULL, 51.143975, 71.43581),
(2, 1, 'Астана', 'Астана', 'Astana', 'Астана', 'Астана', 'Astana', NULL, NULL, NULL, 51.128207, 71.43042),
(3, 2, 'Астана', 'Астана', 'Astana', 'Астана', 'Астана', 'Astana', 'Алматинский р-н', 'Алматы ау.', 'Almaty district', 51.152252, 71.562221),
(4, 2, 'Астана', 'Астана', 'Astana', 'Астана', 'Астана', 'Astana', 'Есильский р-н', 'Есіл ау.', 'Yesil district', 51.091252, 71.45851),
(5, 2, 'Астана', 'Астана', 'Astana', 'Астана', 'Астана', 'Astana', 'Байконурский р-н', 'Байқоңыр ау.', 'Baikonur district', 51.16769, 71.449527),
(6, 2, 'Астана', 'Астана', 'Astana', 'Астана', 'Астана', 'Astana', 'Сарыаркинский р-н', 'Сарыарқа ау.', 'Saryarka district', 51.189169, 71.356246),
(7, 2, 'Астана', 'Астана', 'Astana', 'Астана', 'Астана', 'Astana', 'Нуринский р-н', 'Нұра ау.', 'Nura district', 51.132587, 71.362103),
(8, NULL, 'Алматы', 'Алматы', 'Almaty', NULL, 'NULL', NULL, NULL, NULL, NULL, 44.195539, 76.213006),
(9, 8, 'Алматы', 'Алматы', 'Almaty', 'Алматы', 'Алматы', 'Almaty', NULL, NULL, NULL, 43.237163, 76.945627),
(10, 9, 'Алматы', 'Алматы', 'Almaty', 'Алматы', 'Алматы', 'Almaty', 'Алатауский р-н', 'Алатау ау.', 'Alatau district', 43.300431, 76.835404),
(11, 9, 'Алматы', 'Алматы', 'Almaty', 'Алматы', 'Алматы', 'Almaty', 'Алмалинский р-н', 'Алмалы ау.', 'Almaly district', 43.254421, 76.902184),
(12, 9, 'Алматы', 'Алматы', 'Almaty', 'Алматы', 'Алматы', 'Almaty', 'Ауэзовский р-н', 'Әуезов ау.', 'Auezov district', 43.226667, 76.845204),
(13, 9, 'Алматы', 'Алматы', 'Almaty', 'Алматы', 'Алматы', 'Almaty', 'Бостандыкский р-н', 'Бостандық ау.', 'Bostandyk district', 43.210334, 76.903469),
(14, 9, 'Алматы', 'Алматы', 'Almaty', 'Алматы', 'Алматы', 'Almaty', 'Жетысуский р-н', 'Жетісу ау.', 'Zhetysu district', 43.287262, 76.923187),
(15, 9, 'Алматы', 'Алматы', 'Almaty', 'Алматы', 'Алматы', 'Almaty', 'Медеуский р-н', 'Медеу ау.', 'Medeu district', 43.219664, 76.964923),
(16, 9, 'Алматы', 'Алматы', 'Almaty', 'Алматы', 'Алматы', 'Almaty', 'Наурызбайский р-н', 'Наурызбай ау.', 'Nauryzbay district', 43.186846, 76.831819),
(17, 9, 'Алматы', 'Алматы', 'Almaty', 'Алматы', 'Алматы', 'Almaty', 'Турксибский р-н', 'Түрксіб ау.', 'Turksib district', 43.343443, 76.973313),
(18, NULL, 'Шымкент', 'Шымкент', 'Shymkent', NULL, NULL, NULL, NULL, NULL, NULL, 42.368009, 69.612769),
(19, 18, 'Шымкент', 'Шымкент', 'Shymkent', 'Шымкент', 'Шымкент', 'Shymkent', NULL, NULL, NULL, 42.315521, 69.586942),
(20, 19, 'Шымкент', 'Шымкент', 'Shymkent', 'Шымкент', 'Шымкент', 'Shymkent', 'Абайский р-н', 'Абай ау.', 'Abay district', 42.346182, 69.56806),
(21, 19, 'Шымкент', 'Шымкент', 'Shymkent', 'Шымкент', 'Шымкент', 'Shymkent', 'Аль-Фарабийский р-н', 'Әл-Фараби ау.', 'Al-Farabi district', 42.321368, 69.603094),
(22, 19, 'Шымкент', 'Шымкент', 'Shymkent', 'Шымкент', 'Шымкент', 'Shymkent', 'Енбекшинский р-н', 'Еңбекші ау.', 'Enbekshi district', 42.294763, 69.637221),
(23, 19, 'Шымкент', 'Шымкент', 'Shymkent', 'Шымкент', 'Шымкент', 'Shymkent', 'Каратауский р-н', 'Қаратау ау.', 'Karatau district', 42.40417, 69.635838),
(24, 19, 'Шымкент', 'Шымкент', 'Shymkent', 'Шымкент', 'Шымкент', 'Shymkent', 'Туранский р-н', 'Тұран ау.', 'Turan district', 42.315521, 69.586942),
(25, NULL, 'Абайская обл.', 'Абай обл.', 'Abai region', NULL, NULL, NULL, NULL, NULL, NULL, 49.084555, 79.821862),
(26, 25, 'Абайская обл.', 'Абай обл.', 'Abai region', 'Семей', 'Семей', 'Semey', NULL, NULL, NULL, 49.084555, 79.821862),
(27, 25, 'Абайская обл.', 'Абай обл.', 'Abai region', 'Аягоз', 'Аягөз', 'Ayagoz', NULL, NULL, NULL, 47.965266, 80.436238),
(28, 25, 'Абайская обл.', 'Абай обл.', 'Abai region', 'Курчатов', 'Курчатов', 'Kurchatov', NULL, NULL, NULL, 50.756325, 78.548329),
(29, 25, 'Абайская обл.', 'Абай обл.', 'Abai region', 'Урджар', 'Үржар', 'Urjar', NULL, NULL, NULL, 47.08739, 81.630898),
(30, 25, 'Абайская обл.', 'Абай обл.', 'Abai region', 'Чарск', 'Шар', 'Charsk', NULL, NULL, NULL, 49.584964, 81.047173),
(31, NULL, 'Акмолинская обл.', 'Ақмола обл.', 'Akmola region', NULL, NULL, NULL, NULL, NULL, NULL, 51.785262, 69.908818),
(32, 31, 'Акмолинская обл.', 'Ақмола обл.', 'Akmola region', 'Кокшетау', 'Көкшетау', 'Kokshetau', NULL, NULL, NULL, 53.285136, 69.375515),
(33, 31, 'Акмолинская обл.', 'Ақмола обл.', 'Akmola region', 'Косшы', 'Қосшы', 'Koschi', NULL, NULL, NULL, 50.970262, 71.355366),
(34, 31, 'Акмолинская обл.', 'Ақмола обл.', 'Akmola region', 'Щучинск', 'Щучинск', 'Shchuchinsk', NULL, NULL, NULL, 52.942112, 70.210131),
(35, 31, 'Акмолинская обл.', 'Ақмола обл.', 'Akmola region', 'Степногорск', 'Степногорск', 'Stepnogorsk', NULL, NULL, NULL, 52.346664, 71.879802),
(36, 31, 'Акмолинская обл.', 'Ақмола обл.', 'Akmola region', 'Атбасар', 'Атбасар', 'Atbasar', NULL, NULL, NULL, 51.815761, 68.358335),
(37, 31, 'Акмолинская обл.', 'Ақмола обл.', 'Akmola region', 'Акколь', 'Ақкөл', 'Accol', NULL, NULL, NULL, 51.995855, 70.952948),
(38, 31, 'Акмолинская обл.', 'Ақмола обл.', 'Akmola region', 'Державинск', 'Державин', 'Derzhavinsk', NULL, NULL, NULL, 51.098685, 66.314712),
(39, 31, 'Акмолинская обл.', 'Ақмола обл.', 'Akmola region', 'Макинск', 'Макинск', 'Makinsk', NULL, NULL, NULL, 52.636117, 70.41633),
(40, 31, 'Акмолинская обл.', 'Ақмола обл.', 'Akmola region', 'Степняк', 'Степняк', 'Stepnyak', NULL, NULL, NULL, 52.832, 70.783427),
(41, 31, 'Акмолинская обл.', 'Ақмола обл.', 'Akmola region', 'Есиль', 'Есіл', 'Esil', NULL, NULL, NULL, 51.958004, 66.406215),
(42, 31, 'Акмолинская обл.', 'Ақмола обл.', 'Akmola region', 'Ерейментау', 'Ерейментау', 'Ereymentau', NULL, NULL, NULL, 51.619626, 73.102598),
(43, NULL, 'Актюбинская обл.', 'Ақтөбе обл.', 'Aktobe region', NULL, NULL, NULL, NULL, NULL, NULL, 51.224607, 61.763883),
(44, 43, 'Актюбинская обл.', 'Ақтөбе обл.', 'Aktobe region', 'Актобе', 'Ақтөбе', 'Aktobe', NULL, NULL, NULL, 50.300377, 57.154555),
(45, 43, 'Актюбинская обл.', 'Ақтөбе обл.', 'Aktobe region', 'Кандыагаш', 'Қандыағаш', 'Kandyagash', NULL, NULL, NULL, 49.453476, 57.406874),
(46, 43, 'Актюбинская обл.', 'Ақтөбе обл.', 'Aktobe region', 'Алга', 'Алға', 'Alga', NULL, NULL, NULL, 49.89673, 57.328586),
(47, 43, 'Актюбинская обл.', 'Ақтөбе обл.', 'Aktobe region', 'Жем', 'Жем', 'Jem', NULL, NULL, NULL, 48.774532, 58.072436),
(48, 43, 'Актюбинская обл.', 'Ақтөбе обл.', 'Aktobe region', 'Темир', 'Темір', 'Temir', NULL, NULL, NULL, 49.143142, 57.12633),
(49, 43, 'Актюбинская обл.', 'Ақтөбе обл.', 'Aktobe region', 'Хромтау', 'Хромтау', 'Khromtau', NULL, NULL, NULL, 50.259115, 58.432813),
(50, 43, 'Актюбинская обл.', 'Ақтөбе обл.', 'Aktobe region', 'Шалкар', 'Шалқар', 'Shalkar', NULL, NULL, NULL, 47.831392, 59.61929),
(51, 43, 'Актюбинская обл.', 'Ақтөбе обл.', 'Aktobe region', 'Эмба', 'Ембі', 'Emba', NULL, NULL, NULL, 48.823344, 58.148397),
(52, NULL, 'Алматинская обл.', 'Алматы обл.', 'Almaty region ', NULL, NULL, NULL, NULL, NULL, NULL, 44.195539, 76.213006),
(54, 52, 'Алматинская обл.', 'Алматы обл.', 'Almaty region ', 'Каскелен', 'Қаскелең', 'Kaskelen', NULL, NULL, NULL, 43.202455, 76.622278),
(55, 52, 'Алматинская обл.', 'Алматы обл.', 'Almaty region ', 'Конаев', 'Қонаев', 'Konaev', NULL, NULL, NULL, 43.854836, 77.061545),
(56, 52, 'Алматинская обл.', 'Алматы обл.', 'Almaty region ', 'Есик', 'Есік', 'Esik', NULL, NULL, NULL, 43.365179, 77.450893),
(57, 52, 'Алматинская обл.', 'Алматы обл.', 'Almaty region ', 'Талгар', 'Талғар', 'Talgar', NULL, NULL, NULL, 43.302819, 77.23969),
(58, 52, 'Алматинская обл.', 'Алматы обл.', 'Almaty region ', 'Узынагаш', 'Ұзынағаш', 'Uzynagash', NULL, NULL, NULL, 43.220912, 76.314228),
(59, 52, 'Алматинская обл.', 'Алматы обл.', 'Almaty region ', 'Шелек', 'Шелек', 'Shelek', NULL, NULL, NULL, 43.597532, 78.250591),
(60, NULL, 'Атырауская обл.', 'Атырау обл.', 'Atyrau region', NULL, NULL, NULL, NULL, NULL, NULL, 47.490698, 52.093528),
(61, 60, 'Атырауская обл.', 'Атырау обл.', 'Atyrau region', 'Атырау', 'Атырау', 'Atyrau', NULL, NULL, NULL, 47.106811, 51.916883),
(62, 60, 'Атырауская обл.', 'Атырау обл.', 'Atyrau region', 'Кульсары', 'Құлсары', 'Kulsary', NULL, NULL, NULL, 46.960532, 54.006221),
(63, NULL, 'Восточно-Казахстанская обл.', 'Шығыс Қазақстан обл.', 'East Kazakhstan region', NULL, NULL, NULL, NULL, NULL, NULL, 48.772769, 85.085387),
(64, 63, 'Восточно-Казахстанская обл.', 'Шығыс Қазақстан обл.', 'East Kazakhstan region', 'Усть-Каменогорск', 'Өскемен', 'Ust-Kamenogorsk', NULL, NULL, NULL, 49.948325, 82.627848),
(65, 63, 'Восточно-Казахстанская обл.', 'Шығыс Қазақстан обл.', 'East Kazakhstan region', 'Риддер', 'Риддер', 'Ridder', NULL, NULL, NULL, 50.33886, 83.506329),
(66, 63, 'Восточно-Казахстанская обл.', 'Шығыс Қазақстан обл.', 'East Kazakhstan region', 'Алтай', 'Алтай', 'Altai', NULL, NULL, NULL, 49.725218, 84.273562),
(67, 63, 'Восточно-Казахстанская обл.', 'Шығыс Қазақстан обл.', 'East Kazakhstan region', 'Зайсан', 'Зайсаң', 'Zaisan', NULL, NULL, NULL, 47.467611, 84.876376),
(68, 63, 'Восточно-Казахстанская обл.', 'Шығыс Қазақстан обл.', 'East Kazakhstan region', 'Серебрянск', 'Серебрянск', 'Serebryansk', NULL, NULL, NULL, 49.679277, 83.300651),
(69, 63, 'Восточно-Казахстанская обл.', 'Шығыс Қазақстан обл.', 'East Kazakhstan region', 'Шемонаиха', 'Шемонаиха', 'Shemonaiha', NULL, NULL, NULL, 50.627624, 81.916697),
(70, NULL, 'Жамбылская обл.', 'Жамбыл обл.', 'Zhambyl region', NULL, NULL, NULL, NULL, NULL, NULL, 44.311975, 72.138472),
(71, 70, 'Жамбылская обл.', 'Жамбыл обл.', 'Zhambyl region', 'Тараз', 'Тараз', 'Taraz', NULL, NULL, NULL, 42.899657, 71.392727),
(72, 70, 'Жамбылская обл.', 'Жамбыл обл.', 'Zhambyl region', 'Шу', 'Шу', 'Shu', NULL, NULL, NULL, 43.611782, 73.760237),
(73, 70, 'Жамбылская обл.', 'Жамбыл обл.', 'Zhambyl region', 'Жанатас', 'Жаңатас', 'Zhanatas', NULL, NULL, NULL, 43.55465, 69.722525),
(74, 70, 'Жамбылская обл.', 'Жамбыл обл.', 'Zhambyl region', 'Каратау', 'Қаратау', 'Karatau', NULL, NULL, NULL, 43.179555, 70.46545),
(75, 70, 'Жамбылская обл.', 'Жамбыл обл.', 'Zhambyl region', 'Кордай', 'Қордай', 'Kordai', NULL, NULL, NULL, 43.0477, 74.706657),
(76, 70, 'Жамбылская обл.', 'Жамбыл обл.', 'Zhambyl region', 'Сарыкемер', 'Сарыкемер', 'Sarykemer', NULL, NULL, NULL, 43.00761, 71.515149),
(77, 70, 'Жамбылская обл.', 'Жамбыл обл.', 'Zhambyl region', 'Толе би', 'Төле би', 'Tole bi', NULL, NULL, NULL, 43.675876, 73.761989),
(78, NULL, 'Жетысуская обл.', 'Жетісу обл.', 'Jetysui region', NULL, NULL, NULL, NULL, NULL, NULL, 45.704965, 79.239619),
(79, 78, 'Жетысуская обл.', 'Жетісу обл.', 'Jetysui region', 'Талдыкорган', 'Талдықорған', 'Taldykorgan', NULL, NULL, NULL, 45.017837, 78.382123),
(80, 78, 'Жетысуская обл.', 'Жетісу обл.', 'Jetysui region', 'Жаркент', 'Жаркент', 'Zharkent', NULL, NULL, NULL, 44.169365, 80.003842),
(81, 78, 'Жетысуская обл.', 'Жетісу обл.', 'Jetysui region', 'Сарканд', 'Сарқант', 'Sarkand', NULL, NULL, NULL, 45.413666, 79.916894),
(82, 78, 'Жетысуская обл.', 'Жетісу обл.', 'Jetysui region', 'Текели', 'Текелі', 'Tekeli', NULL, NULL, NULL, 44.863094, 78.764266),
(83, 78, 'Жетысуская обл.', 'Жетісу обл.', 'Jetysui region', 'Уштобе', 'Үштөбе', 'Ushtobe', NULL, NULL, NULL, 45.251221, 77.980827),
(84, 78, 'Жетысуская обл.', 'Жетісу обл.', 'Jetysui region', 'Ушарал', 'Үшарал', 'Usharal', NULL, NULL, NULL, 46.1741, 80.946858),
(85, NULL, 'Западно-Казахстанская обл.', 'Батыс Қазақстан обл.', 'West Kazakhstan region', NULL, NULL, NULL, NULL, NULL, NULL, 49.813556, 50.675456),
(86, 85, 'Западно-Казахстанская обл.', 'Батыс Қазақстан обл.', 'West Kazakhstan region', 'Уральск', 'Орал', 'Uralsk', NULL, NULL, NULL, 51.20403, 51.370528),
(87, 85, 'Западно-Казахстанская обл.', 'Батыс Қазақстан обл.', 'West Kazakhstan region', 'Аксай', 'Ақсай', 'Aksay', NULL, NULL, NULL, 51.164945, 53.020868),
(88, 85, 'Западно-Казахстанская обл.', 'Батыс Қазақстан обл.', 'West Kazakhstan region', 'Зачаганск', 'Зачаганск', 'Zachagansk', NULL, NULL, NULL, 51.181011, 51.309424),
(89, NULL, 'Карагандинская обл.', 'Қарағанды обл.', 'Karaganda region', NULL, NULL, NULL, NULL, NULL, NULL, 48.787403, 74.33762),
(90, 89, 'Карагандинская обл.', 'Қарағанды обл.', 'Karaganda region', 'Караганда', 'Қарағанды', 'Karaganda', NULL, NULL, NULL, 49.80776, 73.088504),
(91, 90, 'Карагандинская обл.', 'Қарағанды обл.', 'Karaganda region', 'Караганда', 'Қарағанды', 'Karaganda', 'Казыбек би р-н', 'Қазыбек би ау.', 'Kazybek bi district', 49.794608, 73.083311),
(92, 90, 'Карагандинская обл.', 'Қарағанды обл.', 'Karaganda region', 'Караганда', 'Қарағанды', 'Karaganda', 'Алихана Бокейханова р-н', 'Әлихан Бөкейханов ау.', 'Alikhana Bokeikhanov district', 49.80776, 73.088504),
(93, 89, 'Карагандинская обл.', 'Қарағанды обл.', 'Karaganda region', 'Темиртау', 'Теміртау', 'Temirtau', NULL, NULL, NULL, 50.058761, 72.953424),
(94, 89, 'Карагандинская обл.', 'Қарағанды обл.', 'Karaganda region', 'Абай', 'Абай', 'Abai', NULL, NULL, NULL, 49.631899, 72.859245),
(95, 89, 'Карагандинская обл.', 'Қарағанды обл.', 'Karaganda region', 'Балхаш', 'Балқаш', 'Balkhash', NULL, NULL, NULL, 46.843703, 74.97731),
(96, 89, 'Карагандинская обл.', 'Қарағанды обл.', 'Karaganda region', 'Каркаралинск', 'Қарқаралы', 'Karkaralinsk', NULL, NULL, NULL, 49.412289, 75.470126),
(97, 89, 'Карагандинская обл.', 'Қарағанды обл.', 'Karaganda region', 'Приозерск', 'Приозер', 'Priozersk', NULL, NULL, NULL, 46.028969, 73.708027),
(98, 89, 'Карагандинская обл.', 'Қарағанды обл.', 'Karaganda region', 'Сарань', 'Саран', 'Saran', NULL, NULL, NULL, 49.801993, 72.828387),
(99, 89, 'Карагандинская обл.', 'Қарағанды обл.', 'Karaganda region', 'Шахтинск', 'Шахтинск', 'Shakhtinsk', NULL, NULL, NULL, 49.705868, 72.594637),
(100, NULL, 'Костанайская обл.', 'Қостанай обл.', 'Kostanay region', NULL, NULL, NULL, NULL, NULL, NULL, 51.602478, 64.015564),
(101, 100, 'Костанайская обл.', 'Қостанай обл.', 'Kostanay region', 'Костанай', 'Қостанай', 'Kostanay', NULL, NULL, NULL, 53.214496, 63.6321),
(102, 100, 'Костанайская обл.', 'Қостанай обл.', 'Kostanay region', 'Рудный', 'Рудный', 'Rudny', NULL, NULL, NULL, 52.964432, 63.133392),
(103, 100, 'Костанайская обл.', 'Қостанай обл.', 'Kostanay region', 'Аркалык', 'Арқалық', 'Arkalyk', NULL, NULL, NULL, 50.253465, 66.915011),
(104, 100, 'Костанайская обл.', 'Қостанай обл.', 'Kostanay region', 'Аулиеколь', 'Әулиекөл', 'Auliekol', NULL, NULL, NULL, 52.360382, 64.136289),
(105, 100, 'Костанайская обл.', 'Қостанай обл.', 'Kostanay region', 'Житикара', 'Жітіқара', 'Zhitikara', NULL, NULL, NULL, 52.183928, 61.189833),
(106, 100, 'Костанайская обл.', 'Қостанай обл.', 'Kostanay region', 'Качар', 'Қашар', 'Kachar', NULL, NULL, NULL, 53.368641, 62.86988),
(107, 100, 'Костанайская обл.', 'Қостанай обл.', 'Kostanay region', 'Карабалык', 'Қарабалық', 'Karabalyk', NULL, NULL, NULL, 53.747341, 62.061953),
(108, 100, 'Костанайская обл.', 'Қостанай обл.', 'Kostanay region', 'Лисаковск', 'Лисаковск', 'Lisakovsk', NULL, NULL, NULL, 52.544221, 62.492632),
(109, 100, 'Костанайская обл.', 'Қостанай обл.', 'Kostanay region', 'Сарыколь', 'Сарыкөл', 'Sarykol', NULL, NULL, NULL, 53.316327, 65.540122),
(110, 100, 'Костанайская обл.', 'Қостанай обл.', 'Kostanay region', 'Тобыл', 'Тобыл', 'Tobyl', NULL, NULL, NULL, 53.207372, 63.705088),
(111, NULL, 'Кызылординская обл.', 'Қызылорда обл.', 'Kyzylorda region', NULL, NULL, NULL, NULL, NULL, NULL, 44.571377, 65.794938),
(112, 111, 'Кызылординская обл.', 'Қызылорда обл.', 'Kyzylorda region', 'Кызылорда', 'Қызылорда', 'Kyzylorda', NULL, NULL, NULL, 44.842544, 65.502563),
(113, 111, 'Кызылординская обл.', 'Қызылорда обл.', 'Kyzylorda region', 'Байконур', 'Байқоңыр', 'Baikonur', NULL, NULL, NULL, 45.617459, 63.321804),
(114, 111, 'Кызылординская обл.', 'Қызылорда обл.', 'Kyzylorda region', 'Аральск', 'Арал', 'Aralsk', NULL, NULL, NULL, 46.797738, 61.660792),
(115, 111, 'Кызылординская обл.', 'Қызылорда обл.', 'Kyzylorda region', 'Айтеке би', 'Әйтеке би', 'Aiteke bi', NULL, NULL, NULL, 45.835934, 62.148317),
(116, 111, 'Кызылординская обл.', 'Қызылорда обл.', 'Kyzylorda region', 'Жанакорган', 'Жаңақорған', 'Zhanakorgan', NULL, NULL, NULL, 43.900451, 67.243723),
(117, 111, 'Кызылординская обл.', 'Қызылорда обл.', 'Kyzylorda region', 'Казалинск', 'Қазалы', 'Kazalinsk', NULL, NULL, NULL, 45.760633, 62.105836),
(118, 111, 'Кызылординская обл.', 'Қызылорда обл.', 'Kyzylorda region', 'Тасбогет', 'Тасбөгет', 'Tasboget', NULL, NULL, NULL, 44.773133, 65.565948),
(119, 111, 'Кызылординская обл.', 'Қызылорда обл.', 'Kyzylorda region', 'Шиели', 'Шиелі', 'Shieli', NULL, NULL, NULL, 44.177802, 66.731387),
(120, NULL, 'Мангистауская обл.', 'Маңғыстау обл.', 'Mangystau region', NULL, NULL, NULL, NULL, NULL, NULL, 44.122553, 53.72203),
(121, 120, 'Мангистауская обл.', 'Маңғыстау обл.', 'Mangystau region', 'Актау', 'Ақтау', 'Aktau', NULL, NULL, NULL, 43.635588, 51.168245),
(122, 120, 'Мангистауская обл.', 'Маңғыстау обл.', 'Mangystau region', 'Жанаозен', 'Жаңаөзен', 'Zhanaozen', NULL, NULL, NULL, 43.338099, 52.855677),
(123, 120, 'Мангистауская обл.', 'Маңғыстау обл.', 'Mangystau region', 'Форт-Шевченко', 'Форт-Шевченко', 'Fort Shevchenko', NULL, NULL, NULL, 44.501504, 50.258737),
(124, 120, 'Мангистауская обл.', 'Маңғыстау обл.', 'Mangystau region', 'Бейнеу', 'Бейнеу', 'Beyneu', NULL, NULL, NULL, 45.322946, 55.188862),
(125, 120, 'Мангистауская обл.', 'Маңғыстау обл.', 'Mangystau region', 'Мангистау', 'Маңғыстау', 'Mangystau', NULL, NULL, NULL, 43.691561, 51.305571),
(126, NULL, 'Павлодарская обл.', 'Павлодар обл.', 'Pavlodar region', NULL, NULL, NULL, NULL, NULL, NULL, 52.068499, 76.242551),
(127, 126, 'Павлодарская обл.', 'Павлодар обл.', 'Pavlodar region', 'Павлодар', 'Павлодар', 'Pavlodar', NULL, NULL, NULL, 52.285577, 76.940722),
(128, 126, 'Павлодарская обл.', 'Павлодар обл.', 'Pavlodar region', 'Экибастуз', 'Екібастұз', 'Ekibastuz', NULL, NULL, NULL, 51.729692, 75.326629),
(129, 126, 'Павлодарская обл.', 'Павлодар обл.', 'Pavlodar region', 'Аксу', 'Ақсу', 'Aksu', NULL, NULL, NULL, 52.035259, 76.933868),
(130, NULL, 'Северо-Казахстанская обл.', 'Солтүстік Қазақстан обл.', 'North Kazakhstan region', NULL, NULL, NULL, NULL, NULL, NULL, 53.668046, 67.982803),
(131, 130, 'Северо-Казахстанская обл.', 'Солтүстік Қазақстан обл.', 'North Kazakhstan region', 'Петропавловск', 'Петропавл', 'Petropavlovsk', NULL, NULL, NULL, 54.86186, 69.139617),
(132, 130, 'Северо-Казахстанская обл.', 'Солтүстік Қазақстан обл.', 'North Kazakhstan region', 'Бесколь', 'Бескөл', 'Beskol', NULL, NULL, NULL, 54.773029, 69.102265),
(133, 130, 'Северо-Казахстанская обл.', 'Солтүстік Қазақстан обл.', 'North Kazakhstan region', 'Булаево', 'Булаев', 'Bulaevo', NULL, NULL, NULL, 54.896056, 70.448157),
(134, 130, 'Северо-Казахстанская обл.', 'Солтүстік Қазақстан обл.', 'North Kazakhstan region', 'Мамлютка', 'Мамлют', 'Mamlyutka', NULL, NULL, NULL, 54.94112, 68.546199),
(135, 130, 'Северо-Казахстанская обл.', 'Солтүстік Қазақстан обл.', 'North Kazakhstan region', 'Сергеевка', 'Сергеев', 'Sergeyevka', NULL, NULL, NULL, 53.888542, 67.416271),
(136, 130, 'Северо-Казахстанская обл.', 'Солтүстік Қазақстан обл.', 'North Kazakhstan region', 'Тайынша', 'Тайынша', 'Tayynsha', NULL, NULL, NULL, 53.852738, 69.78264),
(137, NULL, 'Туркестанская обл.', 'Түркістан обл.', 'Turkestan region', NULL, NULL, NULL, NULL, NULL, NULL, 44.29477, 68.676347),
(138, 137, 'Туркестанская обл.', 'Түркістан обл.', 'Turkestan region', 'Туркестан', 'Түркістан', 'Turkestan', NULL, NULL, NULL, 43.302025, 68.268979),
(139, 137, 'Туркестанская обл.', 'Түркістан обл.', 'Turkestan region', 'Кентау', 'Кентау', 'Kentau', NULL, NULL, NULL, 43.518137, 68.504661),
(140, 137, 'Туркестанская обл.', 'Түркістан обл.', 'Turkestan region', 'Абай', 'Абай', 'Abai', NULL, NULL, NULL, 44.29477, 68.676347),
(141, 137, 'Туркестанская обл.', 'Түркістан обл.', 'Turkestan region', 'Аксу', 'Ақсу', 'Aksu', NULL, NULL, NULL, 44.29477, 68.676347),
(142, 137, 'Туркестанская обл.', 'Түркістан обл.', 'Turkestan region', 'Арыс', 'Арыс', 'Arys', NULL, NULL, NULL, 42.432744, 68.813798),
(143, 137, 'Туркестанская обл.', 'Түркістан обл.', 'Turkestan region', 'Жетысай', 'Жетісай', 'Zhetysay', NULL, NULL, NULL, 40.77432, 68.327442),
(144, 137, 'Туркестанская обл.', 'Түркістан обл.', 'Turkestan region', 'Карабулак', 'Қарабұлақ', 'Karabulak', NULL, NULL, NULL, 42.531379, 69.7739),
(145, 137, 'Туркестанская обл.', 'Түркістан обл.', 'Turkestan region', 'Ленгер', 'Леңгір	', 'Lenger', NULL, NULL, NULL, 42.182057, 69.88212),
(146, 137, 'Туркестанская обл.', 'Түркістан обл.', 'Turkestan region', 'Сарыагаш', 'Сарыағаш', 'Saryagash', NULL, NULL, NULL, 41.451428, 69.167196),
(147, 137, 'Туркестанская обл.', 'Түркістан обл.', 'Turkestan region', 'Шардара', 'Шардара', 'Shardara', NULL, NULL, NULL, 41.256922, 67.968852),
(148, NULL, 'Улытауская обл.', 'Ұлытау обл.', 'Ulytau region', NULL, NULL, NULL, NULL, NULL, NULL, 47.253611, 67.723289),
(149, 148, 'Улытауская обл.', 'Ұлытау обл.', 'Ulytau region', 'Жезказган', 'Жезқазған', 'Zhezkazgan', NULL, NULL, NULL, 47.799711, 67.71409),
(150, 148, 'Улытауская обл.', 'Ұлытау обл.', 'Ulytau region', 'Каражал', 'Қаражал', 'Karazhal', NULL, NULL, NULL, 48.001042, 70.785771),
(151, 148, 'Улытауская обл.', 'Ұлытау обл.', 'Ulytau region', 'Сатпаев', 'Сәтбаев', 'Satpaev', NULL, NULL, NULL, 47.907443, 67.528139);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `k_z_locations`
--
ALTER TABLE `k_z_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oblast` (`oblast`),
  ADD KEY `gorod` (`gorod`),
  ADD KEY `raion` (`raion`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `k_z_locations`
--
ALTER TABLE `k_z_locations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
