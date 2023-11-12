<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AdsView extends Model
{
    use HasFactory;

    protected $fillable = ['viewable_id', 'viewable_type','author_id','author_ip'];

    //Полиморфная связь с таблицами объявлений
    public function viewable(): MorphTo
    {
        return $this->morphTo('viewable');
    }
}
