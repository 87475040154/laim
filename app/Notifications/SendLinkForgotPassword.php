<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendLinkForgotPassword extends Notification
{
    use Queueable;

    public $token = '';
    public $path = '';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $path)
    {
        $this->token =  $token;
        $this->path =  $path;
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
            ->subject('Востановление пароля Лайм.kz')
            ->greeting('Востановление пароля')
            ->line('Нажмите на кнопку ниже')
            ->action('Сменить пароль', url('/'. $this->path.'?resetPasswordToken='.$this->token))
            ->line('На сайте Лайм.kz был создан запрос на востановление пароля, если это были не вы, то будьте осторожны кто то пытается войти на сайт под вашими данными');
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
