<?php

namespace App\Models\Ads;

use App\Models\AdsView;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Business extends Model
{
    use HasFactory;

    public $fillable = [
        'author_id', 'tip_sdelki', 'zagolovok', 'deistvuushii_bisnes', 'cena', 'cena_tip', 'sfera_deyatelnosti', 'mestopolojenie','ploshad_obshaya',
        'tip_stroeniya', 'oblast', 'gorod', 'raion', 'lat', 'lon', 'ulica', 'nomer_doma', 'sostoyanie', 'internet',
        'text_obyavleniya', 'ownerOrRealtor', 'name', 'tel','tel2', 'bueAds', 'top', 'top_8', 'goryachie', 'top_x7', 'top_x30'
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

    // Полиморфное отношение с таблицей View
    public function views(): MorphMany
    {
        return $this->morphMany(AdsView::class, 'viewable');

    }
}
