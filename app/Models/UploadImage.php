<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadImage extends Model
{
    use HasFactory;


    //Метод Загрузка - Обновление Фото

    //$record - Это запись нашего объявления
    //$newImages - Это новые фото для загрузки или пустая строка
    //$oldImageNames - Это имена старых фото

    public static function uploadImage($record, $newImages, $oldImageNames)
    {

        //Удалим старые фото с сервера и БД если в $oldImageNames не переданны имена старых фото
        if ($oldImageNames == '') {

            //Получим из Базы Данных имена всех старых фото и разобъем на массив - это фото с нашей записи которую мы получили с бд
            $image_names = explode(',', $record->images);

            //Если есть имена и это не запись no-image то удалим
            if (count($image_names) > 0 && $image_names[0] != 'no-image') {
                foreach ($image_names as $name) {
                    Storage::delete('/img/adsImg/' . $name);
                }
            }

            //И занесем запись, эта запись нужна чтоб в дальнейщем в объявлении отследить что у объявления нет фото
            $record->images = 'no-image';

        }

        //Удалим только те которые не переданны
        if($oldImageNames != ''){

            //Получим с Базы Данных имена старых фото, и разобъем строку на массив
            $image_names = explode(',', $record->images);

            //Удалим с сервера только те фото которых нет в переданном старом массиве
            foreach ($image_names as $name) {

                //Это проверка есть ли данное имя в массиве
                if ($name != 'no-image' && !in_array($name, $oldImageNames)) {
                    Storage::delete('/img/adsImg/' . $name);
                }
            }

            //Занесем имена старых фото в Базу Данных разбив на строку
            $record->images = implode(',', $oldImageNames);
        }

        //Если есть новые фото загрузим их на сервер
        if ($newImages != '') {

            //Загрузим новые фото на сервер
            foreach ($newImages as $image) {
                $fileName = Str::random(5) . time() . Str::random(5) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('img/adsImg', $fileName);
                $new_image_names[] = $fileName;
            }

            //Если есть имена старых фото то объеденим имена новых и старых фото в 1 массив по порядку
            if ($oldImageNames != '') {

                $counter = 0;
                foreach ($oldImageNames as $old_name) {
                    if ($old_name == null) {
                        $oldAndNewImgNames[] = $new_image_names[$counter];
                        $counter++;
                    } else {
                        $oldAndNewImgNames[] = $old_name;
                    }
                }

                //И теперь занесем в бд имена новых и старых фото разбив на строку
                $record->images = implode(',', $oldAndNewImgNames);

            }

            //Если нет переданных старых фото то просто занесем в бд имена новых
            if($oldImageNames == '') {
                $record->images = implode(',', $new_image_names);
            }

        }

        return;

    }


}
