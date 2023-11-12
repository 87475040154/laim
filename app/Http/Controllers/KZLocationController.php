<?php

namespace App\Http\Controllers;

use App\Models\KZLocation;
use Illuminate\Http\Request;

class KZLocationController extends Controller
{
    //Получить локации с Базы Данных
    public function getLocations(Request $request)
    {
        $locations =  KZLocation::where('parent_id', $request->parent_id)->get();

        //Проверяю есть ли дочерние локации
        foreach($locations as $location){
            if(KZLocation::where('parent_id', $location->id)->first() != null){
                $location->isset_children = true;
            }
        }

        return $locations;
    }

    //Добавить локацию в Базу Данных
    public function addLocation(Request $request)
    {

        //Валидация формы
        $request->validate([
            'oblast'=> 'required|string|min:1|max:255',
            'gorod'=> 'nullable|string|min:1|max:255',
            'raion'=> 'nullable|string|min:1|max:255',
        ]);



        //Проверка нет ли уже данной локации
        $location = KZLocation::where('parent_id', $request->parent_id)
            ->where('oblast',$request->oblast)
            ->where('gorod',$request->gorod)
            ->where('raion',$request->raion)
            ->first();

        if($location != null){
            return response()->json(['error'=>"Данная локация уже существует"], 422);
        }

        //Создадим новую локацию
        $location = KZLocation::create([
            "parent_id"=>$request->parent_id,

            "oblast"=>$request->oblast,
            "gorod"=>$request->gorod,
            "raion"=>$request->raion,
            "lat"=>$request->lat,
            "lon"=>$request->lon,
        ]);
        return $location;

    }

    //Редактировать локацию
    public function updateLocation(Request $request)
    {
        //Валидация формы
        $request->validate([
            'oblast'=> 'required|string|min:1|max:255',
            'gorod'=> 'nullable|string|min:1|max:255',
            'raion'=> 'nullable|string|min:1|max:255',
        ]);

        //Проверка нет ли уже данной локации
        $location = KZLocation::find($request->id);

        if($location == null){
            return response()->json(['error'=>"Данная локация не найденна"], 422);
        }

        $location->oblast = $request->oblast;
        $location->gorod = $request->gorod;
        $location->raion = $request->raion;
        $location->lat = $request->lat;
        $location->lon = $request->lon;
        $location->save();

        return $location;
    }

    //Удалить локацию с Базы Данных
    public function deleteLocation($location_id)
    {
        //Получим данную локацию
        $location = KZLocation::find($location_id);

        //Произведем проверку на наличие дочерних локаций, если есть то вернемся назад
        if($location != null){

            $children_location = KZLocation::where('parent_id', $location_id)->first();

            if($children_location != null){
                return response()->json(['error' => 'Удалите для начала дочерние локации, район, МКР, или ЖК'], 422);
            }
        }

        //Если дочерних локаций не обнаруженно то удалим данную локацию
        $location->delete();

        return 'success';
    }

}
