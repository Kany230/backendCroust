<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControleQHSE extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_reservation',
        'id_local',
        'id_user',
        'dateDebut',
        'dateFin',
        'conclusion',
        'conforme',
        'type'
    ];

    public function reservation(){
        return $this->belongsTo(Reservation::class);
    }

    public function local(){
        return $this->belongsTo(Local::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
