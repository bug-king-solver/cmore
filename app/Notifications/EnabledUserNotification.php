<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnabledUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $tenant;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tenant = tenant();
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
        $url = $this->tenant->route('tenant.home');

        return (new MailMessage())
                    ->greeting('Welcome to ESG Maturity!')
                    ->line('We are happy to share with you that your account is ON!')
                    ->line("Hi {$notifiable->name}, your account is ready to go. You can start using it now!")
                    ->action('Sign In', $url);
    }
}
