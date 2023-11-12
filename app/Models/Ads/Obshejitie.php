<?php

namespace App\Models\Ads;

use App\Models\AdsView;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\View\View;

class Obshejitie extends Model
{
    use HasFactory;

    public $fillable = [
        'author_id', 'zagolovok', 'period_arendi', 'kolichestvo_komnat', 'cena', 'etag','etagnost','ploshad_obshaya',
        'ploshad_kuhni','tip_stroeniya', 'oblast', 'gorod', 'raion', 'lat', 'lon', 'ulica', 'nomer_doma', 'sostoyanie', 'internet', 'sanuzel',
        'dush', 'balkon', 'parkovka', 'mebel', 'pol', 'text_obyavleniya',
        'ownerOrRealtor', 'name', 'tel', 'tel2', 'bueAds', 'top', 'top_8', 'goryachie', 'top_x7', 'top_x30'
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
