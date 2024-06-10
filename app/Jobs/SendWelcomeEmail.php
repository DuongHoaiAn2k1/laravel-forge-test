<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function handle()
    {
        try {
            Mail::to($this->email)->send(new WelcomeEmail($this->email));
            \Log::info('Welcome email sent to ' . $this->email);
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email to ' . $this->email . ': ' . $e->getMessage());
            throw $e; // Rethrow the exception to trigger retry logic
        }
    }
}
