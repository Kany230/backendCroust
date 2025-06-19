<?php

namespace App\Models;

use App\Models\Local;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Equipement extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_local',
        'nom',
        'type',
        'etat',
        'dateDebut',
        'dateFin'
    ];

    public function local(){
        return $this->belongsTo(Local::class, 'id_local');
    }

    public function chambres(){
        return $this->belongsToMany(Chambre::class, 'chambre_equipement', 'id_chambre', 'id_equipement')
                    ->withTimestamps();
    }
}
