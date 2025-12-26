<?php

namespace App\Console;


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
use App\Models\Like;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // С каких таблиц выбрать записи


        //Какждый день в час ночи удалять пользователей не активировавших аккаунт у которых с момента регистрации прошел час
        $schedule->call(function () {
            User::where('email_verified_at', null)
                ->where('created_at', '<', Carbon::now()->subHour())->delete();
        })->dailyAt('01:00');

        //Отправляем объявления в архир - после 7 дней если поле updated_at не было обновлено каждый день в 2 часа ночи
        $schedule->call(function (){
            $tables = [
                Kvartira::class,
                Obshejitie::class,
                Dom::class,
                Ofis::class,
                Zdanie::class,
                Magazin::class,
                Prombaza::class,
                Prochaya::class,
                Business::class,
            ];

            foreach ($tables as $table) {

                // Получите объявления, которые нужно отправить в архив
                $table::where('updated_at', '<', Carbon::now()->subWeek())->chunk(200, function ($ads) use($table) {
                    $ads_id = [];

                    // Удаляем просмотры - собираем id объявлений
                    foreach ($ads as $ad) {

                        // Удалите связанную статистику с помощью связи views
                        $ad->views()->delete();

                        // Удалите связанную статистику просмотра телефона с помощью связи viewTels
                        $ad->viewTels()->delete();

                        // Уменьшите количество объявлений у пользователя
                        User::find($ad->author_id)->decrement('count_ads');

                        // Добавляем id объявления в массив $ads_id
                        $ads_id[] = $ad->id;
                    }

                    // Массово удаляем объявления через модель
                    $tableModel = new $table;
                    $tableModel->whereIn('id', $ads_id)
                        ->update(['control' => 'В архиве']);
                });
            }

        })->dailyAt('02:00');

        //Удалить объявление с архива - Если с момента delete прошло 7 дней каждый день в 3 часа ночи
        $schedule->call(function (){

            $tables = [
                Kvartira::class,
                Obshejitie::class,
                Dom::class,
                Ofis::class,
                Zdanie::class,
                Magazin::class,
                Prombaza::class,
                Prochaya::class,
                Business::class,
            ];

            foreach ($tables as $table) {

                // Получите объявления, которые нужно отправить в архив
                $table::where('control', 'В архиве')->where('updated_at', '<', Carbon::now()->subWeek())->chunk(200, function ($ads) use($table) {
                    $imagesToDelete = [];
                    $adsToDelete = [];

                    // Удаляем лайки - собираем id объявлений
                    foreach ($ads as $ad) {

                        // Удалите связанный лайк
                        $ad->likes()->delete();

                        // Занесем id
                        $adsToDelete[] = $ad->id;

                        // Занесем Фото
                        foreach ($ad->images as $name) {
                            $imagesToDelete[] = '/img/adsImg/' . $name;
                        }
                    }

                    // Выполним массовое удаление объявлений и изображений в транзакции
                    DB::transaction(function () use ($table, $adsToDelete, $imagesToDelete) {
                        // Массовое удаление фото
                        if (!empty($imagesToDelete)) {
                            Storage::delete($imagesToDelete);
                        }

                        // Массовое удаление объявлений
                        $tableModel = new $table;
                        $tableModel->whereIn('id', $adsToDelete)->delete();
                    });
                });
            }
        })->dailyAt('03:00');


        //------------------ УПРАВЛЕНИЕ РЕКЛАМОЙ ------------------//

        // Поднять -- ТОП 8 - Каждые 3 часа поднимаем в топ - На 24 часа -- Проверка каждые 5 минут ->everyFiveMinutes()
        $schedule->call(function (){
            $tables = [
                Kvartira::class,
                Obshejitie::class,
                Dom::class,
                Ofis::class,
                Zdanie::class,
                Magazin::class,
                Prombaza::class,
                Prochaya::class,
                Business::class,
            ];

            //Перебирем и обновим
            foreach ($tables as $table) {

                $table::whereNotNull('top_8')
                    ->where('bue_ads', '<', Carbon::now()->subHours(3))
                    ->chunk(200, function ($ads) use ($table){
                        $ads_id = $ads->pluck('id')->toArray();

                        $tableModel = new $table;
                        $tableModel->whereIn('id', $ads_id)
                            ->update(['bue_ads' => Carbon::now()]);

                    });
            }

        })->everyFiveMinutes();


        // Удаление -- TOP ис объявления - если прошли 24 часа - Проверка каждый час на 17 минуте ->hourlyAt(17)
        $schedule->call(function () {

            $tables = [
                Kvartira::class,
                Obshejitie::class,
                Dom::class,
                Ofis::class,
                Zdanie::class,
                Magazin::class,
                Prombaza::class,
                Prochaya::class,
                Business::class,
            ];

            foreach ($tables as $table) {
                $table::whereNotNull('top')->where('top', '<', Carbon::now()->subDay())
                    ->update([
                        'top' => null,
                        //Если на объявлении нет реклам top_8 top_x7 и top_x30 уберем рекламу с объявления
                        'bue_ads' => DB::raw("CASE WHEN top_8 IS NULL AND top_x7 IS NULL AND top_x30 IS NULL THEN '2020-12-12 12:12:12' ELSE bue_ads END"),
                    ]);
            }

        })->hourlyAt(17);


        // Удаление -- TOP 8 ис объявления - если прошли 24 часа - Проверка каждый час на 17 минуте ->hourlyAt(17)
        $schedule->call(function () {

            $tables = [
                Kvartira::class,
                Obshejitie::class,
                Dom::class,
                Ofis::class,
                Zdanie::class,
                Magazin::class,
                Prombaza::class,
                Prochaya::class,
                Business::class,
            ];

            foreach ($tables as $table) {
                $table::whereNotNull('top_8')->where('top_8', '<', Carbon::now()->subDay())
                    ->update([
                    'top_8' => null,
                    //Если на объявлении нет реклам top_8 top_x7 и top_x30 уберем рекламу с объявления
                    'bue_ads' => DB::raw("CASE WHEN top IS NULL AND top_x7 IS NULL AND top_x30 IS NULL THEN '2020-12-12 12:12:12' ELSE bue_ads END"),
                ]);
            }

        })->hourlyAt(27);


        // Поднять -- Top x7 - Top x30 в топ каждый день - Проверка каждый час на 47 минуте ->hourlyAt(47)
        $schedule->call(function () {

            // С каких таблиц выбрать записи
            $tables = [
                Kvartira::class,
                Obshejitie::class,
                Dom::class,
                Ofis::class,
                Zdanie::class,
                Magazin::class,
                Prombaza::class,
                Prochaya::class,
                Business::class,
            ];

            //Перебирем и обновим
            foreach ($tables as $table) {

                $updatedRecords = $table::whereNotNull('top_x7')->where('bue_ads', '<' , Carbon::now()->subDay())
                    ->orWhereNotNull('top_x30')->where('bue_ads', '<' , Carbon::now()->subDay())
                    ->update([
                        'bue_ads' => Carbon::now(),
                        'top' => Carbon::now(),
                    ]);

            }

        })->hourlyAt(47);

        // Удаление -- Топ х7 или Топ х30 c объявлений - Проверка каждый день в 4:00 ->dailyAt('04:00')
        $schedule->call(function () {

            $tables = [
                Kvartira::class,
                Obshejitie::class,
                Dom::class,
                Ofis::class,
                Zdanie::class,
                Magazin::class,
                Prombaza::class,
                Prochaya::class,
                Business::class,
            ];

            foreach ($tables as $tableClass) {

                $tableClass::where(function ($query) {
                    $query->whereNotNull('top_x7')->where('top_x7', '<', Carbon::now()->subWeek())
                        ->orWhereNotNull('top_x30')->where('top_x30', '<', Carbon::now()->subMonth());
                })->update([
                    'top_x7' => null,
                    'top_x30' => null,
                    //Если на объявлении нет реклам top и top_8 уберем рекламу с объявления
                    'bue_ads' => DB::raw("CASE WHEN top IS NULL AND top_8 IS NULL THEN '2020-12-12 12:12:12' ELSE bue_ads END"),
                ]);

            }

        })->dailyAt('04:00');


        // Проверка проведения оплаты
        $schedule->call(function () {
//            BueAds::where('result', 'В ожидании')->chunk(200, function ($bueAds_arr) {
//
//                // Отправим зарос на проверку оплаты
//                foreach ($bueAds_arr as $bueAds){
//                    // Получим объявление - на которое добавить рекламу
//                    $className = 'App\Models\Ads\\' . $bueAds->table_name;
//                    $ads = $className::find($bueAds->ads_id);
//
//                    if ($bueAds == 'Top x30') {
//                        $ads->bue_ads = Carbon::now();
//                        $ads->top = Carbon::now();
//                        $ads->top_x7 = Null;
//                        $ads->top_x30 = Carbon::now();
//                    }
//                    else if ($bueAds == 'Top x7') {
//                        $ads->bue_ads = Carbon::now();
//                        $ads->top = Carbon::now();
//                        $ads->top_x7 = Carbon::now();
//                        $ads->top_x30 = Null;
//                    }else{
//                        $bueAds = explode(',', $bueAds->bue_ads_type);
//                        foreach ($bueAds as $type) {
//                            if ($type == 'Срочно торг') $ads->srochno_torg = 1;
//                            if ($type == 'Топ 24') {
//                                $ads->bue_ads = Carbon::now();
//                                $ads->top = Carbon::now();
//                            }
//                            if ($type == 'Топ 8 раз') {
//                                $ads->bue_ads = Carbon::now();
//                                $ads->top_8 = Carbon::now();
//                            }
//                        }
//                    }
//
//                    $ads->save();
//
//                    $bueAds->result = "Оплачено";
//                    $bueAds->save();
//                }
//
//            });

        })->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
