<?php

namespace App\Http\Controllers;

use App\Models\Ads\Business;
use App\Models\Ads\Dom;
use App\Models\Ads\Kvartira;
use App\Models\Ads\Magazin;
use App\Models\Ads\Obshejitie;
use App\Models\Ads\Ofis;
use App\Models\Ads\Prochaya;
use App\Models\Ads\Prombaza;
use App\Models\Ads\Snimu;
use App\Models\Ads\Zdanie;
use App\Models\BueAds;
use App\Models\BuySubscription;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //Метод проверяет сравнивает рекапчу если ок вернет true
    protected function checkRecaptcha($token, $ip)
    {
        $client = new Client();
        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => config('recaptcha.secret'),
                'response' => $token,
                'remoteip' => $ip,
            ],
        ]);
        $response = json_decode((string)$response->getBody(), true);
        return $response['success'];
    }

    //Получим ip пользователя. Пс. на локалке выдает 127.0.0.1 на сервере должен норм выдовать, потом проверю
    public function getClientIp()
    {
        $ip = null;

        if (!empty($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
            // Используем первый IP-адрес из списка, если передано несколько через запятую
            $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ipList[0]);
        } elseif (!empty($_SERVER['REMOTE_ADDR']) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    //Метод обновить- изменить данные пользователя
    public function updateUserData(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'name' => 'required|string|max:15',
            'tel' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|max:30',
            'recaptcha_token' => 'required|string'
        ]);


        //Проверка  капчи на сервере мы сравниваем токен который получили на фронтенде и сравниваем с бекендом
        if (config('recaptcha.enabled') && !$this->checkRecaptcha($request->recaptcha_token, $request->ip())) {
            return response()->json(['error' => 'Неверная капча - перезагрузите страницу.',], 422);
        }

        $user = User::where('id', $request->user_id)->first();

        $user->name = mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8");
        $user->tel = $request->tel;
        if ($request->password != '') {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return 'success';
    }



    ////////////// ПОКУПКА РЕКЛАМЫ /////////////////

    // Метод - Добавить заказ в БД
    public function addOrderDB(Request $request)
    {

        //Валидация входящих данных
        $request->validate([
            'author_id'=> 'required|integer|min:1|max:999999999',
            'ads_id'=> 'required|integer|min:1|max:999999999',
            'table_name'=> 'required|string|min:1|max:100',
            'bue_ads_type' => 'required|string|min:1|max: 255'
        ]);

        // Определим стоимость
        $bueAdsType = explode(',', $request->bue_ads_type);
        $summ = 0;

        if (in_array('Top x30', $bueAdsType)) $summ = 3500;
        if (in_array('Top x7', $bueAdsType)) $summ = 2100;
        if (in_array('Топ 24', $bueAdsType)) $summ += 300;
        if (in_array('Топ 8 раз', $bueAdsType)) $summ += 1900;
        if (in_array('Срочно торг', $bueAdsType)) $summ += 90;

        if($summ == 0)return response()->json(['message'=>'Ошибка! Выберите занова услугу для покупки!'], 422);


        //Создадим запись в БД о предстоящей покупки
        $bueAds = BueAds::create([
            'author_id'=>$request->author_id,
            'ads_id'=>$request->ads_id,
            'table_name'=>$request->table_name,
            'summ'=>$summ,
            'bue_ads_type'=>$request->bue_ads_type,
        ]);

        return response()->json(['order'=>$bueAds], 200);

    }

    // После оплаты автоматически будет вызываться этот метод системой оплаты куда передается информация об оплате
    public function checkBueAds(Request $request)
    {

        //Получим - Запись заказа
        $bueAds = BueAds::find($request->order_id);

        //Если платежа с таким invoiceID нет в БД то это ошибка
        if ($bueAds == null) return response()->json(['error' => 'Данного платежа нет в Базе данных, запросите новый платеж'], 422);

        //Если платеж есть но он уже получил статус ВЫполнено то тоже ошибка
        if ($bueAds->result == 'Выполнено') return response()->json(['error' => 'На ваше объявление добавлена реклама!'], 422);


        // Здесь - Нужно проверить платеж в системе на достоверность



        //Если платеж прошел - добавим рекламу на объявление
        $className = 'App\\' . $bueAds->table_name;
        $ads = $className::find($bueAds->ads_id);

        if ($bueAds == 'Top x30') {
            $ads->bueAds = Carbon::now();
            $ads->top = Carbon::now();
            $ads->top_x7 = Null;
            $ads->top_x30 = Carbon::now();
        }
        else if ($bueAds == 'Top x7') {
            $ads->bueAds = Carbon::now();
            $ads->top = Carbon::now();
            $ads->top_x7 = Carbon::now();
            $ads->top_x30 = Null;
        }
        else{
            $bueAds = explode(',', $bueAds->bue_ads_type);
            foreach ($bueAds as $type) {
                if ($type == 'Срочно торг') $ads->srochno_torg = 1;
                if ($type == 'Топ 24') {
                    $ads->bueAds = Carbon::now();
                    $ads->top = Carbon::now();
                }
                if ($type == 'Топ 8 раз') {
                    $ads->bueAds = Carbon::now();
                    $ads->top_8 = Carbon::now();
                }
            }
        }


        // Сохраняем объявление с рекламой
        $ads->save();

        //Изменить статус заказа в БД
        $bueAds->result = 'Выполнено';
        $bueAds->save();

        return response()->json(['success' => 'success'], 200);

    }

    // Метод - удалить заказ с БД, Если не прошла оплата
    public function deleteOrderDB(Request $request){
        // Получаем заказ по переданному ID и проверяем, существует ли он
        $order = BueAds::find($request->order_id);
        if (!$order) return response()->json(['error' => 'Заказ не найден'], 404);

        // Удаляем заказ
        $order->delete();

        // Возвращаем успешный ответ
        return response()->json(['message' => 'Заказ успешно удален'], 200);
    }

}
