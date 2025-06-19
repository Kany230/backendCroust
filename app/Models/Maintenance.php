<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_reclamation',
        'id_technicien',
        'id_user',
        'description',
        'priorite',
        'date_signalement',
        'date_debut',
        'date_fin_prevue',
        'date_fin_reelle',
        'statut',
        'remarques',
        'rapport_final',
        'materiel_utilise'
    ];

    protected $casts = [
        'materiel_utilise' => 'array',
        'date_signalement' => 'date',
        'date_debut' => 'date',
        'date_fin_prevue' => 'date',
        'date_fin_reelle' => 'datetime',
    ];

    public function reclamation(){
        return $this->belongsTo(Reclamation::class, 'id_reclamation', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'id_user');
    }

    public function technicien(){
        return $this->belongsTo(User::class, 'id_technicien');
    }

    public function scopeEnCours($query){
        return $query->where('statut', 'en_cours');
    }
    
    public function scopePourTechnicien($query, $id){
        return $query->where('id_technicien', $id);
    }

    public function scopeParPriorite($query, $priorite){
        return $query->where('statut', $priorite);
    }

    public function scopeEnRetard($query){
        return $query->where('date_fin_prevue', '<', now())
                    ->whereIn('statut', ['programme', 'en_cours']);
    }

    public function setMaterielUtiliseAttribue($value){
        return $this->attributes['materiel_utilise'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getDureeAttribute()
    {
        if ($this->date_debut && $this->date_fin_reelle) {
            return $this->date_debut->diffInDays($this->date_fin_reelle);
        }
        return null;
    }

    public function getEstEnRetardAttribute()
    {
        if ($this->date_fin_prevue && in_array($this->statut, ['programme', 'en_cours'])) {
            return $this->date_fin_prevue < now();
        }
        return false;
    }
}
