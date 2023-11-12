<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BueAds extends Model
{
    use HasFactory;

    protected $fillable = ['author_id', 'ads_id', 'table_name', 'summ', 'bue_ads_type'];
}
