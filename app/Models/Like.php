<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['author_id', 'likeable_id', 'likeable_type'];
    public $timestamps = false;


    //Связь с таблицей User
    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }



    //Полиморфная связь с таблицами объявлений
    public function likeable(): MorphTo
    {
        return $this->morphTo('likeable');
    }


}
