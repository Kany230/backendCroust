<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Reservation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id_local',
        'id_user',
        'dateDebut',
        'dateFin',
        'description',
        'choixLocal',
        'produitOuService',
        'qualiteQHSE',
        'nombreCredit',
        'moyenneAnnuelle',
        'statutDemande',
        'nomFichier',
        'cheminFichier',
        'typeFichier',
    ];

    public function local(){
        return $this->belongsTo(Local::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function controleQHSE(){
        return $this->hasMany(ControleQHSE::class);
    }
}
