<?php

namespace App\Models;

use Illuminate\Cache\HasCacheLock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'reference',
        'dateDebut',
        'dateFin',
        'montant',
        'frequence_paiement',
        'type',
        'statut'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
