<?php

namespace App\Models\Ads;

use App\Models\AdsView;
use App\Models\AdsViewTel;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Prombaza extends Model
{
    use HasFactory;

    public $fillable = [
        'author_id', 'zagolovok', 'tip_obekta', 'cena', 'cena_tip','ploshad_obshaya','ploshad_uchastka','tip_stroeniya', 'oblast', 'gorod', 'raion', 'lat', 'lon', 'ulica',
        'nomer_doma', 'sostoyanie', 'internet', 'ploshad_proizvodstvennih_pomesheniy', 'visota_proizvodstvennih_pomesheniy',
        'ploshad_skladskih_pomesheniy', 'visota_skladskih_pomesheniy','ploshad_ofisnih_pomesheniy',
        'text_obyavleniya', 'raspolojenie','kommunikacii','raznoe',
        'ownerOrRealtor', 'name', 'tel','tel2', 'bueAds', 'top', 'top_8', 'top_x7', 'top_x30'
    ];

    // Преобразуем масив в json и обратно при занесении данных в БД и при получении из нее автоматически
    protected $casts = [
        'images' => 'array',
        'raspolojenie' => 'array',
        'kommunikacii' => 'array',
        'raznoe' => 'array',
        'complain' => 'array',
    ];

    // По умолчанию заносим пустой массив в БД если не переданны данные
    protected $attributes = [
        'images' => '[]',
        'raspolojenie' => '[]',
        'kommunikacii' => '[]',
        'raznoe' => '[]',
        'complain' => '[]',
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

    // Полиморфное отношение с таблицей ViewTel
    public function viewTels(): MorphMany
    {
        return $this->morphMany(AdsViewTel::class, 'viewable');

    }

}
