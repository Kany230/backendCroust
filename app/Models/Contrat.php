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
        'id_reservation',
        'reference',
        'dateDebut',
        'dateFin',
        'montant',
        'frequence_paiement',
        'type',
        'statut',
        'conditions',
        'pdf_path'
    ];

    protected $casts = [
    'dateDebut' => 'datetime',
    'dateFin' => 'datetime',
];


    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function reservation(){
        return $this->belongsTo(Reservation::class, 'id_reservation');
    }

    public function generateReference(){
        return 'CT-' . date('Y') . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public static function getDefaultTermsAndConditions(){
        return "
        TERMES ET CONDITIONS DU CONTRAT DE LOCATION

        1. OBJET DU CONTRAT
        Le présent contrat a pour objet la location du bien réservé selon les modalités définies.

        2. DURÉE
        La location s'étend de la date de début à la date de fin mentionnées dans le contrat.

        3. MONTANT ET PAIEMENT
        Le montant total et la caution sont définis selon la réservation acceptée.

        4. OBLIGATIONS DU LOCATAIRE
        - Respecter les règles de sécurité
        - Signaler tout dommage immédiatement

        5. CAUTION
        Une caution est exigée et sera restituée après vérification de l'état du bien.

        6. RÉSILIATION
        Le contrat peut être résilié selon les conditions légales en vigueur.
        ";
    }
}
