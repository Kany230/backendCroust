<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = [
        'nom',
        'superficie',
        'dateConstruction',
        'localisation_lat',
        'localisation_lng',
    ];

   
}
