<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Maintenance;

class RapportMaintenanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $maintenance;

    /**
     * Create a new message instance.
     */
    public function __construct(Maintenance $maintenance)
    {
        // Load the maintenance with all necessary relationships
        $this->maintenance = $maintenance->load(['reclamation', 'user', 'technicien']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rapport de Maintenance - ' . $this->maintenance->description,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'maintenance.email',
            with: [
                'maintenance' => $this->maintenance,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $attachments = [];
        
        // Attach the PDF report if it exists
        if ($this->maintenance->rapport_pdf_path && Storage::disk('public')->exists($this->maintenance->rapport_pdf_path)) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromStorageDisk('public', $this->maintenance->rapport_pdf_path)
                ->as('rapport_maintenance_' . $this->maintenance->id . '.pdf')
                ->withMime('application/pdf');
        }
        
        return $attachments;
    }
}