<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_resrevation',
        'montant',
        'dateDebut',
        'dateEcheance',
        'method_paiement',
        'statut',
        'type'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function reservation(){
        return $this->belongsTo(Reservation::class);
    }
}
