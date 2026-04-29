<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class SendEmailTest extends Mailable
{
    use Queueable, SerializesModels;

    protected $receiver_emails;
    protected $cc_emails;
    protected $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($receiver_emails, $cc_emails, $content)
    {
        $this->receiver_emails = $receiver_emails;
        $this->cc_emails = $cc_emails;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->markdown('emails.send-email')->with('content',$this->content);

       /* return $this->subject('Email From Econsultation')
                    ->view('emails.sendEmail');*/

                return $this->subject($this->content['title'])->view('emails.sendEmail')
                ->with([
                    'content' => $this->content,
                ]);

    }
}

