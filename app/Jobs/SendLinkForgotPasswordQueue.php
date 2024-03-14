<?php

namespace App\Jobs;

use App\Notifications\SendLinkForgotPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendLinkForgotPasswordQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $token;
    protected $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $token, $path)
    {
        $this->user = $user;
        $this->token = $token;
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Логика отправки уведомления (письма)
        Notification::send($this->user, new SendLinkForgotPassword($this->token, $this->path));
    }
}
