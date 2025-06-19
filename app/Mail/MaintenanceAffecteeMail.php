<?php
namespace App\Mail;

use App\Models\Maintenance;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MaintenanceAffecteeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $maintenance;

    public function __construct(Maintenance $maintenance)
    {
        $this->maintenance = $maintenance;
    }

    public function build()
    {
        return $this->subject('Nouvelle Maintenance Assignée')
        ->view('maintenance.affectee')
        ->with([
            'maintenance' => $this->maintenance
        ]);
    }
}
