<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;

class Local extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_site',
        'nom',
        'superficie',
        'capacite',
        'disponible',
        'statutConforme',
        'type',
    ];

    public function site(){
        return $this->belongsTo(Site::class);
    }

    public function reservations(){
        return $this->hasMany(Reservation::class);
    }

    public function reclamations(){
        return $this->hasMany(Reclamation::class);
    }

    public function paiements(){
        return $this->hasMany(Paiement::class);
    }

    public function equipements(){
        return $this->hasMany(Equipement::class, 'id_local');
    }

    public function controleQHSE(){
        return $this->hasMany(ControleQHSE::class);
    }

    public function maintenance(){
        return $this->hasMany(Maintenance::class);
    }

    public function cartographie(){
        return $this->hasMany(Cartographie::class, 'id_local');
    }

    public function chambres(){
        return $this->hasMany(Chambre::class, 'id_local');
    }

    //les scope utilise et ce qu'on appelle une query scope un moyen pratique de creer une requete reutilisable
    public function scopePavillons($query){
        return $query->where('type', 'pavillon');
    }

    public function scopeCantines($query){
        return $query->where('type', 'cantine');
    }

    public function scopeEspaces($query){
        return $query->where('type', 'espace');
    }

}
