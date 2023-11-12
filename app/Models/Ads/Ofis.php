<?php

namespace App\Models\Ads;

use App\Models\AdsView;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Ofis extends Model
{
    use HasFactory;

    public $fillable = [
        'author_id', 'zagolovok', 'kolichestvo_komnat', 'cena','cena_tip', 'tip_ofisa', 'etag','etagnost','visota_potolkov',
        'ploshad_obshaya', 'tip_stroeniya', 'oblast', 'gorod', 'raion', 'lat', 'lon', 'ulica', 'nomer_doma', 'sostoyanie', 'mebel', 'internet',
        'sanuzel', 'otdelniy_vhod', 'parkovka', 'pol', 'text_obyavleniya', 'ownerOrRealtor', 'name', 'tel','tel2'
        , 'bueAds', 'top', 'top_8', 'goryachie', 'top_x7', 'top_x30'
    ];

    //Отношения
    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Полиморфное отношение с таблицей Like
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // Полиморфное отношение с таблицей View
    public function views(): MorphMany
    {
        return $this->morphMany(AdsView::class, 'viewable');
    }

}
