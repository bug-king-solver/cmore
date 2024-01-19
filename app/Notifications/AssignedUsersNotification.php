<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class AssignedUsersNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public array $message;

    public int $timeout = 90;


    /**
     * The user who made the change.
     */
    protected Authenticatable $assigner;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, Authenticatable $assigner)
    {
        $this->message = $message;
        $this->assigner = $assigner;
        $this->id = md5(time());
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return [];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'id' => $this->id,
            'notificationId' => $this->id,
            'message' => $this->message['message'],
            'userName' => $this->message['userName'],
            'company' => $this->message['company'],
            'period' => $this->message['period'],
            'action' => $this->message['action'],
        ];
    }

    /**
     * Only send the notification if the user is not assigning the question for himself
     */
    public function shouldSend($notifiable, $channel)
    {
        // Keep send to everyone, in this case who is doing the changes knows what other receives
        return true; //$this->user->id !== $notifiable->id;
    }

    /**
     * Determine the notification's delivery delay.
     *
     * @return array<string, \Illuminate\Support\Carbon>
     */
    public function withDelay(object $notifiable): array
    {
        return [
            'database' => now()->addSeconds(5),
        ];
    }

    public function getMessageId()
    {
        return $this->id;
    }
}
