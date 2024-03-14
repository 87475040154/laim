<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chat extends Model
{
    use HasFactory;


    protected $fillable = ['sender_id', 'recipient_id', 'message',
        'author_id', 'tip_obekta','zagolovok','tip_sdelki', 'table_name', 'ads_id', 'kolichestvo_komnat', 'ploshad_obshaya', 'etag','etagnost',
        'cena','cena_tip','period_arendi','images','gorod','ulica','nomer_doma', 'ownerOrRealtor', 'control', 'ads_created'
    ];

    // Преобразуем масив в json и обратно при занесении данных в БД и при получении из нее автоматически
    protected $casts = [
        'images' => 'array',
    ];

    // По умолчанию заносим пустой массив в БД если не переданны данные
    protected $attributes = [
        'images' => '[]',
    ];

    public static function add($data){

        //Провеим есть ли переписка с данным пользователем чтобы обозначить ветку чата chat = 1 тоесть
        $sms = Chat::whereIn('sender_id', [$data['sender_id'], $data['recipient_id']])
            ->whereIn('recipient_id', [$data['sender_id'], $data['recipient_id']])
            ->orderBy('id', 'desc')->first();


        //Если переписка уже была начата по этому объявлению уберем что это чат - так как ниже добавиться сообщение которое будет веткой чата с последним смс
        if($sms != null){
            $sms->chat = false;
            $sms->save();
        }


        $message = new self();
        $message->sender_id = $data['sender_id'];
        $message->recipient_id = $data['recipient_id'];

        // Если есть сообщение а то может быть просто фото
        $message->message = isset($data['message']) ? $data['message']: 'Photo';


        //Загрузить фото если они есть
        if(isset($data['images']) && count($data['images']) > 0){

            //Загрузим новые фото на сервер
            foreach ($data['images'] as $image) {
                $fileName = Str::random(5) . time() . Str::random(5) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('img/messageImg', $fileName);
                $new_image_names[] = $fileName;
            }

            $message->images = $new_image_names;
        }

        //Если есть объявление по которому переписка
        if(isset($data['ads'])){
            $message->author_id = $data['ads']['author_id'];
            $message->tip_obekta = $data['ads']['tip_obekta'];
            $message->zagolovok = array_key_exists('zagolovok', $data['ads']) ? $data['ads']['zagolovok'] : null;
            $message->tip_sdelki = array_key_exists('tip_sdelki', $data['ads']) ? $data['ads']['tip_sdelki'] : null;
            $message->table_name = $data['ads']['table_name'];
            $message->ads_id = $data['ads']['id'];
            $message->kolichestvo_komnat = array_key_exists('kolichestvo_komnat', $data['ads']) ? $data['ads']['kolichestvo_komnat'] : null;
            $message->ploshad_obshaya = $data['ads']['ploshad_obshaya'];
            $message->etag = array_key_exists('etag', $data['ads']) ? $data['ads']['etag'] : null;
            $message->etagnost = array_key_exists('etagnost', $data['ads']) ? $data['ads']['etagnost'] : null;
            $message->cena = $data['ads']['cena'];
            $message->cena_tip = array_key_exists('cena_tip', $data['ads']) ? $data['ads']['cena_tip'] : null;
            $message->period_arendi = array_key_exists('period_arendi', $data['ads']) ? $data['ads']['period_arendi'] : null;
            $message->images = $data['ads']['images'];
            $message->gorod = $data['ads']['gorod'];
            $message->ulica = array_key_exists('ulica', $data['ads']) ? $data['ads']['ulica'] : null;
            $message->nomer_doma = array_key_exists('nomer_doma', $data['ads']) ? $data['ads']['nomer_doma'] : null;
            $message->ownerOrRealtor = $data['ads']['ownerOrRealtor'];
            $message->control = $data['ads']['control'];
            $message->ads_created = date('Y-m-d H:i:s', strtotime($data['ads']['created_at']));
        }

        $message->save();
        return $message;
    }
}
