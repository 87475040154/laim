<?php

namespace App\Http\Controllers;

use App\Models\Ads\Business;
use App\Models\Ads\Dom;
use App\Models\Ads\GeneralAds;
use App\Models\Ads\Kvartira;
use App\Models\Ads\Magazin;
use App\Models\Ads\Obshejitie;
use App\Models\Ads\Ofis;
use App\Models\Ads\Prochaya;
use App\Models\Ads\Prombaza;
use App\Models\Ads\Zdanie;
use App\Models\AdsView;
use App\Models\AdsViewTel;
use App\Models\Like;
use App\Models\User;
use GuzzleHttp\Client;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Debugbar;

class AdsController extends Controller
{

    //Метод проверяет, сравнивает капчу, если ок вернет true //Необходим для сверки капчи на бекенде и фронте от ботов
    protected function checkRecaptcha($token, $ip)
    {
        $client = new Client();
        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret'   => config('recaptcha.secret'),
                'response' => $token,
                'remoteip' => $ip,
            ],
        ]);
        $response = json_decode((string)$response->getBody(), true);
        return $response['success'];
    }

    //Получим ip пользователя. Пс. на локалке выдает 127.0.0.1 на сервере должен норм выдовать, потом проверю
    public function getClientIp(){
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = @$_SERVER['REMOTE_ADDR'];

        $ip = '';

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } elseif (filter_var($remote, FILTER_VALIDATE_IP)) {
            $ip = $remote;
        }
        return $ip;
    }



    //Метод Добавить или  Редактировать  объявление
    public function addOrUpdateAds(Request $request){


        //Проверка  капчи Google recaptcha - на сервере мы сравниваем токен который получили на фронтенде и сравниваем с бекендом
        if (config('recaptcha.enabled') && !$this->checkRecaptcha($request->recaptcha_token, $request->ip())) {
            return response()->json(['error' => 'Неверная капча - перезагрузите страницу'], 422);
        }

        // Общие правила валидации
        $commonRules = [
            'author_id'=> 'required|numeric|min:1|max:999999999',
            'zagolovok'=> 'required|string|min:1|max:50',
            'tip_sdelki'=> 'required|string|min:1|max:255',
            'cena'=> 'required|numeric|min:1|max:999999999',
            'tip_stroeniya'=> 'required|string|min:1|max:255',
            'god_postroiki'=> 'required|integer|min:1|max:99999',
            "images.*"=> "nullable|file|mimes:jpg,jpeg,png,bmp,gif,svg,webp,heif|max:4000",
            'ploshad_obshaya'=> 'required|numeric|min:1|max:999999',
            'sostoyanie'=> 'nullable|string|min:1|max:255',
            'oblast'=> 'required|string|min:1|max:255',
            'gorod'=> 'required|string|min:1|max:255',
            'raion'=> 'nullable|string|min:1|max:255',
            'lat'=> 'required',
            'lon'=> 'required',
            'ulica'=> 'nullable|string|min:1|max:255',
            'nomer_doma'=> 'nullable|string|min:1|max:10',
            'text_obyavleniya'=> 'required|string|min:1|max:2000',
            'name'=> 'required|string|min:1|max:30',
            'tel'=> 'required|string|max:255',
            'tel2'=> 'nullable|string|max:255',
            'recaptcha_token' => 'required|string'
        ];

        // Специфичные правила
        $tableSpecificRules = [];
        switch ($request->table_name) {
            case 'Kvartira':
                $tableSpecificRules = [
                    'period_arendi'=> 'required|string|min:1|max:255',
                    'kolichestvo_komnat'=> 'required|integer|min:1|max:999',
                    'etag'=> 'required|numeric|min:1|max:999',
                    'etagnost'=> 'required|numeric|min:1|max:999',
                    'ploshad_kuhni'=> 'nullable|numeric|min:1|max:999',
                    'sanuzel'=> 'nullable|string|min:1|max:255',
                    'balkon'=> 'nullable|string|min:1|max:255',
                    'mebel'=> 'nullable|string|min:1|max:255',
                    'bezopasnost.*'=> 'nullable|string|min:1|max:2000',
                    'raznoe.*'=> 'nullable|string|min:1|max:2000',
                ];
                break;
            case 'Obshejitie':
                $tableSpecificRules = [
                    'period_arendi'=> 'required|string|min:1|max:255',
                    'kolichestvo_komnat'=> 'required|integer|min:1|max:999',
                    'etag'=> 'required|numeric|min:1|max:999',
                    'etagnost'=> 'required|numeric|min:1|max:999',
                    'ploshad_kuhni'=> 'nullable|numeric|min:1|max:99999',
                    'mebel'=> 'required|string|min:1|max:255',
                    'sanuzel'=> 'required|string|min:1|max:255',
                    'dush'=> 'required|string|min:1|max:255',
                    'balkon'=> 'nullable|string|min:1|max:255',
                    'bezopasnost.*'=> 'nullable|string|min:1|max:2000',
                    'raznoe.*'=> 'nullable|string|min:1|max:2000',
                ];
                break;
            case 'Dom':
                $tableSpecificRules = [
                    'tip_obekta'=> 'required|string|min:1|max:255',
                    'period_arendi'=> 'required|string|min:1|max:255',
                    'kolichestvo_komnat'=> 'required|integer|min:1|max:999',
                    'etagnost'=> 'required|numeric|min:1|max:999',
                    'ploshad_kuhni'=> 'nullable|numeric|min:1|max:999',
                    'ploshad_uchastka'=> 'nullable|integer|min:1|max:99999',
                    'mebel'=> 'nullable|string|min:1|max:255',
                    'sanuzel'=> 'nullable|string|min:1|max:255',
                    'kanalizaciya'=> 'nullable|string|min:1|max:255',
                    'voda'=> 'nullable|string|min:1|max:255',
                    'elektrichestvo'=> 'nullable|string|min:1|max:255',
                    'otoplenie'=> 'nullable|string|min:1|max:255',
                    'gaz'=> 'nullable|string|min:1|max:255',
                    'bezopasnost.*'=> 'nullable|string|min:1|max:2000',
                    'raznoe.*'=> 'nullable|string|min:1|max:2000',
                ];
                break;
            case 'Ofis':
                $tableSpecificRules = [
                    'kolichestvo_komnat'=> 'required|integer|min:1|max:999',
                    'cena_tip'=> 'required|string|min:1|max:255',
                    'tip_ofisa'=> 'required|string|min:1|max:255',
                    'etag'=> 'required|numeric|min:1|max:999',
                    'etagnost'=> 'required|numeric|min:1|max:999',
                    'visota_potolkov'=> 'required|string|min:1|max:99',
                    'mebel'=> 'nullable|string|min:1|max:255',
                    'sanuzel'=> 'nullable|string|min:1|max:255',
                    'otdelniy_vhod'=> 'nullable|string|min:1|max:255',
                    'bezopasnost.*'=> 'nullable|string|min:1|max:2000',
                    'raznoe.*'=> 'nullable|string|min:1|max:2000',
                ];
                break;
            case 'Zdanie':
                $tableSpecificRules = [
                    'etagnost'=> 'required|string|min:1|max:999',
                    'visota_potolkov'=> 'required|string|min:1|max:99',
                    'ploshad_uchastka'=> 'nullable|numeric|min:1|max:99999',
                    'bezopasnost.*'=> 'nullable|string|min:1|max:2000',
                ];
                break;
            case 'Magazin':
                $tableSpecificRules = [
                    'tip_obekta'=> 'required|string|min:1|max:255',
                    'deistvuushii_bisnes'=> 'required|string|min:1|max:255',
                    'cena_tip'=> 'required|string|min:1|max:255',
                    'mestopolojenie'=> 'required|string|min:1|max:255',
                    'etag'=> 'required|numeric|min:1|max:999',
                    'etagnost'=> 'required|numeric|min:1|max:999',
                    'mebel'=> 'nullable|string|min:1|max:255',
                    'sanuzel'=> 'nullable|string|min:1|max:255',
                    'otdelniy_vhod'=> 'nullable|string|min:1|max:255',
                    'bezopasnost.*'=> 'nullable|string|min:1|max:2000',
                    'raznoe.*'=> 'nullable|string|min:1|max:2000',
                ];
                break;
            case 'Prombaza':
                $tableSpecificRules = [
                    'tip_obekta'=> 'required|string|min:1|max:255',
                    'cena_tip'=> 'required|string|min:1|max:255',
                    'ploshad_uchastka'=> 'nullable|numeric|min:1|max:99999',
                    'ploshad_proizvodstvennih_pomesheniy'=> 'nullable|string|min:1|max:255',
                    'visota_proizvodstvennih_pomesheniy'=> 'nullable|string|min:1|max:255',
                    'ploshad_skladskih_pomesheniy'=> 'nullable|string|min:1|max:255',
                    'visota_skladskih_pomesheniy'=> 'nullable|string|min:1|max:255',
                    'ploshad_ofisnih_pomesheniy'=> 'nullable|string|min:1|max:255',
                    'raspolojenie.*'=> 'nullable|string|min:1|max:255',
                    'kommunikacii.*'=> 'nullable|string|min:1|max:2000',
                    'raznoe.*'=> 'nullable|string|min:1|max:2000',
                ];
                break;
            case 'Prochaya':
                $tableSpecificRules = [
                    'deistvuushii_bisnes'=> 'required|string|min:1|max:255',
                    'cena_tip'=> 'required|string|min:1|max:255',
                    'sfera_deyatelnosti'=> 'required|string|min:1|max:255',
                    'ploshad_uchastka'=> 'nullable|numeric|min:1|max:99999',
                    'raspolojenie.*'=> 'required|string|min:1|max:1000',
                    'kommunikacii.*'=> 'nullable|string|min:1|max:2000',
                ];
                break;
            case 'Business':
                $tableSpecificRules = [
                    'deistvuushii_bisnes'=> 'required|string|min:1|max:255',
                    'cena_tip'=> 'required|string|min:1|max:255',
                    'sfera_deyatelnosti'=> 'required|string|min:1|max:255',
                    'mestopolojenie'=> 'required|string|min:1|max:255',
                    'raspolojenie.*'=> 'required|string|min:1|max:1000',
                    'kommunikacii.*'=> 'nullable|string|min:1|max:2000',
                ];
                break;
            default:
                return response()->json(['error' => 'Неизвестное имя таблицы'], 422);
        }

        // Объединим общие и специфичные правила валидации
        $validationRules = array_merge($commonRules, $tableSpecificRules);

        // Валидируем данные с формы
        $request->validate($validationRules);


        // Kvartira, Obshejitie  и тд.
        $modelClass = 'App\Models\Ads\\' . $request->table_name;

        if ($request->addOrUpdate == 'add') {
            $model = new $modelClass;
        } else {
            $model = $modelClass::find($request->id);

            if (!$model || ($request->role == 'user' && $model->author_id != $request->author_id)) {
                return response()->json(['error' => 'У вас нет доступа к редактированию данного объявления'], 422);
            }
        }

        //Если проверки пройденны то добавим или обновим объявление через статический общий медоб
        $ads = GeneralAds::addOrUpdateAds( $model, $request->all() );
        return response()->json(['ads'=>$ads], 200);
    }


    // Метод - Получить объявления из БД пагинацией
    public function getAllAds(Request $request){

        $modelClass = 'App\Models\Ads\\' . $request->table_name; // Kvartira, Obshejitie
        $tableName = (new $modelClass)->getTable(); // Получаем имя таблицы

        // Если получить только МОИ ИЗБРАННЫЕ объявления
        if ($request->getMyLikeAds == 'Получить мои лайки') {
            $query = $modelClass::query();

            // Присоединим таблицу лайков для сортировки по времени добавления лайка
            $query->join('likes', function ($join) use ($request, $tableName, $modelClass) {
                $join->on('likes.likeable_id', '=', "{$tableName}.id")
                    ->where('likes.author_id', $request->user_id)
                    ->where('likes.likeable_type', $modelClass);
            })->orderBy('likes.created_at', 'desc')// Сортировка по времени добавления лайка
                ->select([
                    "{$tableName}.id", "{$tableName}.author_id", 'zagolovok', 'table_name', 'cena', 'gorod', 'raion', 'images',
                    'control', 'srochno_torg', 'top', 'top_8', 'top_x7', 'top_x30', 'bueAds',
                    "{$tableName}.updated_at", "{$tableName}.created_at", 'likes.created_at as like_created_at'
                ]);

        }else{
            $query = $modelClass::query();
            // Определение состояния объявления (архив или активно)
            $controlStatus = (!empty($request->filter['arhiv'])) ? 'В архиве' : 'Активно';

            // Применение условия для фильтрации по состоянию
            $query->where('control', $controlStatus)
                ->orderBy('bueAds', 'desc')
                ->orderBy('updated_at', 'desc')
                ->orderBy('id', 'asc')
                ->select([
                    'id', 'author_id', 'zagolovok', 'table_name', 'cena', 'gorod', 'raion', 'images',
                    'control', 'srochno_torg', 'top', 'top_8', 'top_x7', 'top_x30', 'bueAds',
                    'updated_at', 'created_at'
                ])
                ->with(['likes' => function($query) use ($request) {
                    $query->where('author_id', $request->user_id); // Загружаем только лайки текущего пользователя
                }]);
        }

        // 💡 Общие шаги
        $this->applyFilters($query, $request->filter);

        $ads_arr = $query->cursorPaginate(20);

        foreach ($ads_arr as $index => $ads) {
            $ads->uniqueKey = $index . round(microtime(true) * 1000);
            if ($request->getMyLikeAds == 'Получить мои лайки') $ads->likes = ['true'];
        }

        return response()->json(['ads' => $ads_arr], 200);
    }

    /**
     * Метод для применения фильтров к запросу
     */
    private function applyFilters(&$query, $filter)
    {
        if (empty($filter)) return;

        // Список фильтров, которые нужно проверить
        $filters = [
            'oblast','gorod','tip_sdelki','period_arendi','tip_stroeniya','tip_obekta',
            'kolichestvo_komnat_ot','kolichestvo_komnat_do','deistvuushii_bisnes',
            'cena_tip','cena_ot','cena_do','sfera_deyatelnosti','mestopolojenie',
            'tip_ofisa','etag_ot','etag_do','etagnost_ot','etagnost_do','visota_potolkov_ot',
            'visota_potolkov_do','ploshad_obshaya_ot','ploshad_obshaya_do',
            'ploshad_uchastka_ot','ploshad_uchastka_do','god_postroiki_ot','god_postroiki_do',
            'sostoyanie','mebel','dush','sanuzel','kanalizaciya','voda','otoplenie','raion','tel'
        ];

        foreach ($filters as $filterName) {
            if (!isset($filter[$filterName]) || is_null($filter[$filterName])) continue;

            // Диапазонные фильтры _ot/_do
            if (strpos($filterName, '_ot') !== false || strpos($filterName, '_do') !== false) {
                $dbColumn = str_replace(['_ot','_do'], '', $filterName);

                if (isset($filter[$dbColumn . '_ot']) && !is_null($filter[$dbColumn . '_ot'])) {
                    $query->where($dbColumn, '>=', $filter[$dbColumn . '_ot']);
                }
                if (isset($filter[$dbColumn . '_do']) && !is_null($filter[$dbColumn . '_do'])) {
                    $query->where($dbColumn, '<=', $filter[$dbColumn . '_do']);
                }
            } else {
                // Простое условие
                $query->where($filterName, $filter[$filterName]);
            }
        }
    }


    //Метод узнать колличество объявлений
    public function countAds(Request $request){
        $modelClass = 'App\Models\Ads\\' . $request->table_name; // Kvartira, Obshejitie
        $query = $modelClass::query();

        // Если получить только МОИ ИЗБРАННЫЕ объявления
        if (isset($request->getMyLikeAds) && $request->getMyLikeAds == 'Получить мои лайки') {
            $user = User::find($request->user_id);
            // Получим лайки данного пользователя с таблицы likes
            $likedAdIds = $user->likedAds
                ->where('likeable_type', $modelClass)
                ->pluck('likeable_id')->toArray();
            $query->whereIn('id', $likedAdIds);
        }

        // Отсортируем объявления по фильтру
        $this->applyFilters($query, $request->filter);

        //Получим либо избранные объявления Автора - Либо (Активно или В архиве)
        if($request->getMyLikeAds != 'Получить мои лайки'){
            // Определение состояния объявления (архив или активно)
            $controlStatus = (!empty($request->filter['arhiv'])) ? 'В архиве' : 'Активно';

            // Применение условия для фильтрации по состоянию
            $query->where('control', $controlStatus);
        }

        $countAds= $query->count();

        // Возвращаем ответ
        return response()->json(['countAds'=>$countAds]);

    }

    // Метод получить Координаты и ID объявлений для отображения на Яндекс карте в кластере
    public function getAllAdsYandexCluster(Request $request){
        $modelClass = 'App\Models\Ads\\' . $request->table_name; // Kvartira, Obshejitie
        $tableName = (new $modelClass)->getTable(); // Получаем имя таблицы

        $query = $modelClass::query();

        // Если получить только МОИ ИЗБРАННЫЕ объявления
        if (isset($request->getMyLikeAds) && $request->getMyLikeAds == 'Получить мои лайки') {
            // Присоединим таблицу лайков для сортировки по времени добавления лайка
            $query->join('likes', function ($join) use ($request, $tableName, $modelClass) {
                $join->on('likes.likeable_id', '=', "{$tableName}.id")
                    ->where('likes.author_id', $request->user_id)
                    ->where('likes.likeable_type', $modelClass);
            })->orderBy('likes.created_at', 'desc'); // Сортировка по времени добавления лайка

            // Определяем поля для выборки
            $selectFields = ["{$tableName}.id", 'lat', 'lon', 'cena'];

            // Выбираем объявления с учетом лайков
            $query->select($selectFields);
        }

        // Отсортируем объявления по фильтру
        $filter = $request->filter;
        if ($filter != 'Фильтр не применен') {

            // Доступные поля фильтров
            $filters = [
                'oblast','gorod','tip_sdelki','period_arendi','tip_stroeniya','tip_obekta','kolichestvo_komnat_ot','kolichestvo_komnat_do',
                'deistvuushii_bisnes',  'cena_tip', 'cena_ot', 'cena_do',
                'sfera_deyatelnosti', 'mestopolojenie', 'tip_ofisa', 'etag_ot', 'etag_do', 'etagnost_ot',
                'etagnost_do', 'visota_potolkov_ot', 'visota_potolkov_do', 'ploshad_obshaya_ot', 'ploshad_obshaya_do',
                'ploshad_uchastka_ot', 'ploshad_uchastka_do', 'god_postroiki_ot', 'god_postroiki_do',
                'sostoyanie', 'mebel', 'dush', 'sanuzel', 'kanalizaciya', 'voda', 'otoplenie', 'raion', 'tel'
            ];

            foreach ($filters as $filterName) {
                // Проверяем наличие значения и используем null вместо пустой строки
                if (isset($filter[$filterName]) && !is_null($filter[$filterName])) {
                    // Проверяем диапазонные фильтры
                    if (strpos($filterName, '_ot') !== false || strpos($filterName, '_do') !== false) {
                        $dbColumn = str_replace(['_ot', '_do'], '', $filterName);

                        // Добавляем условия для диапазонов
                        if (isset($filter[$dbColumn . '_ot']) && !is_null($filter[$dbColumn . '_ot'])) {
                            $query->where($dbColumn, '>=', $filter[$dbColumn . '_ot']);
                        }
                        if (isset($filter[$dbColumn . '_do']) && !is_null($filter[$dbColumn . '_do'])) {
                            $query->where($dbColumn, '<=', $filter[$dbColumn . '_do']);
                        }
                    } else {
                        // Простое условие
                        $query->where($filterName, $filter[$filterName]);
                    }
                }
            }
        }

        //Получим либо избранные объявления Автора - Либо (Активно или В архиве)
        if($request->getMyLikeAds == 'Получить мои лайки'){
            $ads_arr = $query->get();
        }
        else{
            // Определение состояния объявления (архив или активно)
            $controlStatus = ($filter != 'Фильтр не применен' && !empty($filter['arhiv'])) ? 'В архиве' : 'Активно';
            // Определяем выбор полей
            $selectFields = ['id', 'lat', 'lon', 'cena'];

            // Применение условия для фильтрации по состоянию
            $query->where('control', $controlStatus)->select($selectFields);

            $ads_arr = $query->get();
        }

        //Вернем найденные объявления
        return response()->json(['ads'=>$ads_arr], 200);
    }
    // Метод получить объявления при клике на Яндекс Кластере
    public function getAllAdsInYandexCluster(Request $request){
        $modelClass = 'App\Models\Ads\\' . $request->table_name; // Kvartira, Obshejitie
        $query = $modelClass::query();

        //Если запрос при клике на сам кластер - то получим данные объявлений входящих в этот кластер
        // Получим объявления по ID
        $query->whereIn('id', $request->arr_ads_id)
            ->orderBy('bueAds', 'desc')
            ->orderBy('updated_at', 'desc')
            ->orderBy('id', 'asc')
            ->select(['id', 'author_id', 'zagolovok', 'table_name', 'cena', 'gorod', 'raion', 'images','control','srochno_torg', 'top', 'top_8', 'top_x7', 'top_x30','bueAds','updated_at', 'created_at'])
            ->with(['likes' => function($query) use ($request) {
                $query->where('author_id', $request->user_id); // Загружаем только лайки текущего пользователя
            }]);


        $ads_arr = $query->cursorPaginate(20);

        // Перебираем объявления и добавляем дополнительные данные
        $index = 0;
        foreach ($ads_arr as $ads) {
            $ads->uniqueKey = $index;
            $index++;
        }

        //Вернуть найденные объявления - для курсорной пагинации при первой загрузке
        return response()->json(['ads'=>$ads_arr], 200);
    }

    //Метод получить 1-но объявление если оно активно для любого пользователя
    public function getOneAds(Request $request){

        // С какой таблицы получить объявление Kvartiras, Obshejities
        $modelClass = 'App\Models\Ads\\' . $request->table_name;

        // Получим объявление по id
        $ads = $modelClass::find($request->ads_id);

        if($ads == null)return response()->json(['error'=>'Данное объявление удалено'],422);

        // Проверяем наличие лайка пользователя
        $ads->userLike = $ads->likes->firstWhere('author_id', $request->user_id) !== null;

        return $ads;
    }

    //Метод получить все объявления пользователя
    public function getUserAds(Request $request){

        $user = User::find($request->author_id);

        // Массив с именами таблиц объявлений
        $tables = [
            'kvartiras', 'obshejities', 'doms', 'ofis', 'zdanies',
            'magazins', 'prombazas', 'prochayas', 'businesses'
        ];

        // Пустой массив для хранения объявлений и лайков
        $allRecords = [];

        // Переберите каждую таблицу и получите объявления пользователя и лайки
        $uniqueKey = 0; // Инициализируем уникальный ключ
        foreach ($tables as $table) {

            // Получим объявления пользователя для текущей таблицы
            $userAds = $user->{$table}()->where('control', '!=', 'В архиве')
                ->with(['likes' => function($query) use ($request) {
                    $query->where('author_id', $request->author_id); // Загружаем только лайки текущего пользователя
                }])->get();

            foreach ($userAds as $ads) {
                $ads->uniqueKey = $uniqueKey; // Добавляем индекс как уникальный ключ
                $uniqueKey++;
            }

            // Добавьте результаты непосредственно в массив $allRecords
            $allRecords = array_merge($allRecords, $userAds->toArray());

        }

        // Выполните сортировку массива $allRecords по полю updated_at в порядке убывания
        usort($allRecords, function ($a, $b) {
            return strtotime($b['updated_at']) - strtotime($a['updated_at']);
        });

        //Вернем данные
        return response()->json([ 'allAds' => $allRecords, 'userData' => $user ],200);
    }

    //Метод Toggle - Запустить - Остановить рекламирование
    public function adsActiveToggle(Request $request){

        //Получим объявление
        $className = 'App\Models\Ads\\' . $request->table_name;
        $ads = $className::find($request->ads_id);

        if($ads == null) return response()->json(['error'=>'Объявление было удалено'], 422);

        //Активно - Не активно
        $ads->control = ($ads->control == 'Активно') ? 'Не активно' : 'Активно';
        $ads->timestamps = false;
        $ads->save();
        return 'success';
    }

    //Метод - Добавит статистику просмотра объявлению
    public function addAdsStatistic(Request $request)
    {
        $modelClassName = 'App\Models\Ads\\' . $request->table_name;

        $view = AdsView::firstOrCreate([
            'viewable_id' => $request->ads_id,
            'viewable_type' => $modelClassName,
            'author_id' => $request->user_id,
            'author_ip' => $this->getClientIp(),
        ]);

        // Проверка была ли созданна запись
        if (!$view->wasRecentlyCreated) {
            return 'Статистика не добавлена';
        }

        // Увеличим счетчик просмотра у объявления
        $ads = $modelClassName::find($request->ads_id);
        $ads->timestamps = false;
        $ads->increment('view');

        return 'Статистика добавлена';
    }

    public function addAdsViewTelStatistic(Request $request)
    {
        $modelClassName = 'App\Models\Ads\\' . $request->table_name;

        $view = AdsViewTel::firstOrCreate([
            'viewable_id' => $request->ads_id,
            'viewable_type' => $modelClassName,
            'author_id' => $request->user_id,
            'author_ip' => $this->getClientIp(),
        ]);

        // Проверка создана ли запись
        if (!$view->wasRecentlyCreated) {
            return 'Статистика не добавлена';
        }

        // Увеличим счетчик просмотра телефона
        $ads = $modelClassName::find($request->ads_id);
        $ads->timestamps = false;
        $ads->increment('viewTel');

        return 'Статистика добавлена';
    }

    //Метод - Добавить жалобу на объявление
    public function addComplain(Request $request){

        // Получим объявление
        $className = 'App\Models\Ads\\' . $request->table_name;
        $ads = $className::find($request->ads_id);

        // Проверка существования и активности объявления
        if (!$ads || $ads->control !== 'Активно') return response()->json(['error' => 'Данное объявление не активно'], 422);


        // Проверим, была ли жалоба уже отправлена данным пользователем
        if (in_array($request->user_id, $ads->complain)) {
            return response()->json(['error' => 'Вы уже оставили жалобу на данное объявление'], 422);
        }

        // Добавим новую жалобу
        $ads->complain = array_merge($ads->complain, [$request->user_id, $request->complain]);

        // Проверим количество похожих жалоб
        $similarComplaints = array_filter($ads->complain, function ($complain) use ($request) {
            return strpos($complain, $request->complain) !== false;
        });

        // Если есть 5 похожих жалоб, делаем объявление неактивным
        if (count($similarComplaints) >= 4) {
            $ads->complain = [$request->complain];
            $ads->control = 'Поступили жалобы';
        }

        $ads->timestamps = false;
        $ads->save();

        return 'Жалоба отправлена';
    }


    //Toggle лайк / Поставить лайк! - Удалить лайк!
    public function addLikeToggle(Request $request){


        $className = 'App\Models\Ads\\' . $request->table_name; // Для какой таблицы
        $ads = $className::findOrFail($request->ads_id); // Найдем это объявление или вернем 404
        $ads->timestamps = false;

        // Найти или создать новую
        $like = Like::firstOrNew([
            'author_id' => $request->author_id,
            'likeable_type' => $className,
            'likeable_id' => $request->ads_id,
        ]);

        if ($like->exists) {// Если лайк уже есть, удаляем его
            $like->delete();
            $ads->decrement('countLike');
            return 'Удалено из избранного';
        }
        else { // Если лайка нет, создаем его
            $like->save();
            $ads->increment('countLike');
            return 'Добавлено в избранное';
        }
    }



    //Метод Удалить объявление, после удаление объявление отправляется в архив на 7 дней потом удаляется автоматом с Console->Kernel.php
    public function deleteAds(Request $request){

        $className = 'App\Models\Ads\\' . $request->table_name;
        $ads = $className::find($request->ads_id);

        //Если объявление не найденно то значить оно удаленно
        if($ads == null) return response()->json(['success'=>'Объявление удалено'], 200);

        // Удалим статистику просмотров views
        $ads->views()->delete();

        // Удалим статистику просмотров телефона views
        $ads->viewTels()->delete();

        // Удалим Лайки данного объявления like
//        $ads->likes()->delete();

        // У автора отнимем к-во объявлений
        User::find($ads->author_id)->decrement('count_ads');

        // Архивируем объявление
        $ads->control = 'В архиве';
        $ads->save();

        return 'success';
    }

}
