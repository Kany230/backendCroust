<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_local',
        'id_user',
        'description',
        'priorite',
        'dateSignalement',
        'dateDebut',
        'dateFin',
        'statut',
        'remarques'
    ];

    public function local(){
        return $this->belongsTo(Local::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    
}
