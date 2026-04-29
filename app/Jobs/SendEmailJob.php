<?php

namespace App\Jobs;

use App\Mail\SendEmailTest;
use App\Services\EmailService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $receiver_emails;
    protected $cc_emails;
    protected $content;
    /**
     * Create a new job instance.
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new SendEmailTest($this->receiver_emails, $this->cc_emails, $this->content);

        if ($this->cc_emails) {
            Mail::to($this->receiver_emails)->cc($this->cc_emails)->send($email);
        } else {
             Mail::to($this->receiver_emails)->send($email);
         }
    }
}
