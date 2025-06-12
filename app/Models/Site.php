<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'superficie',
        'dateConstruction',
        'localisation_lat',
        'localisation_lng'
    ];

    public function locals(){
        return $this->hasMany(Local::class);
    }

    public function cartographie(){
        return $this->hasMany(Cartographie::class);
    }
}
