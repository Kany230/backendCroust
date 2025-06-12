<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_local',
        'montant',
        'dateDebut',
        'dateEcheance',
        'method_paiement',
        'statut'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function local(){
        return $this->belongsTo(Local::class);
    }
}
