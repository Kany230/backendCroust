<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
protected $fillable = [
    'id_local',
    'id_user',
    'dateDemande',
    'description',
    'choixLocal',
    'produitOuService',
    'qualiteQHSE',
    'nombreCredit',
    'moyenneAnnuelle',
    'statutDemande',
    'nomFichier',
    'cheminFichier',
    'typeFichier'
];

    
}
