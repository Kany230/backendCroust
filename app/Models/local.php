<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class local extends Model
{
    protected $fillable = [
        'id_sites',
        'nom',
        'superficie',
        'capacite',
        'disponible',
        'statutConforme',
        'type'
    ] ;

}
