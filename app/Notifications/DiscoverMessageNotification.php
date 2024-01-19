<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DiscoverMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Who is requestion to discover more
     */
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        $message = (new MailMessage())
            ->greeting('A potential customer is interested in ESG Maturity!')
            ->line("**{$this->user->name}** from **{$notifiable->company}** is asking for more details.")
            ->line(
                "Please contact {$this->user->name} by email: **[{$this->user->email}](mailto:{$this->user->email})**"
            )
            ->line('Thank you.');

        return $notifiable->notifications_config['cc'] ?? false
            ? $message->cc($notifiable->notifications_config['cc'])
            : $message;
    }

    /**
     * Determine if the notification should be sent.
     *
     * @param  mixed  $notifiable
     * @param  string  $channel
     * @return bool
     */
    public function shouldSend($notifiable, $channel)
    {
        return isset($notifiable->notifications_config['to']);
    }
}
