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

        //Удалим с сервера только те фото которых нет в переданном старом массиве
        foreach ($record->images as $name) {

            //Это проверка есть ли данное имя в массиве
            if (!in_array($name, $oldImageNames)) {
                Storage::delete('/img/adsImg/' . $name);
            }
        }

        //Занесем имена старых фото в Базу Данных разбив на строку
        $record->images = $oldImageNames;

        //Если есть новые фото загрузим их на сервер
        if (count($newImages) > 0 ) {

            //Загрузим новые фото на сервер
            foreach ($newImages as $image) {
                $fileName = Str::random(5) . time() . Str::random(5) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('img/adsImg', $fileName);
                $new_image_names[] = $fileName;
            }

            //Если есть имена старых фото то объеденим имена новых и старых фото в 1 массив по порядку
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
            $record->images = $oldAndNewImgNames;
        }

        return;

    }


}
