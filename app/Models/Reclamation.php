<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Reclamation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_local',
        'id_user',
        'objet',
        'description',
        'statut'
    ];

    public function local(){
        return $this->belongsTo(Local::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
