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

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        if (!$this->user || !$this->user->email) {
            \Log::error('User or user email is not valid');
            return;
        }

        try {
            Mail::to($this->user->email)->send(new WelcomeEmail($this->user));
            \Log::info('Welcome email sent to ' . $this->user->email);
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email to ' . $this->user->email . ': ' . $e->getMessage());
            throw $e; // Rethrow the exception to trigger retry logic
        }
    }
}
