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

    //Метод получить токен для перехода к оплате
    public function getPayLink(Request $request)
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
        if (in_array('Горячие', $bueAdsType)) $summ += 240;
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


        //Получим токен доступа для проведения платежа
        try {

            // Создаем экземпляр клиента Guzzle
            $client = new Client();

            // Данные для POST-запроса на получение токена
            $data = [
                'grant_type' => "client_credentials",
                'scope' => "webapi usermanagement email_send verification statement statistics payment",
                'client_id' => "test",
                'client_secret' => "yF587AV9Ms94qN2QShFzVR3vFnWkhjbAK3sG",
                'invoiceID' => sprintf("%'.06d", $bueAds->id),
                'amount' => $bueAds->summ,
                'currency' => "KZT",
                'terminal' => "67e34d63-102f-4bd1-898e-370781d0074d",
                'postLink' => $_SERVER['HTTP_HOST'] . "/api/user/checkBueAds",
            ];

            // Отправляем POST-запрос на URL для получения токена
            $response = $client->post('https://testoauth.homebank.kz/epay2/oauth2/token', [
                'form_params' => $data
            ]);

            // Получение тела ответа в виде строки
            $body = $response->getBody()->getContents();

            // Преобразуем JSON-строку в ассоциативный массив
            $responseData = json_decode($body, true);

            // Возвращаем данные в JSON-ответе, включая полученные данные и номер счета и сумму платежа
            return response()->json([
                'body' => $responseData, // token - доступа для проведения оплаты
                'invoiceID' => sprintf("%'.06d", $bueAds->id), // преобразуем оплату с 1 на  000001
                'amount' => $bueAds->summ
            ], 200);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    // После оплаты автоматически будет вызываться этот метод системой оплаты куда передается информация об оплате
    public function checkBueAds(Request $request)
    {


        //Проверим есть ли платеж с таким invoiceID в БД
        $bueAds = BueAds::find(intval($request->invoiceID) );
        $bueAds->status = $request->code;
        $bueAds->save();
        return;

        //Если платежа с таким invoiceID нет в БД то это ошибка
        if ($bueAds == null) return response()->json(['error' => 'Данного платежа нет в Базе данных, запросите новый платеж'], 422);

        //Если платеж есть но он уже получил статус ВЫполнено то тоже ошибка
        if ($bueAds->result == 'Выполнено') return response()->json(['error' => 'Реклама уже применена'], 422);


        //Проверка платежа на достоверность в системе epay
        try {

            // Получение токена доступа
            $client = new Client();
            $data = [
                'grant_type' => 'client_credentials',
                'scope' => 'webapi usermanagement email_send verification statement statistics payment',
                'client_id' => 'test',
                'client_secret' => 'yF587AV9Ms94qN2QShFzVR3vFnWkhjbAK3sG',
                'terminal' => '67e34d63-102f-4bd1-898e-370781d0074d',
            ];
            $response = $client->post('https://testoauth.homebank.kz/epay2/oauth2/token', [
                'form_params' => $data
            ]);
            // Получение тела ответа в виде строки
            $body = $response->getBody()->getContents();
            // Преобразование ответа из JSON в ассоциативный массив
            $responseData = json_decode($body, true);
            // Получение актуального токена из ответа
            $accessToken = $responseData['access_token'];

            // Теперь проверим статус самой транзакции
            $client = new Client();
            // Данные для GET-запроса
            $queryParams = [
                'transactionId' => $request->invoiceID,
            ];
            $response = $client->get("https://testepay.homebank.kz/api/check-status/payment/transaction/$request->invoiceID", [
                'headers' => [
                    'Authorization' => "Bearer $accessToken", // Добавляем токен в заголовок Authorization
                ],
                'query' => $queryParams
            ]);
            // Получение тела ответа в виде строки
            $body = $response->getBody()->getContents();
            // Преобразование ответа из JSON в ассоциативный массив
            $responseData = json_decode($body, true);

            // Теперь в $responseData у вас содержится информация о статусе транзакции
            // CHARGE - Если этот статус, то оплата прошла
            if ($responseData['transaction']['statusName'] != 'CHARGE') {
                return response()->json(['error' => 'Ошибка оплаты'], 422);
            }

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }


        //Если платеж прошел активируем подписку у пользователя
        $className = 'App\\' . $bueAds->table_name;
        $ads = $className::find($bueAds->ads_id);


        //Добавим рекламу объявлению x30
        if ($bueAds->bue_ads_type == 'Top x30') {
            $ads->add_ads_top = Carbon::now();
            $ads->top_x30 = Carbon::now();
            $ads->save();
        }

        //Добавим рекламу объявлению x7
        if ($bueAds->bue_ads_type == 'Top x7') {
            $ads->add_ads_top = Carbon::now();
            $ads->top_x7 = Carbon::now();
            $ads->save();
        }

        //Реклама по 1
        if ($bueAds->bue_ads_type != 'Top x30' && $bueAds->bue_ads_type != 'Top x7') {
            $bueType = explode(',', $bueAds->bue_ads_type);

            foreach ($bueType as $type) {
                if ($type == 'Срочно торг') $ads->srochno_torg = 1;
                if ($type == 'Топ 24') {
                    $ads->add_ads_top = Carbon::now();
                    $ads->top = Carbon::now();
                }
                if ($type == 'Топ 8 раз') {
                    $ads->add_ads_top = Carbon::now();
                    $ads->top_8 = Carbon::now();
                }
            }

            $ads->save();
        }


        //Изменить статус покупки
        $bueAds->result = 'Выполнено';
        $bueAds->save();
        return response()->json(['success' => 'success'], 200);

    }
}
