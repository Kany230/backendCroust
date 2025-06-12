<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
protected $fillable = [
    'id_user',
    'id_local',
    'montant',
    'dateDebut',
    'dateEcheance',
    'method_paiement',
    'statut'
];


}
