<?php

namespace App\Services;

use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use Mail;
use App\Mail\SendMail;
use App\Models\Setting;
use App\Models\User;

class EmailService
{
    public function sendMail($receiver_emails, $cc_emails,  array $content)
    {
         /*if()
         {
            Mail::to($receiver_emails)
            ->cc($cc_emails)
            ->queue(new SendMail($content));
        return true;
         }*/

        dispatch(new \App\Jobs\SendEmailJob($receiver_emails, $cc_emails, $content));
        return true;
    }
}
