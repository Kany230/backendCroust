<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cartographie extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_site',
        'id_local',
        'type',
        'coordonnees_x',
        'coordonnees_y',
        'largeur',
        'hauteur',
        'rotation',
        'couleur',
        'label',
        'details'
    ];

    public function sites(){
        return $this->belongsTo(Site::class);
    }

    public function local(){
        return $this->belongsTo(Local::class);
    }
}
