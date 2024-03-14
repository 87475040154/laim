<?php

namespace App\Models\Ads;

use App\Models\AdsView;
use App\Models\AdsViewTel;
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
        'raspolojenie', 'kommunikacii',
        'text_obyavleniya', 'ownerOrRealtor', 'name', 'tel','tel2', 'bueAds', 'top', 'top_8', 'top_x7', 'top_x30'
    ];

    // Преобразуем масив в json и обратно при занесении данных в БД и при получении из нее автоматически
    protected $casts = [
        'images' => 'array',
        'raspolojenie' => 'array',
        'kommunikacii' => 'array',
        'complain' => 'array',
    ];

    // По умолчанию заносим пустой массив в БД если не переданны данные
    protected $attributes = [
        'images' => '[]',
        'raspolojenie' => '[]',
        'kommunikacii' => '[]',
        'complain' => '[]',
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

    // Полиморфное отношение с таблицей ViewTel
    public function viewTels(): MorphMany
    {
        return $this->morphMany(AdsViewTel::class, 'viewable');

    }
}
