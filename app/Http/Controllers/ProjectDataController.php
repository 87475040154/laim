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
use App\Models\AdsViewStatistic;
use App\Models\Chat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectDataController extends Controller
{

    // Проверка интернет соединения и доступность сервера
    public function checkInternet( Request $request ){
        return 'success';
    }


    //Получить данные проекта - Сколько новых смс у пользователя
    public function getProjectData( Request $request ){


        //Укажем когда пользователь был онлайн
        $user = User::find($request->user_id);
       if($user == null)return response()->json(['error'=>'error'], 422);


        $user->last_active = now();
        $user->save();

        //Укажем сколько новых смс поступило пользователю
        $countNewMessage = Chat::where('recipient_id', $request->user_id)
            ->where('open_recipient', false)
            ->where('delete_recipient', false)->count();

        $countReturnAds= 0; // с жалобами для автора

        //Если это пользователь защел то узнаем сколько обьявлений на которые есть жалобы
        if($request->role == 'user'){

            // Перебираем все отношения к разным типам объявлений
            $relations = ['kvartiras', 'obshejities', 'doms', 'ofis', 'zdanies', 'magazins', 'prombazas', 'prochayas', 'businesses', 'snimus'];

            foreach ($relations as $relation) {
                $countReturnAds += $user->$relation()->where('control', 'Поступили жалобы')->count();
            }

        }

        return response()->json(['countNewMessage'=>$countNewMessage,'countReturnAds'=>$countReturnAds], 200);
    }


}
