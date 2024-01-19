<?php

namespace App\Notifications;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class NewTenantNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $tenant;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $content = "We have a new tenant: *%s*!\n";
        $content .= 'It has been created by *%s* (<mailto:%s|%s>)';

        $content = sprintf(
            $content,
            $this->tenant->company,
            $this->tenant->name,
            $this->tenant->email,
            $this->tenant->email
        );

        return (new SlackMessage())
            ->success()
            ->from('saas', ':money_mouth_face:')
            ->to('#tenants-new')
            ->content($content);
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
        return env('APP_ENV', false) === 'production';
    }
}
