<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserByNonOwnerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    protected $created;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, User $created)
    {
        $this->user = $user;
        $this->created = $created;
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
        return [
            'userName' => $this->user->name,
            'userEmail' => $this->user->email,
            'createdName' => $this->created->name,
            'createdEmail' => $this->created->email,
            'message' => ':createdName (:createdEmail) was added to the users by :userName (:userEmail).',
        ];
    }
}
