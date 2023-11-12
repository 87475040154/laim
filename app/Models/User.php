<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Ads\ArhiveAds;
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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'tel','tel2', 'password', 'role', 'agree', 'remember_token', 'email_verified_at'
    ];

    //Связи с таблицами
    public function kvartiras()
    {
        return $this->hasMany(Kvartira::class, 'author_id');
    }
    public function obshejities()
    {
        return $this->hasMany(Obshejitie::class, 'author_id');
    }
    public function doms()
    {
        return $this->hasMany(Dom::class, 'author_id');
    }
    public function ofis()
    {
        return $this->hasMany(Ofis::class, 'author_id');
    }
    public function zdanies()
    {
        return $this->hasMany(Zdanie::class, 'author_id');
    }
    public function magazins()
    {
        return $this->hasMany(Magazin::class, 'author_id');
    }
    public function prombazas()
    {
        return $this->hasMany(Prombaza::class, 'author_id');
    }
    public function prochayas()
    {
        return $this->hasMany(Prochaya::class, 'author_id');
    }
    public function businesses()
    {
        return $this->hasMany(Business::class, 'author_id');
    }
    public function snimus()
    {
        return $this->hasMany(Snimu::class, 'author_id');
    }

    public function arhivs()
    {
        return $this->hasMany(ArhiveAds::class, 'author_id');
    }


    // Отношение для связи с моделью Like
    public function likedAds()
    {
        return $this->hasMany(Like::class, 'author_id');
    }

    // Отношение для связи с моделью View
    public function viewAds()
    {
        return $this->hasMany(View::class, 'author_id');
    }


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
