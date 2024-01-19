<?php

namespace App\Notifications\Api;

use App\Models\Tenant\Api\ApiTokens;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ApiTokenAfterCreateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected ApiTokens $apiToken;

    protected $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ApiTokens $apiToken, string $token)
    {
        $this->apiToken = $apiToken;
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
        return (new MailMessage())
            ->subject(__('New API token created'))
            ->greeting(__('You have created a new API token!'))
            ->line(__('Here is your token for the API :token', ['token' => $this->apiToken->name]))
            ->line(new HtmlString("<code>{$this->apiToken->id}|{$this->token}</code>"))
            ->line(__('Please keep it safe and do not share it with anyone!'))
            ->line(__('You can revoke this token at any time by deleting it from your profile page.'));
    }
}
