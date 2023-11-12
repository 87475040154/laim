<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerificationNotifi extends Notification implements ShouldQueue
{

    use Queueable;
    public $token = '';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Активация аккаунта laim.kz')
            ->greeting('Активация аккаунта')
            ->line('Добро пожловать в рекламное агентство')

            ->line('Для активации нажмите на кнопку ниже')
            ->action('Активировать аккаунт', url('/auth?accountActivationToken=' . $this->token))
            ->line('Если вы не регистрировались на сайте laim.kz, то просто проигнорируйте данное письмо');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
