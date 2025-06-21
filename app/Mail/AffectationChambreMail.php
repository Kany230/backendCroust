<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Chambre;
use App\Models\User;

class AffectationChambreMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $chambre;
    public $type; // 'assignation' ou 'retrait'

    public function __construct(User $user, Chambre $chambre, string $type)
    {
        $this->user = $user;
        $this->chambre = $chambre;
        $this->type = $type;
    }

    public function build()
    {
        $subject = $this->type === 'assignation' ? 'Affectation à une chambre' : 'Retrait de votre chambre';
        return $this->subject($subject)
                    ->view('chambre.affectation_chambre');
    }
}
