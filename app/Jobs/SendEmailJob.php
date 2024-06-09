<?php

namespace App\Jobs;

use App\Mail\SendEmailMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Email;
use App\Notifications\EmailStatusNotification;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function handle()
    {
        try {
            Mail::to(explode(',', $this->email->recipients))
                ->send(new SendEmailMailable($this->email));

            // Notify success
            $this->email->notify(new EmailStatusNotification('Email sent successfully.'));
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());

            // Notify failure
            $this->email->notify(new EmailStatusNotification('Failed to send email.'));
        }
    }
}
