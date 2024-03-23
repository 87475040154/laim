<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailVerificationQueue;
use App\Jobs\SendLinkForgotPasswordQueue;
use App\Notifications\EmailVerificationNotifi;
use App\Notifications\SendLinkForgotPassword;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    //Метод проверяет сравнивает рекапчу если ок вернет true
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

    //Получить аутентифицированного пользователя
    public function user(Request $request)
    {
        $user = Auth::user();
        return response()->json(['user' => $user]);
    }

    //Метод авторизации - вход на сайт
    public function login(Request $request){

        //Валидация входящих данных
        $request->validate([
            'email'=> 'required|string|email|max:255',
            'password'=> 'required|string|min:6|max:30',
            'recaptcha_token' => 'required|string'
        ]);

        //Проверка  капчи на сервере мы сравниваем токен который получили на фронтенде и сравниваем с бекендом
        if (config('recaptcha.enabled') && !$this->checkRecaptcha($request->recaptcha_token, $request->ip())) {
            return response()->json(['error' => 'Неверная капча - перезагрузите страницу',], 422);
        }


        //Встроенная попытка авторизации
        if(Auth::attempt($request->only('email', 'password'))){

            $user = Auth::user();
            if($user->email_verified_at == null){
                return response()->json(['error'=> 'Email не подтвержден'],401);
            }

            //Создаем токен доступа - Он автоматом будет включен далее в каждый запрос пока мы не вызовим logout
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json(['access_token' => $token, 'token_type' => 'Bearer'], 200);
        }else{
            $user = User::where('email', $request->email)->first();
            if(!$user){
                return response()->json(['error'=> 'Пользователь с таким email еще не зарегистрирован'],401);
            }else{
                return response()->json(['error'=> 'Вы ввели неправильный пароль'],401);
            }
        }
    }

    //Метод авторизации - вход на сайт через Google
    public function googleLogin(Request $request){

        $email = $request->email;
        $name = $request->name;

        //Если вызов через кнопку получим данные пользователя с google
        if($email == ''){
            //Получим данные пользователя с гугл его email, имя
            $accessToken = $request->input('token');
            $url = 'https://www.googleapis.com/oauth2/v3/userinfo';
            $client = new Client();

            try {
                $response = $client->get($url, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                    ],
                ]);
                $userData = json_decode($response->getBody(), true);

                $email = $userData['email'];
                $name = $userData['name'];

            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to fetch user data'], 500);
            }
        }


        $user = User::where('email', $email)->first();
        if($user == null){
            $user = User::create([
                'name' => mb_convert_case(explode(' ', $name)[0], MB_CASE_TITLE, "UTF-8"),
                'email' => $email,
                'email_verified_at'=>Carbon::now(),
                'tel' => null,
                'password' => bcrypt(Carbon::now()),
                'agree' => 1,
            ]);
        }

        //После активации авторизуем пользователя
        Auth::login($user);
        $user = Auth::user();
        //Создаем токен доступа - Он автоматом будет включен далее в каждый запрос пока мы не вызовим logout
        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json(['access_token' => $token, 'token_type' => 'Bearer'], 200);

    }

    //Метод регистрации нового пользователя
    public function register(Request $request){

        //Валидация входящих данных
        $request->validate([
            'name'=> 'required|string|max:15',
            'email'=> 'required|string|email|max:255|unique:users,email',
            'tel'=> 'required|string|max:255',
            'password'=> 'required|string|min:6|max:30',
            'agree'=>'required|accepted',
            'recaptcha_token' => 'required|string'
        ]);

        //Проверка  капчи на сервере мы сравниваем токен который получили на фронтенде и сравниваем с бекендом
        if (config('recaptcha.enabled') && !$this->checkRecaptcha($request->recaptcha_token, $request->ip())) {
            return response()->json(['error' => 'Неверная капча - перезагрузите страницу',], 422);
        }

        //Генерирую токен чтобы отправить его на почту и добавить в бд чтоб потом сравнить при активации емайла
        $token = Str::random(60);

        $user = User::create([
            'name' => mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8"),
            'email' => $request->email,
            'tel' => $request->tel,
            'password' => bcrypt($request->password),
            'agree' => $request->agree,
            'remember_token' => $token
        ]);

        //Отправлю на емайл ссылку активации, email и пароль   // Добавление задачи в очередь
        Queue::push(new SendEmailVerificationQueue($user, $token, $request->path));

        return 'success';
    }

    //Метод непосредственно подтверждения Email
    public function emailVerification(Request $request){

        $request->validate([
            'account_activation_token'=> 'required|string|max:255',
            'recaptcha_token'=> "required|string"
        ]);

        //Проверка капчи
        if(config('recaptcha.enabled') && !$this->checkRecaptcha($request->recaptcha_token, $request->ip())){
            return response()->json(['error'=> 'Неверная капча - перезагрузите страницу'], 422);
        }

        //Активируем аккаунт если есть такой токен
        $user = User::where('remember_token', $request->account_activation_token)->first();
        if(!$user){
            return response()->json(['error'=> 'Вы уже активировали аккаунт. Попробуйте войти на сайт'], 422);
        }
        else{
            $user->remember_token = null;
            $user->email_verified_at = now();
            $user->save();

            //После активации авторизуем пользователя
            Auth::login($user);
            $user = Auth::user();
            //Создаем токен доступа - Он автоматом будет включен далее в каждый запрос пока мы не вызовим logout
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json(['access_token' => $token, 'token_type' => 'Bearer'], 200);
        }
    }

    //Метод отправки письма для подтверждения Email
    public function sendLinkEmailVerification(Request $request){
        $request->validate([
            'email'=> 'required|email|string|max:255',
            'recaptcha_token'=> "required|string"
        ]);

        if(config('recaptcha.enabled') && !$this->checkRecaptcha($request->recaptcha_token, $request->ip())){
            return response()->json(['error'=> 'Неверная капча - перезагрузите страницу'], 422);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json(['error'=> 'Пользователь с таким email еще не зарегистрирован'], 422);
        }else{
            $token = Str::random(60);
            $user->remember_token = $token;
            $user->save();

            // Отправка письма через очередь
            Queue::push(new SendEmailVerificationQueue($user, $token, $request->path));

            return response()->json(['message'=>'success'],200);
        }
    }

    //Метод отправки письма для востановления пароля
    public function sendLinkForgotPassword(Request $request){

        $request->validate([
            'email'=> 'required|email|string|max:255',
            'recaptcha_token'=> "required|string"
        ]);

        if(config('recaptcha.enabled') && !$this->checkRecaptcha($request->recaptcha_token, $request->ip())){
            return response()->json(['error'=> 'Неверная капча - перезагрузите страницу'], 422);
        }


        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json(['error'=> 'Пользователь с таким email еще не зарегистрирован'], 422);
        }else{
            $token = Str::random(60);
            $user->remember_token = $token;
            $user->save();

            // Отправка письма через очередь
            Queue::push(new SendLinkForgotPasswordQueue($user, $token, $request->path));

            return response()->json(['message'=>'success'],200);
        }
    }

    //Метод непосредственно смены пароля не входя на сайт
    public function resetPassword(Request $request){

        $request->validate([
            'password'=> 'required|string|min:6|max:30',
            'token'=> 'required|string|max:255',
            'recaptcha_token'=> 'required|string',
        ]);

        if(config('recaptcha.enabled') && !$this->checkRecaptcha($request->recaptcha_token, $request-ip())){
            return response()->json(['error' => 'Неверная капча - перезагрузите страницу'], 422);
        }


        $user = User::where('remember_token', $request->token)->first();
        if(!$user){
            return response()->json(['error'=>'Ссылка устарела, запросите новую ссылку для сменя пароля'],422);
        }else{
            $user->remember_token = null;
            $user->password = bcrypt($request->password);
            $user->save();

            //После смены пароля авторизуем пользователя
            Auth::login($user);
            $user = Auth::user();
            //Создаем токен доступа - Он автоматом будет включен далее в каждый запрос пока мы не вызовим logout
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json(['access_token' => $token, 'token_type' => 'Bearer'], 200);
        }

    }

    //Метод выхода пользователя из системы
    public function logout(Request $request){
        // Проверяем, что пользователь аутентифицирован
        if (Auth::check()) {
            // Если пользователь аутентифицирован, удаляем его токены
            Auth::user()->tokens()->delete();
        }

        Auth::user()->tokens()->delete();
        Auth::guard('web')->logout();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['logout' => true], 200);
    }

}
