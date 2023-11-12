<?php

namespace App\Models\Ads;

use App\Jobs\UploadImageQueue;
use App\Models\UploadImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Queue;

class GeneralAds extends Model
{
    use HasFactory;

    //Общий метод для всех моделей / ДОБАВЛЕНИЕ - РЕДАКТИРОВАНИЕ ОБЪЯВЛЕНИЯ
    //$modelRecord = new Kvartira() или Kvartira::fing(2) или new Obhejitie(); и тд
    //$adsData - Это данные объявления с формы которые занесем в БД

    public static function addOrUpdateAds($modelRecord, $adsData){

        //В какую таблицу в БД занести данные / Квартира, Магазин и тд. Или готовый о
        $ads = $modelRecord;

        //Заполняю таблицу данными
        $ads->fill($adsData);

        // Поля которые нужно разбить на строку
        $fields = ['mebel_arr', 'raznoe', 'bezopasnost', 'raspolojenie', 'kommunikacii'];
        foreach($fields as $field){
            if(isset($adsData[$field])){
                $ads->$field = implode(',', $adsData[$field]);
            }
        }

        //ЗАГРУЗКА ФОТО
        //Статический метод куда передаю экземпляр запси объявления, и новые фото или '', имена старых фото или '' он загрузит или удалит и вернет
        //Вернет $ads->images = implode(',', $images);
        UploadImage::uploadImage($ads,  $adsData['images'] ?? '', $adsData['old_images'] ?? '');



        // Укажем что оно активно и без жалоб
        $ads->control = 'Активно';
        $ads->complain = '';

        //Если это редактирование то отменим обновление поля updated_at, так как при выборе на главной странице я получаю объявления по этому полю
        if($adsData['addOrUpdate'] == 'update'){
            $ads->timestamps = false;
            $ads->save();
        }
        //Если новое объявление, Добавим данные пользователю что он добавил новое объявление чтоб при подаче нового можно отследить сколько подал этот пользователь объявлений
        else{

            $ads->save();

            $user = User::find($ads->author_id);
            $user->count_ads += 1;
            $user->tel = $adsData['tel'];
            $user->tel2 = $adsData['tel2'];
            $user->save();
        }


        return $ads;
    }
}
