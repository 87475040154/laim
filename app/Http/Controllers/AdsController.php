<?php

namespace App\Http\Controllers;

use App\Models\Ads\ArhiveAds;
use App\Models\Ads\Business;
use App\Models\Ads\Dom;
use App\Models\Ads\GeneralAds;
use App\Models\Ads\Kvartira;
use App\Models\Ads\Magazin;
use App\Models\Ads\Obshejitie;
use App\Models\Ads\Ofis;
use App\Models\Ads\Prochaya;
use App\Models\Ads\Prombaza;
use App\Models\Ads\Snimu;
use App\Models\Ads\Zdanie;
use App\Models\AdsView;
use App\Models\AdsViewStatistic;
use App\Models\Like;
use App\Models\User;
use App\Models\View;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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


        //Проверка  капчи на сервере мы сравниваем токен который получили на фронтенде и сравниваем с бекендом
        if (config('recaptcha.enabled') && !$this->checkRecaptcha($request->recaptcha_token, $request->ip())) {
            return response()->json(['error' => 'Неверная капча - перезагрузите страницу'], 422);
        }

        // Общие правила валидации
        $commonRules = [
            'author_id'=> 'required|numeric|min:1|max:999999999',
            'zagolovok'=> 'required|string|min:1|max:50',
            'cena'=> 'required|numeric|min:1|max:999999999',
            'tip_stroeniya'=> 'required|string|min:1|max:255',
            "images.*"=> "nullable|file|mimes:jpg,jpeg,png,bmp,gif,svg,webp,heif|max:4000",
            'ploshad_obshaya'=> 'required|numeric|min:1|max:999999',
            'sostoyanie'=> 'nullable|string|min:1|max:255',
            'internet'=> 'nullable|string|min:1|max:255',
            'oblast'=> 'required|string|min:1|max:255',
            'gorod'=> 'required|string|min:1|max:255',
            'raion'=> 'nullable|string|min:1|max:255',
            'lat'=> 'required',
            'lon'=> 'required',
            'ulica'=> 'nullable|string|min:1|max:255',
            'nomer_doma'=> 'nullable|string|min:1|max:10',
            'text_obyavleniya'=> 'required|string|min:1|max:2000',
            'ownerOrRealtor'=> 'required|string|min:1|max:15',
            'name'=> 'required|string|min:1|max:30',
            'tel'=> 'required|string|max:255',
            'tel2'=> 'nullable|string|max:255',
            'recaptcha_token' => 'required|string'
        ];

        // Специфичные правила
        $tableSpecificRules = [];
        $model = null;
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
                    'parkovka'=> 'nullable|string|min:1|max:255',
                    'mebel'=> 'nullable|string|min:1|max:255',
                    'pol'=> 'nullable|string|min:1|max:255',
                    'bezopasnost.*'=> 'nullable|string|min:1|max:2000',
                    'raznoe.*'=> 'nullable|string|min:1|max:2000',
                ];
                $model = ($request->addOrUpdate == 'add') ? new Kvartira() : Kvartira::find($request->id);
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
                    'parkovka'=> 'nullable|string|min:1|max:255',
                    'pol'=> 'nullable|string|min:1|max:255',
                    'bezopasnost.*'=> 'nullable|string|min:1|max:2000',
                    'raznoe.*'=> 'nullable|string|min:1|max:2000',
                ];
                $model = ($request->addOrUpdate == 'add') ? new Obshejitie() : Obshejitie::find($request->id);
                break;
            case 'Dom':
                $tableSpecificRules = [
                    'tip_obekta'=> 'required|string|min:1|max:255',
                    'period_arendi'=> 'required|string|min:1|max:255',
                    'kolichestvo_komnat'=> 'required|integer|min:1|max:999',
                    'etagnost'=> 'required|numeric|min:1|max:999',
                    'ploshad_kuhni'=> 'nullable|numeric|min:1|max:999',
                    'ploshad_uchastka'=> 'required|integer|min:1|max:99999',
                    'mebel'=> 'nullable|string|min:1|max:255',
                    'sanuzel'=> 'nullable|string|min:1|max:255',
                    'kanalizaciya'=> 'nullable|string|min:1|max:255',
                    'voda'=> 'nullable|string|min:1|max:255',
                    'elektrichestvo'=> 'nullable|string|min:1|max:255',
                    'otoplenie'=> 'nullable|string|min:1|max:255',
                    'gaz'=> 'nullable|string|min:1|max:255',
                    'parkovka'=> 'nullable|string|min:1|max:255',
                    'pol'=> 'nullable|string|min:1|max:255',
                    'bezopasnost.*'=> 'nullable|string|min:1|max:2000',
                    'raznoe.*'=> 'nullable|string|min:1|max:2000',
                ];
                $model = ($request->addOrUpdate == 'add') ? new Dom() : Dom::find($request->id);
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
                    'parkovka'=> 'nullable|string|min:1|max:255',
                    'pol'=> 'nullable|string|min:1|max:255',
                    'bezopasnost.*'=> 'nullable|string|min:1|max:2000',
                    'raznoe.*'=> 'nullable|string|min:1|max:2000',
                ];
                $model = ($request->addOrUpdate == 'add') ? new Ofis() : Ofis::find($request->id);
                break;
            case 'Zdanie':
                $tableSpecificRules = [
                    'etagnost'=> 'required|string|min:1|max:999',
                    'visota_potolkov'=> 'required|string|min:1|max:99',
                    'ploshad_uchastka'=> 'required|numeric|min:1|max:99999',
                    'bezopasnost.*'=> 'nullable|string|min:1|max:2000',
                ];
                $model = ($request->addOrUpdate == 'add') ? new Zdanie() : Zdanie::find($request->id);
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
                    'parkovka'=> 'nullable|string|min:1|max:255',
                    'pol'=> 'nullable|string|min:1|max:255',
                    'bezopasnost.*'=> 'nullable|string|min:1|max:2000',
                    'raznoe.*'=> 'nullable|string|min:1|max:2000',
                ];
                $model = ($request->addOrUpdate == 'add') ? new Magazin() : Magazin::find($request->id);
                break;
            case 'Prombaza':
                $tableSpecificRules = [
                    'tip_obekta'=> 'required|string|min:1|max:255',
                    'cena_tip'=> 'required|string|min:1|max:255',
                    'ploshad_uchastka'=> 'required|numeric|min:1|max:99999',
                    'ploshad_proizvodstvennih_pomesheniy'=> 'nullable|string|min:1|max:255',
                    'visota_proizvodstvennih_pomesheniy'=> 'nullable|string|min:1|max:255',
                    'ploshad_skladskih_pomesheniy'=> 'nullable|string|min:1|max:255',
                    'visota_skladskih_pomesheniy'=> 'nullable|string|min:1|max:255',
                    'ploshad_ofisnih_pomesheniy'=> 'nullable|string|min:1|max:255',
                    'raspolojenie.*'=> 'nullable|string|min:1|max:255',
                    'kommunikacii.*'=> 'nullable|string|min:1|max:2000',
                    'raznoe.*'=> 'nullable|string|min:1|max:2000',
                ];
                $model = ($request->addOrUpdate == 'add') ? new Prombaza() : Prombaza::find($request->id);
                break;
            case 'Prochaya':
                $tableSpecificRules = [
                    'deistvuushii_bisnes'=> 'required|string|min:1|max:255',
                    'cena_tip'=> 'required|string|min:1|max:255',
                    'sfera_deyatelnosti'=> 'required|string|min:1|max:255',
                    'ploshad_uchastka'=> 'required|numeric|min:1|max:99999',
                    'raspolojenie.*'=> 'required|string|min:1|max:1000',
                    'kommunikacii.*'=> 'nullable|string|min:1|max:2000',
                ];
                $model = ($request->addOrUpdate == 'add') ? new Prochaya() : Prochaya::find($request->id);
                break;
            case 'Business':
                $tableSpecificRules = [
                    'tip_sdelki'=> 'required|string|min:1|max:255',
                    'deistvuushii_bisnes'=> 'required|string|min:1|max:255',
                    'cena_tip'=> 'required|string|min:1|max:255',
                    'sfera_deyatelnosti'=> 'required|string|min:1|max:255',
                    'mestopolojenie'=> 'required|string|min:1|max:255',
                    'raspolojenie.*'=> 'required|string|min:1|max:1000',
                    'kommunikacii.*'=> 'nullable|string|min:1|max:2000',
                ];
                $model = ($request->addOrUpdate == 'add') ? new Business() : Business::find($request->id);
                break;
            case 'Snimu':
                $tableSpecificRules = [
                    'tip_obekta'=> 'required|string|min:1|max:255',
                    'period_arendi'=> 'required|string|min:1|max:255',
                    'kolichestvo_komnat'=> 'required|integer|min:1|max:999',
                    'mebel'=> 'nullable|string|min:1|max:255',
                    'kommunikacii.*'=> 'nullable|string|min:1|max:2000',
                    'bezopasnost.*'=> 'required|string|min:1|max:1000',
                ];
                $model = ($request->addOrUpdate == 'add') ? new Snimu() : Snimu::find($request->id);
                break;
            default:
                return response()->json(['error' => 'Неизвестное имя таблицы'], 422);
        }

        // Объединим общие и специфичные правила валидации
        $validationRules = array_merge($commonRules, $tableSpecificRules);

        // Валидируем данные с формы
        $request->validate($validationRules);

        // Проверка на доступ к редактированию
        if ($request->role == 'admin' && (!$model || empty($model))) {
            return response()->json(['error' => 'У вас нет доступа к редактированию данного объявления'], 422);
        }
        if ($request->addOrUpdate == 'update' && $request->role == 'user' && $model->author_id != $request->author_id) {
            return response()->json(['error' => 'У вас нет доступа к редактированию данного объявления'], 422);
        }

        //Если проверки пройденны то добавим или обновим объявление через статический общий медоб
        $ads = GeneralAds::addOrUpdateAds( $model, $request->all() );

        $ads->images = explode(',', $ads->images);
        isset($ads->mebel_arr) && $ads->mebel_arr != null?$ads->mebel_arr = explode(',', $ads->mebel_arr):$ads->mebel_arr = [];
        isset($ads->raznoe) && $ads->raznoe != null?$ads->raznoe = explode(',', $ads->raznoe):$ads->raznoe = [];
        isset($ads->bezopasnost) && $ads->bezopasnost != null ? $ads->bezopasnost = explode(',', $ads->bezopasnost): $ads->bezopasnost = [];
        isset($ads->raspolojenie) && $ads->raspolojenie != null?$ads->raspolojenie = explode(',', $ads->raspolojenie): $ads->raspolojenie = [];
        isset($ads->kommunikacii) && $ads->kommunikacii != null?$ads->kommunikacii = explode(',', $ads->kommunikacii):$ads->kommunikacii = [];


        return response()->json(['ads'=>$ads], 200);
    }



    // Метод - Получить все объявления из одной таблицы пагинацией 30шт
    public function getAllAds(Request $request){

        //С какой таблицы получаем
        $modelClass = 'App\Models\Ads\\' . $request->table_name;
        $query = $modelClass::query();

        // Данные фильтра
        $filter = $request->filter;

        // Если ищем мои ЛАЙК
        if(isset($request->getMyLikeAds) && $request->getMyLikeAds == 'Получить мои лайки'){

            // Получите пользователя
            $user = User::find($request->user_id);

            // Получите лайки пользователя с заданным likeable_type (modelClass)
            $likedAds = $user->likedAds->where('likeable_type', $modelClass);

            // Получите ID объявлений, на которых стоит лайк пользователя
            $likedAdIds = $likedAds->pluck('likeable_id')->toArray();

            // Фильтрация объявлений по ID и типу лайкнутого объекта
            $query = $query->whereIn('id', $likedAdIds);

        }

        // Если ищем АРХИВНЫЕ
        if($request->filter != 'Фильтр не применен' && $request->filter['arhiv'] != '' && $request->getMyLikeAds == 'Не получать мои лайки'){
            $query = ArhiveAds::query(); //Получить объявления из таблицы arhiveAds
            $query->where('table_name', $request->table_name); //Это чтоб если мы заказываем архив для квартир или объявлений
        }

        // Если применен фильтр
        $filter = $request->filter;
        if ($filter != 'Фильтр не применен') {
            // По каким полям сортировка
            $filters = [
                'ownerOrRealtor', 'tip_sdelki', 'tip_obekta', 'period_arendi', 'kolichestvo_komnat_ot',
                'kolichestvo_komnat_do', 'deistvuushii_bisnes', 'cena_tip', 'cena_ot', 'cena_do',
                'sfera_deyatelnosti', 'mestopolojenie', 'tip_ofisa', 'etag_ot', 'etag_do', 'etagnost_ot',
                'etagnost_do', 'visota_potolkov_ot', 'visota_potolkov_do', 'ploshad_obshaya_ot', 'ploshad_obshaya_do',
                'ploshad_uchastka_ot', 'ploshad_uchastka_do', 'tip_stroeniya', 'sostoyanie', 'mebel', 'dush', 'sanuzel',
                'kanalizaciya', 'voda', 'otoplenie', 'oblast', 'gorod', 'raion', 'tel'
            ];

            // Переберем и применим те, что переданы в фильтре для сортировки
            foreach ($filters as $filterName) {
                if (isset($filter[$filterName]) && $filter[$filterName] !== '') {
                    $dbColumn = str_replace(['_ot', '_do'], '', $filterName);

                    // Если в фильтре есть поле _ot или _do для сравнения больше/меньше
                    if (strpos($filterName, '_ot') !== false || strpos($filterName, '_do') !== false) {
                        $query->when($filter[$dbColumn . '_ot'] != '' || $filter[$dbColumn . '_do'] != 0, function ($query) use ($filter, $dbColumn) {
                            $query->where(function ($query) use ($dbColumn, $filter) {
                                $query->when($filter[$dbColumn . '_ot'] != '', function ($query) use ($dbColumn, $filter) {
                                    $query->where($dbColumn, '>=', $filter[$dbColumn . '_ot']);
                                })->when($filter[$dbColumn . '_do'] != '', function ($query) use ($dbColumn, $filter) {
                                    $query->where($dbColumn, '<=', $filter[$dbColumn . '_do']);
                                });
                            });
                        });
                    } else {
                        $query->where($filterName, $filter[$filterName]);
                    }
                }
            }
        }


        //Если запрос объявлений для Yandex Cluster - с координатами расположения
        if(isset($request->getCoordinates)){

            //Получить или активные или по фильтру
            if($filter == 'Фильтр не применен'){
                $ads_arr = $query->where('control', '=','Активно')->latest()->select(['id', 'tip_obekta','table_name', 'lat', 'lon', 'cena'])->get();
            }else{
                if($filter['arhiv'] != ''){
                    $ads_arr = $query->latest()->select(['id','ads_id', 'table_name', 'lat', 'lon', 'cena'])->get();
                }else{
                    $ads_arr = $query->latest()->select(['id', 'table_name', 'lat', 'lon', 'cena'])->get();
                }
            }


            //Вернем найденные объявления
            return response()->json(['ads'=>$ads_arr], 200);
        }
        //Если запрос уже при клике на кластер - то получим данные объявлений входящих в этот кластер
        if(isset($request->getAdsYandexClusterer)){
            $ads_arr = $query->find($request->arr_ads_id);
        }

        //Если запрос получить мои лайки
        if($request->getMyLikeAds == 'Получить мои лайки'){
            $query->latest();
            $ads_arr = $request->cursorPaginate == 'true' ? $query->cursorPaginate(30) : $query->paginate(30);
        }

        //Если запрос моих архивных
        if($filter != 'Фильтр не применен' && $filter['arhiv'] != '' && $request->getMyLikeAds == 'Не получать мои лайки' && !isset($request->getAdsYandexClusterer)){
            $query->latest();
            $ads_arr = $request->cursorPaginate == 'true' ? $query->cursorPaginate(30) : $query->paginate(30);
        }

        //Если запрос только активных объявлений
        if(!isset($request->getCoordinates) && !isset($request->getAdsYandexClusterer) && $request->getMyLikeAds == 'Не получать мои лайки' && $filter == 'Фильтр не применен'
            || !isset($request->getCoordinates) && !isset($request->getAdsYandexClusterer) && $request->getMyLikeAds == 'Не получать мои лайки' && $filter != 'Фильтр не применен' && $filter['arhiv'] == ''){

            //Получим связанные лайки предварительная загрузка
            // Дополнительные условия и сортировка
            $query->where('control', 'Активно');
            $query->with(['likes' => function ($query) use ($request) {
                $query->where('author_id', $request->user_id);
            }])
                ->orderBy('bueAds', 'desc') // Сначала выводим объявления на которых есть реклама
                ->orderBy('updated_at', 'desc') // Затем получим все остальные
                ->select(['id', 'zagolovok', 'table_name', 'cena', 'oblast', 'gorod', 'raion', 'ulica', 'nomer_doma', 'images', 'srochno_torg', 'top', 'top_8', 'top_x7', 'top_x30','bueAds','updated_at', 'created_at']);

            $ads_arr = $request->cursorPaginate == 'true' ? $query->cursorPaginate(30) : $query->paginate(30);

        }

        // Перебираем объявления и добавляем дополнительные данные
        foreach ($ads_arr as $ads) {
            // Разбиваем поле "images" на массив по запятым
            $ads->images = explode(',', $ads->images);

            // Проверяем наличие лайка пользователя
            $ads->userLike = ($request->getMyLikeAds == 'Получить мои лайки')
                || $ads->likes->firstWhere('author_id', $request->user_id) !== null;
        }

        //Вернуть найденные объявления
        $countAds = 'null';
        if(isset($request->countAds) && $request->cursorPaginate == 'true'){
            $countAds = $query->count();
        }
        return response()->json(['ads'=>$ads_arr,'countAds'=>$countAds], 200);
    }

    //Метод получить 1-но объявление если оно активно для любого пользователя
    public function getOneAds(Request $request){

        //Получим объявление с БД
        $modelClassName = 'App\Models\Ads\\' . $request->table_name;
        // Получим лайки через связь и саму запись
        $ads = $modelClassName::with('likes')->find($request->ads_id);

        //Если объявление Удалено отправить уведомление
        if($ads == null){
            $ads = ArhiveAds::where('ads_id', $request->ads_id)->where('table_name', $request->table_name)->first();
            if($ads == null )return response()->json(['error'=>'Данное объявление удалено'],422);
        }

        //Разобъем строковые данны на массив
        $ads->images = explode(',', $ads->images);
        isset($ads->mebel_arr) && $ads->mebel_arr != null?$ads->mebel_arr = explode(',', $ads->mebel_arr):$ads->mebel_arr = [];
        isset($ads->raznoe) && $ads->raznoe != null?$ads->raznoe = explode(',', $ads->raznoe):$ads->raznoe = [];
        isset($ads->bezopasnost) && $ads->bezopasnost != null ? $ads->bezopasnost = explode(',', $ads->bezopasnost): $ads->bezopasnost = [];
        isset($ads->raspolojenie) && $ads->raspolojenie != null?$ads->raspolojenie = explode(',', $ads->raspolojenie): $ads->raspolojenie = [];
        isset($ads->kommunikacii) && $ads->kommunikacii != null?$ads->kommunikacii = explode(',', $ads->kommunikacii):$ads->kommunikacii = [];


        // Проверяем наличие лайка пользователя
        $ads->userLike = $ads->likes->firstWhere('author_id', $request->user_id) !== null;
        $ads->countLike = $ads->likes->count(); //Количество лайков на объявлении

        return $ads;
    }

    //Метод получить все объявления пользователя
    public function getUserAds(Request $request){
        $user = User::find($request->author_id);

        // Создайте массив с именами таблиц объявлений
        $tables = [
            'kvartiras', 'obshejities', 'doms', 'ofis', 'zdanies',
            'magazins', 'prombazas', 'prochayas', 'businesses', 'snimus'
        ];

        // Создайте пустой массив для хранения объявлений и лайков
        $allRecords = [];

        // Переберите каждую таблицу и получите объявления пользователя и лайки
        foreach ($tables as $table) {

            // Получите объявления пользователя для текущей таблицы и предварительно загрузите связанные лайки
            $userAds = $user->{$table}()->with('likes')->get();

            // Преобразуйте поле "images" и другие поля и проверьте наличие лайка пользователя
            $userAds->transform(function ($ads) use ($request){
                $ads->images = explode(',', $ads->images);
                $ads->mebel_arr = isset($ads->mebel_arr) ? explode(',', $ads->mebel_arr) : [];
                $ads->raznoe = isset($ads->raznoe) ? explode(',', $ads->raznoe) : [];
                $ads->bezopasnost = isset($ads->bezopasnost) ? explode(',', $ads->bezopasnost) : [];
                $ads->raspolojenie = isset($ads->raspolojenie) ? explode(',', $ads->raspolojenie) : [];
                $ads->kommunikacii = isset($ads->kommunikacii) ? explode(',', $ads->kommunikacii) : [];

                $ads->userLike = $ads->likes->where('author_id', $request->author_id)->isNotEmpty();
                return $ads;
            });

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

        $ads_id = $request->ads_id;
        $table_name = $request->table_name ;

        //Получим объявление
        $className = 'App\Models\Ads\\' . $table_name;
        $ads = $className::find($ads_id);

        if($ads == null) return response()->json(['error'=>'Объявление было удалено'], 422);

        //Активно - Не активно
        $ads->control == ($ads->control == 'Активно') ? 'Не активно' : 'Активно';
        $ads->timestamps = false;
        $ads->save();
        return 'success';
    }

    //Метод - Добавит статистику просмотра объявлению
    public function addAdsStatistic(Request $request)
    {
        $modelClassName = 'App\\Models\\Ads\\' . $request->table_name;

        // Проверка наличия записи с учетом уникального индекса
        $view = AdsView::firstOrNew([
            'viewable_id' => $request->ads_id,
            'viewable_type' => $modelClassName,
            'author_id' => $request->user_id,
            'author_ip' => $this->getClientIp(),
        ]);

        // Если запись не существует, то она будет создана
        if (!$view->exists) {
            $view->save();

            $ads = $modelClassName::find($request->ads_id);

            if ($ads) {
                $ads->view += 1;
                $ads->timestamps = false;
                $ads->save();
            }

            return 'Статистика добавлена';
        }

        return 'Статистика не добавлена';
    }

    //Метод - Добавить жалобу на объявление
    public function addComplain(Request $request){
        $ads_id = $request->ads_id;
        $table_name = $request->table_name;

        // Получим объявление
        $className = 'App\Models\Ads\\' . $table_name;
        $ads = $className::find($ads_id);

        // Проверка существования и активности объявления
        if (!$ads || $ads->control !== 'Активно') {
            return response()->json(['error' => 'Данное объявление не активно'], 422);
        }

        // Получим текущие жалобы
        $complains = explode(',', $ads->complain);

        // Проверим, была ли жалоба уже отправлена данным пользователем
        if (in_array($request->user_id, $complains)) {
            return response()->json(['error' => 'Вы уже оставили жалобу на данное объявление'], 422);
        }

        // Добавим новую жалобу
        $complains[] = $request->user_id . ',' . $request->complain;

        // Проверим количество похожих жалоб
        $similarComplaints = array_filter($complains, function ($complaint) use ($request) {
            return strpos($complaint, $request->complain) !== false;
        });

        // Если есть 5 похожих жалоб, делаем объявление неактивным
        if (count($similarComplaints) >= 4) {
            $ads->complain = $request->complain;
            $ads->control = 'Поступили жалобы';
            $ads->timestamps = false;
        } else {
            $ads->complain = implode(',', $complains);
        }

        $ads->save();

        return 'Жалоба отправлена';
    }


    //Toggle лайк / Поставить лайк! - Удалить лайк!
    public function addLikeToggle(Request $request){

        $author_id = $request->author_id;
        $ads_id = $request->ads_id;
        $table_name = $request->table_name;


        $className = 'App\Models\Ads\\' . $table_name; // Для какой таблицы
        $ads = $className::find($ads_id); //Найдем это объявление

        //Если объявление найденно
        if ($ads) {
            // Проверяем, есть ли уже лайк от пользователя
            $like = Like::where('author_id', $author_id)
                ->where('likeable_type', get_class($ads))
                ->where('likeable_id', $ads->id)
                ->first();

            if ($like) {// Если лайк уже есть, удаляем его
                $like->delete();
                return 'Удалено из избранного';
            }
            else { // Если лайка нет, создаем его
                $like = new Like();
                $like->author_id = $author_id;
                $like->likeable()->associate($ads); //Полиморфная связь добавит в колонки  id и путь к таблице например App\Models\Ads\Kvartiras
                $like->save();
                return 'Добавлено в избранное';
            }
        } else {
            return response()->json(['error' => 'Объявление не найдено или не активно'], 422);
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

        // Удалим Лайки данного объявления like
        $ads->likes()->delete();

        // Добавим данное объявление в таблицу Архив
        $archive = new ArhiveAds();
        $archive->fill($ads->toArray());
        $archive->ads_id = $ads->id;
        $archive->delete = Carbon::now();
        $archive->save();

        // У автора отнимем к-во объявлений
        User::find($ads->author_id)->decrement('count_ads');

        // Удалим объявление с самой таблицы
        $ads->delete();

        return 'success';
    }

}
