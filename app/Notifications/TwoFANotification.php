<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

class TwoFANotification extends Notification
{
    use Queueable;

    const MAIL = 'mail';

    const SMS = 'vonage';

    public $token;

    public $notificationChannel;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $notificationChannel = [self::MAIL])
    {
        $this->token = $token;
        $this->notificationChannel = $notificationChannel;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->notificationChannel;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('Verification token to ' . env("APP_NAME", 'ESG Maturity'))
            ->greeting('Hi!')
            ->from(env("MAIL_FROM_ADDRESS", 'no-reply@esg-maturity.com'))
            ->line('Your token for ' . env("APP_NAME", 'ESG Maturity') . ' is:')
            ->line($this->token);
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

    /**
     * Get the Vonage / SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\VonageMessage
     */
    public function toVonage($notifiable)
    {
        return (new VonageMessage())
                ->content('Your token is: ' . $this->token)
                ->from('123456')
                ->unicode();
    }
}
