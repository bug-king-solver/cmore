<?php

namespace App\Notifications;

use App\Models\Domain;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class NewUserBySelfRegistrationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $tenant;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->tenant = tenant();
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
        return ['database', 'mail', 'slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = $this->tenant->route('tenant.users.index');

        return (new MailMessage())
            ->greeting('New User Aboard!')
            ->line("{$this->user->name} ({$this->user->email}) has just registered.")
            ->action('Approve Account', $url)
            ->line('Great news!')
            ->line("{$this->user->name} will only be able to access his ESG Maturity account after your approval!")
            ->line('Can we count with you?');
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
            'userName' => $this->user->name,
            'userEmail' => $this->user->email,
            'message' => ':userName (:userEmail) has just registered.',
        ];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $url = $this->tenant->route('tenant.users.index');

        $content = "*{$this->user->name}* ({$this->user->email}) has registered at *{$url}*!\n";
        $content .= "Company: *{$this->tenant->company}*; ";
        $content .= "Admin contact: *{$this->tenant->name}* (<mailto:{$this->tenant->email}|{$this->tenant->email}>)\n";
        $content .= "<{$url}|Enable Account>";

        return (new SlackMessage())
            ->success()
            ->from('saas', ':robot_face:')
            ->to('#tenants-pocs')
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
        // POC = Proof Of Concept
        return ! in_array($channel, ['mail', 'slack'], false)
            || ($this->tenant->poc === true && $this->tenant->hasUserManualActivationEnabled());
    }
}
