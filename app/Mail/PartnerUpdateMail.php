<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PartnerUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $partner;
    public $pdfData;

    /**
     * Create a new message instance.
     */
    public function __construct($partner, $pdfData)
    {
        $this->partner = $partner;
        $this->pdfData = $pdfData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Activity Roadmap Update - ' . $this->partner->name)
            ->view('emails.partner_update')
            ->attachData($this->pdfData, 'assigned_activities.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
