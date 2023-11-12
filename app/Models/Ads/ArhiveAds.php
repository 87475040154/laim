<?php

namespace App\Models\Ads;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ArhiveAds extends Model
{
    use HasFactory;

    public $fillable = [
        'author_id', 'table_name', 'tip_obekta','sfera_deyatelnosti', 'zagolovok', 'tip_ofisa','deistvuushii_bisnes',
        'period_arendi', 'kolichestvo_komnat', 'cena', 'cena_tip', 'mestopolojenie', 'etag','etagnost',
        'ploshad_obshaya', 'ploshad_kuhni', 'ploshad_uchastka', 'visota_potolkov', 'tip_stroeniya',
        'oblast', 'gorod', 'raion', 'lat', 'lon', 'ulica', 'nomer_doma', 'images', 'sostoyanie',
        'mebel', 'internet', 'dush', 'sanuzel', 'otdelniy_vhod', 'balkon',
        'kanalizaciya', 'voda', 'elektrichestvo', 'otoplenie', 'gaz', 'pol', 'parkovka',
        'ploshad_proizvodstvennih_pomesheniy', 'visota_proizvodstvennih_pomesheniy', 'ploshad_skladskih_pomesheniy',
        'visota_skladskih_pomesheniy', 'ploshad_ofisnih_pomesheniy', 'mebel_arr', 'raznoe', 'bezopasnost', 'raspolojenie',
        'kommunikacii',  'text_obyavleniya', 'ownerOrRealtor', 'name', 'tel', 'tel2','delete', 'view', 'countLike'

    ];

    //Отношения с User
    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Полиморфное отношение с таблицей Like
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
