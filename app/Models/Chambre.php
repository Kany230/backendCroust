<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pavillon',
        'nom',
        'numero',
        'superficie',
        'capacite',
        'statut'
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'chambre_user', 'id_chambre', 'id_user')
                    ->withTimestamps();
    }

    public function equipements(){
        return $this->belongsToMany(Equipement::class, 'chambre_equipement', 'id_chambre', 'id_equipement')
                    ->withTimestamps();
    }

    public function pavillon(){
        return $this->belongsTo(Local::class, 'id_pavillon');
    }

    //Ce code permet de faire 'depuis une chambre je veux recuperer le site associe mais je dois passer d'abord par le local'
    //Une relation hasOneThrough est une relation de 'a un a travers'
    //Donc dans la table chambre j'ai id_local
    //Dans local j'ai id_site
    public function site(){
        return $this->hasOneThrough(Site::class, Local::class, 'id', 'id', 'id_local', 'id_site');
    }

    public function scopeDisponibles(Builder $query){
        return $query->whereRaw('
                capacite > (
                    SELECT COUNT(*)
                    FROM chambre_user
                    WHERE chambre_user.id_chambre = chambres.id
                ) 
        ');
    }
}
