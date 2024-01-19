<?php

namespace App\Notifications;

use App\Models\Tenant\Compliance\Reputational\Analysis;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SendReputationalAnalysisReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The target after update.
     */
    protected Analysis $analysis;

    /**
     * The user who made the change.
     */
    protected Authenticatable $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Analysis $analysis, Authenticatable $user)
    {
        $this->analysis = $analysis;
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
        return [];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $openUrl = route('tenant.compliance.reputational.show', ['analysis' => $this->analysis->id]);

        return [
            'userName' => $this->user->name,
            'analysis' => $this->analysis->name,
            'message' => 'Hey, :userName we’ve finished the first reputational analysis of :analysis. Take a look!',
            'action' => $openUrl,
        ];
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
        return true;
        // return count($this->changes) > 1 || array_key_exists('users', $this->changes);
    }
}
