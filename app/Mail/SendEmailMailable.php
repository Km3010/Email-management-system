<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function build()
    {
        return $this->view('emails.send')
                    ->with([
                        'subject' => $this->email->subject,
                        'body' => $this->email->body,
                    ])
                    ->subject($this->email->subject)
                    ->from($this->email->sender);
    }
}
