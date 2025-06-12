<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_local',
        'nom',
        'numero',
        'superficie',
        'capacite'
    ];

    public function local(){
        return $this->belongsTo(Local::class);
    }
}
