<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectDataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\KZLocationController;
use Laravel\Socialite\Facades\Socialite;

//ГРУПА МАРШРУТОВ ДЛЯ АВТОРИЗАЦИИ -  регистрации итд.
Route::prefix('auth')->group(function () {

    Route::post('register', [AuthController::class, 'register']);//Маршрут вызовит метод регистрации
    Route::post('login',  [AuthController::class, 'login']); // Маршрут вызовит метод входа на сайт
    Route::post('googleLogin',  [AuthController::class, 'googleLogin']); // Маршрут вызовит метод входа на сайт через google

    //Маршруты доступные только авторизованным пользователям
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user', [AuthController::class, 'user']);//Маршрут получения данных авторизованного пользователя
    });
    Route::post('logout', [AuthController::class, 'logout']);//Маршрут разлогиневание пользователя - удаляет токен доступа



    Route::post('sendLinkForgotPassword', [AuthController::class, 'sendLinkForgotPassword']);// Отправить сылку для сброса пароля на почту
    Route::post('resetPassword', [AuthController::class, 'resetPassword']);//Маршрутвызывает метод непосредственно смены пароля

    Route::post('sendLinkEmailVerification', [AuthController::class, 'sendLinkEmailVerification']);//отправить ссылку на почту для верификации e-mail
    Route::put('emailVerification', [AuthController::class, 'emailVerification']);//Маршрут вызывает метод непосредственно верификации e-maila
});


//ЗАЩИЩЕННЫЕ МАРШРУТЫ - для авторизованных пользователей
Route::middleware('auth:sanctum')->group(function () {

    //Объявления
    Route::post('/addOrUpdateAds', [AdsController::class, 'addOrUpdateAds']);//Добавить | Редактировать объявление
    Route::delete('/deleteAds', [AdsController::class, 'deleteAds']); //Удалить объявление
    Route::post('/adsActiveToggle', [AdsController::class, 'adsActiveToggle']); //Запустить - остановить мое обьявление


    // Редактировать данные пользователя
    Route::post('/user/updateUserData', [UserController::class, 'updateUserData']);//Маршрут обновить-данные пользователя

    //Маршрут Toogle -  Добавить в избранное - Убрать
    Route::post('/like', [AdsController::class, 'addLikeToggle']); //Добавить - убрать из понравившегося

    //Маршрут отправить жалобу на объявление
    Route::post('/addComplain', [AdsController::class, 'addComplain']); //Добавить жалобу на объявление

    // Покупка рекламы
    Route::post('/user/addOrderDB', [UserController::class, 'addOrderDB']);//Маршрут - Добавим заказ в БД


});

//НЕ ЗАЩИЩЕННЫЕ МАРШРУТЫ - для всех пользователей

//Маршрут для получения данных о проекте ежеменутно
Route::get('/getProjectData', [ProjectDataController::class, 'getProjectData']);//Получить данные о проекте

//Маршруты получения объявлений Всех
Route::get('/getAllAds', [AdsController::class, 'getAllAds']);//Получить все объявление
Route::get('/countAds', [AdsController::class, 'countAds']);//Узнать колличество всего объявлений
Route::get('/getAllAdsYandexCluster', [AdsController::class, 'getAllAdsYandexCluster']);//Получить координаты объявлений для Яндекс кластера карты
Route::get('/getAllAdsInYandexCluster', [AdsController::class, 'getAllAdsInYandexCluster']);//Получить все объявления при клике на Яндекс кластер
Route::get('/getUserAds', [AdsController::class, 'getUserAds']);//Получить все объявления пользователя
Route::get('/getOneAds', [AdsController::class, 'getOneAds']);//Получить 1 объявление
Route::post('/addAdsStatistic', [AdsController::class, 'addAdsStatistic']);//Добавим статистику на объявление
Route::post('/addAdsViewTelStatistic', [AdsController::class, 'addAdsViewTelStatistic']);//Добавим статистику на просмотр телефона

//Локация Казахстана
Route::get('/KZLocation/getLocations', [KZLocationController::class, 'getLocations']);//Получить Локации

// Добавление продвижение - удаление заявки
Route::post('/user/addAdsPromotion', [UserController::class, 'addAdsPromotion']);// Подключение продвижения
Route::delete('/user/deleteOrderDB', [UserController::class, 'deleteOrderDB']);//Удалить заказ с БД если при оплате произошла ошибка
