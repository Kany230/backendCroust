<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'sexe',
        'age',
        'adresse',
        'telephone',
        'niveauEtude',
        'numDossier',
        'filiere',
        'domaine',
        'numCIN',
        'photo',
        'statut',
        'role'
    ];

    public function reservations(){
        return $this->hasMany(Reservation::class);
    }

    public function reclamations(){
        return $this->hasMany(Reclamation::class);
    }

    public function paiements(){
        return $this->hasMany(Paiement::class);
    }

    public function controleQHSE(){
        return $this->hasMany(ControleQHSE::class);
    }

    public function maintenance(){
        return $this->hasMany(Maintenance::class);
    }

    public function contrat(){
        return $this->hasMany(Contrat::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
