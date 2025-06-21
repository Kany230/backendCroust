<?php

namespace App\Mail;

use App\Models\Contrat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ContratMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $contract;

    public function __construct(Contrat $contract)
    {
        $this->contract = $contract;
    }

    public function build()
    {
        $email = $this->subject('Votre contrat de location - ' . $this->contract->reference)
                      ->view('contrat.email')
                      ->with([
                          'contract' => $this->contract,
                          'user' => $this->contract->user,
                          'reservation' => $this->contract->reservation,
                      ]);

        // Attacher le PDF
        if ($this->contract->pdf_path && Storage::disk('public')->exists($this->contract->pdf_path)) {
            $email->attach(Storage::disk('public')->path($this->contract->pdf_path), [
                'as' => 'contrat_' . $this->contract->reference . '.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $email;
    }
}