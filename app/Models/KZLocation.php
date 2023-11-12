<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KZLocation extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'oblast', 'gorod','raion','lat','lon'];

    // Disable the model timestamps
    public $timestamps = false;
}
